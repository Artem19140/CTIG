<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { usePromptDialog } from '../../../../Composables/usePromptDialog';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '../../../../interfaces/interfaces';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useAuth } from '../../../../Composables/useAuth';
import { Roles } from '../../../../Constants/Roles';
import { getExamPermissions } from '../../../../Domain/Exam/getExamPermissions';
import { useModals } from '../../../../Composables/useModals';
import { useLoadingSnackbar } from '../../../../Composables/useLoadingSnackBar';


const props = defineProps<{exam : Exam | null}>()

const form = useForm({
  cancelledReason: ''
})

const prompt = usePromptDialog()
const loadingSnackbar = useLoadingSnackbar()

const cancelExam = async () => {
  const res = await prompt.open('Укажите причину отмены экзамена')
  if(!res){
    return
  }
  form.cancelledReason = res
  loadingSnackbar.open('Идет отмена')
  form.delete(`exams/${props.exam?.id}`,{
    onSuccess:(page)=>{
      loadingSnackbar.close()
      if(!props.exam) return
      if(!page.flash.success)return
      props.exam.isCancelled = true
      props.exam.cancelledReason = res
    }
  })
  
}

const download = (document :string) => {
    if(!props.exam?.id || !document){
        return
    }
    const form = useForm()
    loadingSnackbar.open('Скачивание')
    form.get(`/exams/${props.exam.id}/documents/${document}/available`,{
      onSuccess:(page) => {
        if(page.flash.redirectUrl){
          window.open(String(page.flash.redirectUrl))
        }
      },
      onFinish:()=>{
        loadingSnackbar.close()
      }
    })
}

const downloadForeignNationalsList = () => {
  window.open(`/exams/${props.exam?.id}/foreign-nationals/list`)
}

const {can} = useAuth()
const {open} = useModals()
const permission = getExamPermissions(props.exam)
</script>

<template>
    <ThreeDotDropdown>
      <AppListDropDownItem 
        title="Скачать кода" 
        :disabled="!permission.canDownloadCodes"
        @click="() => download('codes')" 
        v-if="can([Roles.EXAMINER])"
      />

      <AppListDropDownItem 
        title="Скачать список"
        :disabled="!permission.canDownloadStudList"  
        @click="downloadForeignNationalsList" 
      />

      <AppListDropDownItem 
        title="Скачать ведомость"
        :disabled="!permission.canDownloadStatement" 
        v-if="can([Roles.EXAMINER])"
        @click="() => download('statement')" 
      />

      <AppListDropDownItem 
        title="Скачать протокол" 
        v-if="can([Roles.EXAMINER])"
        :disabled="!permission.canDownloadProtocol"
        @click="() => download('protocol')" 
      />
      
      <AppListDropDownItem 
        title="Редактировать" 
        v-if="can([Roles.SCHEDULER])" 
        @click="open('examEdit', {exam:exam})"
        :disabled="!permission.canEdit"
      />

      <AppListDropDownItem 
        color="text-red" 
        title="Отменить" 
        @click="cancelExam"
        :disabled="!permission.canCancel" 
        v-if="can([Roles.SCHEDULER])" 
      />
    </ThreeDotDropdown>
</template>
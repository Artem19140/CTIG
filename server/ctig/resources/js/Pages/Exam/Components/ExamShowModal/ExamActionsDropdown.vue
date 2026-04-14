<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { usePromptDialog } from '@composables/usePromptDialog';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '@interfaces/interfaces';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { getExamPermissions } from '@domain/Exam/getExamPermissions';
import { useModals } from '@composables/useModals';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';


const props = defineProps<{exam : Exam | null}>()

const emit = defineEmits<{
  (e:'cancel', value:string):void
}>()

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
  form.delete(`/exams/${props.exam?.id}`,{
    onSuccess:(page)=>{
      loadingSnackbar.close()
      if(!page.flash.success)return
      emit('cancel', res)
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
          //modals.open('pdf', {url:page.flash.redirectUrl})
          window.open(String(page.flash.redirectUrl))
        }
      },
      onFinish:()=>{
        loadingSnackbar.close()
      }
    })
}


const auth = useAuth()
const modals = useModals()
const permission = getExamPermissions(props.exam)
</script>

<template>
    <BaseThreeDotDropdown>
      <AppListDropDownItem 
        title="Кода" 
        :disabled="!permission.canDownloadCodes"
        @click="() => download('codes')" 
        v-if="auth.can([Roles.EXAMINER])"
      />

      <AppListDropDownItem 
        title="Список"
        :disabled="!permission.canDownloadStudList"  
        @click="download('list')" 
      />

      <AppListDropDownItem 
        title="Результаты"
        :disabled="!permission.canDownloadStatement" 
        v-if="auth.can([Roles.EXAMINER])"
        @click="() => download('results')" 
      />

      <AppListDropDownItem 
        title="Протокол" 
        v-if="auth.can([Roles.EXAMINER])"
        :disabled="!permission.canDownloadProtocol"
        @click="() => download('protocol')" 
      />
      
      <AppListDropDownItem 
        title="Редактировать" 
        v-if="auth.can([Roles.SCHEDULER])" 
        @click="modals.open('examEdit', {exam:exam})"
        :disabled="!permission.canEdit"
      />

      <AppListDropDownItem 
        color="text-red" 
        title="Отменить" 
        @click="cancelExam"
        :disabled="!permission.canCancel" 
        v-if="auth.can([Roles.SCHEDULER])" 
      />
    </BaseThreeDotDropdown>
</template>
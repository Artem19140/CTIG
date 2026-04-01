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


const props = defineProps<{exam : Exam | null}>()

const form = useForm({
  cancelledReason: ''
})

const prompt = usePromptDialog()

const cancelExam = async () => {
  const res = await prompt.open('Укажите причину отмены экзамена')
  if(!res){
    return
  }
  form.cancelledReason = res
  form.delete(`exams/${props.exam?.id}`,{
    onSuccess:(page)=>{
      if(!props.exam) return
      if(!page.flash.success)return
      props.exam.isCancelled = true
    }
  })
  
}

const downloadForeignNationalsList = () => {
  window.open(`/exams/${props.exam?.id}/foreign-nationals/list`)
}

const formCodes = async () => {
    if(!props.exam?.id){
        return
    }
    window.open(`/exams/${props.exam.id}/codes`)
}

const downloadStatement = () => {
  window.location.href = `/exams/${props.exam?.id}/statement`
}

const downloadProtocol = () => {
  window.location.href = `/exams/${props.exam?.id}/protocol`
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
        @click="formCodes" 
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
        @click="downloadStatement" 
      />

      <AppListDropDownItem 
        title="Скачать протокол" 
        v-if="can([Roles.EXAMINER])"
        :disabled="!permission.canDownloadProtocol"
        @click="downloadProtocol" 
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
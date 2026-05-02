<script setup lang="ts">
import { useForm, useHttp } from '@inertiajs/vue3'
import { usePromptDialog } from '@composables/usePromptDialog';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { useModals } from '@composables/useModals';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';
import { Exam } from '@/interfaces/Exam';

const props = defineProps<{exam : Exam | null}>()

const emit = defineEmits<{
  (e:'cancel', value:string):void,
  (e:'edit', value:Exam):void
}>()

const http = useHttp({
  cancelledReason: ''
})

const prompt = usePromptDialog()
const loadingSnackbar = useLoadingSnackbar()

const cancelExam = async () => {
  const res = await prompt.open('Укажите причину отмены экзамена')
  if(!res){
    return
  }
  http.cancelledReason = res
  loadingSnackbar.open('Идет отмена')
  http.delete(`/exams/${props.exam?.id}`,{
    onSuccess:()=>{
      emit('cancel', res)
    },
    onFinish() {
      loadingSnackbar.close()
    },
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
const {isFinished, isCancelled, isPending} = useExamStatus(props.exam)

const downloadResultslDisabled  = !props.exam?.documentsAvailable.results.available 
const downloadProtocolDisabled = !props.exam?.documentsAvailable.protocol.available 
const downloadListDisabled =  !props.exam?.documentsAvailable.list.available
const editDisabled  = !isPending.value || isCancelled.value 
const cancelDisabled = !isPending.value || isCancelled.value 
const downloadCodesDisabled  = !props.exam?.documentsAvailable.codes.available
</script>

<template>
    <BaseThreeDotDropdown>
      <AppListDropDownItem 
        title="Кода" 
        :disabled="downloadCodesDisabled"
        @click="() => download('codes')" 
        v-if="auth.can([Roles.EXAMINER])"
      />

      <AppListDropDownItem 
        title="Список"
        :disabled="downloadListDisabled"  
        @click="download('list')" 
      />

      <AppListDropDownItem 
        title="Результаты"
        :subtitle="props.exam?.documentsAvailable.results.label"
        :disabled="downloadResultslDisabled" 
        v-if="auth.can([Roles.EXAMINER])"
        @click="() => download('results')" 
      />

      <AppListDropDownItem 
        title="Протокол" 
        v-if="auth.can([Roles.EXAMINER])"
        :disabled="downloadProtocolDisabled"
        @click="() => download('protocol')" 
      />
      
      <AppListDropDownItem 
        title="Редактировать" 
        v-if="auth.can([Roles.SCHEDULER])" 
        @click="modals.open('examEdit', {exam:exam, onEdit:(exam:Exam) => emit('edit', exam)})"
        :disabled="editDisabled"
      />

      <AppListDropDownItem 
        color="text-red" 
        title="Отменить" 
        @click="cancelExam"
        :disabled="cancelDisabled" 
        v-if="auth.can([Roles.SCHEDULER])" 
      />
    </BaseThreeDotDropdown>
</template>
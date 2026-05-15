<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3'
import { usePromptDialog } from '@composables/usePromptDialog';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { useExamStatus } from '@/composables/useExamStatus';
import { Exam, ExamActionPermissions } from '@/interfaces/Exam';
import { RedirectUrl } from '@/interfaces/Interfaces';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const props = defineProps<{
  exam : Exam, 
  permissions:ExamActionPermissions
}>()

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
    const http = useHttp<{},RedirectUrl>()
    loadingSnackbar.open('Скачивание')
    http.get(`/exams/${props.exam.id}/documents/${document}/available`,{
      onSuccess:(response) => {
        if(response.redirectUrl){
          //modals.open('pdf', {url:response.redirectUrl})
          window.open(String(response.redirectUrl))
        }
      },
      onFinish:()=>{
        loadingSnackbar.close()
      }
    })
}

const modals = useModals()
const {isCancelled, isPending} = useExamStatus(props.exam)

const downloadResultslDisabled  = !props.exam?.documentsAvailable.results.available 
const downloadProtocolDisabled = !props.exam?.documentsAvailable.protocol.available 
const downloadListDisabled =  !props.exam?.documentsAvailable.list.available
const editDisabled  = !isPending.value || isCancelled.value 
const cancelDisabled = !isPending.value || isCancelled.value 
const downloadCodesDisabled  = !props.exam?.documentsAvailable.codes.available
</script>

<template>
    <BaseThreeDotDropdown>
      <BaseListItem 
        title="Кода" 
        :disabled="downloadCodesDisabled"
        @click="() => download('codes')" 
        v-if="permissions.codes"
      />

      <BaseListItem 
        title="Список"
        v-if="permissions.list"
        :disabled="downloadListDisabled"  
        @click="download('list')" 
      />

      <BaseListItem 
        title="Результаты"
        :subtitle="props.exam?.documentsAvailable.results.label"
        :disabled="downloadResultslDisabled" 
        v-if="permissions.results"
        @click="() => download('results')" 
      />

      <BaseListItem 
        title="Протокол" 
        v-if="permissions.protocol"
        :disabled="downloadProtocolDisabled"
        @click="() => download('protocol')" 
      />
      <v-divider v-if="permissions.edit || permissions.delete"></v-divider>
      <BaseListItem 
        title="Редактировать" 
        v-if="permissions.edit"
        @click="modals.open('examEdit', {exam:exam, onEdit:(exam:Exam) => emit('edit', exam)})"
        :disabled="editDisabled"
      />
      
      <BaseListItem 
        base-color="red"
        title="Отменить" 
        @click="cancelExam"
        :disabled="cancelDisabled" 
        v-if="permissions.delete"
      />
    </BaseThreeDotDropdown>
</template>
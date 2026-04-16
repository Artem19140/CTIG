<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { usePromptDialog } from '@composables/usePromptDialog';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '@interfaces/Interfaces';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { useModals } from '@composables/useModals';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';


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
const {isFinished, isCancelled, isPending} = useExamStatus(props.exam)
const isExaminer = props.exam?.examiners.some(e => e.id === auth.user.id) ?? false
const hasEnrollment  = computed(() => Boolean(props.exam?.enrollmentsCount))

const downloadStatementlDisabled  = !isFinished.value || isCancelled.value || !hasEnrollment.value || !isExaminer
const downloadProtocolDisabled = !isFinished.value || isCancelled.value || !hasEnrollment.value || !isExaminer
const downloadListDisabled =  !hasEnrollment.value
const editDisabled  = !isPending.value || isCancelled.value 
const cancelDisabled = !isPending.value || isCancelled.value 
const downloadCodesDisabled  =  isCancelled.value || !hasEnrollment.value || !isExaminer || !(props.exam?.codesAvailable ?? false)
console.log(!isPending.value , isCancelled.value )
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
        :disabled="downloadStatementlDisabled" 
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
        @click="modals.open('examEdit', {exam:exam})"
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
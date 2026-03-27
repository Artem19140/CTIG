<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { usePromptDialog } from '../../../../Composables/usePromptDialog';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '../../../../interfaces/interfaces';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useAlert } from '../../../../Composables/useAlert';
import { computed } from 'vue';
import { useAuth } from '../../../../Composables/useAuth';
import { Roles } from '../../../../Constants/Roles';

const props = defineProps<{exam : Exam | null}>()

const form = useForm({
  cancelledReason: ''
})

const canEdit = computed(() => !props.exam?.foreignNationals?.length)

const alert = useAlert()
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

const hasForeignNationals = computed(()=>!props.exam?.foreignNationalsCount && (props.exam?.foreignNationals?.length ?? 0) > 0)



const noForeignNationals = () => {
    if(!props.exam?.foreignNationalsCount && !(props.exam?.foreignNationals?.length ?? 0)){
        alert.open('На экзамен не записано ни одного ИГ!')
        return true
    }
    return false
}

const downloadForeignNationalsList = () => {
  if(noForeignNationals()) return
  window.open(`/exams/${props.exam?.id}/foreign-nationals/list`)
}

const formCodes = async () => {

    if(noForeignNationals()) return
    if(!props.exam?.id){
        return
    }
    window.open(`/exams/${props.exam.id}/codes`)
}

const downloadStatement = () => {
  if(noForeignNationals())return
  window.location.href = `/exams/${props.exam?.id}/statement`
}

const downloadProtocol = () => {

}

const {can} = useAuth()
</script>

<template>
    <ThreeDotDropdown v-if="!exam?.isCancelled">
      <AppListDropDownItem 
        title="Скачать кода" 
        
        @click="formCodes" 
        v-if="can([Roles.EXAMINER]) && hasForeignNationals"
      />

      <AppListDropDownItem 
        title="Скачать список" 
        @click="downloadForeignNationalsList" 
      />

      <AppListDropDownItem 
        title="Скачать ведомость" 
        v-if="exam?.isPast"
        @click="downloadStatement" 
      />

      <AppListDropDownItem 
        title="Скачать протокол" 
        v-if="exam?.isPast"
        @click="downloadProtocol" 
      />

      <AppListDropDownItem 
        title="Редактировать" 
        v-if="canEdit && can([Roles.SCHEDULER])" 
      />

      <AppListDropDownItem 
        color="text-red" 
        title="Отменить" 
        @click="cancelExam" 
        v-if="can([Roles.SCHEDULER])" 
      />
    </ThreeDotDropdown>
</template>
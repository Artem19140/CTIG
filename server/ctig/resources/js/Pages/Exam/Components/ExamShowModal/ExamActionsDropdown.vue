<script setup lang="ts">
import { modalState } from '../../../../Composables/modalState';
import { useForm } from '@inertiajs/vue3'
import { usePromptDialog } from '../../../../Composables/usePromptDialog';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '../../../../interfaces/interfaces';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useAlert } from '../../../../Composables/useAlert';
import { computed } from 'vue';
import { useAuth } from '../../../../Composables/useAuth';
import { Roles } from '../../../../Constants/Roles';

const props = defineProps<{exam : Exam}>()

const form = useForm({
  cancelledReason: ''
})

const canEdit = computed(() => !props.exam?.students?.length)

const { open } = usePromptDialog()
const cancelExam = async () => {
  const res = await open('Укажите причину отмены экзамена')
  if(!res){
    return
  }
  form.cancelledReason = res
  form.delete(`exams/${props.exam.id}`,{
    onSuccess:()=>{
      props.exam.isCancelled = true
    }
  })
  
}
const hasStudents = computed(()=>!props.exam?.studentsCount && props.exam?.students?.length > 0)
const noStudents = () => {
  console.log(props.exam)
    if(!props.exam?.studentsCount && !(props.exam?.students?.length > 0)){
        const {open} = useAlert()
        open('На экзамен не записано ни одного студента!')
        return true
    }
    return false
}

const downloadStudentsList = () => {
    if(noStudents()) return
    window.open(`/exams/${props.exam.id}/students/list`)
}

const formCodes = async () => {
    if(noStudents()) return
    if(!props.exam.id){
        return
    }
    window.open(`/exams/${props.exam.id}/codes`)
}

const {can} = useAuth()
</script>

<template>
    <ThreeDotDropdown>
      <AppListDropDownItem 
        title="Скачать кода" 
        
        @click="formCodes" 
        v-if="can([Roles.EXAMINER]) && hasStudents"
      />

      <AppListDropDownItem 
        title="Скачать список" 
        @click="downloadStudentsList" 
      />

      <AppListDropDownItem 
        title="Скачать ведомость" 
        v-if="!exam?.isPast" 
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
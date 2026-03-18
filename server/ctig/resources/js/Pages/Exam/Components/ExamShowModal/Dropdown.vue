<script setup lang="ts">
import { modalState } from '../../../../Composables/modalState';
import { useForm, usePage } from '@inertiajs/vue3'
import { usePromptDialog } from '../../../../Composables/usePromptDialog';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '../../../../interfaces/interfaces';

const props = defineProps<{exam : Exam}>()

const page = usePage<any>()

const form = useForm({
  cancelledReason: ''
})

const formCodes = async () => {
  console.log(1)
  if(!props.exam?.id){
    return
  }
  modalState.fileUrl = `/exams/${props.exam?.id}/codes`
}

const { open } = usePromptDialog()
const cancellExam = async () => {
  const res = await open('Укажите причину отмены экзамена')
  if(!res){
    return
  }
  form.cancelledReason = res
  form.delete(`exams/${props.exam.id}`,{
    onSuccess: () => {close()}
  })
  
}

const downloadStudList = () => {
  modalState.fileUrl = `exams/${props.exam.id}/students/list`
}
//Только для тестера!
</script>

<template>
  <v-btn-group density="compact" v-if="!exam?.isCancelled">
    <v-btn
      color="primary"
      variant="flat"
      @click="formCodes"
    >
      Скачать кода
    </v-btn>
    <v-divider vertical></v-divider>
    <v-menu>  
      <template v-slot:activator="{ props }">
        <v-btn v-bind="props" variant="outlined">
          <v-icon>mdi-chevron-down</v-icon>
        </v-btn>
      </template>
        
        <v-list>
          <AppListDropDownItem title="Скачать список" @click="downloadStudList" />

          <AppListDropDownItem title="Скачать ведомость" v-if="!exam?.isPast" />

          <AppListDropDownItem title="Редактировать" v-if="!exam?.students?.length" />

          <AppListDropDownItem color="text-red" title="Отменить" @click="cancellExam" />
        </v-list>
        
    </v-menu>
  </v-btn-group>
</template>
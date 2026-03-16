<script setup lang="ts">
import axios from 'axios';
import { modalState } from '../../../../Composables/modalState';
import { useForm, usePage } from '@inertiajs/vue3'
import { usePromptDialog } from '../../../../Composables/usePromptDialog';
import AppListItem from '../../../../Components/UI/AppListItem/AppListItem.vue';

const props = defineProps<{examId : number | null | undefined}>()

const page = usePage<any>()

const form = useForm({
  cancelledReason: ''
})

const formCodes = async () => {
  console.log(1)
  if(!props.examId){
    return
  }
  modalState.fileUrl = `/exams/${props.examId}/codes`
}

const { open } = usePromptDialog()
const cancellExam = async () => {
  const res = await open('Укажите причину отмены экзамена')
  if(!res){
    return
  }
  form.cancelledReason = res
  form.delete(`exams/${props.examId}`,{
    onSuccess: () => {close()}
  })
  
}

const downloadStudList = () => {
  modalState.fileUrl = `exams/${props.examId}/students/list`
}
//Только для тестера!
</script>

<template>
  <v-btn-group density="compact" v-if="page?.props.auth.user.name">
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
          <AppListItem title="Скачать список" @click="downloadStudList" />

          <AppListItem title="Скачать ведомость" />

          <AppListItem title="Редактировать" />

          <AppListItem title="Отменить" @click="cancellExam" />
        </v-list>
        
    </v-menu>
  </v-btn-group>
</template>
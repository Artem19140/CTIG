<script setup lang="ts">
import axios from 'axios';
import { modalState } from '../../../../Composables/modalState';
import { useForm, usePage } from '@inertiajs/vue3'
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import AppInput from '../../../../Components/UI/AppInput/AppInput.vue';

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

const { confirmOpen } = useConfirmDialog()
const cancellExam = async () => {
  const ok = await confirmOpen('Отменить экзамен?')
  if(!ok){
    return
  }
  form.delete(`exams/${props.examId}`,{
    onSuccess: () => {close()}
  })
  
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
          <AppInput title="Скачать список" />

          <AppInput title="Скачать ведомость" />

          <AppInput title="Редактировать" />

          <AppInput title="Отменить" @click="cancellExam" />
        </v-list>
        
    </v-menu>
  </v-btn-group>
</template>
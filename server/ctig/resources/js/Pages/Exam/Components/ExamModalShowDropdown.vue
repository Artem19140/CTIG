<script setup lang="ts">
import axios from 'axios';
import { modalState } from '../../../Composables/modalState';
import { useForm, usePage } from '@inertiajs/vue3'
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';

const props = defineProps<{examId : number | null | undefined}>()

const page = usePage<any>()

const form = useForm({
  cancelledReason: ''
})

const formCodes = async () => {
  if(!props.examId){
    return
  }
  const res = await axios.post(
      `/exams/${props.examId}/codes`,
      {},
      {
        responseType: 'blob'
      }
    )
    const file = new Blob([res.data], { type: 'application/pdf' })
    modalState.fileType = res.data.type
    modalState.fileUrl = URL.createObjectURL(file)
}


const { open, close } = useConfirmDialog()
const confirmOpen = async () => {
  open({
    subtitle:'Удаление экзамена',
    needConfirmText:true,
    inputPlaceholder:'Укажите причину отмены',
    onConfirm: async (reason:string = '')=> {
      form.cancelledReason = reason
      form.delete(`exams/${props.examId}`,{
        onSuccess: () => {close()}
      })
    }
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
          <v-list-item class="cursor-pointer" link>
            <v-list-item-title >
              Редактировать
            </v-list-item-title>
          </v-list-item>
          <v-list-item 
            class="cursor-pointer" 
            link
            @click="confirmOpen"
          >
            <v-list-item-title >
              Отменить
            </v-list-item-title>
          </v-list-item>
          
        </v-list>
        
    </v-menu>
  </v-btn-group>
</template>
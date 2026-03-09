<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import BaseDialog from '../BaseDialog/BaseDialog.vue';
import { modalState } from '../../../Composables/modalState';

const isOpen = ref(false)
const isPdf = computed(() => {
  if (!modalState.fileUrl) return false


  if (modalState.fileType) {
    return modalState.fileType.includes('/pdf')
  }


  return modalState.fileUrl.toLowerCase().endsWith('.pdf')
})

watch(() => modalState.fileUrl, () => {
    isOpen.value = true
})

</script>

<template>
    <BaseDialog
        v-model="isOpen" 
        width="1000"
        height = 1000
        title="Просмотр документа"
        @before-close="(done) =>{modalState.fileUrl = null, done()} "

    >
        <iframe 
            v-if="isPdf"
            :src="modalState.fileUrl"
            width="100%"
            height="100%"
        />
        <div v-else >
            <v-img 
                width="100%"
                height="90%"
                :src="modalState.fileUrl"
            /> 
            <div class="flex justify-center mt-8 gap-4">
                <v-btn color="primary">Скачать</v-btn>
                <v-btn >Заменить</v-btn>
            </div>
        </div>
        
    </BaseDialog>
</template>
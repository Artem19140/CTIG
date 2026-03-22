<script setup lang="ts">
import { computed } from 'vue';
import BaseDialog from '../BaseDialog/BaseDialog.vue';
const modalState  = {}
const isOpen = computed({
  get: () => !!modalState.fileUrl,
  set: (v) => {
    if (!v) modalState.fileUrl = null
  }
})

</script>

<template>
    <BaseDialog
        v-model="isOpen" 
        width="1000"
        height = 1000
        :fullscreen="true"
        title="Просмотр документа"
        @before-close="(done) =>{done(); modalState.fileUrl = null} "
    >
    <iframe 
        :src="modalState.fileUrl"
        width="100%"
        height="100%"
    />


    </BaseDialog>
</template>
<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Enrollment } from '@/interfaces/Interfaces';
import { onMounted } from 'vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})

onMounted(() => {

})
</script>

<template>
    <BaseDialog
        fullscreen
        v-model="isOpen"
        @before-close="(close) => close()"
        :title="`Говорение( ${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport} )`"
    >
        
    </BaseDialog>
</template>
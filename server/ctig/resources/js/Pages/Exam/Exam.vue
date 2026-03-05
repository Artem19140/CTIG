<script setup>

import ExamCreateModal from './Components/ExamCreateModal.vue';
import ExamList from './Components/ExamList.vue';

defineProps({ exams: Array })

</script>

<template>
    
    <v-btn
        v-bind="activatorProps"
        color="green"
        text="Добавить"
        variant="flat"
    ></v-btn>
    <div class="flex flex-col gap-4 w-auto">
        <div >
            <ExamCreateModal />
        </div>
        <ExamList :exams="exams" />
    </div>
    
</template>
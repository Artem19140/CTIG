<script setup lang="ts">
    import ExamCreateModal from './ExamCreateModal.vue';
    import ExamTable from './ExamTable.vue';

    const props = defineProps<{
        exams: any 
    }>()

</script>

<template>
    
    <div class="w-4/5 mt-30 mx-auto">
        <div class="mb-3 flex items-center gap-3">
            <div class="text-h1" style="font-size: 1.5rem;">Экзамены</div>
            <ExamCreateModal />
            <v-btn>Фильтры</v-btn>
        </div>

        <v-card  >
            <ExamTable :exams="exams.data" />
        </v-card>
    </div>
</template>
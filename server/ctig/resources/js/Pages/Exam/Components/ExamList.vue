<script setup lang="ts">
    import ExamCreateModal from './ExamCreateModal.vue';
    import ExamTable from './ExamTable.vue';
    import ExamTableDropDown from './ExamTableDropDown.vue';

    const props = defineProps<{
        exams: any 
    }>()
</script>

<template>
    
    <div class="w-[800px] mx-auto mt-16">
        <div class="mb-3 flex items-center gap-3">
            <div class="text-h1" style="font-size: 1.5rem;">Экзамены</div>
            <v-btn icon variant="text">
                <v-icon>mdi-filter-menu</v-icon>
            </v-btn>
            <v-spacer />
            <ExamCreateModal />
            <ExamTableDropDown />
        </div>

        <v-card>
            <ExamTable :exams="exams.data" width="700" />
        </v-card>
        

    </div>
</template>
<script setup lang="ts">
    import StudentCreateModal from './StudentCreateModal.vue';
    import StudentTable from './StudentTable.vue';
    import StudentFilters from './StudentFilters.vue';

    const props = defineProps<{
        students: any
    }>()

</script>

<template>
      <div class="w-4/5 mt-30 mx-auto">
        <div class="mb-3 flex items-center gap-3">
            <div class="text-h1" style="font-size: 1.5rem;">Студенты</div>
            <StudentCreateModal />
            <StudentFilters />
        </div>
    <v-card>
        <StudentTable :students="students.data"/>
    </v-card>
    </div>
</template>
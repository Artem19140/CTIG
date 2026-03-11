<script setup lang="ts">
    import StudentCreateModal from './StudentCreateModal.vue';
    import StudentTable from './StudentTable.vue';
    import StudentFilters from './StudentFilters.vue';
    import { ref, watch } from 'vue';
    import type { Student } from '../../../interfaces/interfaces';

    const props = defineProps<{
        students: any 
    }>()

    const loading = ref<boolean>(false)
    

</script>

<template>
    <div class="w-[800px] mx-auto mt-16">
        <div class="mb-3 flex items-center gap-3">
            <div class="text-h1" style="font-size: 1.5rem;">Студенты</div>
            <v-spacer></v-spacer>
            <StudentCreateModal />
        </div>
        <v-card>
            <template v-slot:text>
                <StudentFilters v-model="loading" />
                
             </template>
             
                <StudentTable :students="students" :loading="loading" />
        </v-card>
        
    </div>
</template>
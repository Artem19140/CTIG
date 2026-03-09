<script setup lang="ts">
    import ExamCreateModal from './ExamCreateModal.vue';
    import ExamTable from './ExamTable.vue';

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
            <v-menu>  
                <template v-slot:activator="{ props }">
                    <v-btn icon v-bind="props" variant="text">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>
                    
                <v-list>
                    <v-list-item class="cursor-pointer" link>
                        <v-list-item-title >
                        Скачать сертификаты ФИС ФРДО
                        </v-list-item-title>
                    </v-list-item>

                    <v-list-item class="cursor-pointer" link>
                        <v-list-item-title >
                        Скачать справки ФИС ФРДО
                        </v-list-item-title>
                    </v-list-item>

                    <v-list-item class="cursor-pointer" link>
                        <v-list-item-title >
                        Скачать плоскую таблицу
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
                
            </v-menu>
        </div>

        <v-card>
            <ExamTable :exams="exams.data" width="700" />
        </v-card>
        

    </div>
</template>
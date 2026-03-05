<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { formatterTime } from '../../../Helpers/heplers';

    const props = defineProps<{
        exams: any[]    // если это массив объектов
    }>()
    const isFull = (enrolled:number, capacity:number) => {
        return enrolled / capacity === 1
    }
</script>

<template>
    <v-card
        class="mx-auto"
        v-for="exam in exams" :key="exam.id"
        width="800px"
    >
        <template v-slot:title>
            <span class="font-weight-black">{{exam.name}}</span>
            
        </template>
        
        <v-card-item >
            <div class="flex justify-between">
                <div class="flex flex-col gap-4 w-auto">
                    <span>Дата: {{formatterDate(exam.beginTime)}}</span>
                    <span>Время: {{formatterTime(exam.beginTime)}}</span>
                </div>
                <div>
                    <span :class="{'text-red-500': isFull(13, exam.capacity)}">13/{{ exam.capacity }}</span>
                </div>
                
            </div>
            
        </v-card-item>
    </v-card>
</template>
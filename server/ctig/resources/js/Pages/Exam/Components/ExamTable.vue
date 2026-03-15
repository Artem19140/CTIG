<script setup lang="ts">
    import { ref } from 'vue';
    import { formatterTime , formatterDate } from '../../../Helpers/heplers';
    import ExamShowModal from './ExamShowModal/ExamShowModal.vue';

   const props = defineProps<{
        exams: any
        width?: string
    }>()
    const headers = [
            {title : "Название",sortable: false, key: 'shortName', align: 'start' },
            {title : "Дата",sortable: false, key: 'beginTime', align: 'start' },
            {title : "Запись",sortable: false, key: 'studentsCount', align: 'start' },
        ]

    const openModal = (event :Event, {item} :any) => {
        examId.value = null
        examId.value = item.id
        showModal.value = true
    }

    const showModal = ref(false)
    const examId = ref()
</script>

<template>
    <v-data-table-server
        :headers="headers"
        :items="exams"
        :width="width"
        hover
        @click:row="openModal"
    >
    <!-- <td :class="{'text-red-500': ((item.studentsCount / item.capacity) === 1)}">{{` ${item.studentsCount }/${ item.capacity }`}}</td> -->
        <template #item.beginTime="{item}">
            {{ formatterDate(item.beginTime) }} {{ formatterTime(item.beginTime) }}
        </template>
        <template #item.studentsCount="{item}">
            <div :class="{'text-red-500': ((item.studentsCount / item.capacity) === 1)}">{{` ${item.studentsCount }/${ item.capacity }`}}</div>
        </template>
    </v-data-table-server>
    <ExamShowModal v-model="showModal" :id="examId" />
</template>
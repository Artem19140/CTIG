<script setup lang="ts">
    import { ref } from 'vue';
    import { formatterTime , formatterDate } from '../../../Helpers/heplers';
    import ExamShowModal from './ExamShowModal.vue';

   const props = defineProps<{
        exams: any
        width?: string
    }>()
    const headers = [
            {title : "Название",sortable: false, key: 'name'},
            {title : "Дата",sortable: false, key: 'beginTime'},
            {title : "Запись",sortable: false, key: 'enrollment'},
        ]

    const openModal = (id:number) => {
        examId.value = null
        examId.value = id
        showModal.value = true
    }

    const showModal = ref(false)
    const examId = ref()
</script>

<template>
    <v-data-table
        :headers="headers"
        :items="exams"

        :width="width"
        hover
    >
        <template v-slot:item="{item}">
            <tr @click="openModal(item.id)">
                <td>{{ item.name }}</td>
                <td>{{ formatterDate(item.beginTime) }} {{ formatterTime(item.beginTime) }}</td>
                <td :class="{'text-red-500': ((item.studentsCount / item.capacity) === 1)}">{{` ${item.studentsCount }/${ item.capacity }`}}</td>
            </tr>

        </template>
    </v-data-table>
    <ExamShowModal v-model="showModal" :id="examId" />
</template>
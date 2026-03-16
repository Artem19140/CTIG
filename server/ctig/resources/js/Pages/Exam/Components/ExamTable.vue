<script setup lang="ts">
    import { formatterTime , formatterDate } from '../../../Helpers/heplers';
    import { useExamShowModal } from '../../../Composables/modalWindows/useExamShowModal';

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
       const {open} = useExamShowModal()
        open(item.id)
    }
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
</template>
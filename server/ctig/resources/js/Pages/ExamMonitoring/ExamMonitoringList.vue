<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import { formatterDate, formatterTime } from '../../Helpers/heplers';
import ExamActionsDropdown from '../Exam/Components/ExamShowModal/ExamActionsDropdown.vue';

defineOptions({
  layout: EmployeeLayout,
})
const props = defineProps<{
    exams:any
}>()

const headers = [
    {title:'Название', key:"shortName",sortable:false},
    {title:'Дата и время', key:"beginTime",sortable:false},
    {title:'Запись', key:"capacity", sortable:false, align:'center'},
    {title:'', key:"actions", sortable:false, align:'center'}
]

const open = (event:Event, {item} : any) => {
    router.visit(`/exams/${item.id}/monitoring`)
}
</script>

<template>
    <v-card width="500" class="mx-auto mt-32">
        <v-card-title>
            Экзамены для мониторинга
        </v-card-title>

        <v-card-text>
            <v-data-table
                :items="exams.data ?? []"
                :headers="headers"
                hover
   
                hide-default-footer
                @click:row="open"
            >
                <template #item.capacity="{ item }">
                    {{` ${item.studentsCount }/${ item.capacity }`}}
                </template>
                <template #item.actions="{ item }">
                    <ExamActionsDropdown :exam="item" />
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>
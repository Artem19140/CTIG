<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    exams:any
}>()

const headers = [
    {title:'Название', key:"shortName",sortable:false},
    {title:'Дата и время', key:"beginTime",sortable:false},
    {title:'Запись', key:"capacity", sortable:false, align:'center'}
]

const open = (event:Event, {item} : any) => {
    router.visit(`/exams/${item.id}/monitoring`)
}
</script>

<script lang="ts">
import { formatterDate, formatterTime } from '../../Helpers/heplers';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

export default {
    layout: EmployeeLayout,
}
</script>

<template>
    <v-card width="500" class="mx-auto mt-32">
        <v-card-title>
            Экзамены для мониторинга
        </v-card-title>

        <v-card-text>
            <v-data-table
                :items="exams.data"
                :headers="headers"
                hover
                hide-default-footer
                @click:row="open"
            >
                <template #item.beginTime="{ item }">
                    {{ formatterTime(item?.beginTime)  ?? '-'}}, {{ formatterDate(item?.beginTime)  ?? '-'}}
                </template>
            </v-data-table>
            
        </v-card-text>
    </v-card>
</template>
<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { formatterTime } from '../../../Helpers/heplers';
    import ExamCreateModal from './ExamCreateModal.vue';

    const props = defineProps<{
        exams: any[]    // если это массив объектов
    }>()

    const isFull = (enrolled:number, capacity:number) => {
        return enrolled / capacity === 1
    }

    const headers = [
        {title : "Название",sortable: false, key: 'name'},
        {title : "Дата",sortable: false, key: 'date'},
        {title : "Время",sortable: false, key: 'time'},
        {title : "Адрес",sortable: false, key: 'address'},
        {title : "Тестеры",sortable: false, key: 'testers'},
        {title : "Запись",sortable: false, key: 'enrollment'},
        {title : "Действия",sortable: false, key: 'enrollment'}
    ]

</script>

<template>
    <v-card  class="w-4/5 mt-30 mx-auto">
        <ExamCreateModal />
        <v-data-table
            :headers="headers"
            :items="exams"
            hide-default-footer
            width="1000"
        >

            <template v-slot:item="{item}">
                <tr>
                    <td>{{ item.name }}</td>
                    <td>{{ formatterDate(item.beginTime) }}</td>
                    <td>{{ formatterTime(item.beginTime) }}</td>
                    <td>{{ item.address }}</td>
                    <td></td> <!--  {{ item.testers[1] }} -->
                    <td :class="{'text-red-500': isFull(13, item.capacity)}">13/{{ item.capacity }}</td>
                    <td>
                        1
                    </td>
                </tr>

            </template>
        </v-data-table>
    </v-card>
</template>
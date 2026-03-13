<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { modalState } from '../../../Composables/modalState'
    import AppPaginator from '../../../Components/UI/AppPaginator/AppPaginator.vue';
    import type { Student } from '../../../interfaces/interfaces';

    function studentShowModal(id: number) {
        modalState.studentId = id  
    }

   const props =defineProps<{
        students: any | null, // почему студенты то ?
        loading : boolean
    }>()

    const headers = [
        {title : "ID",sortable: false, key: 'id', align: 'start' },
        {title : "ФИО",sortable: false, key: 'fio', align: 'start' },
        {title : "Дата рождения",sortable: false, key: 'dateBirth', align: 'start' },
        {title : "Паспорт",sortable: false, key: 'passport', align: 'start' }
    ]
</script>

<template>

    <v-data-table
            :headers="headers"
            :items="students.data"
            key="id"
            width="1000px"
            hover
            :loading="loading"
        >
        
            <template v-slot:item="{item}">
                <tr @click="studentShowModal(item.id)" class="cursor-pointer">
                    <td>{{ item?.id  ?? ''}}</td>
                    <td>{{ item?.surname  ?? ''}} {{ item?.name?.[0]  ?? ''}}. {{ item?.patronymic?.[0] ?? '' }}.</td>
                    <td>{{ formatterDate(item?.dateBirth)  ?? '-'}}</td>
                    <td>{{ item?.passportSeries  ?? ''}} {{ item?.passportNumber  ?? ''}}</td>
                </tr>
            </template>
            <template v-slot:bottom>
                <app-paginator :obj="students" /> <!--// почему студенты то ?-->
            </template>
    </v-data-table>
</template>
<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { modalState } from '../../../Composables/modalState'

    function studentShowModal(id: number) {
        modalState.studentId = id  
    }

   const props =defineProps<{
        students: any[],
        loading : boolean
    }>()

    const headers = [
        {title : "ID",sortable: false, key: 'id'},
        {title : "ФИО",sortable: false, key: 'fi'},
        {title : "Дата рождения",sortable: false, key: 'dateBirth'},
        {title : "Паспорт",sortable: false, key: 'passport'}
    ]

</script>

<template>

    <v-data-table
            :headers="headers"
            :items="students"
            key="id"
            width="1000px"
            hover
            :loading="loading"
        >
        
            <template v-slot:item="{item}">
                <tr @click="studentShowModal(item.id)" class="cursor-pointer">
                    <td>{{ item.id  ?? ''}}</td>
                    <td>{{ item.surname  ?? ''}} {{ item.name[0]  ?? ''}}. {{ item.patronymic[0] ?? '' }}.</td>
                    <td>{{ formatterDate(item.dateBirth)  ?? '-'}}</td>
                    <td>{{ item.passportSeries  ?? ''}} {{ item.passportNumber  ?? ''}}</td>
                </tr>
            </template>
    </v-data-table>
</template>
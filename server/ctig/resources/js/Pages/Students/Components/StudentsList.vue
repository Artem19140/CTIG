<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import StudentCreateModal from './StudentCreateModal.vue';

    const props = defineProps<{
        students: any[]
    }>()

    const headers = [
        {title : "ID",sortable: false, key: 'id'},
        {title : "Фамилия",sortable: false, key: 'surname'},
        {title : "Имя",sortable: false, key: 'name'},
        {title : "Дата рождения",sortable: false, key: 'dateBirth'},
        {title : "Серия",sortable: false, key: 'passportSeries'},
        {title : "Номер",sortable: false, key: 'passportNumber'},
        {title : "Гражданство",sortable: false, key: 'citizenship'}
    ]

</script>

<template>
    <v-card class="w-4/5 mt-30 mx-auto">
        <StudentCreateModal />
        <v-data-table
            :headers="headers"
            :items="students"
            hide-default-footer
            width="1000"
        >

            <template v-slot:item="{item}">
                <tr>
                    <td>{{ item.id }}</td>
                    <td>{{ item.surname }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ formatterDate(item.dateBirth) }}</td>
                    <td>{{ item.passportSeries }}</td>
                    <td>{{ item.passportNumber }}</td>
                    <td>{{ item.citizenship }}</td>
                </tr>
            </template>
        </v-data-table>
    </v-card>
</template>
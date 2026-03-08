<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { modalState } from '../../../Composables/modalState'

    function studentShowModal(id: number) {
        modalState.studentId = id  
    }

   const props = withDefaults(defineProps<{
        students: any[]
        width?: string
    }>(), {
        students: () => [],
        width: "100%"          
    });

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

    <v-data-table
            :headers="headers"
            :items="students"
            key="id"
            hide-default-footer
            :width="width"
            hover
        >
            <template v-slot:item="{item}">
                <tr @click="studentShowModal(item.id)" class="cursor-pointer">
                    <td>{{ item.id }}</td>
                    <td>{{ item.surname }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ formatterDate(item.dateBirth) }}</td>
                    <td>{{ item.passportSeries }}</td>
                    <td>{{ item.passportNumber }}</td>
                    <td>{{ item.citizenship }}</td>
                    <!-- creator -->
                </tr>
            </template>
        </v-data-table>
</template>
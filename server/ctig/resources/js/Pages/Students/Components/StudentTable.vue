<script setup lang="ts">
    import { ref } from 'vue';
import StudentShowModal from './StudentShowModal.vue';
import { formatterDate } from '../../../Helpers/heplers';

   const props = withDefaults(defineProps<{
        students: any[]
        width?: string
    }>(), {
        students: () => [], // функция возвращает дефолтный массив
        width: "100%"           // дефолтное число
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

    const showModal = ref(false)
    const studentId = ref()

    const openShowModal = (id : number ) => {
        studentId.value = null
        studentId.value = id
        showModal.value = true
    }

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
                <tr @click="openShowModal(item.id)">
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
        <StudentShowModal v-model="showModal" :id="studentId"/>
</template>
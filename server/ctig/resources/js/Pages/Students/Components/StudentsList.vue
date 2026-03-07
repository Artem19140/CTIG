<script setup lang="ts">
    import { ref } from 'vue';
    import { formatterDate } from '../../../Helpers/heplers';
    import StudentCreateModal from './StudentCreateModal.vue';
    import StudentShowModal from './StudentShowModal.vue';
import StudentTable from './StudentTable.vue';

    const props = defineProps<{
        students: any[]
    }>()

    // const headers = [
    //     {title : "ID",sortable: false, key: 'id'},
    //     {title : "Фамилия",sortable: false, key: 'surname'},
    //     {title : "Имя",sortable: false, key: 'name'},
    //     {title : "Дата рождения",sortable: false, key: 'dateBirth'},
    //     {title : "Серия",sortable: false, key: 'passportSeries'},
    //     {title : "Номер",sortable: false, key: 'passportNumber'},
    //     {title : "Гражданство",sortable: false, key: 'citizenship'}
    // ]

    // const showModal = ref(false)
    // const studentId = ref()

    // const openShowModal = (id : number ) => {
    //     studentId.value = null
    //     studentId.value = id
    //     showModal.value = true
    // }

</script>

<template>
      <div class="w-4/5 mt-30 mx-auto">
        <div class="mb-3 flex items-center gap-3">
            <div class="text-h1" style="font-size: 1.5rem;">Студенты</div>
            <StudentCreateModal />
            <v-btn>Фильтры</v-btn>
        </div>
    <v-card>

        <!-- <v-data-table
            :headers="headers"
            :items="students"
            key="id"
            hide-default-footer
            width="1000"
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

                </tr>
            </template>
        </v-data-table> -->
        <StudentTable :students="students" width="500" />
    </v-card>
    </div>
    <!-- <StudentShowModal v-model="showModal" :id="studentId"/> -->
</template>
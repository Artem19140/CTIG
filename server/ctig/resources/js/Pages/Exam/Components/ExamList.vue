<script setup lang="ts">
    import { ref } from 'vue';
    import { formatterDate } from '../../../Helpers/heplers';
    import { formatterTime } from '../../../Helpers/heplers';
    import ExamCreateModal from './ExamCreateModal.vue';
    import ExamShowModal from './ExamShowModal.vue';

    const props = defineProps<{
        exams: any[]   
    }>()

    const headers = [
        {title : "Название",sortable: false, key: 'name'},
        {title : "Дата",sortable: false, key: 'date'},
        {title : "Время",sortable: false, key: 'time'},
        {title : "Адрес",sortable: false, key: 'address'},
        {title : "Запись",sortable: false, key: 'enrollment'},
        {title : "Действия",sortable: false}
    ]

    const openModal = (id:number) => {
        examId.value = null
        examId.value = id
        showModal.value = true
    }

    const showModal = ref(false)
    const examId = ref()

</script>

<template>
    
    <div class="w-4/5 mt-30 mx-auto">
        <div class="mb-3 flex items-center gap-3">
            <div class="text-h1" style="font-size: 1.5rem;">Экзамены</div>
            <ExamCreateModal />
            <v-btn>Фильтры</v-btn>
        </div>

        <v-card  >
            <v-data-table
                :headers="headers"
                :items="exams"
                hide-default-footer
                width="1000"
                hover
                title=""
            >

                <template v-slot:item="{item}">
                    <tr @click="openModal(item.id)">
                        <td>{{ item.name }}</td>
                        <td>{{ formatterDate(item.beginTime) }}</td>
                        <td>{{ formatterTime(item.beginTime) }}</td>
                        <td>{{ item.address }}</td>
                        <td :class="{'text-red-500': ((item.studentsCount / item.capacity) === 1)}">{{` ${item.studentsCount }/${ item.capacity }`}}</td>
                        <td @click.stop">
                            1
                        </td>
                    </tr>

                </template>
            </v-data-table>
        </v-card>
    </div>
    <ExamShowModal v-model="showModal" :id="examId" />
</template>
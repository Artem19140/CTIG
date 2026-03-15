<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { modalState } from '../../../Composables/modalState'
    import AppPaginator from '../../../Components/UI/AppPaginator/AppPaginator.vue';
    import type { Student } from '../../../interfaces/interfaces';
    import { useStudentShowModal } from '../../../Composables/modalWindows/useStudentShowModal';

    

    function studentShowModal(event :Event, {item} : any) {
        const {open} = useStudentShowModal()
        open(item.id)
    }

   const props =defineProps<{
        students: any | null, // почему студенты то ?
        loading : boolean
    }>()

    const headers = [
        {title : "ID",sortable: false, key: 'id', align: 'start' },
        {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
        {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
        {title : "Дата рождения",sortable: false, key: 'dateBirth', align: 'center' }
    ]
</script>

<template>
    <v-data-table
            :headers="headers"
            :items="students.data"
            @click:row="studentShowModal"
            key="id"
            width="1000px"
            hover
            :loading="loading"
        >
            <template  #item.dateBirth="{ item }">
                {{ formatterDate(item?.dateBirth)  ?? '-'}}
            </template>
            <template v-slot:bottom>
                <app-paginator :obj="students" />
            </template>
    </v-data-table>
</template>
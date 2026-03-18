<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { useStudentShowModal } from '../../../Composables/modalWindows/useStudentShowModal';
    import StudentCreateModal from './StudentCreateModal.vue';
    import type { Student, Paginated } from '../../../interfaces/interfaces';
    import BaseServerTable from '../../../Components/BaseServerTable.vue';

    function studentShowModal(item : any) {
        const {open} = useStudentShowModal()
        open(item.id)
    }

   const props =defineProps<{
        students: Paginated<Student>
    }>()

    const headers = [
        {title : "ID",sortable: false, key: 'id', align: 'start' },
        {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
        {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
        {title : "Дата рождения",sortable: false, key: 'dateBirth', align: 'center' }
    ]
</script>

<template>
    <BaseServerTable
        :headers="headers"
        :elements="students"
        title="Студенты"
        @row-click="studentShowModal"
    >
        <template #toolbar-left>
            <v-btn icon variant="text">
                <v-icon>mdi-filter-menu</v-icon>
            </v-btn>
        </template>
        <template #toolbar-actions>
            <StudentCreateModal />
        </template>

        <template  #item.dateBirth="{ item }">
            {{ formatterDate(item?.dateBirth)  ?? '-'}}
        </template>

    </BaseServerTable>
</template>
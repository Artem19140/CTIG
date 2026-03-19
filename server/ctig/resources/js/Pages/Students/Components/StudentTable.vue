<script setup lang="ts">
    import { formatterDate } from '../../../Helpers/heplers';
    import { useStudentShowModal } from '../../../Composables/modalWindows/useStudentShowModal';
    import StudentCreateModal from './StudentCreateModal.vue';
    import type { Student, Paginated } from '../../../interfaces/interfaces';
    import BaseServerTable from '../../../Components/BaseServerTable.vue';
    import StudentTableFilters from './StudentTableFilters.vue';
    import { useForm } from '@inertiajs/vue3';

    function studentShowModal(item : any) {
        const {open} = useStudentShowModal()
        open(item.id)
    }

   const props = defineProps<{
        students: Paginated<Student>,
        filters:any
    }>()

    const headers = [
        {title : "ID",sortable: false, key: 'id', align: 'start' },
        {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
        {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
        {title : "Дата рождения",sortable: false, key: 'dateBirth', align: 'center' }
    ]

    const formFilters = useForm({
        surname: props.filters.surname ?? undefined,
        name: props.filters.name ?? undefined,
        patronymic: props.filters.patronymic ?? undefined,
        passportSeries: props.filters.passportSeries ?? undefined,
        passportNumber: props.filters.passportNumber ?? undefined,
        id:props.filters.id ?? undefined,
    })
</script>

<template>
    <BaseServerTable
        :loading="formFilters.processing"
        :headers="headers"
        :elements="students"
        title="Студенты"
        @row-click="studentShowModal"
    >
        <template #toolbar-left>
            <StudentTableFilters 
                :filters="filters" 
                :form="formFilters" 
            />
        </template>
        <template #toolbar-actions>
            <StudentCreateModal />
        </template>

        <template  #item.dateBirth="{ item }">
            {{ formatterDate(item?.dateBirth)  ?? '-'}}
        </template>

    </BaseServerTable>
</template>
<script setup lang="ts">
import ForeignNationalCreateModal from '../ForeignNationalCreateModal.vue';
import type { ForeignNational, Paginated } from '../../../../interfaces/interfaces';
import BaseServerTable from '../../../../Components/BaseServerTable.vue';
import ForeignNationalTableFilters from './ForeignNationalTableFilters.vue';
import { useForm } from '@inertiajs/vue3';
import { useModals } from '../../../../Composables/useModals';
import AddButton from '../../../../Components/AddButton/AddButton.vue';
import ForeignNationalTableDropdown from './ForeignNationalTableDropdown.vue';
const {open} = useModals()

function foreignNationalShowModal(item : any) {
    open('foreignNationalShow', {foreignNationalId:item.id})
}

const props = defineProps<{
    foreignNationals: Paginated<ForeignNational>,
    filters:any
}>()

const headers = [
    {title : "ID",sortable: false, key: 'id', align: 'center' },
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
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
        :elements="foreignNationals"
        :page="filters.page"
        :items-per-page="filters.perPage"
        title="ИГ"
        @row-click="foreignNationalShowModal"
    >
        <template #toolbar-left>
            <ForeignNationalTableFilters 
                :filters="filters" 
                :form="formFilters" 
            />
        </template>
        <template #toolbar-actions>
            <AddButton
                text="Добавить"
                @click="open('foreignNationalCreate')"
            />
            <ForeignNationalTableDropdown />
        </template>
    </BaseServerTable>
</template>
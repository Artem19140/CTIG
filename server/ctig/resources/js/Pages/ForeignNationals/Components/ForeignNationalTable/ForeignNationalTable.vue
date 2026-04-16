<script setup lang="ts">
import type { ForeignNational, Paginated } from '@interfaces/Interfaces';
import BaseServerTable from '@components/BaseComponents/BaseServerTable/BaseServerTable.vue';
import ForeignNationalTableFilters from './ForeignNationalTableFilters.vue';
import { useForm } from '@inertiajs/vue3';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ForeignNationalTableDropdown from './ForeignNationalTableDropdown.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@/constants/Roles';
import AppPaginator from '@/components/UI/AppPaginator/AppPaginator.vue';
import { ref } from 'vue';

const modals = useModals()

function foreignNationalShowModal(item : any) {
    modals.open('foreignNationalShow', {foreignNationalId:item.id})
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

const auth = useAuth()
const loading = ref<boolean>(false)
</script>

<template>
    <BaseServerTable
        :loading="loading"
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
            <AppAddButton
                text="Добавить"
                @click="modals.open('foreignNationalCreate')"
            />
            <ForeignNationalTableDropdown  v-if="auth.can([Roles.DIRECTOR])" />
        </template>
        <template #bottom="{ page, itemsPerPage, pageCount, setPage }">
            <AppPaginator
                :meta="foreignNationals.meta"
                :links="foreignNationals.links"
                v-model="loading"
            />
        </template>
    </BaseServerTable>
</template>
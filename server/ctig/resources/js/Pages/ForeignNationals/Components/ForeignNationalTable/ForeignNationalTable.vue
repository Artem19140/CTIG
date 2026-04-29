<script setup lang="ts">
import type { ForeignNational, Paginated } from '@interfaces/Interfaces';
import BasePaginatedTable from '@components/BaseComponents/BasePaginatedTable/BasePaginatedTable.vue';
import ForeignNationalTableFilters from './ForeignNationalTableFilters.vue';
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
    foreignNationals: Paginated<ForeignNational>
}>()

const headers = [
    {title : "ID",sortable: false, key: 'id', align: 'center' },
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
]

const auth = useAuth()
const loading = ref<boolean>(false)
</script>

<template>
    <BasePaginatedTable
        :loading="loading"
        :headers="headers"
        :elements="foreignNationals"
        title="Иностранные граждане"
        @row-click="foreignNationalShowModal"
    >
        <template #toolbar-left>
            <ForeignNationalTableFilters  
                v-model="loading"
            />
        </template>
        <template #toolbar-actions>
            <AppAddButton
                text="Добавить"
                @click="modals.open('foreignNationalCreate')"
            />
            <ForeignNationalTableDropdown  v-if="auth.can([Roles.DIRECTOR])" />
        </template>
        <template #bottom>
            <AppPaginator
                :meta="foreignNationals.meta"
                :links="foreignNationals.links"
                v-model="loading"
            />
        </template>
    </BasePaginatedTable>
</template>
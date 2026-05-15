<script setup lang="ts">
import type { Paginated } from '@interfaces/Interfaces';
import BasePaginatedTable from '@components/BaseComponents/BasePaginatedTable/BasePaginatedTable.vue';
import ForeignNationalTableFilters from './ForeignNationalTableFilters.vue';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ForeignNationalTableDropdown from './ForeignNationalTableDropdown.vue';
import { computed, ref } from 'vue';
import { ForeignNationalIndex, ForeignNationalPagePermissions } from '@/interfaces/ForeignNational';

const modals = useModals()

function foreignNationalShowModal(item : any) {
    modals.open('foreignNationalShow', {foreignNationalId:item.id})
}

const props = defineProps<{
    foreignNationals: Paginated<ForeignNationalIndex>,
    permissions:ForeignNationalPagePermissions
}>()

const headers = [
    {title : "ID",sortable: false, key: 'id', align: 'center' },
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
]

const loading = ref<boolean>(false)
const dropDownAccess = computed(() => 
    props.permissions.ministryEducation ||
    props.permissions.export ||
    props.permissions.statistics
)
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
                v-if="permissions.create"
            />
            <ForeignNationalTableDropdown  
                v-if="dropDownAccess"
                :permissions="permissions"
            />
        </template>
    </BasePaginatedTable>
</template>
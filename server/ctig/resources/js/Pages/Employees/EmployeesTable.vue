<script setup lang="ts">
import Dropdown from './Dropdown.vue';
import BaseServerTable from '../../Components/UI/BaseServerTable.vue';
import EmployeeCreateModal from './EmployeeCreateModal.vue';

const props = defineProps<{
  employees : any
}>()

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Должность",sortable: false, key: 'jobTitle', align: 'center' },
    {title : "",sortable: false, key: 'actions', align: 'center' }
]
</script>

<template>
    <BaseServerTable 
        title="Сотрудники"
        :headers="headers"
        :elements="employees"
    >
        <template #toolbar-actions>
            <EmployeeCreateModal />
        </template>
        <template #item.actions="{item}">
            <Dropdown :employee="item" />
        </template>
    </BaseServerTable>
</template>
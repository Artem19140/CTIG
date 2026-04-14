<script setup lang="ts">
import Dropdown from './Dropdown.vue';
import BaseServerTable from '@components/BaseComponents/BaseServerTable/BaseServerTable.vue';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';

const props = defineProps<{
  employees : any
}>()

const {open} = useModals()

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Должность",sortable: false, key: 'jobTitle', align: 'start' },
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
            <AppAddButton text="Добавить" 
            @click="open('employeeCreate')" />
        </template>
        <template #item.actions="{item}">
            <Dropdown :employee="item" />
        </template>
    </BaseServerTable>
</template>
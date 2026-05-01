<script setup lang="ts">
import Dropdown from './Dropdown.vue';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import BaseTable from '@/components/BaseComponents/BaseTable/BaseTable.vue';
import AppBorderedButton from '@/components/UI/AppBorderedButton/AppBorderedButton.vue';
import { Employee } from '@/interfaces/Employee';

const props = defineProps<{
    employees : Employee[]
}>()

const {open} = useModals()

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Должность",sortable: false, key: 'jobTitle', align: 'start' },
    {title : "email",sortable: false, key: 'email', align: 'start' },
    {title : "",sortable: false, key: 'actions', align: 'center' }
]
</script>

<template>
    <BaseTable 
        :headers="headers"
        :items="employees"
        toolbarColor="white"
        class="p-2"
    >
        <template #toolbar-actions>
            <div class="flex gap-4">
                <AppBorderedButton 
                    text="Уволенные"
                />
                <AppAddButton text="Добавить" 
                    @click="open('employeeCreate')" 
                />
            </div>
        </template>
        <template #item.actions="{item}">
            <Dropdown :employee="item" />
        </template>
    </BaseTable>
</template>
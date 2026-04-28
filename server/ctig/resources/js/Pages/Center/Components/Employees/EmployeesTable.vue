<script setup lang="ts">
import Dropdown from './Dropdown.vue';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import { User } from '@/interfaces/Interfaces';
import BaseTable from '@/components/BaseComponents/BaseTable/BaseTable.vue';

const props = defineProps<{
    employees : User[]
}>()

const {open} = useModals()

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Должность",sortable: false, key: 'jobTitle', align: 'start' },
    {title : "",sortable: false, key: 'actions', align: 'center' }
]
</script>

<template>
    <BaseTable 
        :headers="headers"
        :items="employees"
        toolbarColor="white"
    >
        <template #toolbar-actions>
            <div class="flex gap-4">
                <v-btn
                    @click=""
                    border
                >
                    Уволенные
                </v-btn>
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
<script setup lang="ts">
import Dropdown from './Dropdown.vue';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import BaseTable from '@/components/BaseComponents/BaseTable/BaseTable.vue';
import AppBorderedButton from '@/components/UI/AppBorderedButton/AppBorderedButton.vue';
import { Employee } from '@/interfaces/Employee';
import { Head } from '@inertiajs/vue3';
import DetailsDropdown from '@/components/UI/DetailsDropdown/DetailsDropdown.vue';
import BaseList from '@/components/BaseComponents/BaseList/BaseList.vue';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const props = defineProps<{
    employees : Employee[]
}>()

const {open} = useModals()

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Должность",sortable: false, key: 'jobTitle', align: 'start' },
    {title : "email",sortable: false, key: 'email', align: 'start' },
    {title : "Роли",sortable: false, key: 'roles', align: 'center' },
    {title : "",sortable: false, key: 'actions', align: 'center' }
]
</script>

<template>
    <Head>
        <title>Сотрудники</title>
    </Head>
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

        <template #item.roles="{item}">
            <DetailsDropdown>
                <BaseList width="300" density="compact">
                    <BaseListItem
                        v-for="role in item.roles"
                    >
                        <div class="whitespace-pre">
                            {{ role.label }}
                        </div>
                        
                    </BaseListItem>
                    <div v-if="item.roles.length === 0">Роли не назначены</div>
                </BaseList>
            </DetailsDropdown>
        </template>
        
        <template #item.actions="{item}">
            <Dropdown :employee="item" />
        </template>
    </BaseTable>
</template>


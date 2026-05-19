<script setup lang="ts">
import EmployeeLayout from '@/layouts/EmployeeLayout.vue';
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue';
import EmployeesTable from '../Center/Components/Employees/EmployeesTable.vue';
import { Center } from '@/interfaces/Center';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';

const props = defineProps<{
    center: {
        data:Center
    }
}>()

defineOptions({
  layout: [EmployeeLayout, SuperAdminLayout],
})

const headers = [
    {title : "ID",sortable: false, key: 'id', align: 'center' }, 
    {title : "Название",sortable: false, key: 'shortName', align: 'start' },
    {title : "Сотрудников",sortable: false, key: 'employeesCount', align: 'center' }
]
</script>

<template>
    <BaseContainer>
        <EmployeesTable
            :employees="center.data.employees"
            :headers="headers"
        />
    </BaseContainer>
</template>
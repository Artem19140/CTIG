<script setup lang="ts">
import { computed, ref } from 'vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import CenterData from './Components/Data/CenterData.vue';
import EmployeesTable from './Components/Employees/EmployeesTable.vue';
import { router } from '@inertiajs/vue3';
import { useAuth } from '@/composables/useAuth';
import { Employee } from '@/interfaces/Employee';
import { Address } from '@/interfaces/Address';
import AddressesList from './Components/Addresses/AddressesList.vue';

defineOptions({
  layout: [EmployeeLayout],
})

const {user} = useAuth()
const centerId = computed(() => user.center_id)

const props = defineProps<{
    tab: Tab
    data?: {
        data:any
    },
    employees?:{
        data:Employee[]
    } 
    addresses?:{
        data:Address[]
    } 
}>()

type Tab = 'data' | 'employees' | 'addresses'

const tab = ref<Tab>(props.tab ?? 'data')

const visit = (route : string) => {
    router.visit(`/centers/${centerId.value}${route}`)
}
</script>

<template>
    <BaseContainer>
        <v-tabs v-model="tab" color="primary">
            <v-tab value="data" @click="() => visit('')">Данные</v-tab>
            <v-tab value="employees" @click="() => visit('/employees')">Сотрудники</v-tab>
            <v-tab value="addresses" @click="() => visit('/addresses')">Адреса</v-tab>
        </v-tabs>

        <v-divider></v-divider>

        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="data" v-if="data?.data">
                <CenterData :data="data.data" />
            </v-tabs-window-item>

            <v-tabs-window-item value="employees" v-if="employees?.data">
                <EmployeesTable :employees="employees.data" />
            </v-tabs-window-item>

            <v-tabs-window-item value="addresses" v-if="addresses">
                <AddressesList :addresses="addresses.data" />
            </v-tabs-window-item>
        </v-tabs-window>
    </BaseContainer>
</template>
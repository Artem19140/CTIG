<script setup lang="ts">
import AppAutocomplete from '@/components/UI/AppAutocomplete/AppAutocomplete.vue'
import AppInput from '@/components/UI/AppInput/AppInput.vue'
import { Roles } from '@/constants/Roles';
import { onMounted, ref } from 'vue';
import {  EmployeeCreate } from '@/interfaces/Employee';
import { useHttp } from '@inertiajs/vue3';

const props = defineProps<{
    errors: Partial<Record<keyof EmployeeCreate, string>>,
    loading:boolean
}>()

const surname = defineModel<string | null>('surname', {default:null})
const name = defineModel<string | null>('name',{default:null})
const patronymic = defineModel<string | null>('patronymic',{default:null})
const roles = defineModel<Array<number | undefined>>('roles', {default:[]})
const email = defineModel<string | null>('email',{default:null})
const jobTitle = defineModel<string | null>('jobTitle',{default:null})

const rolesList = ref<Roles[]>()

const http = useHttp()
onMounted(() => {
    http.get('/roles', {
        onSuccess:(response : any) => {
            rolesList.value = response.data
        }
    })
})
</script>

<template>
    <AppInput 
        label="Фамилия"
        v-model="surname"
        :error-messages="errors.surname"
    />
    <AppInput 
        label="Имя"
        v-model="name"
        :error-messages="errors.name"
    />
    <AppInput 
        label="Отчество"
        v-model="patronymic"
        :error-messages="errors.patronymic"
    />

    <AppInput 
        label="Должность"
        v-model="jobTitle"
        :error-messages="errors.jobTitle"
    />

    <AppAutocomplete 
        label="Роли"
        :loading="loading"
        :disabled="loading"
        v-model="roles"
        :items="rolesList"
        item-title="label"
        item-value="id"
        multiple
        :error-messages="errors.roles"
    />

    <AppInput 
        label="e-mail@"
        v-model="email"
        :error-messages="errors.email"
    />
</template>
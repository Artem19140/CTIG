<script setup lang="ts">
import { ForeignNationalFilters } from '@/interfaces/ForeignNational';
import BaseFilter from '@components/BaseComponents/BaseFilter/BaseFilter.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppNumberInput from '@components/UI/AppNumberInput/AppNumberInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<{
    flash:{
        filters:ForeignNationalFilters
    }
}>()

const filters = computed<ForeignNationalFilters>(() =>
    page.flash.filters ?? {
        surname: null,
        name: null,
        patronymic: null,
        passportNumber: null,
        passportSeries: null,
        id: null,
    }
)

const form = useForm<ForeignNationalFilters>({
    surname: filters.value.surname ?? null,
    name: filters.value.name ?? null,
    patronymic: filters.value.patronymic ?? null,
    passportSeries: filters.value.passportSeries ?? null,
    passportNumber: filters.value.passportNumber ?? null,
    id: filters.value.id ?  Number(filters.value.id) : null,
})


const loading = defineModel<boolean>({default:false})
</script>

<template>
    <BaseFilter
        :url="'/foreign-nationals'"
        :form="form"
        v-model="loading"
        :filters="page.flash.filters"
    >
        <AppInput
            v-model="form.surname"
            label="Фамилия"
            :error-messages="form.errors.surname"
        />
        <AppInput
            v-model="form.name"
            :error-messages="form.errors.name"
            label="Имя"
        />
        <AppInput
            v-model="form.patronymic"
            label="Отчество"
        />
        <AppInput
            v-model="form.passportSeries"
            :error-messages="form.errors.passportSeries"
            label="Серия"
        />
        <AppInput
            v-model="form.passportNumber"
            :error-messages="form.errors.passportNumber"
            label="Номер"
        />
        <AppNumberInput 
            label="ID" 
            control-variant="hidden" 
            :error-messages="form.errors.id"
            v-model="form.id"  
            :min="0"  
        />
    </BaseFilter>
</template>
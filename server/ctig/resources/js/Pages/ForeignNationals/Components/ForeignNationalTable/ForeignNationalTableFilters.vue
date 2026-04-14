<script setup lang="ts">
import BaseFilter from '@components/BaseComponents/BaseFilter/BaseFilter.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppNumberInput from '@components/UI/AppNumberInput/AppNumberInput.vue';

const props = defineProps<{
    filters : any,
    form : any
}>()

</script>

<template>
    <BaseFilter
        :url="'/foreign-nationals'"
        :form="form"
        :appliedFilters="filters"
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
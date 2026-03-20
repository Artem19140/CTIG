<script setup lang="ts">
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseFilter from '../../../Components/BaseFilter/BaseFilter.vue';

const props = defineProps<{
  filters:any,
  form:any
}>()

</script>

<template>
    <BaseFilter
        :url="'/exams'"
        :form="form"
        :appliedFilters="filters"
    >
        <AppAutocomplete
            label="Тип экзамена"
            v-model="form.examTypeId"
            :error-messages="form.errors.examTypeId"
        />
        <AppInput 
            v-model="form.dateFrom"
            label="Дата с"
            type="date"
            :error-messages="form.errors.dateFrom"
        />
        <AppInput 
            v-model="form.dateTo"
            label="Дата по"
            type="date"
            :error-messages="form.errors.dateTo"
        />
        <v-checkbox 
            v-model="form.completed"
            label="Прошедшие"
            :error-messages="form.errors.completed"
        />
        <v-checkbox 
            v-model="form.cancelled"
            label="Отмененные"
            :error-messages="form.errors.cancelled"
        />
    </BaseFilter>
</template>
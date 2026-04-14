<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppCheckbox from '@components/UI/AppCheckbox/AppCheckbox.vue';
import BaseFilter from '@components/BaseComponents/BaseFilter/BaseFilter.vue';
import AppPeriodDate from '@components/UI/AppPeriodDate/AppPeriodDate.vue';

const props = defineProps<{
  filters:any,
  form:any
}>()

const page = usePage()

</script>

<template>
    <BaseFilter
        :url="'/exams'"
        :form="form"
        :appliedFilters="filters"
    >
        <AppAutocomplete
            label="Тип экзамена"
            :items="page.props.examTypes"
            item-title="name"
            item-value="id"
            v-model="form.examTypeId"
            :error-messages="form.errors.examTypeId"
        />
        <AppPeriodDate 
            :errors="form.errors"
            v-model:date-from="form.dateFrom"
            v-model:date-to="form.dateTo"
        />
        
        <AppCheckbox 
            v-model="form.completed"
            label="Прошедшие"
            :error-messages="form.errors.completed"
        />
        <AppCheckbox 
            v-model="form.cancelled"
            label="Отмененные"
            :error-messages="form.errors.cancelled"
        />
    </BaseFilter>
</template>
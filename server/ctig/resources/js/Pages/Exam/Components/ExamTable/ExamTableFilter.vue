<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppAutocomplete from '../../../../Components/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '../../../../Components/AppInput/AppInput.vue';
import BaseFilter from '../../../../Components/BaseFilter/BaseFilter.vue';

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
        Период
        <div class="d-flex align-center gap-2">
           
            <AppInput 
            v-model="form.dateFrom"
            label=""
            type="date"
            :error-messages="form.errors.dateFrom"
            />
            <AppInput 
                v-model="form.dateTo"
                label=""
                type="date"
                :error-messages="form.errors.dateTo"
            />
        </div>
        
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
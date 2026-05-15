<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppCheckbox from '@components/UI/AppCheckbox/AppCheckbox.vue';
import BaseFilter from '@components/BaseComponents/BaseFilter/BaseFilter.vue';
import AppPeriodDate from '@components/UI/AppPeriodDate/AppPeriodDate.vue';
import { computed} from 'vue';
import { ExamFilters } from '@/interfaces/Exam';
import AppNumberInput from '@/components/UI/AppNumberInput/AppNumberInput.vue';

const page = usePage<{
    flash:{
        filters:ExamFilters
    }
}>()

const filters = computed<ExamFilters>(() =>
    page.flash.filters 
    // ?? {
    //     dateFrom: null,
    //     cancelled: null,
    //     examTypeId: null,
    //     dateTo: null,
    //     finished: null,
    // }
)

const form = useForm<ExamFilters>({
    dateFrom: filters.value?.dateFrom ?? null,
    cancelled: Boolean(filters.value?.cancelled) ?? null,
    examTypeId:filters.value?.examTypeId ? Number(filters.value?.examTypeId) : null,
    dateTo:filters.value?.dateTo ?? null,
    finished: Boolean(filters.value?.finished) ?? null,
    id: filters.value.id ?  Number(filters.value.id) : null,
})


const loading = defineModel<boolean>({default:false})
</script>

<template>
    <BaseFilter
        :url="'/exams'"
        :form="form"
        v-model="loading"
        :filters="filters"
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

        <AppNumberInput
            v-model="form.id"
            label="ID"
            :min="1"
            :error-messages="form.errors.id"
        />
        
        <AppCheckbox 
            v-model="form.cancelled"
            label="Отмененные"
            :error-messages="form.errors.cancelled"
        />

        <AppCheckbox 
            v-model="form.finished"
            label="Прошедшие"
            :error-messages="form.errors.finished"
        />
    </BaseFilter>
</template>
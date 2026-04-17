<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppCheckbox from '@components/UI/AppCheckbox/AppCheckbox.vue';
import BaseFilter from '@components/BaseComponents/BaseFilter/BaseFilter.vue';
import AppPeriodDate from '@components/UI/AppPeriodDate/AppPeriodDate.vue';
import { computed, onMounted} from 'vue';

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
    cancelled: filters.value?.cancelled ?? null,
    examTypeId:filters.value?.examTypeId ?? null,
    dateTo:filters.value?.dateTo ?? null,
    finished: filters.value?.finished ?? null,
})

type ExamFilters = {
    dateFrom: string | null,
    cancelled: boolean | null,
    examTypeId: string | null,
    dateTo: string | null,
    finished: boolean | null,
}
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
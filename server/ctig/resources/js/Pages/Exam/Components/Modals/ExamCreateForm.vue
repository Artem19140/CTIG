<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useHttp } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppTextarea from '@components/UI/AppTextarea/AppTextarea.vue';
import AppDateInput from '@components/UI/AppDateInput/AppDateInput.vue';
import AppNumberInput from '@components/UI/AppNumberInput/AppNumberInput.vue';
import { Address } from '@/interfaces/Address';
import { Employee } from '@/interfaces/Employee';
import { ExamType } from '@/interfaces/Exam';

const props = defineProps<{
    form:any, 
    hasEnrollment?:boolean
}>()

const addresses = ref<Address[]>()
const examiners = ref<Employee[]>()
const examTypes = ref<ExamType[]>()


const http = useHttp()

onMounted( async () => {
    http.get('/exams/create/modal-data', {
        onSuccess:(response:any) => {
            addresses.value = response.addresses
            examiners.value = response.examiners
            examTypes.value = response.examTypes
        }
    })
})
</script>

<template>
    <div class="flex flex-column gap-2">
        <AppAutocomplete 
            label="Тип экзамена"
            item-title="name"
            :items="examTypes"
            v-model="form.examTypeId"
            key="id"
            :error-messages="form.errors.examTypeId"
            :loading="http.processing"
            item-value="id"
            :disabled="hasEnrollment"
            clearable
        />
        <div class="flex gap-5">
            <div class="flex-1">
                <AppDateInput 
                    label="Дата"
                    v-model="form.date"
                    :disabled="hasEnrollment"
                    :error-messages="form.errors.date"
                />
            </div>

            <div class="flex-1">
                <AppInput 
                    label="Время"
                    type="time"
                    :disabled="hasEnrollment"
                    v-model="form.time"
                    :error-messages="form.errors.time"
                />
            </div>
        </div>
        
        <AppAutocomplete 
            label="Адрес"
            item-title="address"
            :items="addresses"
            item-value="id"
            :disabled="hasEnrollment"
            v-model="form.addressId"
            :error-messages="form.errors.addressId"
            :loading="http.processing"
            
        />

        <AppNumberInput 
            v-model="form.capacity"
            :error-messages="form.errors.capacity"
            control-variant="hidden"
            label="Вместимость"
            :min="0"
        />

        <AppAutocomplete 
            label="Экзаменаторы"
            item-title="fullName"
            :items="examiners"
            v-model="form.examiners"
            item-value="id"
            :error-messages="form.errors.examiners"
            multiple    
            :loading="http.processing"
        />

        <AppTextarea
            label="Комментарий"
            v-model="form.comment"
            :error-messages="form.errors.comment"
            hint="Максимум 256 символов"
            maxlength="256"
        />
    </div>
</template>
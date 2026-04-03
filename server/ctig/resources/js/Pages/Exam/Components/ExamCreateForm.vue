<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Address, ExamType, User } from '../../../interfaces/interfaces';
import { useHttp } from '@inertiajs/vue3';
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import AppTextarea from '../../../Components/AppTextarea/AppTextarea.vue';


const props = defineProps<{
    form:any, 
    hasEnrollment?:boolean
}>()

const addresses = ref<Address[]>()
const examiners = ref<User[]>()
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
const examinersErrors = computed(() => {
    return Object.entries(props.form.errors)
        .filter(([key]) => key.startsWith('examiners.'))
        .map(([, value]) => value)
})
const maxCapacity = ref<number | null>(null)
const onSelect = (value:number | null) => {
    if(!value){
        return
    }
    maxCapacity.value = addresses.value?.find(item => item.id === value)?.maxCapcity ?? null
}
</script>

<template>
    <v-form>
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
                <AppInput 
                label="Дата"
                type="date"
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
            @update:modelValue="onSelect"
        />

        <v-number-input 
            variant="solo-filled"
            v-model="form.capacity"
            :error-messages="form.errors.capacity"
            :disabled="hasEnrollment"
            control-variant="hidden"
            label="Количество ИГ"
            :min="0"
            :hint="`Максимум ${maxCapacity ?? '-'} человек`"
            :max="maxCapacity ?? 0"
        />

        <AppAutocomplete 
            label="Экзаменаторы"
            item-title="fullName"
            :items="examiners"
            v-model="form.examiners"
            item-value="id"
            :error-messages="examinersErrors"
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
    </v-form>
</template>
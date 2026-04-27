<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppAddButton from '@/components/UI/AppAddButton/AppAddButton.vue';
import AppInput from '@/components/UI/AppInput/AppInput.vue';
import AppNumberInput from '@/components/UI/AppNumberInput/AppNumberInput.vue';
import { router, useHttp } from '@inertiajs/vue3';

const isOpen = defineModel<boolean>({default:false})

const http = useHttp({
    address:null,
    capacity:null
})

const add = () => {
    http.post('/addresses',{
        onSuccess:() => {
            isOpen.value = false
            router.reload()
        }
    })
}
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        width="500"
        title="Создание адреса"
        @before-close="(close) => close()"
    >
        <AppInput 
            label="Адрес"
            placeholder="Введите адрес"
            v-model="http.address"
            :error-messages="http.errors.address"
        />
        <AppNumberInput 
            label="Вместимость"
            v-model="http.capacity"
            :error-messages="http.errors.capacity"
        />
        <template #actions>
            <AppAddButton
                @click="add"
                :disabled="http.processing || !http.address || !http.capacity"
                :loading="http.processing"
                text="Добавить"
            />
        </template>
    </BaseDialog>
</template>
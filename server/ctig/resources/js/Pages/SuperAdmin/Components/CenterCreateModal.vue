<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppAutocomplete from '@/components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '@/components/UI/AppInput/AppInput.vue';
import AppPasswordInput from '@/components/UI/AppPasswordInput/AppPasswordInput.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { router, useHttp } from '@inertiajs/vue3';

const isOpen = defineModel<boolean>({default:false})
const http = useHttp<{
    shortName:string | null,
    password:string | null,
    timeZone:string | null
}>({
    shortName:null,
    password:null,
    timeZone:null
})

const add = () => {
    http.post('/admin/centers',{
        onSuccess(response, httpResponse) {
            console.log(response)
            isOpen.value = false
            router.reload()
        },
    })
}
const timeZones = [
    'Europe/Moscow',
    'Europe/Samara'
]
</script>

<template>
    <BaseDialog
        width="500"
        v-model="isOpen"
        @before-close="(close) => close()"
    >
        <AppInput
            v-model="http.shortName"
            label="Короткое название"
            :error-messages="http.errors.shortName"
        />
        <AppAutocomplete
            v-model="http.timeZone"
            label="Временная зона"
            :items="timeZones"
            :error-messages="http.errors.timeZone"
        />
        <AppPasswordInput
            v-model="http.password"
            label="Пароль"
            :error-messages="http.errors.password"
        />
        <template #actions>
            <AppPrimaryButton
                text="Добавить"
                @click="add"
                :loading="http.processing"
                :disabled="http.processing"
            />
        </template>
    </BaseDialog>
</template>
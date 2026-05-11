<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import BaseDialog from '../BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '../UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppPasswordInput from '../UI/AppPasswordInput/AppPasswordInput.vue';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';

const isOpen = defineModel<boolean>({default:false})
const http = useHttp<{password :string| null}>({
    password:null
})
const logout = () => {
    http.post('/logout/all', {
        onSuccess(response, httpResponse) {
            isOpen.value = false
            const {add} = useSnackbarQueue()
            add('Успешный выход с других устройств', 'green')
            http.resetAndClearErrors()
        },
    })
}
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        width="500"
        @before-close="(close) => {
            http.resetAndClearErrors()
            http.cancel()
            close()
        }"
    >
        <div class="mb-4">
            Чтобы выйти с других устройств, необходимо ввести пароль
        </div>
        
        <AppPasswordInput
            label="Введите пароль"
            v-model="http.password"
            :error-messages="http.errors.password"
            class="mb-2"
        />
        <template #actions>
            <AppPrimaryButton 
                @click="logout"
                :disabled="!http.isDirty || http.processing"
                :loading="http.processing"
                text="Выйти"
            />
        </template>
    </BaseDialog>
</template>
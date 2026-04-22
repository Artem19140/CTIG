<script setup lang="ts">
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';
import AppCheckbox from '../UI/AppCheckbox/AppCheckbox.vue';
import BaseDialog from '../BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '../UI/AppPrimaryButton/AppPrimaryButton.vue';

const {isOpen, confimation, confimationError, message, close, ok} = useConfirmationOptionsDialog()

</script>

<template>
    <BaseDialog
        v-model="isOpen"
        width="400"
        title="Внимание!"
        @before-close="(closeDialog) => {
            close()
            closeDialog()
        }"
    >   
        {{ message }}
        <AppCheckbox
            v-model="confimation"
            label="Подтвердить"
            :error-messages="confimationError ? 'Обязательно!' : null"
        />
        <template #actions >
            <AppPrimaryButton
                @click="ok"
                text="Подтвердить"
            />
        </template>
    </BaseDialog>
</template>
<script setup lang="ts">
import { confirmDialog, useConfirmDialog } from '../../../Composables/useConfirmDialog';
const {close} = useConfirmDialog()

const  handleOK = () => {
    if(confirmDialog.needConfirmText && !confirmDialog.confirmText){
        confirmDialog.errorMessage = 'Поле обязательно для заполнения'
        return
    }
    confirmDialog.onConfirm?.(confirmDialog.confirmText)
}
</script>

<template>
    <v-dialog
        v-model="confirmDialog.isOpen"
        persistent
        @keyup.esc="close"
        
    >
        <v-card
        class="mx-auto"
        :subtitle="confirmDialog.subtitle"
        width="400"
        :title="confirmDialog.title"
        >
            <v-card-text>
                <v-textarea
                    autofocus
                    v-if="confirmDialog.needConfirmText"
                    v-model="confirmDialog.confirmText"
                    :messages="confirmDialog.message"
                    :placeholder="confirmDialog.placeholder"
                    :error-messages="confirmDialog.errorMessage"
                    rows="1"
                    hint="Максимум 256 символов"
                    maxlength="256"
                    auto-grow
                    counter
                ></v-textarea>
            </v-card-text>
        <v-card-actions>
            <v-btn color="primary" variant="flat" @click="handleOK">
                {{confirmDialog.okText}}
            </v-btn>

            <v-btn @click="close">
                {{ confirmDialog.cancelText }}
            </v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
</template>
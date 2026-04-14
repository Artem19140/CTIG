<script setup lang="ts">
import { usePromptDialog } from '@composables/usePromptDialog';

const {isOpen, message, value, errorMessages, promptOk, close} = usePromptDialog()

const rules = {
  required: (value: string | null | undefined) =>
    !!value || 'Поле обязательно для заполнения'
}
</script>

<template>
    <v-dialog
        v-model="isOpen"
        persistent
        width="400"
    >
        <v-card
            width="400"
            subtitle="Подтвердите действие"
        >
            <v-card-text>
                {{ message }}
                <v-textarea
                    :error-messages="errorMessages"
                    class="mt-4"
                    :rules="[rules.required]"
                    v-model="value"
                    rows="1"
                    auto-grow
                    label="Введите текст"
                />
            </v-card-text>

            
            <v-card-actions>
                <v-btn  color="primary" variant="flat" @click="promptOk">
                    Отправить
                </v-btn>

                <v-btn @click="close">
                    Отмена
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
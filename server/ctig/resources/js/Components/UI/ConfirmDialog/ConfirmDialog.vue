<script setup lang="ts">
import {useConfirmDialog } from '../../../Composables/useConfirmDialog';

const {isOpen, message, confirmOk, confirmClose} = useConfirmDialog()
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
            </v-card-text>
        <v-card-actions>
            <v-btn color="primary" variant="flat" @click="confirmOk">
                Подтвердить
            </v-btn>

            <v-btn @click="confirmClose">
                Отмена
            </v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
</template>
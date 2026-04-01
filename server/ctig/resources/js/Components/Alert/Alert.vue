<script setup lang="ts">
import { watch } from 'vue';
import { useAlert } from '../../Composables/useAlert';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

watch(
    () => page.flash,
    (flash) => {
        if (flash?.error) {
            isOpen.value = true
            message.value = String(flash.error)
        }
    },
    { immediate: true }
)

const {isOpen, message, close} = useAlert()
</script>

<template>
    <v-dialog
        v-model="isOpen"
        persistent
        width="400"
    >
        <v-card
            width="400"
            subtitle="Внимание"
        >
            <v-card-text>
                {{ message }}
            </v-card-text>
        <v-card-actions>
            <v-btn color="primary" variant="flat" @click="close">
                Ok
            </v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
</template>
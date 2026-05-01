<script setup lang="ts">
import AppLogo from '@/components/UI/AppLogo/AppLogo.vue';

const props = defineProps<{
    subtitle?:string
}>()
</script>

<template>
    <v-container class="fill-height d-flex align-center justify-center">
        <v-card elevation="6" max-width="500" class="pa-6 text-center w-100">

        <v-card-title class="justify-center mb-4">
            <div class="flex justify-center items-center mb-4">
                <AppLogo 
                    max-width="150" 
                />
            </div>
        </v-card-title>

        <v-card-subtitle class="mb-6 text-h6">
            {{ subtitle }}
        </v-card-subtitle>
            <slot /> 
        </v-card>
    </v-container>
</template>
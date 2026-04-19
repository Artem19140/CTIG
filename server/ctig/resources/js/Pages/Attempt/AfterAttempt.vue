<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { ForeignNational } from '@interfaces/Interfaces';
import { router } from '@inertiajs/vue3';

const props=defineProps<{
    foreignNational:ForeignNational,
    examName: string
}>()
</script>

<template>
    <v-container width="700" class="mt-20">
        <v-card>
            <v-card-text>
                <v-img
                class="mx-auto"
                    width="250"
                    src="/storage/images/tigr_full.png"
                >
            
                </v-img>
                {{foreignNational.surname}} {{foreignNational.name}}, спасибо за прохождение экзамена "{{ examName}}" в системе ТИГР. 
                Ожидайте результаты экзамена. В случае вопросов обратитесь в центр тестирования.
            </v-card-text>
            <v-card-actions class="justify-center">
                <AppPrimaryButton 
                    text="На главную"
                    @click="router.visit('/login')"
                />
            </v-card-actions>
        </v-card>
    </v-container>
</template>
<script setup lang="ts">
import PrimaryButton from '../../Components/PrimaryButton/PrimaryButton.vue';
import { Student } from '../../interfaces/interfaces';
import { router } from '@inertiajs/vue3';

const props=defineProps<{
    student:Student,
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
                {{student.surname}} {{student.name}}, спасибо за прохождение экзамена "{{ examName}}" в системе ТИГР. 
                Ожидайте результаты экзамена. В случае вопросов обратитесь в центр тестирования.
            </v-card-text>
            <v-card-actions class="justify-center">
                <PrimaryButton 
                    text="На главную"
                    @click="router.visit('/login')"
                />
            </v-card-actions>
        </v-card>
    </v-container>
</template>
<script setup lang="ts">
import BaseEntryCard from '@/components/BaseComponents/BaseEntryCard/BaseEntryCard.vue';
import BaseLayout from '@/layouts/BaseLayout.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { router } from '@inertiajs/vue3';
</script>

<template>
    <BaseLayout>
        <BaseEntryCard>
            <div>
                Спасибо за прохождение экзамена в системе ТИГР.
                Ожидайте результаты экзамена. В случае вопросов обратитесь в центр тестирования.
            </div>
            <template #actions>
                <div class="flex justify-center">
                    <AppPrimaryButton 
                        text="На главную"
                        @click="router.visit('/login')"
                    />
                </div>
            </template>
        </BaseEntryCard>
    </BaseLayout>
</template>
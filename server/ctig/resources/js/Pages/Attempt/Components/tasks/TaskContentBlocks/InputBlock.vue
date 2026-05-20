<script setup lang="ts">
import AppTextarea from '@/components/UI/AppTextarea/AppTextarea.vue';
import { inject } from 'vue'

const props = defineProps<{
    field_id:string
}>()

const form = inject<Record<string, any>>('form')
</script>

<template>
    <AppTextarea 
        v-if="form"
        v-model="form[field_id]"
        :name="field_id"
        class="my-0"
    />
</template>

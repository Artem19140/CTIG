<script setup lang="ts">
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';

const props = defineProps<{
    loading:boolean,
    success:boolean,
}>()
</script>

<template>
    <div  class="text-grey text-caption" style="font-size: 12px;">
        <div v-if="loading" class="flex items-center gap-2">
            <AppProgressCircular size="20" />
            <span>Идет сохранение ответа...</span>
        </div>
        
        <div v-if="success">
            Ответ сохранено
        </div>
    </div>
</template>
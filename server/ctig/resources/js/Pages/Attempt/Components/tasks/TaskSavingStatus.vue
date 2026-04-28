<script setup lang="ts">
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';
import AppRefreshButton from '@/components/UI/AppRefreshButton/AppRefreshButton.vue';

const props = defineProps<{
    loading:boolean,
    success:boolean,
    error:boolean
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

        <div v-if="error" class="flex items-center text-red">
            Ошибка сохранения, повторите действие
            <AppRefreshButton icon-size="20" />
        </div>
    </div>
</template>
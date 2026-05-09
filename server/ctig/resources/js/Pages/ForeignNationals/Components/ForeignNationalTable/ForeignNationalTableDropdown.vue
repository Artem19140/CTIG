<script setup lang="ts">
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const modals = useModals()
</script>

<template>
    <BaseThreeDotDropdown>
        <BaseListItem 
            title="Еженедельный отчет"
            @click="modals.open('ministryEducationReport')"
        />
        <BaseListItem 
            title="Экспорт ИГ"
            @click="modals.open('foreignNationalExport')"
        />
        <BaseListItem 
            title="Статистика"
            @click="modals.open('statistics')"
        />
    </BaseThreeDotDropdown>
</template>
<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';

const modals = useModals()
</script>

<template>
    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            title="Еженедельный отчет"
            @click="modals.open('ministryEducationReport')"
        />
        <AppListDropDownItem 
            title="Экспорт ИГ"
            @click="modals.open('foreignNationalExport')"
        />
        <AppListDropDownItem 
            title="Статистика"
            @click="modals.open('statistics')"
        />
    </BaseThreeDotDropdown>
</template>
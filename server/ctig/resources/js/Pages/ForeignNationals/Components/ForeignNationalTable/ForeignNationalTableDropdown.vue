<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';

const modals = useModals()
</script>

<template>
    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            title="Выгрузить за период"
            @click="modals.open('foreignNationalExport')"
        />
        <AppListDropDownItem 
            title="Статистика"
            @click="modals.open('statistics')"
        />
    </BaseThreeDotDropdown>
</template>
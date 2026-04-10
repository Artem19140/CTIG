<script setup lang="ts">
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useModals } from '../../../../Composables/useModals';

const modals = useModals()
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Выгрузить за период"
            @click="modals.open('foreignNationalExport')"
        />
        <AppListDropDownItem 
            title="Статистика"
            @click="modals.open('statistics')"
        />
    </ThreeDotDropdown>
</template>
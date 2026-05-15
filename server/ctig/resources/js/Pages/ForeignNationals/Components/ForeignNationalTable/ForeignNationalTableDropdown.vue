<script setup lang="ts">
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';
import { ForeignNationalPagePermissions } from '@/interfaces/ForeignNational';

const props = defineProps<{
    permissions:ForeignNationalPagePermissions
}>()

const modals = useModals()
</script>

<template>
    <BaseThreeDotDropdown>
        <BaseListItem 
            title="Еженедельный отчет"
            v-if="permissions.ministryEducation"
            @click="modals.open('ministryEducationReport')"
        />
        <BaseListItem 
            title="Экспорт ИГ"
            v-if="permissions.export"
            @click="modals.open('foreignNationalExport')"
        />
        <BaseListItem 
            title="Статистика"
            v-if="permissions.statistics"
            @click="modals.open('statistics')"
        />
    </BaseThreeDotDropdown>
</template>
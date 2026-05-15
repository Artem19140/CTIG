<script setup lang="ts">
import ThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';
import { ForeignNational, ForeignNationalActionsPermissions } from '@/interfaces/ForeignNational';
import { Enrollment } from '@/interfaces/Enrollment';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null,
    permissions:ForeignNationalActionsPermissions | null
}>()

const emit = defineEmits<{
    (e:'edit', value: ForeignNational):void,
    (e:'enroll', value:Enrollment):void,
    (e:'delete', value:ForeignNational):void
}>()
</script>

<template>
    <ThreeDotDropdown v-if="permissions">
        <BaseListItem 
            title="Записать на экзамен"
            v-if="permissions.enroll"
            @click="open('enrollment', {foreignNational})"
        />
        <BaseListItem
            title="Редактировать"
            v-if="permissions.edit"
            @click="open('foreignNationalEdit', {foreignNational, onEdit:(foreignNational : ForeignNational)=>emit('edit', foreignNational)})"
        />
    </ThreeDotDropdown>
</template>
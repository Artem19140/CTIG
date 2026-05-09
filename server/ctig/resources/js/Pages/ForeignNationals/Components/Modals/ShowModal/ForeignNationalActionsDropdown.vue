<script setup lang="ts">
import ThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';
import { ForeignNational } from '@/interfaces/ForeignNational';
import { Enrollment } from '@/interfaces/Enrollment';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null
}>()

const emit = defineEmits<{
    (e:'edit', value: ForeignNational):void,
    (e:'enroll', value:Enrollment):void,
    (e:'delete', value:ForeignNational):void
}>()
</script>

<template>
    <ThreeDotDropdown>
        <BaseListItem 
            title="Записать на экзамен"
            @click="open('enrollment', {foreignNational})"
        />
        <BaseListItem
            title="Редактировать"
            @click="open('foreignNationalEdit', {foreignNational, onEdit:(foreignNational : ForeignNational)=>emit('edit', foreignNational)})"
        />
    </ThreeDotDropdown>
</template>
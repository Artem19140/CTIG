<script setup lang="ts">
import BaseThreeDotDropdown from '@/components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import AppListDropDownItem from '@/components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { useModals } from '@/composables/useModals';
import { Exam } from '@/interfaces/Interfaces';
import { useExamStatus } from '@/composables/useExamStatus';

const props = defineProps<{
    exam: Exam
}>()

const {open} = useModals()
const {isGoing, isPending} = useExamStatus(props.exam)
</script>

<template>
    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            title="Комментарий"
            :disabled="!isGoing"
            @click="open('examComment', {exam:exam})"
        />

        <AppListDropDownItem 
            :disabled="isPending"
            title="Итоги экзамена"
        />
    </BaseThreeDotDropdown>
</template>
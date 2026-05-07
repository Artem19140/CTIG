<script setup lang="ts">
import BaseThreeDotDropdown from '@/components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import AppListDropDownItem from '@/components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { useModals } from '@/composables/useModals';
import { ExamMonitoring } from '@/interfaces/Exam';

const props = defineProps<{
    exam: ExamMonitoring
}>()

const {open} = useModals()
</script>

<template>
    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            title="Комментарий (протокол)"
            :disabled="!exam.editProtocolCommentAvailable"
            @click="open('examComment', {exam:exam})"
        />

        <AppListDropDownItem 
            title="Карточка экзамена"
            @click="open('examShow', {examId:exam.id})"
        />
    </BaseThreeDotDropdown>
</template>
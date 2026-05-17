<script setup lang="ts">
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';
import BaseThreeDotDropdown from '@/components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@/composables/useModals';
import { ExamMonitoring } from '@/interfaces/Exam';

const props = defineProps<{
    exam: ExamMonitoring,
    available:{
        protocolComment:boolean
    }
}>()

const {open} = useModals()
</script>

<template>
    <BaseThreeDotDropdown>
        <BaseListItem 
            title="Комментарий (протокол)"
            :disabled="!available.protocolComment"
            @click="open('examComment', {exam:exam})"
        />

        <BaseListItem 
            title="Карточка экзамена"
            @click="open('examShow', {examId:exam.id})"
        />
    </BaseThreeDotDropdown>
</template>
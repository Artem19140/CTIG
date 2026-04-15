<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '@composables/usePromptDialog';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { router } from '@inertiajs/vue3';
import { Enrollment, Exam } from '@interfaces/Interfaces';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';

const props = defineProps<{ 
    enrollment:Enrollment,
    exam : Exam
}>()

const promptDialog = usePromptDialog()
const ban = async () => {
    const res = await promptDialog.open(`Укажите причину снятия ${props.enrollment.foreignNational.fullName} с экзамена`)
    if(!res){
        return
    }
    const loadingSnack = useLoadingSnackbar()
    loadingSnack.open('Идет аннулирование')
    router.put(`/attempts/${props.enrollment.attempt?.id}/ban`, 
    {banReason : res},
    {
        onFinish:()=> {
            loadingSnack.close()
        }
    }
    )
    
}

const getSpeakingTasks = () => {
    alert('Тут будет устная часть')
}
const isNotBanned = computed(() => props.enrollment.attempt?.status === 'banned')
const { isCancelled, isCompleted, isGoing } = useExamStatus(props.exam)
</script>

<template>
    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            v-if="!isCancelled && !isCompleted" 
            title="Подтвердить оплату" 
            @click=""
        />
        <AppListDropDownItem 
            v-if="exam.hasSpeakingTasks"
            title="Устная часть" 
            @click="getSpeakingTasks"
        />
        <AppListDropDownItem 
            v-if="!isCancelled && !isCompleted && isGoing && isNotBanned"   
            color="text-red" 
            title="Снять с экзамена" 
            @click="ban"
        />
    </BaseThreeDotDropdown>
</template>
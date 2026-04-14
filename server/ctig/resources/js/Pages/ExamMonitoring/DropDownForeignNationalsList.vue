<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '@composables/usePromptDialog';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { router } from '@inertiajs/vue3';
import { Exam } from '@interfaces/interfaces';

const props = defineProps<{
    foreignNational:any, 
    exam : Exam
}>()

const promptDialog = usePromptDialog()
const ban = async () => {
    const res = await promptDialog.open(`Укажите причину снятия ${props.foreignNational.fullName} с экзамена`)
    if(!res){
        return
    }
    const loadingSnack = useLoadingSnackbar()
    loadingSnack.open('Идет аннулирование')
    router.put(`/attempts/${props.foreignNational?.attempts[0]?.id}/ban`, 
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
</script>

<template>
    <BaseThreeDotDropdown>
        <AppListDropDownItem v-if="!exam.isPast && !exam.isCancelled" title="Подтвердить оплату" @click=""/>
        <AppListDropDownItem title="Устная часть" @click="getSpeakingTasks" v-if="exam.hasSpeakingTasks" />
        <AppListDropDownItem v-if="exam.isGoing && !exam.isCancelled"   color="text-red" title="Снять с экзамена" @click="ban"/>
    </BaseThreeDotDropdown>
</template>
<script setup lang="ts">
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '../../Composables/usePromptDialog';
import ThreeDotDropdown from '../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useLoadingSnackbar } from '../../Composables/useLoadingSnackBar';
import { router } from '@inertiajs/vue3';
import { Exam } from '../../interfaces/interfaces';

const props = defineProps<{
    foreignNational:any, 
    exam : Exam
}>()


const ban = async () => {
    const {open} = usePromptDialog()
    const res = await open(`Укажите причину снятия ${props.foreignNational.fullName} с экзамена`)
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
    <ThreeDotDropdown>
        <AppListDropDownItem v-if="!exam.isPast && !exam.isCancelled" title="Подтвердить оплату" @click=""/>
        <AppListDropDownItem title="Устная часть" @click="getSpeakingTasks" v-if="exam.hasSpeakingTasks" />
        <AppListDropDownItem v-if="exam.isGoing && !exam.isCancelled"   color="text-red" title="Снять с экзамена" @click="ban"/>
    </ThreeDotDropdown>
</template>
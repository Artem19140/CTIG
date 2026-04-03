<script setup lang="ts">
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '../../Composables/usePromptDialog';
import ThreeDotDropdown from '../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useLoadingSnackbar } from '../../Composables/useLoadingSnackBar';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    foreignNational:any, 
    hasSpeakingTasks : boolean
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
        <AppListDropDownItem v-if="!foreignNational?.hasPayment" title="Подтвердить оплату" @click=""/>
        <AppListDropDownItem title="Устная часть" @click="getSpeakingTasks" v-if="hasSpeakingTasks" />
        <AppListDropDownItem title="Снять с экзамена" @click="ban"/>
    </ThreeDotDropdown>
</template>
<script setup lang="ts">
import axios from 'axios';
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '../../Composables/usePromptDialog';
import ThreeDotDropdown from '../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';

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
    axios.put(`/attempts/${props.foreignNational?.attempts[0]?.id}/ban`, {banReason : res})
}

const getSpeakingTasks = () => {
    alert('Тут будет устная часть')
}
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem title="Снять с экзамена" @click="ban"/>
        <AppListDropDownItem title="Устная часть" @click="getSpeakingTasks" v-if="hasSpeakingTasks" />
    </ThreeDotDropdown>
</template>
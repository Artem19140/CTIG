<script setup lang="ts">
import axios from 'axios';
import AppListDropDownItem from '../../Components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '../../Composables/usePromptDialog';

const props = defineProps<{
    student:any
}>()


const ban = async () => {
    const {open} = usePromptDialog()
    const res = await open(`Укажите причину снятия ${props.student.fullName} с экзамена`)
    if(!res){
        return
    }
    axios.put(`/attempts/${props.student?.attempts[0]?.id}/ban`, {banReason : res})
}
</script>

<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-btn icon
                variant="text"
                v-bind="props"
            >
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
        <v-list>
            <AppListDropDownItem title="Снять с экзамена" @click="ban"/>
        </v-list>
    </v-menu>
</template>
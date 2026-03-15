<script setup lang="ts">
import axios from 'axios';
import AppListItem from '../../Components/UI/AppListItem/AppListItem.vue';
import { useConfirmDialog } from '../../Composables/useConfirmDialog';

const props = defineProps<{
    student:any
}>()


const ban = async () => {
    const {confirmOpen} = useConfirmDialog()
    const ok = await confirmOpen(`Снять ${props.student.fullName} с экзамена?`)
    if(!ok){
        return
    }
    axios.put(`/attempts/${props.student?.attempts[0]?.id}/ban`)
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
            <AppListItem title="Снять с экзамена" @click="ban"/>
        </v-list>
    </v-menu>
</template>
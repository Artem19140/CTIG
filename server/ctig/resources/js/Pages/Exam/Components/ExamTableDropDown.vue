<script setup lang="ts">
import { ref } from 'vue';
import FrdoModal from './FrdoModal.vue';
import AppListDropDownItem from '../../../Components/UI/AppListDropDownItem/AppListDropDownItem.vue';

const isFrdoModalOpen = ref<boolean>(false)

</script>

<template>
    <v-menu>  
        <template v-slot:activator="{ props }">
            <v-btn icon v-bind="props" variant="text">
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
            
        <v-list>
            <AppListDropDownItem title="Скачать отчет ФИС ФРДО" @click="isFrdoModalOpen = true" />
            <AppListDropDownItem title="Скачать плоскую таблицу"/>
        </v-list>
        
    </v-menu>

    <FrdoModal v-model="isFrdoModalOpen"/>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import FrdoModal from './FrdoModal.vue';
import AppListItem from '../../../Components/UI/AppListItem/AppListItem.vue';

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
            <AppListItem title="Скачать отчет ФИС ФРДО" @click="isFrdoModalOpen = true" />
            <AppListItem title="Скачать плоскую таблицу"/>
        </v-list>
        
    </v-menu>

    <FrdoModal v-model="isFrdoModalOpen"/>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import FrdoModal from './FrdoModal.vue';

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
            <v-list-item class="cursor-pointer" link @click="isFrdoModalOpen = true">
                <v-list-item-title >
                    Скачать отчет ФИС ФРДО
                </v-list-item-title>
            </v-list-item>

            <v-list-item class="cursor-pointer" link>
                <v-list-item-title >
                Скачать плоскую таблицу
                </v-list-item-title>
            </v-list-item>
        </v-list>
        
    </v-menu>

    <FrdoModal v-model="isFrdoModalOpen"/>
</template>
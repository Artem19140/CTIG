<script setup lang="ts">
    import MigrantsList from '@/components/MigrantsList/MigrantsList.vue';
import { ref } from 'vue';
</script>

<template>
    <v-card>
        <v-btn color="success">Добавить</v-btn>
        <MigrantsList />
    </v-card>
    
    <router-view />
</template>

<style></style>
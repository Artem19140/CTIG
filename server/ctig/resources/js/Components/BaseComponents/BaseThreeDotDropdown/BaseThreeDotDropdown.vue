<script setup lang="ts">
import BaseList from '../BaseList/BaseList.vue';


</script>

<template>
    <v-menu >
        <template  v-slot:activator="{ props }">
            <v-btn icon
                variant="text"
                v-bind="props"
            >
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
        <base-list nav width="250">
            <slot />
        </base-list>
    </v-menu>
</template>
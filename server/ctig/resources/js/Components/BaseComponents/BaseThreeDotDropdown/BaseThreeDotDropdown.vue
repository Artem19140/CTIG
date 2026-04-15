<script setup lang="ts">

</script>

<template>
    <v-menu >
        <template v-slot:activator="{ props }">
            <v-btn icon
                variant="text"
                v-bind="props"
            >
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
        <v-list width="250">
            <slot />
        </v-list>
    </v-menu>
</template>
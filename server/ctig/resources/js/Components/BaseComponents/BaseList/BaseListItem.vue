<script setup lang="ts">

</script>

<template>
    <v-list-item 
    >
        <slot />
        <template v-slot:append>
            <slot name="append" />
        </template>
    </v-list-item>
</template>
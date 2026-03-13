<script setup lang="ts">
const props = defineProps<{
    attempt:any
}>()
</script>

<template>
    <v-list>
        <v-list-item>
            Время
        </v-list-item>
        <v-list-item
            v-for="task in attempt"
            :key="task.id"
        >
            <v-list-title>
                Задание {{ task}}
            </v-list-title>

        </v-list-item>
    </v-list>
</template>
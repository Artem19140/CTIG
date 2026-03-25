<script setup lang="ts">
const props = defineProps<{
    tasks:any
}>()
</script>

<template>
    <v-list-item>
        <div
            v-for="task in tasks"
            :key="task.id"
        >
                {{ task.order}} {{ task?.attemptAnswer?.attemptAnswer || task?.attemptAnswer?.answerId ? "Есть" : "нет" }}
        </div>
    </v-list-item>
</template>
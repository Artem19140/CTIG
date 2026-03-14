<script setup lang="ts">
const props = defineProps<{
    tasks:any
}>()
</script>

<template>
    <v-list-item>
        <v-list-item-title>
           Задания 
        </v-list-item-title>
    </v-list-item>
    
    <v-list-item
        v-for="task in tasks"
        :key="task.id"
    >
        <v-list-item-title>
           
        </v-list-item-title>
        <!-- <v-list-item-subtitle>
            {{ task.order}} {{ task.attemptAnswer.attemptAnswer ? "Есть" : "нет" }}
        </v-list-item-subtitle> -->
    </v-list-item>
</template>
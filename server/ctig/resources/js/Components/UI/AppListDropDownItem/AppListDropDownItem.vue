<script setup lang="ts">
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';


const props = defineProps<{
    title:string,
    color?:string,
    subtitle?:string | null,
}>()
</script>

<template>
    <BaseListItem link class="cursor-pointer">
        <v-list-item-title :class="color">
            {{ title }}
        </v-list-item-title>
        <v-list-item-subtitle :class="color">
            {{ subtitle }}
        </v-list-item-subtitle>
    </BaseListItem>
</template>
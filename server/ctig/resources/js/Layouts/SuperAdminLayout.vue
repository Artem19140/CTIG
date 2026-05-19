<script setup lang="ts">
import BaseDrawer from '@/components/BaseComponents/BaseDrawer/BaseDrawer.vue';
import BaseList from '@/components/BaseComponents/BaseList/BaseList.vue';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';
import { router } from '@inertiajs/vue3';

const items = [
    {
        label:'Центры', 
        url:'admin/centers',
    },
    {
        label:'Статистика', 
        url:'admin/home',
    },
    {
        label:'Поиск', 
        url:'foreign-nationals',
    },

]

const go = (url:string) => {
    router.visit(`/${url}`)
}
</script>

<template>
    <BaseDrawer
        location="right"
    >
        <BaseList nav>
            <BaseListItem
                v-for="item in items"
                :key="item.label"
                @click="go(item.url)"
            >
                {{ item.label }}
            </BaseListItem>
        </BaseList>
    </BaseDrawer>
    <slot />
</template>
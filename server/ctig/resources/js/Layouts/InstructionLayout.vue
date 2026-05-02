<script setup lang="ts">
import { useAuth } from '@/composables/useAuth';
import EmployeeLayout from './EmployeeLayout.vue';
import { Roles } from '@/constants/Roles';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import BaseList from '@/components/BaseComponents/BaseList/BaseList.vue';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';
import BaseDrawer from '@/components/BaseComponents/BaseDrawer/BaseDrawer.vue';

const auth = useAuth()

interface ItemsInstruction {
    label:string,
    url:string,
    access:Array<Roles>
}

const items:Array<ItemsInstruction> = [
    {
        label:'Иностранные граждане', 
        url:'foreign-nationals',
        access:[]
    },
    {
        label:'Экзамены', 
        url:'exams',
        access:[]
    },
    {
        label:'Мониторинг', 
        url:'exams/monitoring',
        access:[Roles.EXAMINER]
    },
    {
        label:'Проверка', 
        url:'exams/checking',
        access:[Roles.EXAMINER]
    },
    {
        label:'Расписание', 
        url:'exams/schedule',
        access:[]
    },
    {
        label:'Центр', 
        url:'centers',
        access:[Roles.ORG_ADMIN]
    },
]

const go = (url :string) => {
    router.visit(`/instruction/${url}`)
}

const visibleItems = computed(() =>
  items.filter(item => !item.access || auth.can(item.access))
)
</script>

<template>
    <EmployeeLayout>
        <BaseDrawer
            permanent
            location="right"
        >
            <BaseList nav>
                <BaseListItem
                    v-for="item in visibleItems"
                    :key="item.label"
                    @click="go(item.url)"
                >
                    {{ item.label }}
                </BaseListItem>
            </BaseList>
        </BaseDrawer>
        <slot />
    </EmployeeLayout>
    
</template>
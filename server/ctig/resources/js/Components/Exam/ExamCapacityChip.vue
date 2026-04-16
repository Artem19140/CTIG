<script setup lang="ts">
import { capacityColor } from '@/helpers/heplers';
import AppStatusChip from '../UI/AppStatusChip/AppStatusChip.vue';
import { Exam } from '@/interfaces/Interfaces';
import { computed, ref } from 'vue';

const props = defineProps<{
    exam?:Exam | null
}>()
const capacity = ref<number | null>(props.exam?.capacity ?? null)
const enrollmentsCount = ref<number>(props.exam?.enrollmentsCount ?? 0)
const full = computed(() =>{
    if(!enrollmentsCount.value || !capacity.value) return
    return (enrollmentsCount.value / capacity.value)  === 1
}  )
</script>

<template>
    <span v-if="!full">{{ `${exam?.enrollmentsCount}/${exam?.capacity}` }}</span>
    <AppStatusChip
        v-else
        :text="`${exam?.enrollmentsCount}/${exam?.capacity}`"
        color="green"
    />
</template>
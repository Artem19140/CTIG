<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps<{
    timeBegin:string,
    timeEnd:string
}>()
//нужно выдавать просто точку старта и запускать
const timeLeft = ref<number>(0)
let interval: any = null

const calculateTime = () => {
    props.timeBegin
    const now = new Date().getTime()
    const end = new Date(props.timeEnd).getTime()

    timeLeft.value = Math.floor((end - now) / 1000)
    if (timeLeft.value <= 0) {
        timeLeft.value = 0
        clearInterval(interval)
        onTimeEnd()
    }
    
}
const onTimeEnd = () => {
    console.log('Время вышло!')
}

const formattedTime = computed(() => {
    const seconds = timeLeft.value % 60
    return `${minutes.value}:${seconds.toString().padStart(2, '0')}`
})

const minutes = computed(() => {
    return Math.floor(timeLeft.value / 60)
})

onMounted(() => {
    calculateTime()
    interval = setInterval(calculateTime, 1000)
})

onUnmounted(() => {
    clearInterval(interval)
})

</script>

<template>
    <div> Время: <span :class="(minutes < 5) ? 'text-red' : ''">{{formattedTime}}</span></div>
</template>
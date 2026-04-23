<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps<{
    endsAt:number,
    serverNow:number
}>()

let interval:number | null = null
//нужно выдавать просто точку старта и запускать
const timeLeft = ref<number>(0)

const offset = ref(0)

const syncTime = () => {
  const clientNow = Math.floor(Date.now() / 1000)
  offset.value = props.serverNow - clientNow
}


const calculateTime = () => {

    const now = Math.floor(Date.now() / 1000) + offset.value

    timeLeft.value = Math.max(0, props.endsAt - now)
    if (timeLeft.value <= 0) {
        timeLeft.value = 0
        stopTimer()
        onTimeEnd()
    }
    
}

const startTimer = () => {
  calculateTime()
  interval = window.setInterval(calculateTime, 1000)
}

const stopTimer = () => {
  if (interval) clearInterval(interval)
  interval = null
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
    syncTime()
    startTimer()
    interval = setInterval(calculateTime, 1000)
    setInterval(syncTime, 60000)
document.addEventListener('visibilitychange', calculateTime)
})

onUnmounted(() => {
    stopTimer()
})

</script>

<template>
    <div> Время: <span :class="(minutes < 5) ? 'text-red' : ''">{{formattedTime}}</span></div>
</template>
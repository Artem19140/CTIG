<script setup lang="ts">
import { computed, ref } from 'vue'

const props = defineProps<{ value: string }>()

const audioRef = ref<HTMLAudioElement | null>(null)

const currentTime = ref(0)
const duration = ref(0)
const played = ref<boolean>(false)

const playedTime = computed(() => {
    return (currentTime.value / duration.value) * 100
})

const togglePlay = () => {
  if (!audioRef.value) return
    audioRef.value.play()
    played.value = true

}

const onTimeUpdate = () => {
  if (!audioRef.value) return
  currentTime.value = audioRef.value.currentTime
}

const onLoaded = () => {
  if (!audioRef.value) return
  duration.value = audioRef.value.duration
}

function format(time: number) {
  const m = Math.floor(time / 60)
  const s = Math.floor(time % 60)
  return `${m}:${s.toString().padStart(2, '0')}`
}
</script>

<template>
    <div v-if="value">
        <v-alert
            type="info"
            variant="tonal"
            class="ma-2"
            >
                <strong>ВНИМАНИЕ!</strong> Аудиозапись можно прослушать только один раз. 
                Не <strong>перезагружайте</strong> и не <strong>закрывайте</strong> вкладку во время прослушивания.
        </v-alert>
        <audio
            ref="audioRef"
            :src="value"
            @timeupdate="onTimeUpdate"
            @loadedmetadata="onLoaded"
            preload="auto"
        />
        <div class="flex items-center">
            <v-btn 
                icon 
                @click="togglePlay" 
                v-if="!played"
                variant="text"
            >
                <v-icon>mdi-play</v-icon>
            </v-btn>

            <v-progress-linear
                color="blue-lighten-3"
                :model-value="playedTime"
                :height="20"
            >{{ format(currentTime) }} / {{ format(duration) }}
            </v-progress-linear>
        </div>
    </div>
    <div v-else class="flex justify-center ">
        Запись уже прослушана
    </div>
</template>

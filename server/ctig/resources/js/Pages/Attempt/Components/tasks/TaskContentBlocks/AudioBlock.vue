<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import { computed, ref } from 'vue'
import { useAttempt } from '@/composables/useAttempt';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
import { useHttp } from '@inertiajs/vue3';

const props = defineProps<{ 
    value: string 
    task:Task
}>()

const emit = defineEmits<{ 
    (e:'audio-played'):void
}>()

const audioRef = ref<HTMLAudioElement | null>(null)

const currentTime = ref(0)
const duration = ref(0)

const playedTime = computed(() => {
    return (currentTime.value / duration.value) * 100
})

const audioPlayed = ref<boolean>(props.task.attemptAnswer.audioPlayed ?? false)

const {audioPlaying, audioStartPlaying, audioStopPlaying, examAttempt} = useAttempt()
const http = useHttp()
const togglePlay = () => {

    if(!audioPlaying.value){
        audioStartPlaying()
    }else{
        const {add} = useSnackbarQueue()
        add('Воспроизводится другая аудиозапись', 'red')
        return
    }

    if (!audioRef.value) return
    audioRef.value.play()

    http.put(`/attempts/${examAttempt.value?.id}/answers/${props.task.attemptAnswer.id}/audio`,{
        onSuccess(response, httpResponse) {
            
        },
    })
}

const onTimeUpdate = () => {
  if (!audioRef.value) return
  currentTime.value = audioRef.value.currentTime
}

const onLoaded = () => {
  if (!audioRef.value) return
  duration.value = audioRef.value.duration
}

const onEnded = () => {
  audioStopPlaying()
  currentTime.value = 0
  audioPlayed.value = true
}

function format(time: number) {
  const m = Math.floor(time / 60)
  const s = Math.floor(time % 60)
  return `${m}:${s.toString().padStart(2, '0')}`
}

</script>

<template>
    <div class="mb-4">
        <div v-if="value">
            <v-alert
                type="info"
                variant="tonal"
                class="ma-2"
                >
                    <div v-if="!audioPlayed">
                        <strong>ВНИМАНИЕ!</strong> Аудиозапись можно прослушать только один раз. 
                        Не <strong>перезагружайте</strong> и не <strong>закрывайте</strong> вкладку во время прослушивания.
                    </div>
                    <div v-else>
                        Запись уже прослушана
                    </div>
                    
            </v-alert>
            <div v-if="!audioPlayed">
                <audio

                    ref="audioRef"
                    :src="value"
                    @timeupdate="onTimeUpdate"
                    @loadedmetadata="onLoaded"
                    preload="auto"
                    @ended="onEnded"
                />
                <div class="flex items-center">
                    <v-btn 
                        icon 
                        @click="togglePlay" 
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
        </div>
    </div>
</template>

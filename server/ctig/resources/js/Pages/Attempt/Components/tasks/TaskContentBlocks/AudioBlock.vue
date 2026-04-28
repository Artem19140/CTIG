<script setup lang="ts">
import { Attempt } from '@/interfaces/Interfaces';
import { Task } from '@/interfaces/Task';
import { useHttp } from '@inertiajs/vue3';
import { computed, ref } from 'vue'
import { useAttempt } from '@/composables/useAttempt';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';

const props = defineProps<{ 
    value: string 
    task:Task, 
    attempt: Attempt
}>()

const emit = defineEmits<{ 
    (e:'audio-played'):void
}>()

const audioRef = ref<HTMLAudioElement | null>(null)

const currentTime = ref(0)
const duration = ref(0)
const played = ref<boolean>(false)

const playedTime = computed(() => {
    return (currentTime.value / duration.value) * 100
})

const http = useHttp()
const {audioPlaying} = useAttempt()

const togglePlay = () => {

    if(!audioPlaying.value){
        audioPlaying.value = true
    }else{
        const {add} = useSnackbarQueue()
        add('Воспроизводится другая аудиозапись', 'red')
        return
    }
    
    if(played.value) return
    if (!audioRef.value) return
    audioRef.value.play()
    played.value = true
    if(!props.attempt?.id) return
    http.put(`/attempts/${props.attempt.id}/answers/${props.task.attemptAnswer.id}/audio`)
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
  audioPlaying.value = false
  played.value = false
  currentTime.value = 0
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
                    <div v-if="!task.attemptAnswer?.audioPlayed">
                        <strong>ВНИМАНИЕ!</strong> Аудиозапись можно прослушать только один раз. 
                        Не <strong>перезагружайте</strong> и не <strong>закрывайте</strong> вкладку во время прослушивания.
                    </div>
                    <div v-else>
                        Запись уже прослушана
                    </div>
                    
            </v-alert>
            <div v-if="!task.attemptAnswer?.audioPlayed">
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
        </div>
    </div>
</template>

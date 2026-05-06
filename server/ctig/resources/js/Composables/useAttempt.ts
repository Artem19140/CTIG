import { Attempt } from "@/interfaces/Attempt";
import { AttemptAnswer } from "@/interfaces/Task";
import { ref } from "vue";

const examAttempt = ref<Attempt | null>(null)
const audioPlaying = ref<boolean>(false)
const errors = ref<Array<number>>([])

export const useAttempt = ()  => {
    const updateAnswer = (taskId: number, attemptAnswer: AttemptAnswer) => {
        if (!examAttempt.value) return

        const index = examAttempt.value.tasks.findIndex(t => t.id === taskId)
        if (index === -1) return

        examAttempt.value.tasks[index] = {
            ...examAttempt.value.tasks[index],
            attemptAnswer: {
                ...attemptAnswer
            }
        }
    }

    const setError = (taskId:number) => {
        errors.value.push(taskId)
    }

    const removeError = (taskId:number) => {
        errors.value = errors.value.filter(e => e != taskId)
    }

    const audioPlayed = (taskId: number, attemptAnswer: AttemptAnswer) => {
        if (!examAttempt.value) return

    }

    const audioStartPlaying = () => {
        audioPlaying.value = true
    }

    const audioStopPlaying = () => {
        audioPlaying.value = false
    }
    return {
            updateAnswer, 
            examAttempt, 
            audioPlaying, 
            errors, 
            audioStartPlaying, 
            audioStopPlaying,
            setError,
            removeError
        }
}

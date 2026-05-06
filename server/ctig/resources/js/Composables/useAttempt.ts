import { Attempt } from "@/interfaces/Attempt";
import { AttemptAnswer } from "@/interfaces/Task";
import { ref } from "vue";

const examAttempt = ref<Attempt | null>(null)
const audioPlaying = ref<boolean>(false)
const errors = ref<Set<number>>(new Set())
const saving = ref<Set<number>>(new Set())

export const useAttempt = ()  => {
    const loading= ref<boolean>(true)
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
        errors.value.add(taskId)
    }

    const removeError = (taskId:number) => {
        errors.value.delete(taskId)
    }

    const setSaving = (taskId:number) => {
        saving.value.add(taskId)
    }

    const removeSaving = (taskId:number) => {
        saving.value.delete(taskId)
    }

    const audioPlayed = (taskId: number) => {
        if (!examAttempt.value) return
        const index = examAttempt.value.tasks.findIndex(t => t.id === taskId)
        if (index === -1) return
        examAttempt.value.tasks[index].attemptAnswer.audioPlayed = true
        examAttempt.value.tasks[index] = {
            ...examAttempt.value.tasks[index]
        }
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
            removeError,
            saving,
            setSaving,
            removeSaving,
            loading,
            audioPlayed
        }
}

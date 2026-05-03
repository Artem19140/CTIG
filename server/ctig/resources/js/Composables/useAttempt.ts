import { Attempt } from "@/interfaces/Attempt";
import { AttemptAnswer } from "@/interfaces/Task";
import { ref } from "vue";

const examAttempt = ref<Attempt | null>(null)
const audioPlaying = ref<boolean>(false)
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

    const audioPlayed = (taskId: number, attemptAnswer: AttemptAnswer) => {
        if (!examAttempt.value) return

    }
    return {updateAnswer, examAttempt, audioPlaying}
}

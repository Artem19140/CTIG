import { Attempt } from "@/interfaces/Interfaces";
import { useHttp } from "@inertiajs/vue3";
import { ref } from "vue";


export const examAttempt = ref<Attempt>()

export const useExamAttempt = ()  => {
    const http = useHttp({
        answer:null
    })
    const updateAnswer = (answerId:number, answer: any) => {
        http.answer = answer
        if(!examAttempt.value) return
        http.put(`/exam-attempts/${examAttempt.value.id}/answers/${answerId}`,{
            onSuccess:(response:any) => {
                const task = examAttempt.value?.tasks.find(t => t.attemptAnswer.id = answerId)
                if(!task) return
                task.attemptAnswer = response.attemptAnswer
            }
        })
    }

    return {updateAnswer, examAttempt, http}
}

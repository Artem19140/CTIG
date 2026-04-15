import { ExamStatus } from "@/constants/ExamStatus";
import { Exam } from "@/interfaces/Interfaces";
import { computed } from "vue";

export const useExamStatus = (exam : Exam) => {
    
    const is = (status: string) => computed(() => exam.status === status)
    const isNot = (status: string) => computed(() => exam.status !== status)

    const isGoing = is(ExamStatus.GOING)

    const isCancelled = is(ExamStatus.CANCELLED)

    const isFinished = is(ExamStatus.FINISHED)

    const isPending = is(ExamStatus.PENDING)

    return{is, isNot, isCancelled, isFinished, isGoing, isPending}
}
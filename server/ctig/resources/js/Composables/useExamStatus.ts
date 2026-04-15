import { ExamStatus } from "@/constants/ExamStatus";
import { Exam } from "@/interfaces/Interfaces";
import { computed } from "vue";

export const useExamStatus = (exam : Exam) => {
    const isGoing = computed(() => exam.status === ExamStatus.GOING)

    const isCancelled = computed(() => exam.status === ExamStatus.CANCELLED)

    const isFinished = computed(()=>exam.status === ExamStatus.FINISHED)

    const isPending = computed(():boolean => exam.status === ExamStatus.PENDING)
    return{ isCancelled, isFinished, isGoing, isPending}
}
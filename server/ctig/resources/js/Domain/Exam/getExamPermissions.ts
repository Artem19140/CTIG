import { ExamStatus } from "@/constants/ExamStatus"
import { Exam } from "@interfaces/interfaces"
import { computed } from "vue"

export const getExamPermissions = (exam: Exam | null) => {
    if (!exam) {
        return {
            canDownloadCodes: false,
            canDownloadList: false,
            canDownloadProtocol: false,
            canDownloadStatement: false,
            canEdit: false,
            canCancel: false,
        }
        }
    const { status, foreignNationalsCount } = exam
    const hasEnrollment  = computed(() => foreignNationalsCount > 0)
    const isCompleted = computed(() => status === ExamStatus.COMPLETED )
    const isCancelled = computed(() => status === ExamStatus.CANCELLED )
    const isGoing = computed(() => status === ExamStatus.GOING )

    const canDownloadStatement  = isCompleted.value && !isCancelled.value && hasEnrollment.value 
    const canDownloadProtocol = isCompleted.value && !isCancelled.value && hasEnrollment.value 
    const canDownloadList =  !isCancelled.value && hasEnrollment.value
    const canEdit  = !isCompleted.value && !isCancelled.value  && !isGoing.value
    const canCancel = !isCompleted.value && !isCancelled.value && !isGoing.value
    const canDownloadCodes  =  !isCompleted.value && !isCancelled.value && hasEnrollment.value 
    const canDownloadStudList  =  hasEnrollment.value 
    
    return {
                canDownloadCodes,
                canDownloadList, 
                canDownloadProtocol, 
                canDownloadStatement, 
                canEdit, 
                canCancel,
                canDownloadStudList
            } 
}
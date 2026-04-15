import { ExamStatus } from "@/constants/ExamStatus"
import { Exam } from "@interfaces/Interfaces"
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
    const { status, enrollmentsCount } = exam
    const hasEnrollment  = computed(() => enrollmentsCount > 0)
    const isFinished = computed(() => status === ExamStatus.FINISHED )
    const isCancelled = computed(() => status === ExamStatus.CANCELLED )
    const isGoing = computed(() => status === ExamStatus.GOING )

    const canDownloadStatement  = isFinished.value && !isCancelled.value && hasEnrollment.value 
    const canDownloadProtocol = isFinished.value && !isCancelled.value && hasEnrollment.value 
    const canDownloadList =  !isCancelled.value && hasEnrollment.value
    const canEdit  = !isFinished.value && !isCancelled.value  && !isGoing.value
    const canCancel = !isFinished.value && !isCancelled.value && !isGoing.value
    const canDownloadCodes  =  !isFinished.value && !isCancelled.value && hasEnrollment.value 
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
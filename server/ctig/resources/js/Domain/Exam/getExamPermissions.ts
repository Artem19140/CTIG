import { Exam } from "../../interfaces/interfaces"

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
    const { isGoing, isCancelled, isPast, foreignNationalsCount } = exam
    const hasEnrollment  = foreignNationalsCount > 0

    const canDownloadStatement  = isPast && !isCancelled && hasEnrollment 
    const canDownloadProtocol = isPast && !isCancelled && hasEnrollment 
    const canDownloadList =  !isCancelled && hasEnrollment 
    const canEdit  = !isPast && !isCancelled  && !isGoing
    const canCancel = !isPast && !isCancelled && !isGoing
    const canDownloadCodes  =  !isPast && !isCancelled && hasEnrollment 
    const canDownloadStudList  =  hasEnrollment 
    
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
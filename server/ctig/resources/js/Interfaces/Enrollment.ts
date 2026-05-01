import { Attempt } from "./Attempt";
import { Exam } from "./Exam";
import { ForeignNationalEnrollment } from "./ForeignNational";

export interface Enrollment{
    id:number,
    foreignNational:ForeignNationalEnrollment,
    hasPayment:boolean,
    isLoading?: boolean,
    exam:Exam,
    attempt:Attempt | null,
    examResult:string
}

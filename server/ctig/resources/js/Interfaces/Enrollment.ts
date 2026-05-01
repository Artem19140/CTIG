import { Attempt } from "./Attempt";
import { Exam } from "./Exam";
import { ForeignNational } from "./ForeignNational";

export interface Enrollment{
    id:number,
    foreignNational:ForeignNational,
    hasPayment:boolean,
    isLoading?: boolean,
    exam:Exam,
    attempt:Attempt | null,
    examResult:string
}

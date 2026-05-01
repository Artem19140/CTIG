import { Employee } from "./Employee"
import { Enrollment } from "./Enrollment"
import { ForeignNational } from "./ForeignNational"

export interface Exam{
    id:number,
    name:string,
    shortName:string,
    beginTime:string,
    endTime:string,
    capacity:number,
    group:number | null,
    sessionNumber:number | null,
    comment:string,
    examiners:Array<Employee>,
    address:string,
    creator:Employee | null,
    createdAt:string | null,
    foreignNationalsCount:number,
    cancelledReason:string | null,
    status:string
    foreignNationals:Array<ForeignNational>,
    examTypeId:number,
    enrollments:Array<Enrollment>,
    enrollmentsCount:number,
    codesAvailable:boolean,
    addressId:number
}

export interface ExamIndex{
    id:number,
    name:string,
    shortName:string,
    beginTime:string,
    status:string
    enrollmentsCount:number,
}

export interface ExamCalendar{
    id:number,
    start:string,
    end:string,
    name:string,
    status:string
}

export interface ExamType{
    id:number,
    name:string
}

export interface ExamForm{
    examTypeId: number | null,
    addressId: number | null,
    comment:string,
    examiners: Array<number | Employee>,
    time:string | null,
    date:string | null,
    capacity:number | null
}

export interface ExamFilters  {
    dateFrom: string | null,
    cancelled: boolean | null,
    examTypeId: number | null,
    dateTo: string | null,
    finished: boolean | null,
}

export interface ExamMonitoring  {
    id:number,
    name:string,
    shortName:string,
    beginTime:string,
    endTime:string,
    protocolComment:string,
    status:string,
    hasSpeakingTasks:boolean,
    enrollments:Array<Enrollment>,
}
import { Task } from "./Task"

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
    examiners:Array<User>,
    address:string,
    creator:User | null,
    createdAt:string | null,
    foreignNationalsCount:number,
    attempts: Array<Attempt> | null,
    cancelledReason:string | null,
    date:string,
    status:string
    foreignNationals:Array<ForeignNational>,
    hasSpeakingTasks:boolean,
    examTypeId:number,
    protocolComment:string,
    enrollments:Array<Enrollment>,
    enrollmentsCount:number,
    codesAvailable:boolean,
    addressId:number
}

export interface Enrollment{
    id:number,
    foreignNational:ForeignNational,
    hasPayment:boolean,
    isLoading?: boolean,
    exam:Exam,
    attempt:Attempt | null,
    examResult:string
}

export interface User{
    id:number,
    name:string,
    surname:string,
    patronymic:string,
    email:string, 
    fullName:string
}

export interface ForeignNational{
    id:number,
    name:string,
    surname:string,
    patronymic:string | undefined,
    nameLatin:string,
    surnameLatin:string,
    patronymicLatin:string | undefined,
    passportNumber:string | undefined,
    passportSeries:string | undefined,
    issuedBy:string,
    issuedDate:string,
    phone:string,
    creator?:User | null,
    exams?:Array<Exam> | null,
    passportScan?:string | null,
    createdAt?:string,
    citizenship:string | null,
    dateBirth:string,
    attempts?:Array<Attempt> | null,
    fullName?:string,
    fullNameLatin?:string,
    fullPassport?:string,
    isLoading?: boolean,
    passportTranslateScan?:string | null,
    comment:''
    gender:string | null,
    enrollments:Array<Enrollment>,
    creatorFullName:string,
    addressReg:string
}

export type IForeignNationalCreateForm = Omit<
  ForeignNational,
  'id' | 'creator' | 'exams' | 'createdAt' | 'passportScan'  | 'attempts' | 'exam' | 'fullName' | 'fullPassport'  
       |'passportTranslateScanPath' | 'passportScanPath' | 'creatorFullName' | 'enrollments'
> & {
  passportScan: File | null
  passportTranslateScan: File | null
  noPassportNumber: boolean
  noPassportSeries: boolean
  noPatronymic: boolean
  noPatronymicLatin:boolean
  hasPayment:boolean
  examId: number | null
}

export interface Attempt{
    id:number,
    startedAt:string,
    finishedAt:string | null,
    isPassed:boolean | null,
    status:string,
    exam:Exam,
    expiredAt:string,
    tasks: Task[],
    foreignNational: ForeignNational,
    examName:string,
    endsAt:number,
    serverNow:number,
    speakingFinishedAt: string | null,
    speakingStartedAt: string | null,
    violations:Array<Violation>
}

export interface Violation{
    id:number,
    comment:string,
    createdAt:string
}

export interface Address{
    id:number,
    address:string,
    maxCapcity:number,
    isActive:boolean,
    loading:boolean,
    examsExists:boolean
}

export interface ExamType{
    id:number,
    name:string
}

export interface ExamForm{
    examTypeId: number | null,
    addressId: number | null,
    comment:string,
    examiners: Array<number | User>,
    time:string | null,
    date:string | null,
    capacity:number | null
}

export type Paginated<T> = {
    data: T[],
    links:{
        first:string,
        last:string | null,
        prev:string | null,
        next:string
    }
    meta: {
        from: number,
        current_page: number,
        per_page: number,
        to:number
    }
}
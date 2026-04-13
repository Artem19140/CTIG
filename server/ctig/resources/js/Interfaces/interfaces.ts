export interface Exam{
    id:number,
    name:string,
    shortName:string,
    beginTime:string,
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
    isCancelled:boolean,
    cancelledReason:string | null,
    date:string,
    isPast:boolean,
    isGoing:boolean,
    foreignNationals:Array<ForeignNational>,
    hasSpeakingTasks:boolean,
    examTypeId:number,
    protocolComment:string,
    enrollments:Array<Enrollment>
}

export interface Enrollment{
    id:number,
    foreignNational:ForeignNational,
    hasPayment:boolean,
    isLoading?: boolean,
    exam:Exam,
    attempt:Attempt | null
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
    enrollments:Array<Enrollment>
}

export type IForeignNationalCreateForm = Omit<
  ForeignNational,
  'id' | 'creator' | 'exams' | 'createdAt' | 'passportScan'  | 'attempts' | 'exam' | 'fullName' | 'fullPassport'  
       |'passportTranslateScanPath' | 'passportScanPath'
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
    exam:Array<Exam>
}

export interface Address{
    id:number,
    address:string,
    maxCapcity:number
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
    time:string,
    date:string,
    capacity:number | null
}

export type Paginated<T> = {
    data: T[]
    meta: {
        total: number
    }
}

export type api<T> = {
    data: T | null
}
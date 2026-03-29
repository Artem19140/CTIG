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
    date:string,
    isPast:boolean,
    isGoing:boolean,
    foreignNationals:Array<ForeignNational>,
    hasSpeakingTasks:boolean,
    examTypeId:number
}

export interface User{
    id:number,
    name:string,
    surname:string,
    patronymic:string,
    email:string
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
    migrationCardRequisite:string | undefined,
    issuedBy:string,
    issuedDate:string,
    addressReg:string,
    phone:string,
    creator:User | null,
    exams:Array<Exam>,
    passportScanPath:string | null,
    photo:string | null,
    createdAt:string,
    citizenship:string,
    dateBirth:string,
    attempts:Array<Attempt>,
    fullName:string,
    fullPassport:string,
    photoPath:string,
    isLoading?: boolean,
    passportTranslateScanPath?:string | null,
    comment:''
}

export type ForeignNationalCreateForm = Omit<
  ForeignNational,
  'id' | 'creator' | 'exams' | 'createdAt' | 'passportScan' | 'photo' | 'attempts' | 'exam' | 'fullName' | 'fullPassport' | 'photoPath' 
       |'passportTranslateScanPath' | 'passportScanPath'
> & {
  passportScan: File | null
  passportTranslateScan: File | null
  photo:File | null
  noPassportNumber: boolean
  noPassportSeries: boolean
  noPatronymic: boolean
  noMigrationCard: boolean
  gender:string | null
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
    address:string
}

export interface ExamType{
    id:number,
    name:string
}

export interface ExamForm{
    examTypeId: number | null,
    addressId: number | null,
    comment:string,
    examiners: Array<number>,
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
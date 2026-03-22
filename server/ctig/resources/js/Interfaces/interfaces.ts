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
    studentsCount:number,
    attempts: Array<Attempt> | null,
    isCancelled:boolean,
    date:string,
    isPast:boolean,
    students:Array<Student>,
    hasSpeakingTasks:boolean
}

export interface User{
    id:number,
    name:string,
    surname:string,
    patronymic:string,
    email:string
}

export interface Student{
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
    passportScan:string | null,
    photo:string | null,
    createdAt:string,
    citizenship:string,
    dateBirth:string,
    attempts:Array<Attempt>,
    fullName:string,
    fullPassport:string,
    photoPath:string
}

export type StudentCreateForm = Omit<
  Student,
  'id' | 'creator' | 'exams' | 'createdAt' | 'passportScan' | 'photo' | 'attempts' | 'exam' | 'fullName' | 'fullPassport' | 'photoPath'
> & {
  passportScan: File | null
  passportTranslateScan: File | null
  photo:File | null
  noPassportNumber: boolean
  noPassportSeries: boolean
  noPatronymic: boolean
  noMigrationCard: boolean
  gender:string | null

  examId: number | null
}

export interface Attempt{
    id:number,
    startedAt:string,
    finishedAt:string | null,
    isPassed:boolean | null,
    status:string,
    student:User,
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
    date:string
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
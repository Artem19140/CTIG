export interface Task{
    id: number,
    content:Object,
    type:string,
    attemptAnswer:AttemptAnswer,
    answers:Array<Answer>,
    mark:number,
    description:string,
    order:number
}

export interface AttemptAnswer{
    id:number,
    answer:any,
    checkedAt:string,
    mark:number | null,
    audioPlayed:boolean
}

export interface Answer{
    id:number,
    content:Object,
}
import { Exam } from "./Exam";
import { ForeignNational } from "./ForeignNational";
import { Task } from "./Task";
import { Violation } from "./Violation";

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
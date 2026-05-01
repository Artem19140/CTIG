import { Roles } from "@/constants/Roles"

export interface Employee{
    id:number,
    surname:string,
    name:string,
    patronymic:string | null,
    email:string,
    jobTitle:string,
    roles:Array<Role>,
    fullName:string
}

export interface EmployeeFormI extends Omit<Employee, 'id' | 'roles' | 'fullName'>{
    roles:Array<number | undefined>
}

export interface Role{
    id:number,
    name:Roles
}
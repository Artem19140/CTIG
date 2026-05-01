import { Roles } from "@/constants/Roles"

export interface Employee{
    id:number,
    surname:string,
    name:string,
    patronymic:string | null,
    email:string,
    jobTitle:string,
    roles:Array<Role>
}

export interface EmployeeCreate extends Omit<Employee, 'id' | 'roles'>{
    roles:Array<number | undefined>,
    password:string,
    password_confirmation:string
}

export interface EmployeeEdit extends Omit<Employee, 'roles'>{
    roles:Array<number>
}

export interface Role{
    id:number,
    name:Roles
}
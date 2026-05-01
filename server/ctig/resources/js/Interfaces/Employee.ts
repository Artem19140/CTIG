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

export interface EmployeeFormI extends Omit<Employee, 'id' | 'roles'>{
    roles:Array<number | undefined>,
    
}

export interface Role{
    id:number,
    name:Roles
}
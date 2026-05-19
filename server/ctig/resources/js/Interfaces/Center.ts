import { Employee } from "./Employee"

export interface Center {
  id:number,
  name: string
  ogrn: string
  inn: string
  address: string
  certificatesIssueAddress: string
  directorFio: string
  nameGenitive: string
  commissionChairman:string,
  employees:Employee[]
}
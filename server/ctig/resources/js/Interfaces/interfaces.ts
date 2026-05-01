
export interface RedirectUrl{
    redirectUrl:string
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
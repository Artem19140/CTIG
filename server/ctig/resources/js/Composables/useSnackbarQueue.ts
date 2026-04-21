import { ref, } from "vue";

const messages = ref<SnackBar[]>([])
const queue = ref()
export const useSnackbarQueue = () => {
    const add = (
                    text:string, 
                    color:string,  
                    prependIcon?:string,
                    timeout = 5000,
                ) => {
        queue.value.clear()
        messages.value.push({
            text:text,
            color:color,
            timeout:timeout,
            prependIcon:prependIcon ?? ''
        })
    }

    return {messages, queue, add}
}

type SnackBar = {
    text:string,
    color:string,
    timeout:number,
    prependIcon:string | null
}
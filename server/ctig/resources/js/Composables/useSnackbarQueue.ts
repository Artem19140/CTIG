import { ref } from "vue";

const messages = ref<SnackBar[]>([])
const queue = ref()
export const useSnackbarQueue = () => {
    const add = (text:string, color:string) => {
        messages.value.push({
            text:text,
            color:color
        })
    }

    return {messages, queue, add}
}

type SnackBar = {
    text:string,
    color:string
}
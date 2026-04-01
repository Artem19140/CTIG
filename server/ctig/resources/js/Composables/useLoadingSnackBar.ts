import { ref } from "vue";

const isOpen = ref<boolean>(false)
const message = ref<string | null>(null)
const isSuccess = ref<boolean>(false)
let timer: number | null = null

export const useLoadingSnackbar = () => {
    const open = (text:string) => {
        if (timer) {
            clearTimeout(timer)
        }

        timer = window.setTimeout(() => { // обязательно window.setTimeout для TS
            message.value = text
            isSuccess.value = false
            isOpen.value = true
            timer = null
        }, 300)
        
    }

    const close = () => {
        if (timer) {
            clearTimeout(timer)
            timer = null
        }
        message.value = null
        isOpen.value = false
        //isSuccess.value=true
    }

    const success = () => {
        message.value = null
        isOpen.value = false
        isSuccess.value=true
    }

    return {open, isOpen, message, success, isSuccess, close}
}
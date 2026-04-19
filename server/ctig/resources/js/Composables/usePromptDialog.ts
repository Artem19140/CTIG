import { ref } from "vue";

const isOpen = ref<boolean>(false)
const message = ref<string | null>(null)
const value = ref<string | null>(null)
const errorMessages = ref<string | null>(null)

let resolvePromise:((value : string | null) => void) | null = null; 

const reset = () => {
    isOpen.value = false
    message.value = null
    value.value = null
    errorMessages.value=null
}

export const usePromptDialog = () => {
    if (isOpen.value) {
        resolvePromise?.(null)
    }

    const open = (text: string) => {
        reset()
        isOpen.value = true
        message.value = text
        value.value = null
        return new Promise<string | null>((resolve) => {
            resolvePromise = resolve
        })
    }

    

    const close = () => {
        reset()
        resolvePromise?.(null)
        resolvePromise = null
    }

    const promptOk = () => {
        if(!value.value){
            errorMessages.value='Поле обязательно'
            return
        }
        
        const result = value.value
        reset()

        resolvePromise?.(result)
        resolvePromise = null
    }

    return {isOpen, message, value, errorMessages , close, promptOk, open}
}
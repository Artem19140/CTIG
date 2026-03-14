import { ref } from "vue"


const isOpen = ref<boolean>(false)
const message = ref<string | null>(null)

export const useAlert =() => {
    const open = (text:string = '') => {
        isOpen.value = true
        message.value = text
    }

    const close = () => {
        isOpen.value = false
        message.value = null
    }

    return {isOpen, message,open, close}

}
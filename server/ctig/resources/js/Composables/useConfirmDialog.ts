import {  ref } from 'vue'
const isOpen = ref<boolean>(false)
const message = ref<string | null>(null)
let resolvePromise:((value : boolean) => void) | undefined;

export const useConfirmDialog = () => {
    const confirmOpen = (text:string = '') =>{
        isOpen.value = true
        message.value = text

        return new Promise<boolean>((resolve) => {
            resolvePromise = resolve
        })
    }

    const confirmOk = () => {

        isOpen.value = false
        message.value = null
        resolvePromise?.(true)
        resolvePromise = undefined
    }

    const confirmClose = () => {
        isOpen.value = false
        message.value = null
        resolvePromise?.(false)
        resolvePromise = undefined
    }

    return {confirmOpen, confirmClose,confirmOk, isOpen, message}
}

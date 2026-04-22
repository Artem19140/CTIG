import {  ref, watch } from 'vue'
const isOpen = ref<boolean>(false)
const message = ref<string | null>(null)
const confimation = ref<boolean>(false)
const confimationError = ref<boolean>(false)
let resolvePromise:((value : boolean) => void) | undefined;
watch(() => confimation.value, (confimation) => {
    if(confimation){
        confimationError.value = false
    }
})
export const useConfirmationOptionsDialog = () => {
    const open = (text:string = '') =>{
        isOpen.value = true
        message.value = text
        confimationError.value = false
        confimation.value = false
        return new Promise<boolean>((resolve) => {
            resolvePromise = resolve
        })
    }

    const ok = () => {
        if(!confimation.value){
            confimationError.value = true
            return
        }
        isOpen.value = false
        message.value = null
        resolvePromise?.(true)
        resolvePromise = undefined
    }

    const close = () => {
        isOpen.value = false
        message.value = null
        resolvePromise?.(false)
        resolvePromise = undefined
    }

    return {open, close,ok, confimation, confimationError, isOpen, message}
}

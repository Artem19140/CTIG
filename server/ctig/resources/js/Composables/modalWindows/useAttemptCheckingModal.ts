import axios from "axios"
import { ref } from "vue"

const isOpen = ref<boolean>(false)
const tasks = ref()
const attemptId = ref()
const loading = ref<boolean>(false)

export const useAttemptCheckingModal = () =>{

    const open = async (attemptId : number) => {
        isOpen.value= true
        loading.value= true
        const res = await axios.get(`/attempts/${attemptId}/checking/tasks`)
        tasks.value = res.data
        loading.value= false
    }
    const close = () => {
        isOpen.value= false
        
        loading.value= false
        attemptId.value = null
        tasks.value = null
    }

    return {isOpen, tasks, loading, open, close,attemptId}
}
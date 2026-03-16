import axios from "axios";
import { ref } from "vue";

const isOpen = ref<boolean>(false)
const exam = ref()
const loading = ref<boolean>(false)
const error = ref<boolean>(false)

export const useExamShowModal = () => {
    const open = async (studentId : number) => {
        isOpen.value= true
        loading.value= true
        if(exam.value?.id !== studentId){
            exam.value = null
            const res = await axios.get(`/exams/${studentId}`)
            exam.value =  res.data.data
        }
        loading.value= false
    }
    const close = () => {
        isOpen.value= false
        loading.value= false
    }

    return {isOpen, exam, loading, error, open, close}
}
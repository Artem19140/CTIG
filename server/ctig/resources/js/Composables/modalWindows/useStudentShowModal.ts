import axios from "axios";
import { ref } from "vue";

const isOpen = ref<boolean>(false)
const student = ref()
const loading = ref<boolean>(false)

export const useStudentShowModal = () => {
    const open = async (studentId : number) => {
        isOpen.value= true
        loading.value= true
        if(student.value?.id !== studentId){
            student.value = null
            const res = await axios.get(`/students/${studentId}`)
            student.value =  res.data.data
        }
        loading.value= false
    }
    const close = () => {
        isOpen.value= false
        loading.value= false
    }

    return {isOpen, student, loading, open, close}
}
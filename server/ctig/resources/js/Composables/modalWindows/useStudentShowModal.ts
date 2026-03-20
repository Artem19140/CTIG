import { ref } from "vue";
import { router,useForm } from "@inertiajs/vue3";

const isOpen = ref<boolean>(false)
const student = ref()
const loading = ref<boolean>(false)
const error = ref<boolean>(false)

export const useStudentShowModal = () => {
    const open = async (studentId : number) => {
        isOpen.value= true
        loading.value= true
        if(student.value?.id !== studentId){
            const form = useForm({profile:true})
            form.get(`/students/${studentId}`,{
                onSuccess:(page) => {
                    if(page.flash.student){
                        student.value = page.flash.student
                    }
                    else{
                        error.value = true
                    }
                }
            })
        }
        loading.value= false
    }
    const close = () => {
        isOpen.value= false
        loading.value= false
        error.value = false
    }

    return {isOpen, student, loading, error, open, close}
}
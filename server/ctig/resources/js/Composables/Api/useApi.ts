import { ref } from "vue"

export const useApi = () => {
    const loading = ref<boolean>(false)
    const error =  ref<any>(null)
    const data = ref<any>(null)

    const request = async (fn : () => Promise<any>) => {
        loading.value = true
        error.value = false
        data.value = null
        try{
            const res = await fn()
            data.value = res.data
        }catch(e){
            error.value = true
        }finally{
            loading.value = false
        }
        
    }

    return {request, loading, error, data}
}
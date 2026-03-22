import { useApi } from "./useApi"
import axios from "axios"

export const useUser = () => {
    const {request, data, loading, error} = useApi()
    const get = (id:number) => {
        
        return request(() => axios.get(`users/${id}`))
    }
    return {get, data, loading, error}
}
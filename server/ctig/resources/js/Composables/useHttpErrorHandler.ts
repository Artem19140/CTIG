import { router } from '@inertiajs/vue3'
import { useSnackbarQueue } from './useSnackbarQueue'


export const useHttpErrorHandler = () => {
    const {add} = useSnackbarQueue()
    const handle = (error : any) => {
        
        switch(error.status){
            case 400:
                const message = JSON.parse(error.data)?.message
                addSnackBar(message)
                break;
            
            case 401:
                router.visit('/login')
                addSnackBar('Вы не авторизованы')
                break;
            case 403:
                addSnackBar('Нет доступа')
                break;
            case 404:
                addSnackBar('Не найдено')
                break;
            case 500:
                addSnackBar('Ошибка сервер')
                break;
            case 503:
                addSnackBar('Неизвестная ошибка')
                break;

            default:
            addSnackBar('Неизвестная ошибка')
        }
        
    }

    const addSnackBar = (text:string) =>{
        add(text, 'red')
    }
    return {handle}
}
import { router } from '@inertiajs/vue3'
import { useAlert } from './useAlert'

const {open} = useAlert()
export const useHttpErrorHandler = () => {


    const handle = (error : any) => {
        //const message = JSON.parse(error.response.data)?.message
        switch(error.status){
            case 400:
            //open(message)
                break;
            case 500:
            open('Ошибка сервера')
                break;
            case 401:
            router.visit('/login')
            open('Вы не авторизованы')
                break;
            case 403:
            open('Нет доступа')
                break;
            case 404:
            open('Не найдено')
                break;
            case 503:
                open('Неизвестная ошибка')
                break;

            default:
            open('Неизвестная ошибка')
        }
        
    }
    return {handle}
}
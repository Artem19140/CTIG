import axios from 'axios';
import { useAlert } from './Composables/useAlert';
import { router } from '@inertiajs/vue3'



window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const {open} = useAlert()

axios.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {

    if (!error.response) {
      console.error('Network error');
      open('Ошибка сети');
      return Promise.reject(error);
    }

    const status = error.response.status;

    switch (status) {
      case 401:
        router.visit('/login')
        open('Вы не авторизованы')
        break;

      case 403:
        console.error('Forbidden');
        open('Нет доступа')
        break;

      case 404:
        console.error('Not found');
        open('Не найдено')
        break;

      case 422:
        open(error.response.data)
        console.error('Validation error', error.response.data);
        break;

      case 500:
        open('Ошибка сервера')
        console.error('Server error');
        break;

      default:
        open('Неизвестная ошибка')
        console.error('API error', error.response);
    }
    return Promise.reject(error);
  }
);
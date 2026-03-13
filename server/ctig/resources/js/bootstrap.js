import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


axios.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {

    if (!error.response) {
      console.error('Network error');
      alert('Ошибка сети');
      return Promise.reject(error);
    }

    const status = error.response.status;

    switch (status) {
      case 401:
        window.location.href = '/login';
        break;

      case 403:
        console.error('Forbidden');
        alert('Нет доступа');
        break;

      case 404:
        console.error('Not found');
        break;

      case 422:
        console.error('Validation error', error.response.data);
        break;

      case 500:
        console.error('Server error');
        alert('Ошибка сервера');
        break;

      default:
        console.error('API error', error.response);
    }
    throw error;
    return Promise.reject(error);
  }
);
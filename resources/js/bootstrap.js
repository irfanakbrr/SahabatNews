import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

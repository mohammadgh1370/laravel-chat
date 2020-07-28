require('./bootstrap');

window.Vue = require('vue');

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

require('./components/chat/app');
const app = new Vue({
    el: '#app',
});

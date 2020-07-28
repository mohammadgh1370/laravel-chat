import VueTimeago from 'vue-timeago'

Vue.use(VueTimeago, {
    name: 'Timeago', // Component name, `Timeago` by default
    locale: 'en', // Default locale
});
require('./../../echo');
import ChatApplication from './components/ChatApplication.vue';
Vue.component('chat-application', ChatApplication);

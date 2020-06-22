require('./bootstrap');

import router from './router.js'
import store from './store'
import Axios from 'axios';
import Main from './components/Main';
import VCalendar from 'v-calendar';

window.Vue = require('vue');
Vue.use(VCalendar, {
    componentPrefix: 'vc',
});

axios.defaults.headers.common['Authorization'] = "Bearer " + document.querySelector('meta[name="api-token"]').getAttribute('content');

new Vue({
    router: router,
    store: store,
    watch: {
        '$route': function (to, from) {
            if (to.path !== from.path) {
                document.getElementById('navbar-toggle-button').setAttribute('aria-expanded', 'false');
                document.getElementById('navbarSupportedContent').classList.remove('show');
            }
          }
    },
    components: {
        app: Main
    }
}).$mount('#app');

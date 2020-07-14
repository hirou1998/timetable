require('./bootstrap');

import router from './router.js'
import store from './store'
import Axios from 'axios';
import Main from './components/Main';
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';

window.Vue = require('vue');
Vue.use(ElementUI, { locale });

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

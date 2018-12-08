import Vue from 'vue';
import Notifications from 'vue-notification';
import Datatable from '../../../node_modules/vue2-datatable-component/dist/min';
import '../../../node_modules/vue2-datatable-component/dist/min.css';

Vue.use(Notifications);
Vue.use(Datatable);

import '../scss/dashboard.scss';
import tablePanel from './components/tablePanel';
import store from './store/';

new Vue({
    el: '#content',
    template: '<tablePanel />',
    components: { tablePanel },
    store,
});

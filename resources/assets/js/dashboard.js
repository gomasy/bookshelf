import Vue from 'vue';
import Notifications from 'vue-notification';
import Datatable from 'vue2-datatable-component';

Vue.use(Notifications);
Vue.use(Datatable);

import '../scss/dashboard.scss';
import tablePanel from './components/tablePanel.vue';

new Vue({
    el: '#content',
    components: { tablePanel },
    template: '<tablePanel :options="options" />',
    data: () => ({
        options: {
            ajax: {
                'Content-Type': 'application/json',
                'X-Csrf-Token': document.head.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
        },
    }),
});

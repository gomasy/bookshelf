import Vue from 'vue';
import Notifications from 'vue-notification';
import Datatable from 'vue2-datatable-component';

// components
import tablePanel from './components/tablePanel.vue';

export default function(options) {
    Vue.use(Notifications);
    Vue.use(Datatable);

    return new Vue({
        el: '#content',
        components: { tablePanel },
        template: '<tablePanel :options="options" />',
        data: () => ({
            options: options,
        }),
    });
}

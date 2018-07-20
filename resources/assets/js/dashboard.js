import './general.js';
import '../scss/dashboard.scss';

import Vue from 'vue';
import Datatable from 'vue2-datatable-component';
Vue.use(Datatable);

// components
import tablePanel from './components/tablePanel.vue';
import registerForm from './components/registerForm.vue';

const table = new Vue({
    el: '#content',
    components: { tablePanel },
    template: '<tablePanel ref="tablePanel" />',
    methods: {
        create(entry) {
            this.$refs.tablePanel.create(entry);
        },
    },
});

new Vue({
    el: '#register',
    components: { registerForm },
    template: '<registerForm :table="table" />',
    data: () => ({
        table: table,
    }),
});

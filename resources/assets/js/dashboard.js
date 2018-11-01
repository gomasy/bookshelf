import Vue from 'vue';
import Datatable from 'vue2-datatable-component';

// components
import registerForm from './components/registerForm.vue';
import tablePanel from './components/tablePanel.vue';

export default function() {
    Vue.use(Datatable);

    const table = new Vue({
        el: '#content',
        components: { tablePanel },
        template: '<tablePanel ref="tablePanel" />',
        methods: {
            create(entry) {
                this.$refs.tablePanel.create(entry);
            },
            reader() {
                this.$refs.tablePanel.reader();
            },
        },
    });

    return new Vue({
        el: '#register',
        components: { registerForm },
        template: '<registerForm :table="table" />',
        data: () => ({
            table: table,
        }),
    });
}

import Vue from 'vue';
import store from './store/';
import Datatable from '../../../node_modules/vue2-datatable-component/dist/min';
import '../../../node_modules/vue2-datatable-component/dist/min.css';

Vue.use(Datatable);

import editShelves from './components/editShelves';

new Vue({
    el: '#shelves',
    template: `
        <div id="shelves">
            <editShelves />
        </div>
    `,
    components: { editShelves },
    store,
});

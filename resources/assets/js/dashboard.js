import '../js/core.js';
import '../scss/dashboard.scss';

import Vue from 'vue';
import Datatable from 'vue2-datatable-component';

Vue.use(Datatable);
$.get('list.json').done(result => {
    new Vue({
        el: '#app',
        template: `
            <div class="panel panel-default">
                <div class="panel-body">
                    <datatable v-bind="$data">
                        <button class="btn btn-primary" :class="{ 'disabled': selection.length != 1 }">編集</button>
                        <button class="btn btn-danger" :class="{ 'disabled': !selection.length }">削除</button>
                    </datatable>
                </div>
            </div>
        `,
        data: () => ({
            columns: [
                { 'title': 'タイトル', field: 'title', sortable: true },
                { 'title': '巻号', field: 'volume' },
                { 'title': '著作等', field: 'authors', sortable: true },
                { 'title': '出版日', field: 'published_date', sortable: true },
            ],
            data: result,
            total: result.length,
            selection: [],
            query: {},
        }),
        methods: {
        },
    });
});

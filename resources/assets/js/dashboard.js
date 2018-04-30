import '../js/core.js';
import '../scss/dashboard.scss';

import Vue from 'vue';
import Datatable from 'vue2-datatable-component';
import editModal from './components/editModal.vue';

Vue.use(Datatable);
const table = new Vue({
    el: '#content',
    components: { editModal },
    template: `
        <div id="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <datatable v-bind="$data">
                        <button class="btn btn-primary" :disabled="selection.length != 1" data-toggle="modal" data-target="#edit-modal" @click="edit">編集</button>
                        <button class="btn btn-danger" :disabled="selection.length == 0" @click="remove">削除</button>
                    </datatable>
                </div>
            </div>
            <div id="modal">
                <editModal ref="editModal" :columns="columns" :selection="selection" />
            </div>
        </div>
    `,
    data: {
        columns: [
            {
                title: 'タイトル',
                field: 'title',
                sortable: true,
                type: 'text',
                required: true,
            },
            {
                title: '巻号',
                field: 'volume',
                type: 'text',
                required: false,
            },
            {
                title: '著作等',
                field: 'authors',
                sortable: true,
                type: 'text',
                required: true,
            },
            {
                title: '出版日',
                field: 'published_date',
                sortable: true,
                type: 'date',
                required: true,
            },
        ],
        data: [],
        total: 0,
        selection: [],
        query: {},
    },
    watch: {
        query: {
            handler() {
                this.fetch();
            },
        },
    },
    methods: {
        fetch() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '/list.json');
            xhr.responseType = 'json';
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.addEventListener('load', event => {
                const result = event.target.response;

                this.data = result;
                this.total = result.length;
            });
            xhr.send();
        },
        edit() {
            this.$refs.editModal.open();
        },
        remove() {
            const xhr = new XMLHttpRequest();
            const ids = [];
            for (let e of this.selection) ids.push(e.id);

            xhr.open('POST', '/delete');
            xhr.setRequestHeader('Content-Type', 'application/json;charset=utf-8');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.addEventListener('load', event => {
                if (event.target.status == 204) {
                    for (let id of ids) {
                        for (let i = 0; i < this.data.length; i++) {
                            if (this.data[i].id == id) this.data.splice(i, 1);
                        }
                    }
                    this.total = this.data.length;
                }
            });
            xhr.send(JSON.stringify({ ids: ids }));
        },
    },
});

new Vue({
    el: '#register',
    template: `
        <form class="form-inline" id="register" @submit.prevent="create">
            <input class="form-control" type="text" placeholder="ISBN or JP番号" v-model="code" required>
            <button class="btn btn-info" type="submit">登録</button>
            <button class="btn btn-success" type="button">読み取る</button>
        </form>
    `,
    data: {
        code: '',
    },
    methods: {
        create() {
            const xhr = new XMLHttpRequest();

            xhr.open('POST', '/create');
            xhr.responseType = 'json';
            xhr.setRequestHeader('Content-Type', 'application/json;charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.addEventListener('load', event => {
                if (event.target.status == 200) {
                    table.data.push(event.target.response);
                    table.total++;

                    this.code = '';
                }
            });
            xhr.send(JSON.stringify({ code: this.code }));
        },
    },
});

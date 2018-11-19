<template>
    <main class="dashboard" id="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <datatable v-bind="$data">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#edit-modal" :disabled="selection.length != 1" @click="edit">編集</button>
                    <button class="btn btn-danger" :disabled="selection.length == 0" @click="remove">削除</button>
                </datatable>
            </div>
        </div>
        <div id="modal">
            <editModal ref="edit" />
            <cameraModal ref="camera" />
            <confirmModal ref="confirm" />
            <previewModal ref="preview" />
        </div>
        <notifications position="bottom right" />
    </main>
</template>

<script>
import Vue from 'vue';
import registerForm from './registerForm.vue';
import thFilter from './th-Filter.vue';
import tdImage from './td-Image.vue';

// modal
import editModal from './modals/editModal.vue';
import cameraModal from './modals/cameraModal.vue';
import confirmModal from './modals/confirmModal.vue';
import previewModal from './modals/previewModal.vue';
import addConfirmBody from './modals/addConfirmBody.vue';

// utils
import notify from '../utils/notify.js';

export default {
    props: [ 'options' ],
    components: { editModal, cameraModal, confirmModal, previewModal },
    data: () => ({
        columns: [
            {
                field: 'images',
                type: 'hidden',
                tdComp: tdImage,
            },
            {
                title: 'タイトル',
                field: 'title',
                sortable: true,
                thComp: thFilter,
                thStyle: 'width: 60%',
                required: true,
            },
            {
                title: '巻号',
                field: 'volume',
                thStyle: 'width: 20%',
            },
            {
                title: '著者等',
                field: 'authors',
                sortable: true,
                thComp: thFilter,
                thStyle: 'width: 20%',
                required: true,
            },
            {
                title: '出版社',
                field: 'publisher',
                visible: false,
            },
            {
                title: '価格',
                field: 'price',
                visible: false,
            },
        ],
        data: [],
        query: {},
        selection: [],
        supportBackup: true,
        total: 0,
    }),
    methods: {
        fetch(query) {
            let url = '/list.json?';
            if (query !== undefined) {
                Object.keys(query).map(k => url += k + '=' + query[k] + '&');
            }

            fetch(url.substring(url.length - 1, -1), {
                headers: this.options.ajax,
            }).then(async response => {
                if (!response.ok) {
                    return Promise.reject(response);
                }

                return await response.json();
            }).then(result => {
                this.data = result.data;
                this.total = result.total;
            });
        },
        before_create(callback, code, confirmed) {
            fetch('/fetch?code=' + code, {
                headers: this.options.ajax,
            }).then(async response => {
                if (!response.ok) {
                    return Promise.reject(response);
                }
                const entry = await response.json();
                const reqId = response.headers.get('X-Request-Id');

                if (confirmed) {
                    this.create(entry, reqId);
                    callback();
                } else {
                    this.$refs.confirm.open(callback, addConfirmBody, entry, reqId);
                }
            }).catch(async e => this.notify(await e));
        },
        create(entry, reqId) {
            fetch('/create', {
                method: 'post',
                headers: this.options.ajax,
                body: JSON.stringify({ 'id': reqId }),
            }).then(async response => {
                this.notify(await response);

                if (!response.ok) {
                    return Promise.reject(response);
                }

                return await response.json();
            }).then(entry => {
                this.data.push(entry);
                this.total++;
            });
        },
        readerProxy() {
            this.$refs.camera.start();
        },
        edit() {
            this.$refs.edit.open();
        },
        remove() {
            const ids = [];
            this.selection.map(e => ids.push(e.id));
            this.$refs.confirm.open(() => {
                fetch('/delete', {
                    method: 'post',
                    headers: this.options.ajax,
                    body: JSON.stringify({ ids: ids }),
                }).then(response => {
                    if (!response.ok) {
                        return Promise.reject(response);
                    }

                    this.fetch(this.query);
                });
            }, {
                template: '<div class="modal-body" id="confirm-body">本当に削除しますか？</div>',
            });
        },
        notify(response) {
            notify(this.$notify, response);
        },
    },
    watch: {
        query: {
            handler(query) {
                this.fetch(query);
            },
            deep: true,
        },
    },
    mounted() {
        new Vue({
            el: '#register',
            components: { registerForm },
            template: '<registerForm :table="table" />',
            data: () => ({
                table: this,
            }),
        });
    },
};
</script>

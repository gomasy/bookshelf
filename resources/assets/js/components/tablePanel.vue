<template>
    <main class="dashboard" :class="viewMode" id="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <datatable v-bind="$data">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#edit-modal" :disabled="selection.length != 1" @click="edit">編集</button>
                    <button class="btn btn-danger" :disabled="selection.length == 0" @click="remove">削除</button>
                </datatable>
            </div>
        </div>
        <div id="modal">
            <editModal ref="edit" :books="books" />
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

import { Books, UserSetting } from '../books/';
import { tdImage, thFilter } from './tpanel/';
import { addConfirmBody, cameraModal, confirmModal, editModal, previewModal } from './modals/';

export default {
    components: {
        addConfirmBody,
        cameraModal,
        confirmModal,
        editModal,
        previewModal,
        tdImage,
        thFilter,
    },
    data: () => ({
        books: null,
        viewMode: '',
        imageSize: 'thumb',
        columns: [
            {
                field: 'images',
                type: 'hidden',
                tdComp: 'tdImage',
                thStyle: 'width: 52px',
            },
            {
                title: 'タイトル',
                field: 'title',
                sortable: true,
                thComp: 'thFilter',
                thStyle: 'width: 40%',
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
                thComp: 'thFilter',
                thStyle: 'width: 15%',
                required: true,
            },
            {
                title: '出版社',
                field: 'publisher',
                hStyle: 'width: 20%',
                visible: true,
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
        pageSizeOptions: [ 10, 20, 50, 100 ],
        supportBackup: true,
        total: 0,
    }),
    methods: {
        fetch(query) {
            this.books.fetch(query, result => {
                this.data = result.data;
                this.total = result.total;
            });
        },
        before_create(callback, code, confirmed) {
            this.books.before_create(code, (entry, reqId) => {
                if (confirmed) {
                    this.books.create(entry, reqId);
                    callback(entry, reqId);
                } else {
                    this.$refs.confirm.open(callback, addConfirmBody, entry, reqId);
                }
            });
        },
        create(entry, reqId) {
            if (this.books.create(entry, reqId)) {
                this.data.push(entry);
                this.total++;
            }
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
            this.$refs.confirm.open(items => {
                this.books.delete(items, () => this.fetch(this.query));
            }, {
                template: '<div class="modal-body" id="confirm-body">本当に削除しますか？</div>',
            }, ids);
        },
    },
    watch: {
        query: {
            handler(query) {
                this.fetch(query);
                Vue.ls.set('query', this.query);
            },
            deep: true,
        },
    },
    created() {
        this.books = new Books(this.$notify);
        this.query = Vue.ls.get('query', {});
        UserSetting.get().then(obj => {
            switch (obj.display_format) {
            case 1:
                [ this.viewMode, this.imageSize ] = [ 'album', 'large' ];
                break;
            }
        });
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

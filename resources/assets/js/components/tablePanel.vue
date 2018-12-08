<template>
    <main class="dashboard" :class="viewMode" id="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <datatable v-bind="$data">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#edit-modal" :disabled="selection.length != 1" @click="edit">編集</button>
                    <button class="btn btn-danger" :disabled="selection.length == 0" @click="remove">削除</button>
                    <select class="form-control select-shelves" v-model="query.sid">
                        <option v-for="shelf in shelves" :key="shelf.id" :value="shelf.id">{{ shelf.name }}</option>
                    </select>
                </datatable>
            </div>
        </div>
        <div id="modal">
            <editModal ref="edit" />
            <cameraModal ref="camera" />
            <confirmModal ref="confirm" />
            <previewModal ref="preview" :settings="settings" />
        </div>
        <notifications position="bottom right" />
    </main>
</template>

<script>
/* eslint "vue/no-unused-components":0 */

import Vue from 'vue';
import { mapActions, mapState } from 'vuex';
import registerForm from './registerForm';

import { Books } from '../books/';
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
        query: {
            limit: 20,
            sid: null,
        },
        selection: [],
        pageSizeOptions: [ 10, 20, 50, 100 ],
        supportBackup: true,
        total: 0,
    }),
    computed: {
        ...mapState({
            settings: 'settings',
            shelves: 'shelves',
            viewMode: 'viewMode',
            imageSize: 'imageSize',
        }),
    },
    methods: {
        ...mapActions({
            getSettings: 'getSettings',
        }),
        fetch(query) {
            this.books.fetch(query).then(result => {
                this.data = result.data;
                this.total = result.total;
            });
            localStorage.setItem('query', JSON.stringify(query));
        },
        before_create(callback, code, confirmed) {
            this.books.before_create(code, (entry, reqId) => {
                if (confirmed) {
                    this.create(entry, reqId);
                    callback(entry, reqId);
                } else {
                    this.$refs.confirm.open(callback, addConfirmBody, entry, reqId);
                }
            });
        },
        create(entry, reqId) {
            if (this.books.create(entry, reqId, this.query.sid)) {
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
                this.books.delete(items).then(() => this.fetch(this.query));
            }, {
                template: '<div class="modal-body" id="confirm-body">本当に削除しますか？</div>',
            }, ids);
        },
    },
    watch: {
        query: {
            handler(query) {
                if (query.sid !== null) {
                    this.fetch(query);
                }
            },
            deep: true,
        },
    },
    created() {
        this.books = new Books(this.$notify);
        this.getSettings().then(() => {
            this.query = JSON.parse(localStorage.getItem('query')) || this.query;
            if (this.query.sid === undefined || this.query.sid === null) {
                this.query.sid = this.shelves.find(e => e.name === 'default').id;
            }

            switch (this.settings.display_format) {
            case 1:
                this.$store.dispatch('setViewMode', { mode: 'album', size: 'large' });
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

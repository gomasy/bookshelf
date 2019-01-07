<template>
    <main class="dashboard" :class="viewMode" id="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <datatable v-bind="$data">
                    <div class="table-buttons">
                        <button class="btn btn-primary" :disabled="selection.length != 1" @click="edit">編集</button>
                        <button class="btn btn-danger" :disabled="selection.length == 0" @click="remove">削除</button>
                        <select class="form-control select-shelves" v-model="query.sid">
                            <option v-for="shelf in shelves" :key="shelf.id" :value="shelf.id">{{ shelf.name }}</option>
                        </select>
                    </div>
                </datatable>
            </div>
        </div>
        <component ref="modal" :is="currentModal" :selection="selection" />
        <loading ref="loading" />
        <notifications position="bottom right" />
    </main>
</template>

<script>
/* eslint "vue/no-unused-components":0 */

import Vue from 'vue';
import { mapActions, mapState } from 'vuex';
import { Books } from '../books/';
import { tdImage, thFilter } from './tpanel/';
import {
    addConfirmBody, deleteConfirmBody,
    cameraModal,confirmModal, editModal, previewModal, selectorModal,
} from './modals/';

import registerForm from './registerForm';
import loading from './loading';
import columns from './columns.json';

export default {
    components: {
        cameraModal, confirmModal, editModal, previewModal, selectorModal,
        tdImage, thFilter, loading,
    },
    data: () => ({
        'tbl-class': '',
        books: null,
        register: null,
        currentModal: null,
        columns: columns,
        data: [],
        selection: [],
        query: {
            limit: 20,
            sid: null,
        },
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
        updateStatus(data) {
            if (this.settings.status) {
                (data || this.data).map(e => {
                    switch (e.status_id) {
                    case 1:
                        e.trClass = 'unread';
                        break;
                    case 2:
                        e.trClass = 'reading';
                        break;
                    case 3:
                        e.trClass = 'already-read';
                        break;
                    }
                });
            } else {
                this['tbl-class'] = 'table-striped table-hover';
            }
        },
        fetch(query) {
            this.books.fetch(query).then(result => {
                this.updateStatus(result.data);
                this.data = result.data;
                this.total = result.total;
            });
            localStorage.setItem('query', JSON.stringify(query));
        },
        beforeCreate(callback, type, payload, confirmed) {
            this.$refs.loading.show('検索中・・・');
            this.books.beforeCreate(this.query.sid, type, payload, entry => {
                if (confirmed) {
                    this.create(entry);
                    callback(entry);
                } else {
                    this.currentModal = entry.length > 1 ? selectorModal : confirmModal;
                    this.$nextTick(() => {
                        this.$refs.modal.open(callback, entry, addConfirmBody);
                    });
                }
            }, () => {
                this.$refs.loading.hide();
            });
        },
        create(entry) {
            this.books.create(this.query.sid, entry).then(result => {
                result.map(e => {
                    this.data.push(e);
                    this.total++;
                });
                this.updateStatus();
            });
        },
        reader() {
            this.currentModal = cameraModal;
            this.$nextTick(() => this.$refs.modal.start());
        },
        edit() {
            this.currentModal = editModal;
            this.$nextTick(() => this.$refs.modal.open());
        },
        remove() {
            const ids = [];
            this.selection.map(e => ids.push(e.id));
            this.currentModal = confirmModal;
            this.$nextTick(() => {
                this.$refs.modal.open(items => {
                    this.books.delete(items).then(() => this.fetch(this.query));
                }, ids, deleteConfirmBody);
            });
        },
        preview(url) {
            this.currentModal = previewModal;
            this.$nextTick(() => this.$refs.modal.open(url));
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
        selection: {
            handler(data) {
                this.updateStatus(data);
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
        this.register = new Vue({
            el: '#register',
            template: '<registerForm :table="table" />',
            components: { registerForm },
            data: () => ({
                table: this,
            }),
        });
    },
};
</script>

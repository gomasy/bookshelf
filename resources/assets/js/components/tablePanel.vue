<template>
    <main class="dashboard" :class="viewMode" id="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <datatable class="main-table" v-bind="$data">
                    <div class="table-buttons">
                        <button class="btn btn-primary" :disabled="selection.length !== 1" @click="edit">編集</button>
                        <button class="btn btn-danger" :disabled="!selection.length" @click="remove">削除</button>
                        <button class="btn btn-warning" :disabled="!selection.length || shelves.length < 2" @click="move">移動</button>
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
    addConfirmBody, booksMoveConfirmBody, deleteConfirmBody,
    cameraModal,confirmModal, editModal, previewModal, selectorModal,
} from './modals/';

import registerForm from './registerForm';
import loading from './loading';
import columns from './columns.json';

export default {
    components: { tdImage, thFilter, loading },
    data: () => ({
        'tbl-class': null,
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
            statuses: 'statuses',
            viewMode: 'viewMode',
            imageSize: 'imageSize',
        }),
        ids() {
            return this.selection.map(e => e.id);
        },
        books() {
            return new Books(this.$notify);
        },
    },
    methods: {
        ...mapActions({
            getSettings: 'getSettings',
        }),
        updateStatus(data) {
            if (this.settings.status) {
                (data || this.data).forEach(e => {
                    e.trStyle = 'background: ' + this.statuses[e.status_id - 1].color;
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

                localStorage.setItem('query', JSON.stringify(query));
            }).catch(e => {
                alert('一覧の読み込みに失敗しました。');
                if (e.status === 401) {
                    localStorage.removeItem('query');
                }
            });
        },
        beforeCreate(callback, type, payload, confirmed) {
            const controller = new AbortController();

            this.$refs.loading.show('検索中・・・', controller);
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
            }, controller);
        },
        create(entry) {
            this.books.create(this.query.sid, entry).then(result => {
                result.forEach(e => {
                    this.data.push(e);
                    this.total++;
                });
                this.updateStatus();
            });
        },
        reader() {
            this.currentModal = cameraModal;
            this.$nextTick(() => this.$refs.modal.open());
        },
        edit() {
            this.currentModal = editModal;
            this.$nextTick(() => this.$refs.modal.open());
        },
        remove() {
            this.currentModal = confirmModal;
            this.$nextTick(() => {
                this.$refs.modal.open(items => {
                    Books.delete(items).then(() => this.fetch(this.query));
                }, this.ids, deleteConfirmBody);
            });
        },
        move() {
            const options = { current: this.query.sid, next: null };

            this.currentModal = confirmModal;
            this.$nextTick(() => {
                this.$refs.modal.open((items, options) => {
                    Books.move(items, options.current, options.next).then(() => this.fetch(this.query));
                }, this.ids, booksMoveConfirmBody, options);
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
        this.getSettings().then(() => {
            const query = localStorage.getItem('query');
            if (query) {
                this.query = JSON.parse(query);
            }

            if (this.query.sid === null) {
                this.query.sid = this.shelves.find(e => e.name === 'default').id;
            }

            // set viewmode
            this.$store.dispatch('setViewMode', this.settings.display_format);
        });
    },
    mounted() {
        new Vue({
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

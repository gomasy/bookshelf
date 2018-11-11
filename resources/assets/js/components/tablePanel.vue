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
        </div>
        <notifications position="bottom right" />
    </main>
</template>

<script>
import Vue from 'vue';
import thFilter from './th-Filter.vue';
import registerForm from './registerForm.vue';

// modal
import editModal from './modals/editModal.vue';
import cameraModal from './modals/cameraModal.vue';
import confirmModal from './modals/confirmModal.vue';

export default {
    props: [ 'options' ],
    components: { editModal, cameraModal, confirmModal },
    data: () => ({
        columns: [
            {
                title: 'タイトル',
                field: 'title',
                sortable: true,
                thComp: thFilter,
                thStyle: 'width: 60%',
                type: 'text',
                required: true,
            },
            {
                title: '巻号',
                field: 'volume',
                type: 'text',
                required: false,
                thStyle: 'width: 20%',
            },
            {
                title: '著者等',
                field: 'authors',
                sortable: true,
                thComp: thFilter,
                thStyle: 'width: 20%',
                type: 'text',
                required: true,
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

            fetch(url.substring(url.length - 1, -1)).then(async response => {
                if (!response.ok) {
                    return Promise.reject(response);
                }

                return await response.json();
            }).then(result => {
                this.data = result.data;
                this.total = result.total;
            });
        },
        before_create(cba, code, confirmed) {
            fetch('/fetch?code=' + code).then(async response => {
                if (!response.ok) {
                    return Promise.reject(response);
                }

                const entry = await response.json();
                if (confirmed) {
                    this.create(entry);
                    cba();
                } else {
                    this.$refs.confirm.open(() => {
                        return '<p>一件見つかりました。本当に登録しますか？</p>タイトル: ' + entry.title;
                    }, cba, entry);
                }
            }).catch(async e => this.notify(await e));
        },
        create(entry) {
            fetch('/create', {
                method: 'post',
                headers: this.options.ajax,
                body: JSON.stringify(entry),
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
                return '本当に削除しますか？';
            }, () => {
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
            });
        },
        notify(response) {
            switch (response.status) {
            case 200:
                this.$notify({
                    type: 'success',
                    title: '完了',
                    text: '該当する書籍の登録に成功しました',
                });
                break;
            case 404:
                this.$notify({
                    type: 'warn',
                    title: '見つかりません',
                    text: '該当する書籍が見つかりませんでした',
                });
                break;
            case 409:
                this.$notify({
                    type: 'error',
                    title: '登録済み',
                    text: 'その書籍は既に登録されています',
                });
                break;
            }
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

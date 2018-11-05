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
        </div>
        <notifications position="bottom right" />
    </main>
</template>

<script>
import Vue from 'vue';
import registerForm from './registerForm.vue';
import editModal from './editModal.vue';
import cameraModal from './cameraModal.vue';
import thFilter from './th-Filter.vue';

export default {
    props: [ 'options' ],
    components: { editModal, cameraModal },
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
                thStyle: 'width: 15%',
            },
            {
                title: '著者等',
                field: 'authors',
                sortable: true,
                thComp: thFilter,
                thStyle: 'width: 15%',
                type: 'text',
                required: true,
            },
            {
                title: '出版日',
                field: 'published_date',
                sortable: true,
                thComp: thFilter,
                thStyle: 'width: 10%',
                type: 'date',
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

            fetch(url.substring(url.length - 1, -1), {
                method: 'get',
                headers: this.options.ajax,
            }).then(response => {
                if (!response.ok) {
                    throw response;
                }

                return response.json();
            }).then(result => {
                this.data = result.data;
                this.total = result.total;
            });
        },
        create(entry) {
            this.data.push(entry);
            this.total++;
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

            fetch('/delete', {
                method: 'post',
                headers: this.options.ajax,
                body: JSON.stringify({ ids: ids }),
            }).then(response => {
                if (!response.ok) {
                    throw response;
                }

                this.fetch(this.query);
                this.$notify({
                    type: 'success',
                    title: '完了',
                    text: '本の削除に成功しました',
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

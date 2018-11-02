<template>
    <div id="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <datatable v-bind="$data">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#edit-modal" :disabled="selection.length != 1" @click="edit">編集</button>
                    <button class="btn btn-danger" :disabled="selection.length == 0" @click="remove">削除</button>
                </datatable>
            </div>
        </div>
        <div id="modal">
            <editModal ref="editModal" :columns="columns" :selection="selection" :options="options" />
            <cameraModal ref="cameraModal" />
        </div>
    </div>
</template>

<script>
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
            this.$refs.cameraModal.start();
        },
        edit() {
            this.$refs.editModal.open();
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
            });
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
};
</script>

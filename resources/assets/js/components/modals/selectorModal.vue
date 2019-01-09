<template>
    <div class="modal fade" id="selector-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <datatable v-bind="$data">
                        <p>{{ total }} 件見つかりました（重複は除く）。登録したい本を選択して下さい。</p>
                    </datatable>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" @click="hide">キャンセル</button>
                    <button class="btn btn-info" @click="create">確認して登録</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
/* eslint "vue/no-unused-components":0 */

import { tdImage } from '../tpanel/';

export default {
    components: { tdImage },
    data: () => ({
        callback: null,
        columns: [
            { field: 'images', tdComp: 'tdImage', thStyle: 'width: 53px' },
            { field: 'title', title: 'タイトル' },
            { field: 'volume', title: '巻号', thStyle: 'width: 20%' },
        ],
        entry: [],
        data: [],
        selection: [],
        query: { limit: 5 },
        total: 0,
        HeaderSettings: false,
        pageSizeOptions: [ 5, 10, 20, 50 ],
    }),
    methods: {
        open(callback, entry) {
            this.callback = callback;
            this.entry = entry;
            this.total = entry.length;
            this.handleQueryChange();

            $('#selector-modal').modal('show');
        },
        create() {
            this.callback(this.selection);
            this.hide();
        },
        hide() {
            $('#selector-modal').modal('hide');
        },
        handleQueryChange() {
            this.data = this.entry.slice(this.query.offset, this.query.offset + this.query.limit);
        },
    },
    watch: {
        query: {
            handler() {
                this.handleQueryChange();
            },
            deep: true,
        },
    },
};
</script>

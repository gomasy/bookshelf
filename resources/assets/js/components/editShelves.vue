<template>
    <div class="form-group">
        <h4>編集</h4>
        <datatable v-bind="$data" />
        <confirmModal ref="confirm" />
        <hr>
        <div class="form-group form-inline">
            <input class="form-control" placeholder="本棚の名前" v-model="shelfName">
            <button class="btn btn-info" :disabled="!shelfName.length" @click="create">追加</button>
        </div>
    </div>
</template>

<script>
/* eslint "vue/no-unused-components":0 */

import { mapActions, mapState } from 'vuex';
import { tdModifyBtn } from './tpanel/';
import { confirmModal, shelfRemoveConfirmBody } from './modals';
import { Settings } from '../books/';

export default {
    components: { confirmModal, tdModifyBtn },
    data: () => ({
        shelfName: '',
        columns: [
            { field: 'name', thStyle: 'width: 100%' },
            { tdComp: 'tdModifyBtn' },
        ],
        query: {},
        data: [],
        total: 0,
        HeaderSettings: false,
        Pagination: false,
    }),
    computed: {
        ...mapState({
            shelves: 'shelves',
        }),
    },
    methods: {
        ...mapActions({
            getSettings: 'getSettings',
        }),
        fetch() {
            this.getSettings().then(() => {
                this.data = this.shelves;
                this.total = this.shelves.length;
            });
        },
        async create() {
            try {
                await Settings.createShelves(this.shelfName);

                this.shelfName = '';
                this.fetch();
            } catch (e) {
                alert('重複しているか、使用できない名前です。');
            }
        },
        remove(id) {
            const options = { checked: false };

            this.$refs.confirm.open(async (items, options) => {
                await Settings.removeShelves(id, options.checked);
                this.fetch();
            }, null, shelfRemoveConfirmBody, options);
        },
    },
    created() {
        this.fetch();
    },
};
</script>

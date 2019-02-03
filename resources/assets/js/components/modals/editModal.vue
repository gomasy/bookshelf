<template>
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>編集</h4>
                </div>
                <form class="form-horizontal" @submit.prevent="submit">
                    <div class="modal-body">
                        <div class="form-group" v-for="col in columns" :key="col.field">
                            <label class="col-sm-2 control-label" v-if="col.type !== 'hidden'">{{ col.title }}</label>
                            <div class="col-sm-9">
                                <input class="form-control" v-model="items[col.field]" :type="col.type" :required="col.required" :disabled="total > 1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ラベル</label>
                            <div class="col-sm-9">
                                <select class="form-control" v-model="items.status_id">
                                    <option v-for="status in statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-dismiss="modal">キャンセル</button>
                        <button class="btn btn-info" type="submit">決定</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { Books } from '../../books/';

export default {
    props: [ 'columns', 'selection' ],
    data: () => ({
        items: {},
        total: 0,
    }),
    computed: {
        ...mapState({
            statuses: 'statuses',
        }),
    },
    methods: {
        open() {
            this.items = { ...this.selection[0] };
            this.total = this.selection.length;

            $('#edit-modal').modal('show');
        },
        submit() {
            this.selection.forEach((obj, index) => {
                Object.keys(obj).forEach(key => {
                    if (this.total === 1 || key === 'status_id') {
                        this.$set(this.selection[index], key, this.items[key]);
                    }
                });
            });

            Books.edit(this.selection).then(() => {
                $('#edit-modal').modal('hide');
            });
        },
    },
};
</script>

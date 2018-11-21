<template>
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>編集</h4>
                </div>
                <form class="form-horizontal" @submit.prevent="submit">
                    <div class="modal-body">
                        <div class="form-group" v-for="col in this.$parent.columns" :key="col.field">
                            <label class="col-sm-2 control-label" v-if="col.type !== 'hidden'">{{ col.title }}</label>
                            <div class="col-sm-9">
                                <input class="form-control" v-model="items[col.field]" :type="col.type" :required="col.required">
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
import { Books } from '../../books/';

export default {
    data: () => ({
        books: null,
        items: {},
    }),
    methods: {
        open() {
            this.items = Object.assign({}, this.$parent.selection[0]);
        },
        submit() {
            this.books.edit(this.items, () => {
                this.$parent.columns.map(col => {
                    this.$parent.selection[0][col.field] = this.items[col.field];
                });
                $('#edit-modal').modal('hide');
            });
        },
    },
    mounted() {
        this.books = new Books(this.$notify);
    }
};
</script>

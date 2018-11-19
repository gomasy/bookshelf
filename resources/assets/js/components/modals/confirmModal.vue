<template>
    <div class="modal fade" id="confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body" id="confirm-body">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">キャンセル</button>
                    <button class="btn btn-info" type="button" @click="accept">OK</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';

export default {
    data: () => ({
        callback: null,
        items: null,
        options: null,
    }),
    methods: {
        open(callback, confirmBody, items, options) {
            this.items = items;
            this.callback = callback;
            this.options = options;

            new Vue({
                components: { confirmBody },
                el: '#confirm-body',
                template: '<confirmBody :book="book" />',
                data: () => ({ book: items }),
            });

            $('#confirm-modal').modal('show');
        },
        accept() {
            if (typeof this.callback === 'function') {
                this.callback(this.items, this.options);
            }

            $('#confirm-modal').modal('hide');
        },
    },
};
</script>

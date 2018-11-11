<template>
    <div class="modal fade" id="confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">
                    <span v-html="body"></span>
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
export default {
    data: () => ({
        body: '',
        callback: null,
        items: null,
    }),
    methods: {
        open(before_cb, after_cb, items) {
            this.items = items;
            this.callback = after_cb;

            if (typeof before_cb === 'function') {
                this.body = before_cb(this.items);
            }

            $('#confirm-modal').modal('show');
        },
        accept() {
            if (typeof this.callback === 'function') {
                this.callback(this.items);
            }

            $('#confirm-modal').modal('hide');
        },
    },
};
</script>

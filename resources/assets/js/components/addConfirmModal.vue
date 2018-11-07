<template>
    <div class="modal fade" id="add-confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">
                    {{ items }}
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
        items: {},
        callback: null,
    }),
    methods: {
        open(entry, callback) {
            this.items = entry;
            this.callback = callback;
            $('#add-confirm-modal').modal('show');
        },
        accept() {
            this.$parent.create(this.items);
            this.callback();
            $('#add-confirm-modal').modal('hide');
        },
    },
};
</script>

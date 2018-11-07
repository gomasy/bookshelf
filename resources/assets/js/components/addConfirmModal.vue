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
                    <button class="btn btn-info" type="button" @click="create">OK</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        items: {},
    }),
    methods: {
        open(entry) {
            this.items = entry;
            $('#add-confirm-modal').modal('show');
        },
        create() {
            this.$parent.create(this.items['isbn']);
            $('#add-confirm-modal').modal('hide');
        },
    },
};
</script>

<template>
    <div class="modal fade" id="confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <component :is="body" :items="items"></component>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">キャンセル</button>
                    <button class="btn btn-info" type="button" @click="accept" ref="ok">OK</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        items: [],
        callback: null,
        options: null,
        body: null,
    }),
    methods: {
        open(callback, items, confirmBody, options) {
            this.callback = callback;
            this.items = items;
            this.body = confirmBody;
            this.options = options;

            $('#confirm-modal').modal('show');
            this.$refs.ok.focus();
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

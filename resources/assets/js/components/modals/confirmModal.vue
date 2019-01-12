<template>
    <div class="modal fade" id="confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <component :is="body" :items="items" :options="options"></component>
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
        options: {},
        body: null,
    }),
    methods: {
        open(callback, items, body, options) {
            this.callback = callback;
            this.items = items;
            this.body = body;
            this.options = options;

            $('#confirm-modal').modal('show');
            this.$refs.ok.focus();
        },
        accept() {
            this.callback(this.items, this.options);

            $('#confirm-modal').modal('hide');
        },
    },
};
</script>

<template>
    <div class="loading" v-if="visible">
        <div class="loading-inner">
            <div class="loading-circle"></div>
            <span class="loading-text">{{ text }}</span>
            <button class="btn loading-cancel-btn" @click="cancel">キャンセル</button>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        controller: null,
        text: '',
        visible: false,
    }),
    methods: {
        show(text, controller) {
            this.controller = controller;
            this.text = text;
            this.visible = true;
        },
        hide() {
            this.visible = false;
        },
        cancel() {
            if (typeof this.controller !== 'undefined') {
                this.controller.abort();
            }

            this.hide();
        },
    },
};
</script>

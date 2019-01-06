<template>
    <div class="loading" v-if="visible">
        <div class="loading-inner">
            <div class="loading-circle"></div>
            <span class="loading-text">{{ text }}</span>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        visible: false,
        text: '',
    }),
    methods: {
        show(text) {
            this.text = text;
            this.visible = true;
        },
        hide() {
            this.visible = false;
        },
    },
};
</script>

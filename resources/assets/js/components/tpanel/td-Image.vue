<template>
    <img :src="images" :class="size" @click="open">
</template>

<script>
export default {
    props: [ 'value' ],
    data: () => ({
        table: null,
        images: null,
        size: null,
    }),
    methods: {
        open() {
            this.table.$refs.preview.open(this.value);
        },
        update() {
            this.images = this.value[this.size];
            this.size = this.table.imageSize;
        }
    },
    watch: {
        value() {
            this.update();
        },
        size() {
            this.update();
        },
    },
    created() {
        this.table = this.$root.$children[0];
        this.update();
    },
};
</script>

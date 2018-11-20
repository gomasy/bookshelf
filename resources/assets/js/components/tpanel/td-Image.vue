<template>
    <img :src="value.thumb" @click="open">
</template>

<script>
export default {
    props: [ 'value' ],
    methods: {
        open() {
            const table = this.$root.$children[0];
            table.$refs.preview.open(this.value);
        },
    },
};
</script>

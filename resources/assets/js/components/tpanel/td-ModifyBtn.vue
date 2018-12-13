<template>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-danger" style="margin-top: -5px" :disabled="row.name === 'default'" @click="remove">削除</button>
    </div>
</template>

<script>
export default {
    props: [ 'row' ],
    methods: {
        remove() {
            this.$root.$children[0].remove(this.row.id);
        },
    },
};
</script>

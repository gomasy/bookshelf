<template>
    <div class="modal-body" id="confirm-body">
        <p>本当に削除しますか？</p>
        <input type="checkbox" v-model="checked"> 本棚の中身も削除する
    </div>
</template>

<script>
export default {
    props: [ 'options' ],
    data: () => ({
        checked: null,
    }),
    watch: {
        checked() {
            this.$set(this.options, 'checked', this.checked);
        },
    },
    created() {
        this.checked = this.options.checked;
    },
};
</script>

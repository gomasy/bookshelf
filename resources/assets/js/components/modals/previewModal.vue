<template>
    <div class="modal fade" id="preview-modal">
        <div class="modal-dialog">
            <img :src="image" v-if="image !== null">
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        image: null,
    }),
    methods: {
        open(image) {
            this.image = image.large;
            $('#preview-modal').modal('show');
        },
    },
};
</script>

<template>
    <div class="modal fade" id="preview-modal">
        <div class="modal-dialog">
            <img :class="animation" :src="image" v-if="image !== null">
        </div>
    </div>
</template>

<script>
export default {
    props: [ 'settings' ],
    data: () => ({
        image: null,
        animation: '',
    }),
    methods: {
        open(image) {
            if (parseInt(this.settings.animation)) {
                this.animation = 'spin';
            }

            this.image = image.large;
            $('#preview-modal').modal('show');
        },
    },
};
</script>

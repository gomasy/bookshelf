<template>
    <div class="modal fade" id="preview-modal">
        <img :class="animation" :src="image" v-if="image !== null">
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data: () => ({
        image: null,
        animation: '',
    }),
    computed: {
        ...mapState({
            'settings': 'settings',
        }),
    },
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

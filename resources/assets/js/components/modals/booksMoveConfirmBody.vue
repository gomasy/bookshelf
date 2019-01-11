<template>
    <div class="modal-body" id="confirm-body">
        <div class="form-group">
            <p>{{ items.length }} 件移動します。移動先の本棚を指定してください。</p>
            <select class="form-control" v-model="selected">
                <option v-for="shelf in availShelves" :key="shelf.id" :value="shelf.id">{{ shelf.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    props: [ 'items', 'options' ],
    data: () => ({
        selected: null,
    }),
    computed: {
        ...mapState({
            shelves: 'shelves',
        }),
        availShelves() {
            const shelves = [];
            this.shelves.map(e => {
                if (this.options.current !== e.id) {
                    shelves.push(e);
                }
            });

            return shelves;
        },
    },
    watch: {
        selected() {
            this.$parent.options.next = this.selected;
        },
    },
};
</script>

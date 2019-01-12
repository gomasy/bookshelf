<template>
    <div class="modal-body" id="confirm-body">
        <div class="form-group">
            <p>{{ items.length }} 件移動します。移動先の本棚を指定してください。</p>
            <select class="form-control" v-model="selection">
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
        selection: null,
    }),
    computed: {
        ...mapState({
            shelves: 'shelves',
        }),
        availShelves() {
            return this.shelves.filter(e => e.id !== this.options.current);
        },
    },
    methods: {
        handleSelectionChange() {
            this.$set(this.options, 'next', this.selection);
        },
    },
    created() {
        this.selection = this.availShelves[0].id;
        this.handleSelectionChange();
    },
    watch: {
        selection() {
            this.handleSelectionChange();
        },
    },
};
</script>

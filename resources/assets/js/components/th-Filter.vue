<template>
    <div class="btn-group">
        {{ title }}
        <a href="#" data-toggle="dropdown">
            <i class="fas fa-filter" :class="{ 'text-muted': !keyword }"></i>
        </a>
        <ul class="dropdown-menu" style="padding: 3px;">
            <div class="input-group input-group-sm">
                <input class="form-control" type="search" ref="input" v-model="keyword" @keydown.enter="search" :placeholder="`${title}を検索`">
                <span class="input-group-btn">
                    <button class="btn btn-default fas fa-search" @click="search"></button>
                </span>
            </div>
        </ul>
    </div>
</template>

<script>
export default {
    props: [ 'field', 'title', 'query' ],
    data: () => ({
        keyword: '',
    }),
    mounted() {
        $(this.$el).on('shown.bs.dropdown', () => this.$refs.input.focus());
    },
    watch: {
        keyword(kw) {
            if (kw === '') this.search();
        },
    },
    methods: {
        search() {
            const { query } = this;

            this.$set(query, this.field, this.keyword);
            query.offset = 0;
        },
    },
};
</script>

<template>
    <div class="navbar-buttons">
        <form class="form-inline" id="register" @submit.prevent="create">
            <select class="form-control" name="type" v-model="type">
                    <option value="code">番号</option>
                    <option value="title">タイトル</option>
                </select>
            <input class="form-control" type="text" :id="type" :placeholder="placeholder" v-model="p" required>
            <button class="btn btn-info" type="submit">{{ btnText }}</button>
        </form>
        <button class="btn btn-warning" @click="reader">読み取る</button>
    </div>
</template>

<script>
export default {
    props: [ 'table' ],
    data: () => ({
        p: '',
        type: 'code',
    }),
    methods: {
        create() {
            this.table.beforeCreate(result => {
                this.table.create(result);
                this.p = '';
            }, this.type, this.p);
        },
        reader() {
            this.table.reader();
        },
    },
    computed: {
        btnText() {
            return this.type !== 'code' ? '検索する' : '登録する';
        },
        placeholder() {
            return this.type !== 'code' ? '検索したい本の名前' : 'ISBN or JP番号';
        },
    },
    watch: {
        type() {
            this.p = '';
        },
    }
};
</script>

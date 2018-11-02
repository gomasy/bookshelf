<template>
    <div class="navbar-buttons">
        <form class="form-inline" id="register" @submit.prevent="create">
            <input class="form-control" id="code" type="text" placeholder="ISBN or JP番号" v-model="code" required>
            <button class="btn btn-info" type="submit">登録する</button>
        </form>
        <button data-toggle="modal" data-target="#camera-modal" class="btn btn-warning" @click="openReader">読み取る</button>
    </div>
</template>

<script>
export default {
    props: [ 'table', 'options' ],
    data: () => ({
        code: '',
    }),
    methods: {
        create() {
            fetch('/create', {
                method: 'post',
                headers: this.options.ajax,
                body: JSON.stringify({ code: this.code }),
            }).then(response => {
                if (!response.ok) {
                    throw response;
                }

                return response.json();
            }).then(json => {
                this.table.create(json);
                this.code = '';
            });
        },
        openReader() {
            this.table.readerProxy();
        },
    },
};
</script>

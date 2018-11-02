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
                this.notify(response);

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
        notify(response) {
            switch (response.status) {
            case 200:
                this.$notify({
                    type: 'success',
                    title: '完了',
                    text: '該当する書籍の登録に成功しました',
                });
                break;
            case 404:
                this.$notify({
                    type: 'warn',
                    title: '見つかりません',
                    text: '該当する書籍が見つかりませんでした',
                });
                break;
            case 409:
                this.$notify({
                    type: 'error',
                    title: '登録済み',
                    text: 'その書籍は既に登録されています',
                });
                break;
            }
        },
    },
};
</script>

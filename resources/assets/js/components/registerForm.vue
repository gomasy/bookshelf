// vim:ft=javascript

<template>
    <form class="form-inline" id="register" @submit.prevent="create">
        <input class="form-control" type="text" placeholder="ISBN or JP番号" v-model="code" required>
        <button class="btn btn-info" type="submit">登録</button>
        <button class="btn btn-success" type="button">読み取る</button>
    </form>
</template>

<script>
export default {
    props: [
        'table',
    ],
    data: () => ({
        code: '',
    }),
    methods: {
        create() {
            const xhr = new XMLHttpRequest();

            xhr.open('POST', '/create');
            xhr.responseType = 'json';
            xhr.setRequestHeader('Content-Type', 'application/json;charset=utf-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content);
            xhr.addEventListener('load', event => {
                if (event.target.status == 200) {
                    this.table.create(event.target.response);
                    this.code = '';
                }
            });
            xhr.send(JSON.stringify({ code: this.code }));
        },
    },
}
</script>

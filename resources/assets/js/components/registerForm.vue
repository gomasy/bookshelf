<template>
    <form class="form-inline" id="register" @submit.prevent="create">
        <input class="form-control" id="code" type="text" placeholder="ISBN or JP番号" v-model="code" required>
        <label class="control-label" id="btn-barcode">
            <input type="file" accept="image/*" capture="environment" tabindex="-1" v-on:change="reader">
        </label>
        <button class="btn btn-info" type="submit">登録する</button>
    </form>
</template>

<script>
import Quagga from 'quagga';

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
                if (event.target.status === 200) {
                    this.table.create(event.target.response);
                    this.code = '';
                }
            });
            xhr.send(JSON.stringify({ code: this.code }));
        },
        reader(event) {
            const reader = new FileReader();

            reader.onload = event => {
                Quagga.decodeSingle({
                    src: event.target.result,
                    readers: {
                        format: 'ean_reader',
                        config: {
                            supplements: [
                                'ean_5_reader',
                                'ean_2_reader',
                            ],
                        },
                    },
                }, result => {
                    if (result.codeResult) {
                        alert('success');
                    } else {
                        alert('fail');
                    }
                });
            };
            reader.readAsDataURL(event.target.files[0]);
        },
    },
};
</script>

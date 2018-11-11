<template>
    <div class="modal-body" id="confirm-body">
        <p>一件見つかりました。本当に登録しますか？</p>
        タイトル: {{ this.book.title }}
    </div>
</template>

<script>
export default {
    props: [ 'book' ],
};
</script>

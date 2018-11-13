<template>
    <div class="modal-body" id="confirm-body">
        <p>一件見つかりました。本当に登録しますか？</p>
        <div class="add-inner">
            <img width="112px" height="160px" :src="this.book.images.medium">
            <div class="add-text">
                <span><code>タイトル:</code> {{ this.book.title }}</span>
                <span v-if="this.book.volume !== ''"><code>巻号:</code> {{ this.book.volume }}</span>
                <span><code>著者等:</code> {{ this.book.authors }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [ 'book' ],
};
</script>

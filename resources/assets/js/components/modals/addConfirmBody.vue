<template>
    <div class="modal-body" id="confirm-body">
        <p>一件見つかりました。本当に登録しますか？</p>
        <div class="add-inner">
            <img width="112px" height="160px" :src="this.items[0].images.medium">
            <div class="add-text">
                <span><code>タイトル:</code> {{ this.items[0].title }}</span>
                <span v-if="this.items[0].volume !== ''"><code>巻号:</code> {{ this.items[0].volume }}</span>
                <span><code>著者等:</code> {{ this.items[0].authors }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [ 'items' ],
};
</script>

<template>
    <div class="modal-body" id="confirm-body">
        <p>{{ items.length }} 件削除します。本当によろしいですか？</p>
    </div>
</template>

<script>
export default {
    props: [ 'items' ],
};
</script>

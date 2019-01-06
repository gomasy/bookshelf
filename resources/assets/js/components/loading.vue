<template>
    <div class="loading" v-if="visible">
        <div class="loading-circle"></div>
        <span class="loading-text">検索中・・・</span>
    </div>
</template>

<script>
export default {
    data: () => ({
        visible: false,
    }),
};
</script>

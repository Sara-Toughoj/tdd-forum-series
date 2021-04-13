<template>

</template>

<script>
    import Replies from "../components/Replies";
    import SubscribeButton from "../components/SubscribeButton";

    export default {
        props: ['InitialRepliesCount'],

        components: {
            Replies, SubscribeButton
        },
        data() {
            return {
                repliesCount: this.InitialRepliesCount
            }
        }
    }
</script>

<style scoped>

</style>

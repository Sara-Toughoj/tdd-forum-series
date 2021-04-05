<template>

</template>

<script>
    import Replies from "../components/Replies";

    export default {
        props: ['InitialRepliesCount'],

        components: {Replies},
        data() {
            return {
                repliesCount: this.InitialRepliesCount
            }
        }
    }
</script>

<style scoped>

</style>

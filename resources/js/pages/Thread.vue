<template>

</template>

<script>
    import Replies from "../components/Replies";
    import SubscribeButton from "../components/SubscribeButton";

    export default {
        props: [
            'thread'
        ],

        components: {
            Replies, SubscribeButton
        },
        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                slug: this.thread.slug,
                editing: false,
            }
        },

        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post'](`/locked-threads/${this.slug}`).then(() => {
                    this.locked = !this.locked;
                    flash('Lock Toggled successfully');
                });
            },

            update(){

            }
        }
    }
</script>

<style scoped>

</style>

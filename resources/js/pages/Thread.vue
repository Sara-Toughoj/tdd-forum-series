<template>

</template>

<script>
    import Replies from "../components/Replies";
    import SubscribeButton from "../components/SubscribeButton";

    export default {
        props: [
            'dataThread', 'url'
        ],

        components: {
            Replies, SubscribeButton
        },
        data() {
            return {
                thread: this.dataThread,
                repliesCount: this.dataThread.replies_count,
                locked: this.dataThread.locked,
                slug: this.dataThread.slug,
                editing: false,
                form: {}
            }
        },

        created() {
            this.resetForm();
        },

        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post'](`/locked-threads/${this.slug}`).then(() => {
                    this.locked = !this.locked;
                    flash('Lock Toggled successfully');
                });
            },

            update() {
                axios.patch(this.url, this.form).then((data) => {
                    this.editing = false;
                    this.thread = data.data;
                    flash('Updated successfully');
                });
            },

            resetForm() {
                this.form = {
                    body: this.dataThread.body,
                    title: this.dataThread.title,
                }
                this.editing = false;

            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <div>
        <div v-for="(reply,index) in items">
            <reply :data="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>
        <new-reply @created="add" :endpoint="endpoint"></new-reply>
    </div>

</template>

<script>
    import Reply from "./Reply";
    import NewReply from "./NewReply";

    export default {
        components: {
            Reply,
            NewReply
        },
        props: ['data',],

        data() {
            return {
                items: this.data,
                endpoint: location.pathname + '/replies'
            }
        },
        methods: {
            remove(index) {
                this.items.splice(index, 1);
                flash('Deleted');

                this.$emit('removed')
            },
            add(reply) {
                this.items.push(reply)
                this.$emit('added')
            }
        }

    }
</script>

<style scoped>

</style>

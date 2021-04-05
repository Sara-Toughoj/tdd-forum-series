<template>
    <div>
        <div v-for="(reply,index) in items">
            <reply :data="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>
    </div>

</template>

<script>
    import Reply from "./Reply";

    export default {
        components: {Reply},
        props: ['data'],

        data() {
            return {
                items: this.data
            }
        },
        methods: {
            remove(index) {
                this.items.splice(index, 1);
                flash('Deleted');

                this.$emit('removed')
            }
        }

    }
</script>

<style scoped>

</style>

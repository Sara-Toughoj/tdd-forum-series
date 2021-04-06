<template>
    <div>
        <div v-for="(reply,index) in items">
            <reply :data="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>

        <paginator></paginator>

        <new-reply @created="add" :endpoint="endpoint"></new-reply>
    </div>

</template>

<script>
    import Reply from "./Reply";
    import NewReply from "./NewReply";
    import collection from "../mixins/collection";

    export default {
        components: {
            Reply,
            NewReply
        },

        mixins: [
            collection
        ],

        data() {
            return {
                dataSet: false,
                endpoint: location.pathname + '/replies'
            }
        },
        created() {
            this.fetch();
        },

        methods: {
            fetch() {
                axios.get(this.url())
                    .then(this.refresh)
            },

            url() {
                return `${location.pathname}/replies`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            }
        }

    }
</script>

<style scoped>

</style>

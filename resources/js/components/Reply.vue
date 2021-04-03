<template>

</template>

<script>
    import Favorite from "./Favorite";

    export default {
        components: {
            Favorite
        },

        data() {
            return {
                editing: false,
                body: ''
            }
        },

        props: [
            "attributes"
        ],

        created() {
            this.body = this.attributes.body
        },

        methods: {
            update() {
                axios.patch("/replies/" + this.attributes.id, {
                    body: this.body
                }).then(() => {
                    this.editing = false
                    flash('Updated')
                });
            },

            destroy() {
                axios.delete("/replies/" + this.attributes.id)
                    .then(() => {
                        $(this.$el).fadeOut(300, () => {
                            flash('Deleted');
                        });
                    });
            }
        }

    }
</script>

<style scoped>

</style>
s

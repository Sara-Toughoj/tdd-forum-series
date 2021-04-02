<template>

</template>

<script>
    export default {
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
            }
        }

    }
</script>

<style scoped>

</style>
s

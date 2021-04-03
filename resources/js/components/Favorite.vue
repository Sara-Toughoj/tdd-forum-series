<template>
    <button :class="classes" @click="toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
        </svg>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        data() {
            return {
                count: 10,
                active: false
            }
        },
        props: [
            "reply"
        ],
        computed: {
            classes() {
                return [
                    'btn',
                    this.active ? 'btn-primary' : 'btn-secondary'
                ]
            },

            endpoint() {
                return `/replies/${this.reply.id}/favorites`;
            }
        },
        created() {
            this.count = this.reply.favoritesCount
            this.active = this.reply.isFavorited
        },

        methods: {
            toggle() {
                this.active ? this.create() : this.destroy();
            },

            create() {
                axios.delete(this.endpoint)
                    .then(() => {
                        --this.count;
                        this.active = false;
                    })
            },

            destroy() {
                axios.post(this.endpoint)
                    .then(() => {
                        ++this.count;
                        this.active = true
                    });
            }

        }
    }
</script>

<style scoped>
</style>

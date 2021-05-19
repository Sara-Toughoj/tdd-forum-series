<template>
    <button class="btn  btn-light btn-outline-primary" @click="subscribe"> {{label}}</button>
</template>

<script>
    export default {
        props: {
            'isSubscribed': {
                default: false
            }
        },
        data() {
            return {
                active: this.isSubscribed
            }
        },
        computed: {
            label() {
                return this.active ? 'Unsubscribe' : 'Subscribe';
            }
        },

        methods: {
            subscribe() {
                let method = this.active ? 'delete' : 'post';

                axios({
                    method: method,
                    url: `${location.pathname}/subscriptions`
                }).then(() => {
                    flash(this.label)
                    this.toggleSubscription()
                });
            },
            toggleSubscription() {
                this.active = !this.active;
            }
        }
    }
</script>

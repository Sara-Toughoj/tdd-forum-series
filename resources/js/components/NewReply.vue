<template>
    <div>

        <div v-if="signedIn">
            <div class="form-group mt-3">
            <textarea v-model="body"
                      required
                      name="body"
                      id="body"
                      class="form-control"
                      placeholder="Have something to say ?"
                      rows="5">
            </textarea>
            </div>
            <button type="submit"
                    class="btn btn-primary"
                    @click="addReply"> Post
            </button>
        </div>
        <p v-else>
            Please
            <a href="/login">
                sign in
            </a>
            to participate
        </p>
    </div>

</template>

<script>
    export default {
        props: [
            'endpoint',
        ],

        data() {
            return {
                body: '',
            }
        },

        computed: {

            signedIn() {
                return window.App.signedIn;
            }

        },

        methods: {
            addReply() {
                axios.post(this.endpoint, {
                    body: this.body
                }).then(data => {
                    this.body = '';
                    flash('Your reply has been posted.');
                    this.$emit('created', data.data);
                });
            }
        }
    }
</script>

<style scoped>

</style>

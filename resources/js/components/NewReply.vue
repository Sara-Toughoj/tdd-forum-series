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
    import Tribute from "tributejs";

    export default {
        data() {
            return {
                body: '',
            }
        },

        mounted() {
            let tribute = new Tribute({
                // column to search against in the object (accepts function or string)
                lookup: 'value',
                // column that contains the content to insert by default
                fillAttr: 'value',
                values: function (query, cb) {
                    axios.get('/api/users', {params: {name: query}})
                        .then(function (response) {
                            console.log(response);
                            cb(response.data);
                        });
                },
            });
            tribute.attach(document.querySelectorAll("#body"));
        },
        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {
                    body: this.body
                }).then(data => {
                    this.body = '';
                    flash('Your reply has been posted.');
                    this.$emit('created', data.data);
                }).catch((error) => {
                    if (typeof error.response.data.body == 'undefined') {
                        flash(error.response.data, 'danger');
                    } else {
                        flash(error.response.data.body[0], 'danger');
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>

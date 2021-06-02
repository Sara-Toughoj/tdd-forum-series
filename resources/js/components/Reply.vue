<template>
    <div class="card mt-3" :id="'reply-'+ id">
        <div class="card-header" :class="{'bg-success':isBest}">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name"
                       v-text="reply.owner.name">
                    </a>
                    said <span v-text="ago"> </span>
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <fancy-editor class="mb-2" v-model="body" :value="body" required></fancy-editor>
                        <button class="btn btn-primary"> Update</button>
                        <button class="btn btn-link" @click="editing=false" type="button"> Cancel
                        </button>
                    </div>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level" v-if="authorize('owns' , reply.thread) || authorize('owns' , reply)">
            <div v-if="authorize('owns' , reply)">
                <button class="btn btn-primary mr-3" @click="editing=true"> Edit</button>
                <button class="btn btn-danger " @click="destroy"> Delete</button>
            </div>
            <button v-if="authorize('owns' , reply.thread)" v-show="!isBest" class="btn btn-secondary ml-auto" @click="markBestReply">
                Best Reply?
            </button>
        </div>
    </div>
</template>

<script>
    import Favorite from "./Favorite";
    import moment from 'moment';

    export default {
        components: {
            Favorite
        },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: '',
                isBest: this.reply.isBest,
            }
        },

        props: [
            "reply"
        ],

        created() {
            this.body = this.reply.body

            window.events.$on('best-reply-selected', id => {
                this.isBest = (this.id == id);
            });
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            }
        },

        methods: {
            update() {
                axios.patch("/replies/" + this.id, {
                    body: this.body
                }).then(() => {
                    this.editing = false
                    flash('Updated')
                }).catch(error => {
                    if (typeof error.response.data.body == 'undefined') {
                        flash(error.response.data, 'danger');
                    } else {
                        flash(error.response.data.body[0], 'danger');
                    }
                });
            },

            destroy() {
                axios.delete("/replies/" + this.id)
                    .then(() => {
                        this.$emit('deleted', this.id);
                    });
            },

            markBestReply() {
                axios.post(`/replies/${this.id}/best`).then(() => {
                    window.events.$emit('best-reply-selected', this.id)
                    flash('Marked as best');
                })
            }
        }

    }
</script>

<style scoped>

</style>
s

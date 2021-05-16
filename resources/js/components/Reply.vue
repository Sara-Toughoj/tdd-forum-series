<template>
    <div class="card mt-3" :id="'reply-'+ id">
        <div class="card-header" :class="{'bg-success':isBest}">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.name"
                       v-text="data.owner.name">
                    </a>
                    said <span v-text="ago"> </span>
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control mb-2" v-model="body" required></textarea>
                        <button class="btn btn-primary"> Update</button>
                        <button class="btn btn-link" @click="editing=false" type="button"> Cancel
                        </button>
                    </div>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level">
            <div v-if="authorize('UpdateReply' , reply)">
                <button class="btn btn-primary mr-3" @click="editing=true"> Edit</button>
                <button class="btn btn-danger " @click="destroy"> Delete</button>
            </div>
            <button v-show="!isBest" class="btn btn-secondary ml-auto" @click="markBestReply"> Best Reply?</button>
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
                id: null,
                body: '',
                isBest: false,
                reply: this.data,
            }
        },

        props: [
            "data"
        ],

        created() {
            this.body = this.data.body
            this.id = this.data.id
        },

        computed: {
            ago() {
                return moment(this.data.created_at).fromNow() + '...';
            }
        },

        methods: {
            update() {
                axios.patch("/replies/" + this.data.id, {
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
                axios.delete("/replies/" + this.data.id)
                    .then(() => {
                        this.$emit('deleted', this.data.id);
                    });
            },

            markBestReply() {
                axios.post(`/replies/${this.data.id}/best`).then(() => {
                    this.isBest = true;
                    flash('Marked as best');
                })
            }
        }

    }
</script>

<style scoped>

</style>
s

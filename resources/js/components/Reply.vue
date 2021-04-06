<template>
    <div class="card mt-3" :id="'reply-'+ id">
        <div class="card-header">
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
                <div class="form-group">
                    <textarea class="form-control mb-2" v-model="body"></textarea>
                    <button class="btn btn-primary" @click="update"> Update</button>
                    <button class="btn btn-link" @click="editing=false"> Cancel</button>
                </div>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-primary mr-3" @click="editing=true"> Edit</button>
            <button class="btn btn-danger " @click="destroy"> Delete</button>
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
                body: ''
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
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            },

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
                });
            },

            destroy() {
                axios.delete("/replies/" + this.data.id)
                    .then(() => {
                        this.$emit('deleted', this.data.id);
                    });
            }
        }

    }
</script>

<style scoped>

</style>
s

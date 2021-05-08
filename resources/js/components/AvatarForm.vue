<template>
    <div>
        <div class="level">
            <img :src="avatar" widt h="50" height="50" class="mr-3 mb-3">
            <h1 v-text="user.name"></h1>
        </div>
        <image-upload name="avatar" @loaded="onLoad"></image-upload>
    </div>
</template>

<script>
    import ImageUpload from "./ImageUpload";

    export default {
        props: ['user'],
        components: {ImageUpload},
        data() {
            return {
                url: `/api/users/${this.user.id}/avatar`,
                avatar: this.user.avatar,
            }
        },
        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            },
        },
        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },
            persist(avatar) {
                let data = new FormData();
                data.append('avatar', avatar);

                axios.post(this.url, data).then(() => {
                    flash('Avatar uploaded');
                });
            },
        }


    }
</script>

<style scoped>

</style>

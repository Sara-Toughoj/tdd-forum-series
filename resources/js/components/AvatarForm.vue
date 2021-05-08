<template>
    <div>
        <h1 v-text="user.name"></h1>
        <!--        accept="image/*" id="file" ref="myFiles" multiple-->
        <input type="file" @change="onChange">
        <button type="submit" class="btn btn-primary"> Add Avatar</button>
        <img :src="avatar" width="50" height="50">
    </div>
</template>

<script>
    export default {
        props: ['user'],
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
            onChange(e) {
                if (!e.target.files[0]) return;


                let avatar = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(avatar);

                reader.onload = e => {
                    console.log(e);
                    this.avatar = e.target.result;
                }

                this.persist(avatar);

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

<template>
    <input type="file" @change="onChange" accept="image/*">
</template>

<script>
    export default {
        methods: {
            onChange(e) {
                if (!e.target.files[0]) return;


                let file = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;
                    this.$emit('loaded', {src, file});
                }

            },
            persist(file) {
                let data = new FormData();
                data.append('file', file);
                axios.post(this.url, data).then(() => {
                    flash('Avatar uploaded');
                });
            },
        }
    }
</script>

<style scoped>

</style>

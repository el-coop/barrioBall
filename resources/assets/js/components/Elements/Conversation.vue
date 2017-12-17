<template>
    <div>
    </div>
</template>
<script>
    export default {
        props: {
            conversation: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                messages: {}
            }
        },

        watch: {
            conversation(){
                axios.get('/user/conversations/' + this.conversation).then((response) => {
                    console.log(response);
                });
            }
        }
    }
</script>
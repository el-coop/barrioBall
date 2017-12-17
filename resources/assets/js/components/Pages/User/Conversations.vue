<script>
    export default {
        props: {
            conversations: {
                type: Array
            },
            currentUser: {
                type: Number,
                required: true
            }
        },

        data() {
            return {
                currentConversation: 1
            }
        },

        methods: {
            changeConversation(id) {
                console.log(id)
            }
        }
    }
</script>
<style scoped>
    .list-group {
        overflow-y: auto;
    }
    .btn-col {
        background-color: white;
    }
</style>

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
                currentConversation: 0,
                sender: ''
            }
        },



        methods: {
            changeConversation(id) {
                this.currentConversation = id;
                let conversation = this.conversations.filter((conversation) => {
                    return conversation.id == this.currentConversation
                })[0];
                this.sender = conversation.users.filter((user) => {
                    return user.id != this.currentUser;
                })[0].username;
                this.$refs.mobileView.toggle(1);
            }
        },


        mounted(){
            this.currentConversation = this.conversations[0].id;
            this.sender =  this.conversations[0].users.filter((user) => {
                return user.id != this.currentUser;
            })[0].username;

        },
    }
</script>
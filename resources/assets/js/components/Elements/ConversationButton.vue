<template>
        <button @click="select" class="list-group-item list-group-item-action" type="button" :class="{unread: !isRead, active: isActive}">{{user}}</button>
</template>
<style scoped>
    .unread {
        background-color: #e6ffff;
    }
</style>
<script>
    export default {
        props: {
            conversation: {
                type: Object,
                required: true
            },
            currentUser: {
                type: Number,
                required: true
            }
        },

        computed: {
            user() {
                return this.conversation.users.filter((user) => {
                    return user.id != this.currentUser;
                })[0].username;
            },
            isRead() {
                return this.conversation.pivot.read;
            },

            isActive() {
                return true;
            }
        },

        methods: {
            select() {
                this.$emit('conversationChange', this.conversation.id)
            }
        },

    }
</script>
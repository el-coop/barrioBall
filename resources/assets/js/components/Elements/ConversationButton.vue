<template>
        <button @click="select" class="list-group-item list-group-item-action" type="button" :class="{'btn-warning': !read, active: isActive}">{{user}}</button>
</template>
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
            },
            current: {
                type: Number,
                required: true
            }
        },

        data(){
          return {
              read: this.conversation.pivot.read
          }
        },

        computed: {
            user() {
                return this.conversation.users.filter((user) => {
                    return user.id != this.currentUser;
                })[0].username;
            },
            isActive() {
                return this.conversation.id == this.current;
            },
        },

        methods: {
            select() {
                this.read = true;
                this.$emit('conversation-change', this.conversation.id);
            }
        }

    }
</script>
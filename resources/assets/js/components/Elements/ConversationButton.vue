<template>
    <a @click="select" href="#" class="list-group-item list-group-item-action">
        {{user}}
        <span class="float-right">
            <span class="badge badge-warning" v-if="!read">New</span>
        </span>
    </a>
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
		},

		data() {
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
		},

		methods: {
			select() {
				if (!this.read) {
					this.$bus.$emit('decrement-notifications');
					this.read = true;
				}
				this.$emit('conversation-change', this.conversation);
			}
		}

	}
</script>
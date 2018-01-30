<template>

</template>

<script>
	export default {
		props: {
			user: {
				required: true,
				type: Number
			}
		},

		created() {
			this.$echo.private('App.Models.User.' + this.user)
				.notification((notification) => {
					if (notification.message.user_id != this.user) {
						this.$bus.$emit('increment-notification');
					}
					this.$bus.$emit('new-notification', notification);
				});
		}

	}
</script>

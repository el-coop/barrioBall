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
					this.$bus.$emit('new-notification',notification);
				});
		}

	}
</script>

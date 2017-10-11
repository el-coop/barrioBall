<template>
    <button @click="confirm" type="button">
        <slot></slot>
    </button>
</template>

<script>
    export default{
    	props: {
			title: {
				required: true,
				type: String
			},
			type: {
				type: String,
                default: 'warning'
			},
			text: {
				type: String,
				default: ''
			},
			confirmText: {
				type: String,
				required: true
			},
			cancelText: {
				type: String,
				required: true
			},
        },

        methods: {
        	confirm(){
				this.$swal({
					title: this.title,
					text: this.text,
					type: this.type,
					showCancelButton: true,
					confirmButtonColor: '#fff',
					cancelButtonColor: '#fff',
					confirmButtonText: this.confirmText,
					cancelButtonText: this.cancelText,
					confirmButtonClass: 'btn btn-success mr-2',
					cancelButtonClass: 'btn btn-danger ml-2',
					buttonsStyling: false
				}).then(() =>{
						$(this.$el).closest('form').submit();
				},() => {

                });
            }
        }
    }
</script>

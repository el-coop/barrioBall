<template>
    <form @submit.prevent="submitClicked">
        <form-errors :errors="errors"></form-errors>
        <slot></slot>
        <div  class="form-group">
            <button :class="btnClass" ref="submit">
                <slot name="submit"></slot>
            </button>
        </div>
    </form>
</template>

<script>
    export default{
		props: {
			method: {
				type: String,
				default: 'post'
			},
			action: {
				type: String,
				default: window.document.URL
			},
            event: {
				type: String,
                default: 'completed'
            },
            btnClass: {
				type: String,
                default: 'btn btn-info'
            },
            autoSubmit: {
				type: Boolean,
                default: true
            }
		},

		data() {
			return {
				loading: false,
				errors: null,
				extraData: {},
				btnText: ''
			}
		},

		methods: {
			submitClicked(){
			    if(this.autoSubmit){
			    	this.submit();
                } else{
					this.$emit('submit-clicked');
                }
            },

			submit(){
				this.showLoadingButton();
				this.errors = null
				let data = this.makeData();
				axios[this.method](this.action, data).then(response => {
					this.showNormalButton();
					this.$emit(this.event, response.data, data);
				}, error => {
					this.showNormalButton();
					this.errors = error.response.data;
				});
			},

			makeData(){
				let data = new FormData(this.$el);
				$.each(this.extraData, (key, value) => {
					data.append(key, value);
				});
				return data;
			},

			addData(key, value){
				this.extraData[key] = value;
			},

			showLoadingButton(){
				let button = $(this.$refs.submit);
				this.btnText = button.html();
				button.html('<i class="fa fa-cog fa-spin"></i>');
			},

			showNormalButton(){
				let button = $(this.$refs.submit);
				button.html(this.btnText);
			}

		}
    }
</script>

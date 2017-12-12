<template>
    <form @submit.prevent="submitClicked">
        <form-errors :errors="errors" v-if="errorsBox"></form-errors>
        <slot></slot>
        <div class="form-group" :class="btnWrapperClass">
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
                default: 'search-completed'
            },
			btnClass: {
				type: String,
				default: 'btn btn-info sm-btn-block'
			},
			btnWrapperClass: {
				type: String,
				default: ''
			},
            autoSubmit: {
				type: Boolean,
                default: true
            },
            errorsBox: {
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
					this.$emit('submit-clicked');
				} else{
					this.$emit('submit-clicked');
                }
            },

            clearErrors(){
				$(this.$el).find('.form-group').removeClass('has-error');
				this.errors = null
            },

			submit(){
            	this.clearErrors();
				this.showLoadingButton();
				let data = this.makeData();
				axios[this.method](this.action, data).then(response => {
					this.showNormalButton();
					this.$emit(this.event, response.data, data);
				}, error => {
					this.showNormalButton();
					this.errors = error.response.data.errors;
					this.$emit('error', error.response.data.errors);
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

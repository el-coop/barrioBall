<template>
    <div>
        <button class="btn" :class="btnClass" @click="toggleModal">
            <slot name="button"></slot>
        </button>
        <div class="modal fade" ref="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" v-if="!! this.$slots['header']">
                        <slot name="header"></slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div class="modal-footer" v-if="!! this.$slots['footer']">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	export default{

		props: {
		    btnClass: {
		    	type: String,
                default: 'btn-info'
            }
        },

		methods: {
			toggleModal(){
				console.log('here');
				$(this.$refs.modal).modal('show');
            }
        }
	}
</script>

<template>
    <transition name="switch-fade">
        <div v-show="isActive">
            <slot></slot>
        </div>
    </transition>
</template>
<script>
	export default {
		props: {
			icon: {
				type: String,
				required: true
			},
		},

		data() {
			return {
				active: false
			}
		},

        computed: {
		    isActive(){
		    	return this.active || this.$mq == 'lg' || this.$mq == 'xl';
            }
        },

		methods: {
			toggle(active) {
				if (active == this._uid) {
					this.active = true;
				} else {
					this.active = false;
				}
			}
		}
	}
</script>


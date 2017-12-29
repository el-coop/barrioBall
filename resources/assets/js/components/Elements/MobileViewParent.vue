<template>
    <div class="mobile-view">
        <div class="bubble-wrapper d-lg-none">
            <div v-for="child in children" key="child._uid" v-show="child._uid != active"
                 class="btn bubble" @click="activate(child)" :class="child.btn">
                <i class="fa m-auto fa-2x" :class="child.icon"></i>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script>
	export default {
		data() {
			return {
				children: [],
				active: null
			}
		},

		methods: {
			activate(child) {
				this.children.forEach((element) => {
					element.toggle(child._uid);
				});
				this.active = child._uid;
			},

            toggle(index){
				this.activate(this.children[index]);
            }
		},

		mounted() {
			this.children = this.$children;
			this.activate(this.children[0]);
		}
	}
</script>




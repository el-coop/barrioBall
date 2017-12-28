<template>
    <div class="mobile-view">
        <div class="bubble-wrapper d-lg-none">
            <div v-for="child in children" key="child._uid" v-show="child._uid != active"
                 class="btn btn-primary bubble" @click="activate(child)">
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
			}
		},

		mounted() {
			this.children = this.$children;
			this.activate(this.children[0]);
		}
	}
</script>




<template>
    <div class="row">
        <div class="col-12 col-md-6">
            <label>User Agent: </label>
            <p>{{ rowData.user_agent }}</p>
            <label>Exception: </label>
            <tree-view :data="exception" :options="{maxDepth: 0}"></tree-view>
        </div>
        <div class="col-12 col-md-6">
            <label>VM: </label>
            <tree-view :data="vm" :options="{maxDepth: 0}"></tree-view>
        </div>
    </div>
</template>

<script>
	export default{
		props: {
			rowData: {
				type: Object,
				required: true
			}
		},

		data(){
			return {
				exception: this.rowData.exception,
				vm: JSON.parse(this.rowData.vm)
			}
		},
	}
</script>

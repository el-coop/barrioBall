<template>
    <div class="row">
        <div class="col-12 col-md-6">
            <label>Request: </label>
            <tree-view :data="request" :options="{maxDepth: 0}"></tree-view>
        </div>
        <div class="col-12 col-md-6">
            <label>Exception: </label>
            <tree-view :data="exception" :options="{maxDepth: 0}"></tree-view>
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
				request: {},
				exception: {}
			}
		},

		mounted(){
			this.request = JSON.parse(this.rowData.request);
			this.exception = JSON.parse(this.rowData.exception);
		}
	}
</script>

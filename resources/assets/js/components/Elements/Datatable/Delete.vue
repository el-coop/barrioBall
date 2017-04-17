<template>
    <span class="delete-action">
        <button class="btn btn-danger" @click="itemAction('delete', rowData, rowIndex)"><i class="fa fa-trash-o"></i></button>
    </span>
</template>
<script>
    export default{
		props: {
			rowData: {
				type: Object,
				required: true
			},
			rowIndex: {
				type: Number
			}
		},
		methods: {
			itemAction (action, data, index) {
				console.log('custom-actions: ' + action, data.name, index);
				this.$emit('action',{
					action,
                    data,
                    index
                })
			}
		}
	}
</script>

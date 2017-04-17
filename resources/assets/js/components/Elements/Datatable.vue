<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-inline form-group">
                    <label>Search:</label>
                    <input type="text" v-model="filter" @keyup.enter="updateParams" class="form-control">
                    <button class="btn btn-primary" @click="updateParams"><i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-inline form-group pull-right">
                    <label>Per Page:</label>
                    <select v-model="perPage" class="form-control" @change="updateParams">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <vuetable ref="table"
                              :api-url="url"
                              pagination-path=""
                              :detail-row-component="detailRow"
                              @vuetable:cell-clicked="cellClicked"
                              :fields="fields"
                              :css="css"
                              :per-page="perPage"
                              :append-params="params"
                              @vuetable:pagination-data="paginationData"
                              @vuetable:loading='tableLoading'
                              @vuetable:loaded='tableLoaded'>
                        <template slot="actions" scope="props">
                            <div class="custom-actions">
                                <button class="btn" :class="deleteClass"
                                        @click="onAction('delete', props.rowData, props.rowIndex)">
                                    <i class="fa" :class="deleteIcon"></i>
                                </button>
                            </div>
                        </template>
                    </vuetable>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <vuetable-pagination-info ref="paginationInfo"
                                                  info-class="pull-left">
                        </vuetable-pagination-info>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <vuetable-pagination ref="pagination"
                                             :css="cssPagination"
                                             @vuetable-pagination:change-page="changePage">
                        </vuetable-pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
	export default{
		props: {
			url: {
				type: String,
				required: true
			},
			fields: {
				type: Array,
				required: true
			},
			detailRow: {
				type: String,
				default: false
			},
			deleteClass: {
				type: String,
				default: 'btn-danger'
			},
			deleteIcon: {
				type: String,
				default: 'fa-trash'
			}
		},

		data(){
			return {
				loading: false,
				css: {
					tableClass: 'table table-striped table-bordered',
					ascendingIcon: 'glyphicon glyphicon-chevron-up',
					descendingIcon: 'glyphicon glyphicon-chevron-down',
					sortHandleIcon: 'glyphicon glyphicon-menu-hamburger',
				},
				cssPagination: {
					wrapperClass: 'pull-right',
					activeClass: 'btn-primary',
					disabledClass: 'disabled',
					pageClass: 'btn btn-border',
					linkClass: 'btn btn-border',
				},
				params: {},
				filter: null,
				perPage: 10
			}
		},

		methods: {
			updateParams(){
				this.params = {
					filter: this.filter
				};
				Vue.nextTick(() => {
					this.$refs.table.refresh()
				})
			},
			paginationData(paginationData){
				this.$refs.pagination.setPaginationData(paginationData);
				this.$refs.paginationInfo.setPaginationData(paginationData);
			},
			changePage(page){
				this.$refs.table.changePage(page);
			},
			cellClicked (data, field, event) {
				if (!this.detailRow) {
					return;
				}
				this.$refs.table.toggleDetailRow(data.id)
			},
			tableLoading(){
				this.css.tableClass = 'table table-striped table-bordered loading';
			},
			tableLoaded(){
				this.css.tableClass = 'table table-striped table-bordered';
			},
			onAction (action, data, index) {
				this.$emit(action,{
					data,
                    index
                });
			}
		}
	}
</script>

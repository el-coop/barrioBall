<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group" :class="{ 'form-inline' : inlineForms}">
                    <label>Search:&nbsp;&nbsp;</label>
                    <div class="input-group">
                        <input type="text" class="form-control" v-model="filter" @keyup.enter="updateParams">
                        <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" @click="updateParams">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group float-md-right" :class="{ 'form-inline' : inlineForms}">
                    <label>Per Page:&nbsp;&nbsp;</label>
                    <select v-model="perPage" class="form-control" @change="updateParams">
                        <option v-for="perPageOption in perPageOptions" :value="perPageOption">{{perPageOption}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
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
            </div>
        </div>
        <div class="row text-center text-md-left">
            <div class="col-12"  :class="{ 'col-md-6' : inlineForms}">
                <vuetable-pagination-info ref="paginationInfo">
                </vuetable-pagination-info>
            </div>
            <div class="col-12 d-flex d-md-block" :class="{ 'col-md-6' : inlineForms}">
                <vuetable-pagination ref="pagination"
                                     :css="cssPagination"
                                     @vuetable-pagination:change-page="changePage">
                </vuetable-pagination>
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
				default: null
			},
			deleteClass: {
				type: String,
				default: 'btn-danger'
			},
			deleteIcon: {
				type: String,
				default: 'fa-trash'
			},
            inlineForms: {
				default: true
            },
            perPageOptions: {
				type: Array,
                default(){
					return [10,20,50,100];
				}
            },
            extraParams: {
				type: Object,
                default(){
					return {};
                }
            }
		},

		data(){
			return {
				loading: false,
				css: {
					tableClass: 'table table-striped table-bordered',
					ascendingIcon: 'fa fa-chevron-up',
					descendingIcon: 'fa fa-chevron-down',
					sortHandleIcon: 'fa fa-bars',
				},
				cssPagination: {
					wrapperClass: 'pagination mx-auto float-md-right',
					activeClass: 'active',
					disabledClass: 'disabled',
					pageClass: 'btn btn-outline-secondary',
					linkClass: '',
					icons: {
						first: 'fa fa-angle-double-left',
						prev: 'fa fa-chevron-left',
						next: 'fa fa-chevron-right',
						last: 'fa fa-angle-double-right',
					}
				},
				params: this.extraParams,
				filter: null,
				perPage: this.perPageOptions[0]
			}
		},

		methods: {
			updateParams(){
				this.params.filter = this.filter;
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
				this.$emit(action, {
					data,
					index
				});
			}
		}
	}
</script>

import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);
import Vuetable from 'vuetable-2/src/components/Vuetable.vue';
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination.vue';
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue';
Vue.component('vuetable', Vuetable);
Vue.component('vuetablePagination', VuetablePagination);
Vue.component('vuetablePaginationInfo', VuetablePaginationInfo);
import TreeView from "vue-json-tree-view"
Vue.use(TreeView);

require('./Pages/pages');
require('./Elements/elements');

Vue.component('mapel', require('./Map.vue'));

Vue.component('leafletMap', require('./LeafletMap.vue'));

Vue.component('formErrors', require('./Form/FormErrors.vue'));
Vue.component('ajaxForm', require('./Form/AjaxForm.vue'));
Vue.component('datePicker', require('./Form/Inputs/DatePicker.vue'));
Vue.component('timePicker', require('./Form/Inputs/TimePicker.vue'));
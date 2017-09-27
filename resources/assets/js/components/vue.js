import Paginate from 'vuejs-paginate';
import Vuetable from 'vuetable-2/src/components/Vuetable.vue';
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue';
import TreeView from "vue-json-tree-view";
Vue.component('paginate', Paginate);
Vue.component('vuetable', Vuetable);
Vue.component('vuetablePaginationInfo', VuetablePaginationInfo);
Vue.use(TreeView);

require('./Pages/pages');
require('./Elements/elements');
require('./Form/form');

Vue.component('leafletMap', require('./LeafletMap.vue'));
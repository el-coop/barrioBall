import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);
import Vuetable from 'vuetable-2/src/components/Vuetable.vue';
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue';
Vue.component('vuetable', Vuetable);
Vue.component('vuetablePaginationInfo', VuetablePaginationInfo);
import TreeView from "vue-json-tree-view"
Vue.use(TreeView);

require('./Pages/pages');
require('./Elements/elements');
require('./Form/form');

Vue.component('mapel', require('./Map.vue'));
Vue.component('leafletMap', require('./LeafletMap.vue'));
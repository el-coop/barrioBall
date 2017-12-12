import Paginate from 'vuejs-paginate';
import TreeView from "vue-json-tree-view";
import VueSweetalert2 from 'vue-sweetalert2';

Vue.component('paginate', Paginate);
Vue.use(TreeView);
Vue.use(VueSweetalert2);

require('./Pages/pages');
require('./Elements/elements');
require('./Form/form');

Vue.component('leafletMap', require('./LeafletMap.vue'));
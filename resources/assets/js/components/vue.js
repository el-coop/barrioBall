import Paginate from 'vuejs-paginate';
import TreeView from "vue-json-tree-view";
import VueSweetalert2 from 'vue-sweetalert2';
import VueMq from 'vue-mq';


Vue.component('paginate', Paginate);
Vue.use(TreeView);
Vue.use(VueSweetalert2);
Vue.use(VueMq, {
	breakpoints: { // default breakpoints - customize this
		xs: 576,
		sm: 768,
		md: 992,
		lg: 1200,
		xl: Infinity,
	}
})

require('./Pages/pages');
require('./Elements/elements');
require('./Form/form');

Vue.component('leafletMap', require('./LeafletMap.vue'));
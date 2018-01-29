import Paginate from 'vuejs-paginate';
import TreeView from "vue-json-tree-view";
import VueSweetalert2 from 'vue-sweetalert2';
import VueMq from 'vue-mq';
import VueEcho from 'vue-echo';

Vue.use(VueEcho, {
	broadcaster: 'socket.io',
	key: process.env.SOCET_IO_KEY,
	host: window.location.hostname + ':6001'
});

Vue.use(VueMq, {
	breakpoints: { // default breakpoints - customize this
		xs: 576,
		sm: 768,
		md: 992,
		lg: 1200,
		xl: Infinity,
	}
});
Vue.component('paginate', Paginate);
Vue.use(VueSweetalert2);
Vue.use(TreeView);

require('./Pages/pages');
require('./Elements/elements');
require('./Form/form');
require('./Global/global');
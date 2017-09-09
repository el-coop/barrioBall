window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
	window.$ = window.jQuery = require('jquery');
	window.Tether = require('tether');

	require('bootstrap');
} catch (e) {}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */


// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
/**
 * Other dependencies
 */

window.datetimepicker = require('eonasdan-bootstrap-datetimepicker');
window.L = require('leaflet/dist/leaflet');
window.L.leafletgeocoder = require('leaflet-control-geocoder/dist/Control.Geocoder');
window.moment = require('moment');
window.swal = require('sweetalert2');

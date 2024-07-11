import './firebase'
window._ = require('lodash');


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.Swal = require('sweetalert2');
window.repeater = require('jquery.repeater');



window.toastr = require('toastr');
toastr.options.closeButton = false;
toastr.options.rtl = true;
toastr.options.closeMethod = 'fadeOut';
toastr.options.positionClass = 'toast-bottom-left';
toastr.options.extendedTimeOut = 20000;
toastr.options.showEasing = 'swing';
toastr.options.hideEasing = 'linear';
toastr.options.showMethod = 'fadeIn';
toastr.options.fadeIn = 'fadeOut';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

 import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
	// encrypted:false ,
    wsHost: window.location.hostname, // just host name without its port like
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
     forceTLS: false,
	wsPort: process.env.MIX_LARAVEL_WEBSOCKETS_PORT,
	enabledTransports: ['ws', 'wss'],
	// authEndpoint : `http://127.0.0.1:8000/broadcasting/auth`,
	
    // disableStats: true
});


window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// set url of app as global variable
// because we can't send axios request directly to server
// What's happened without full url? currentURL + axiosURL
// ex currentURL=abc.com/room   axiosURL= api/test   ==> result = abc.com/room/api/test
// so we can just use full axiosURL
 window.axios.defaults.baseURL = 'http://127.0.0.1:8000/';
/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

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

window.io = require("socket.io-client");
 //   let remember_token = this.$store.getters.user.remember_token;

    // Have this in case you stop running your laravel echo server
    /*
    if (remember_token){
    window.Echo = new Echo({
      broadcaster: "socket.io",
      host: "127.0.0.1:6001",
      csrfToken: token.content,
      auth: {
        headers: {
         Authorization: "Bearer " + remember_token
        }
      }
    });
}
    
-*/


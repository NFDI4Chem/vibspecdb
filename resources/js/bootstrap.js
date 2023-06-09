import _ from 'lodash'
import Pusher from 'pusher-js'

window._ = _

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios'
window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import { Buffer } from 'buffer'
window.Buffer = Buffer

import Echo from 'laravel-echo'

window.Pusher = Pusher

let config = {
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  wsHost: import.meta.env.VITE_PUSHER_HOST,
  forceTLS: false,
  encrypted: true,
  disableStats: true,
  enabledTransports: ['ws', 'wss'],
}

if (import.meta.env.VITE_PROD !== 'true') {
  config = {
    ...config,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
  }
}

window.Echo = new Echo(config)

/* // via Pusher SDK:
import PusherJS from 'pusher-js';

window.client = new PusherJS(
    import.meta.env.VITE_PUSHER_APP_KEY, 
    {
        wsHost: '127.0.0.1',
        wsPort: import.meta.env.VITE_PUSHER_PORT,
        forceTLS: false,
        encrypted: true,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
    }
);
*/

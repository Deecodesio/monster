import Vue from 'vue'
import { ToastPlugin, ModalPlugin } from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'

import i18n from '@@@/libs/i18n'
import router from './router'
import store from './store'
import App from './App.vue'

import './global-components'
import './vue.css'


import '@@@axios'
import '@@@/libs/acl'
import '@@@/libs/portal-vue'
import '@@@/libs/clipboard'
import '@@@/libs/toastification'
import '@@@/libs/sweet-alerts'
import '@@@/libs/vue-select'
import '@@@/libs/tour'

import '@@@/@fake-db/db'
import Meta from 'vue-meta'
import axios from '@axios'

Vue.use(ToastPlugin)
Vue.use(ModalPlugin)
Vue.use(Meta)

Vue.use(VueCompositionAPI)
require('@@@core/assets/fonts/feather/iconfont.css')

require('@resources/scss/core.scss')

require('@resources/assets/scss/style.scss')
window.$ = window.jQuery = require('jquery');
Vue.config.productionTip = false

import VueSocialauth from 'vue-social-auth'

// FIXED: CRITICAL - Detect if this page is loaded in OAuth popup window
if (window.opener && window.location.search.includes('code=')) {
  console.log('FIXED: OAuth callback detected in popup'); // DEBUG
  const urlParams = new URLSearchParams(window.location.search)
  const code = urlParams.get('code')
  const state = urlParams.get('state')
  
  // FIXED: Send OAuth response back to parent window using '*' to avoid cross-origin issues
  if (code && window.opener) {
    window.opener.postMessage({ 
      code: code, 
      state: state,
      scope: urlParams.get('scope')
    }, '*') // FIXED: Use '*' instead of specific origin to handle localhost:3000 vs localhost:8000
    
    // FIXED: Close popup after sending message
    setTimeout(() => {
      window.close()
    }, 100)
  }
} else {
  // FIXED: Normal page load - not in popup, initialize Vue app
  
  // FIXED: Initialize vue-social-auth with Google OAuth settings from database
  axios.get('/get_defaults').then(
    (response) => {
      console.log('FIXED: Google OAuth config loaded:', response.data); // DEBUG
      
      // FIXED: Configure vue-social-auth with Google credentials
      Vue.use(VueSocialauth, {
        providers: {
          google: {
            clientId: response.data.google_client_id, // From database settings
            redirectUri: response.data.google_redirect_url // Must match Google Console
          }
        }
      })
    },
    (error) => {
      console.error('FIXED: Failed to load Google OAuth config:', error); // DEBUG
    }
  );

  // FIXED: Mount Vue app (only if NOT in popup)
  new Vue({
    router,
    store,
    i18n,
    render: h => h(App),
  }).$mount('#app')
}

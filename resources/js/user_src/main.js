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

axios.get('/get_defaults').then(
  (response) => {
    console.log(response);
    Vue.use(VueSocialauth, {

      providers: {
        google: {
          clientId: response.data.google_client_id,
          client_secret: response.data.google_client_secret,
          redirectUri: response.data.google_redirect_url // Your client app URL
        }

      }
    })
  },
  (error) => {

  }
);


new Vue({
  router,
  store,
  i18n,
  render: h => h(App),
}).$mount('#app')

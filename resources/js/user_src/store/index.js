import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import ecommerceStoreModule from '@@@/views/apps/e-commerce/eCommerceStoreModule'
import cart from '@@@/views/user/restaurants/cart'
import defaults from '@@@/defaults'

import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    app,
    appConfig,
    verticalMenu,
    'app-ecommerce': ecommerceStoreModule,
    'deliware_cart':cart,
    'defaults':defaults,

  },
  strict: process.env.DEV,
})

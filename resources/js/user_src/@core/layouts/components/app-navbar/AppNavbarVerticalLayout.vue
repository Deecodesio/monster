<template>
  <div>
    <!-- Mobile Only Header (≤576px) -->
    <b-row class="d-flex d-sm-none px-3 py-2 align-items-center justify-content-between">
      <!-- Left: Delivery to + Location -->
      <b-col cols="6">
        <!-- <div style="font-size: 14px; font-weight: 600;">Delivery To</div> -->
        <useraddress />
      </b-col>

      <!-- Right: Login Button / User Dropdown -->
      <b-col cols="6" class="text-right">
        <user-dropdown />
      </b-col>
    </b-row>

    <!-- Full Layout for Larger Screens -->
    <b-row class="d-none d-sm-flex">
      <div class="d-flex justify-content-center align-items-center container px-4">
        <b-col>
          <b-link class="navbar-brand" to="/">
            <span class="brand-logo">
              <b-img :src="rows_detail" :alt="alt" class="logo_image" />
            </span>
          </b-link>
        </b-col>

        <b-col md="2" class="pt-3">
          <useraddress />
        </b-col>

        <b-col md="4">
          <search-bar />
        </b-col>

        <b-col md="4" class="pt-2 text-r" style="display: flex; flex-direction: row-reverse; margin-top: 10px !important;">
          <user-dropdown style="margin: 0px 20px 0px 20px;" />
          <cart-dropdown />
        </b-col>
      </div>
    </b-row>
  </div>
</template>


<script>
import {
    BCol,
    BRow,
    BLink, BNavbarNav, BButton
} from 'bootstrap-vue'
import useraddress from './components/useraddress.vue'
import Ripple from 'vue-ripple-directive'

import Locale from './components/Locale.vue'
import SearchBar from './components/SearchBar.vue'
import DarkToggler from './components/DarkToggler.vue'
import CartDropdown from './components/CartDropdown.vue'
import NotificationDropdown from './components/NotificationDropdown.vue'
import UserDropdown from './components/UserDropdown.vue'
import { BImg } from 'bootstrap-vue'
import { $themeConfig } from '@@@themeConfig'

export default {
    components: {
        BLink,

        // Navbar Components
        BNavbarNav,
        Locale,
        SearchBar,
        DarkToggler,
        CartDropdown,
        NotificationDropdown,
        UserDropdown,
        BImg,
        useraddress,
        BCol,
        BRow,
        BButton,

    },
    data() {
        return {

            rows_detail: '',
            alt: '',

        }
    },
    directives: {

        Ripple,
    },
    setup(props) {


        // App Name
        const { appName, appLogoImage } = $themeConfig.app

        return {

            // App Name
            appName,
            appLogoImage,
        }
    },
    props: {
        toggleVerticalMenuActive: {
            type: Function,
            default: () => { },
        },
    },
    created() {
        this.$http.get('/get_site_logo/')
            .then(res => {

                this.rows_detail = res.data.logo

                this.alt = res.data.app_name


            })
    },

}
</script>
<style lang="scss" scoped>
.logo_image {
  max-width: 800px !important;
  height: 60px !important;
  padding: 0% !important;
  margin-top: 1rem !important;

  @media (max-width: 1200px) {
    max-width: 600px !important;
    height: 55px !important;
  }

  @media (max-width: 992px) {
    max-width: 400px !important;
    height: 50px !important;
  }

  @media (max-width: 768px) {
    max-width: 280px !important;
    height: 45px !important;
  }

  @media (max-width: 576px) {
    max-width: 200px !important;
    height: 40px !important;
  }
}
 .py-2 {
    padding-bottom: 0.5rem !important;
}
.text-r {
  text-align: right;

  @media (max-width: 576px) {
    text-align: center;
  }
}

.pad-1 {
  padding: 10px 20px !important;

  @media (max-width: 768px) {
    padding: 8px 16px !important;
  }

  @media (max-width: 576px) {
    padding: 6px 12px !important;
  }
}
</style>


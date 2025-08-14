<template>
  <b-row class="align-items-center">
    <!-- Logo -->
    <b-col cols="3">
      <b-link class="navbar-brand" to="/">
        <span class="brand-logo">
          <b-img :src="rows_detail || '/default-logo.png'" :alt="alt || 'Logo'" class="logo_image_mobile" />
        </span>
      </b-link>
    </b-col>
    
    <!-- Address -->
    <b-col cols="6">
      <useraddress />
    </b-col>
    
    <!-- User Dropdown -->
    <b-col cols="3" class="text-right">
      <user-dropdown />
    </b-col>
  </b-row>
</template>

<script>
import {
    BCol, 
    BRow,
  BLink, BNavbarNav,
} from 'bootstrap-vue'
import useraddress from './mobilecomponents/useraddress.vue'

import Locale from './mobilecomponents/Locale.vue'
import SearchBar from './mobilecomponents/SearchBar.vue'
import UserDropdown from './mobilecomponents/UserDropdown.vue'
import { BImg } from 'bootstrap-vue'
import { $themeConfig } from '@@@themeConfig'

export default {
  components: {
    BLink,

    // Navbar Components
    BNavbarNav,
    Locale,
    SearchBar,
    UserDropdown,
    BImg,
    useraddress,
    BCol, 
    BRow,


  },
  data() {
    return {
      rows_detail: '',
      alt: '',
    }
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
      default: () => {},
    },
  },
  created() {
    this.$http.get('/get_site_logo/')
      .then(res => {
        this.rows_detail = res.data.logo
        this.alt = res.data.app_name
      })
  },
  methods:{
    goback(){
      return this.$router.go(-1);
    }
  }
}
</script>
<style lang="scss" scoped>

.text-r {text-align:right;}
// .nav-search{
//   width: 600px;
// }
.pad-1{padding: 10px 20px;}

.logo_image_mobile {
  max-width: 120px !important;
  width: 100% !important;
  height: 35px !important;
  display: block;
  margin: 0 auto;
  object-fit: contain;
  padding: 0% !important;

  @media (max-width: 580px) {
    max-width: 100px !important;
    height: 30px !important;
  }
  @media (max-width: 360px) {
    max-width: 80px !important;
    height: 25px !important;
  }
}
</style>

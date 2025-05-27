<template>
  <b-row >
    
      
     <b-col     cols="6"  style="display: -webkit-inline-box;">

   

      <useraddress />
     </b-col>
    
     <b-col         cols="6"     >

     <b-row>      
      
      <b-col md-12>
      <user-dropdown />
    </b-col>
    </b-row>
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
</style>

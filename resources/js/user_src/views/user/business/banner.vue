<template>
 <div>
   <div class="pb-2" >
    <b-img style="width: 1920px;height: 300px;" :src="banner.image"/>
    <div class="container">
    <div style="position: absolute; z-index: 999; top: 140px; "><h1 style="color: #fff;">{{ banner.name }}</h1><hr></div>
    </div>
  </div>
   
  </div>
</template>

    <script>
    import {
      BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
    } from 'bootstrap-vue'
    import Ripple from 'vue-ripple-directive'

    
    export default {
      components: {
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
      },
      directives: {
        Ripple,
      },
      data() {
    return {
      rows: {},
      banner:[],
    }
  },
      created() {

        this.$http.get('/business_banner/'+localStorage.bussiness_id)
      .then(res => {
        this.banner = res.data[0];
          })
      }
      
     
      
    }
    </script>
    <style>
     .pt-10{
      padding-top: 10rem !important;
    }
    </style>
    
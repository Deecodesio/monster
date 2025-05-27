<template>
    <!-- Coming soon page-->
    <div class="misc-wrapper">
  
      <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
          <h2 class="mb-1">
           {{$t('Welcome')}} {{user_info.restaurant_name}}
          </h2>
          <p class="mb-3">
          
          </p>
          <h2 class="mb-1">
            {{$t('table')}}  {{rows.table_name}}
          </h2>
          <!-- form -->
          <b-form
            inline
            class="row justify-content-center m-0 mb-2"
           
          >
          <b-img
            fluid
            :src="downImg"
          
            alt="Coming soon page"
          />
  
          <h4 class="mb-1">
            To Make an Order Kindly Scan the QR Code From Your Mobile Phone
          </h4>
          </b-form>
          <h4 class="mb-1" style="display: flex;
    flex-wrap: wrap;
    align-content: flex-end;
    justify-content: space-around;">
            <VueQRCodeComponent 
                :text="value"
                
                error-level="L">
            </VueQRCodeComponent>
          </h4>
         
        </div>
      </div>
    </div>
  <!-- / Coming soon page-->
  </template>
  
  <script>
  /* eslint-disable global-require */
  import { BLink, BForm, BFormInput, BButton, BImg } from 'bootstrap-vue'
  import VuexyLogo from '@core/layouts/components/Logo.vue'
  import store from '@@/store/index'
  import VueQRCodeComponent from 'vue-qr-generator'
  export default {
    components: {
      VuexyLogo,
      BLink,
      BForm,
      BButton,
      BFormInput,
      BImg,
      VueQRCodeComponent
    },
    data() {
      return {
        downImg: require('@@/assets/images/pages/scan.jpg'),
        rows:{},
        user_info:{},
        value: 'https://teerya.com',
        size: 300,
      }
    },
   
    created() {
       
       this.user_info.id=   localStorage.id;
       this.user_info.code = this.$route.params.id;
      this.$http.post('/store/print_tables',this.user_info)
        .then(res => { 
          console.log(res.data);
          this.value= res.data.qr
          this.rows = res.data.table;
          this.user_info = res.data.restaurant;
         })
  
       
    },

    computed: {
      imgUrl() {
        if (store.state.appConfig.layout.skin === 'dark') {
          // eslint-disable-next-line vue/no-side-effects-in-computed-properties
          this.downImg = require('@@/assets/images/pages/coming-soon-dark.svg')
          return this.downImg
        }
        return this.downImg
      },
    },
  }
  </script>
  
  <style lang="scss">
  @import '~@resources/scss/vue/pages/page-misc.scss';
  </style>
  
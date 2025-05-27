<template>

    <div class="container" style="margin-top:100px;">
        <b-card>
          <b-row>
            <b-col cols="3">
               
            </b-col>
            <b-col cols="6" style="text-align: center;">
              <h2>{{$t('Thank you, your wallet has been topped up')}}  {{ $store.state['defaults'].currency }}  {{$store.state['defaults'].wallet }} </h2>
            </b-col>
            <b-col cols="3">
               
            </b-col>
          </b-row>
         
              <b-row>
                <b-col lg="3" md="3" cols="3">
               
            </b-col>
                <b-col lg="6" md="6" cols="6">
                    <div class="text-l" style=" text-align: -webkit-center;">
                    
                    <lottie-player src="/animations/procpay.json"  background="#FFFDFD"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>
                  
                  </div>
              </b-col>
              <b-col lg="3" md="3" cols="3">
               
            </b-col>
            </b-row>
            <b-row>
                <b-col cols="3">
               
            </b-col>
            <b-col cols="6">
                <b-button style="width:100%;font-size: 16px;" variant="primary" @click="home()" >{{$t("Go to home")}}</b-button>
            </b-col>
            <b-col cols="3">
              
            </b-col>
          </b-row>
            </b-card>
          
        </div>
                 
       
          </template>
          
          <script>
          import {
            BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard,
             BCardText, BLink, BAvatar, BImg,BMedia,
          } from 'bootstrap-vue'
          import Ripple from 'vue-ripple-directive'
          import store from '@@@/store'
          export default {
            components: {
              BButton,
              BForm,BMedia,
              BFormGroup,
              BFormInput,
              BRow,
              BCol,
              BCard,
              BCardText,
              BLink,
              BAvatar,BImg,
            },
            directives: {
              Ripple,
            },
            props: {
             
              socialData: {
                type: Object,
                default: () => {},
              },
            },
            data() {
              return {
                localOptions: JSON.parse(JSON.stringify(this.socialData)),
                wallet:{},
                wallet1:{},
              }
            },
            created(){
           
             
                let userData = JSON.parse(localStorage.getItem('webuserData'))
                this.wallet.user_id = userData.id
                console.log(this.wallet);
                this.$http
                        .post("/add_wallet_1",this.wallet)
                        .then(
                            (response => {
                                if (response.data.status == true) {
                                    this.wallet1 = response.data.data
                                    this.$toast({
                                  component: ToastificationContent,
                                  position: 'bottom-right',
                                  props: {
                                    title: this.$t(response.data.message),
                                    icon: 'CoffeeIcon',
                                    variant: 'success',
                                  },
                                })
                               
                              }  else {
        
                                  this.$toast({
                                  component: ToastificationContent,
                                  position: 'bottom-right',
                                  props: {
                                    title: this.$t(response.data.message),
                                    icon: 'CoffeeIcon',
                                    variant: 'failure',
                                  },
                                })
                                }
                            
                              
                              } )                  
                        )
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                           
             
        
              
        },
    
     
            methods: {
              resetForm() {
                this.localOptions = JSON.parse(JSON.stringify(this.socialData))
              },
              home()
              {
                this.$router.push({ name: "home" })
              },
        
              add_wallet()
              {
                let userData = JSON.parse(localStorage.getItem('webuserData'))
                this.wallet.user_id = userData.id
                this.$http
                        .post("/add_wallet_1",this.wallet)
                        .then(
                            (response => {
                                if (response.data.status == true) {
                                    this.wallet1 = response.data.data
                                    this.$toast({
                                  component: ToastificationContent,
                                  position: 'bottom-right',
                                  props: {
                                    title: this.$t(response.data.message),
                                    icon: 'CoffeeIcon',
                                    variant: 'success',
                                  },
                                })
                               
                              }  else {
        
                                  this.$toast({
                                  component: ToastificationContent,
                                  position: 'bottom-right',
                                  props: {
                                    title: this.$t(response.data.message),
                                    icon: 'CoffeeIcon',
                                    variant: 'failure',
                                  },
                                })
                                }
                            
                              
                              } )                  
                        )
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                           
             
        
              }
        
            },
          }
          </script>
          
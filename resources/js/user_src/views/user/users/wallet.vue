<template>
<b-card>
      <b-row>
        <b-col cols="1">
        <b-img src="/wallet.svg" fluid alt="Scan" /></b-col>
        
        <b-col lg="6" md="6" cols="9" class="pt-4"> {{$t("Wallet Balance")}} :  {{ $store.state['defaults'].currency }}  {{localOptions.wallet_amount}}  </b-col>
      <b-col lg="2" md="1" class="pt-4"> </b-col>
      <b-col lg="3" md="5" class="pt-4"> 

       <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" style="width: 100%;margin-bottom: 4%;" @click="wallet1()" >
             {{$t('Add Wallet')}}
        </b-button>
      </b-col>
    </b-row>
    <b-modal id="modal-address" title="Login" ok-only ok-title="Login" cancel-title="Close" hide-footer            hide-header            size="sm"            centered            ref="my-modal">
       
       <b-row>
           

           <b-col lg="12" md="12" sm="12">
            <label>{{$t('Add Wallet')}}</label>
            <b-form-input
                           id="basicInput"
                           :placeholder="$t('Amount')"
                           v-model="wallet.amount"
                           />
           </b-col>
           
           <b-col lg="12" md="12" sm="12">
                      
              
           </b-col>
           <b-col lg="12" md="12" sm="12">
            <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            style="width: 100%;margin-top: 20px;"
            @click="add_wallet()"
          >
             {{$t('Submit')}}
          </b-button> 
              
                    </b-col>
       </b-row>

   </b-modal>
    </b-card>
  </template>
  
  <script>
  import {
    BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BCardText, BLink, BAvatar, BImg
  } from 'bootstrap-vue'
  import Ripple from 'vue-ripple-directive'
  import store from '@@@/store'
  export default {
    components: {
      BButton,
      BForm,
      BFormGroup,
      BFormInput,
      BRow,
      BCol,
      BCard,
      BCardText,
      BLink,
      BAvatar,BImg
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
      }
    },
    methods: {
      resetForm() {
        this.localOptions = JSON.parse(JSON.stringify(this.socialData))
      },

      wallet1(){

this.$refs['my-modal'].show()

},

add_wallet()
{
let userData = JSON.parse(localStorage.getItem('webuserData'))

this.wallet.user_id = userData.id
this.$http
        .post("/add_wallet",this.wallet)
        .then(
            (response => {
              localStorage.setItem("wallet1", this.wallet.amount);
                if (response.data.status == true) {
                    
                  if (response.data.data.checkout_url != null) {
                this.$router.push({ name: "pay_for_wallet", params: { id: response.data.data.checkout_url,name:response.data.data.ClientSecret,user_id:this.wallet.user_id} })
               
              } 
                this.$refs['my-modal'].hide()
                
                } else {

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
           
// this.$router.push({ name: "pay_for_wallet", params: { id: res.data.data.checkout_url,name:res.data.data.ClientSecret} })

}
    },
  }
  </script>
  
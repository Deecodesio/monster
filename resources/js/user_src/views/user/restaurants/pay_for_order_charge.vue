<template>
    <div class="container" style="margin-top:100px;">
      <b-row>
        <b-col cols="12"  lg="4"></b-col>
        <b-col cols="12" lg="4">
          <b-card>
<p class="f-size-2 text-center pb-1">{{$t('Payment Gateway')}}</p>
        <table width="100%" style="margin-bottom:40px;">
        
        <tr>
          <td>{{$t("Item Total")}}</td>
          <td>{{$store.state['defaults'].currency}} {{rows.item_total}}</td>
        </tr>
        <tr>
          <td>{{$t("Packaging Charge")}}</td>
          <td>{{$store.state['defaults'].currency}} {{rows.restaurant_packaging_charge}}</td>
        </tr>
        <tr>
          <td>{{$t("Discount")}}</td>
          <td>{{$store.state['defaults'].currency}} {{rows.offer_discount}}</td>
        </tr>
        <tr id="dcharge_bill">
          <td>{{$t("Delivery Charge")}}</td>
          <td>{{$store.state['defaults'].currency}} {{rows.delivery_charge}}</td>
        </tr>
        <tr id="tips_bill">
          <td>{{$t("Tips")}}</td>
          <td>{{$store.state['defaults'].currency}} {{rows.tips}}</td>
        </tr>
        <tr id="tips_bill">
          <td>{{$t("Tax")}} </td>
          <td>{{$store.state['defaults'].currency}} {{rows.tax}}</td>
        </tr>
        <tr>
          <td>
            <p>{{$t("Bill Total")}} </p>
          </td>
          <td>
            {{$store.state['defaults'].currency}} {{rows.bill_amount}}
          </td>
        </tr>
        
        
      </table>

      <b-col cols="12" class="pad0">
        <div class="group" v-for="(data,index) in cust_cards" :key="index">
          <b-form-radio
            v-model="Selected"
            name="payment-source"
            :value="data.id"
            :id="index"
          >
          **** **** **** {{data.card.last4}}
          </b-form-radio>
          <div id="saved-card"></div>

          <!-- <label>
            <span><input type="radio" name="payment-source" value="saved-card-1" checked></span>
            <div id="saved-card"></div>
          </label> -->
        </div>
        <!-- <b-form-radio
            v-model="Selected"
            name="payment-source"
            value="0"
            id="index"
          >
             
        </b-form-radio> -->
        <stripe-element-card
              ref="elementRef"
              :pk="pk"
              @token="tokenCreated"
            />
        <button @click="submit">Pay {{rows.bill_amount}}</button>
      </b-col>
      </b-card>

        </b-col>
        <b-col cols="4" lg="4"></b-col>
      </b-row>
      
    </div>
  </template>

  <script>
  import { BModal, BButton, VBModal, BAlert,BCard,BFormRadioGroup, BFormGroup , BRow,BCol,BFormRadio } from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { BTabs, BTab } from 'bootstrap-vue'
import { StripeCheckout } from '@vue-stripe/vue-stripe';
import { StripeElementPayment } from '@vue-stripe/vue-stripe';
import { StripePlugin } from '@vue-stripe/vue-stripe';
import { StripeElementCard } from '@vue-stripe/vue-stripe';

import Vue from 'vue';

const options = {
  pk: "pk_test_51MeeenCoJKKM5ijQ1J6gaCThlPqrp0Vcs7sLIquinRA1AS4rEtNfBZfH6yyr67ZrEqrQiOMuyd8befLzqTcr7Jtg00Qs6iwBKI",
  
};

Vue.use(StripePlugin, options);
  export default {
    head: {
    script: [
      { src: 'https://js.stripe.com/v3' },
    ],
  },
    components: {
      BButton,
      BModal,
      BAlert,
      BCard,
      BFormRadioGroup, BFormGroup,BTabs, BTab,BRow,BCol,StripeCheckout,StripeElementPayment,StripeElementCard,BFormRadio 
    },
    mounted () {
    this.generatePaymentIntent();
    const style = {
      base: {
        color: 'black',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '14px',
        '::placeholder': {
          color: '#aab7c4',
        },
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
      },
    };
 
  },
 
    data() {
        
    return {
       
        rows :{},
        loading: false,
        
        pk: 'pk_test_51HzcjvJHSwEedL7PROSrTFu77f8HT56WA8csGicftxrVhirbj60Imi5dSrmADak7vQ3KcMB3BHT8IybCksTWniB400yB5C9UMK',
        token: null,
        cust_cards :[],
        fn_token:null,
        Selected:''
      
    }
  },
  computed: {
    stripeElements () {
      const appearance = {
        theme: 'stripe'
      };
      return this.$stripe.elements(appearance);
    },
  },
  methods: {
    generatePaymentIntent(){
      // https://stripe.com/docs/payments/save-during-payment
      this.$http.post('/pay_for_order/'+this.$route.params.id)
                    .then(res => {
                     
                       
                        this.rows=res.data.data
                        this.cust_cards = res.data.card
                        console.log(this.cust_cards)
                        
                    })
                    
    },

   
    submit () {
      console.log(this.Selected)
      if(this.Selected == 0){
        this.$refs.elementRef.submit();
      }else{
        this.$http.get('/validate_card/'+this.Selected+'/'+this.$route.params.id)
                    .then(res => {
                        console.log(res)
                        if(res.data.status == true){
                          this.$router.push({ name: "trackorder", params: { id: this.$route.params.id} })
                        }else{
                          this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                              title: "Payment Failed",
                              icon: 'UserIcon',
                              variant: 'danger',
                            },
                          })
                        }
                      })
      }
    },
    tokenCreated (token) {
      console.log(token);
      this.fn_token = JSON.stringify(token)
      this.$http.get('/validate_token/'+this.fn_token+'/'+this.$route.params.id)
                    .then(res => {
                     
                       
                        console.log(res)
                        if(res.data.status == true){
                          this.$router.push({ name: "trackorder", params: { id: this.$route.params.id} })
                        }else{
                          this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                              title: "Payment Failed",
                              icon: 'UserIcon',
                              variant: 'danger',
                            },
                          })
                        }
                      
                        
                    })
    },
  },
  created(){
       

    },
   
  }
  </script>
  <style scoped>
  .pt-10{
    padding-top: 10rem !important;
  }
  /* Variables */
* {
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, sans-serif;
  font-size: 16px;
  -webkit-font-smoothing: antialiased;
  display: flex;
  justify-content: center;
  align-content: center;
  height: 100vh;
  width: 100vw;
}

form {
  width: 30vw;
  min-width: 500px;
  align-self: center;
  box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
    0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
  border-radius: 7px;
  padding: 40px;
}

.hidden {
  display: none;
}

#payment-message {
  color: rgb(105, 115, 134);
  font-size: 16px;
  line-height: 20px;
  padding-top: 12px;
  text-align: center;
}

#payment-element {
  margin-bottom: 24px;
}

/* Buttons and links */
button {
  background: #5469d4;
  font-family: Arial, sans-serif;
  color: #ffffff;
  border-radius: 4px;
  border: 0;
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: block;
  transition: all 0.2s ease;
  box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
  width: 100%;
}
button:hover {
  filter: contrast(115%);
}
button:disabled {
  opacity: 0.5;
  cursor: default;
}

/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
  border-radius: 50%;
}
.spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.spinner:before,
.spinner:after {
  position: absolute;
  content: "";
}
.spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: #5469d4;
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}
.spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: #5469d4;
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@media only screen and (max-width: 600px) {
  form {
    width: 80vw;
    min-width: initial;
  }
}

#custom-button {
  height: 30px;
  outline: 1px solid grey;
  background-color: green;
  padding: 5px;
  color: white;
}

#card-error {
  color: red;
}
  </style>

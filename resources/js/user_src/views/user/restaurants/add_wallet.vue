<template>
    <div class="container" style="margin-top:100px;">
      <b-row>
        <b-col cols="12"  lg="4"></b-col>
        <b-col cols="12" lg="4">
          <b-card>
       <p class="f-size-2 text-center pb-1">Payment Gateway</p>
        <table width="100%" style="margin-bottom:40px;">
        
      
        <tr id="dcharge_bill">
          <td>{{$t("Wallet Amount")}}</td>
          <td> {{ $store.state['defaults'].currency }}   {{ value }}  </td>
        </tr>
      
       
        
        
      </table>

      <b-col cols="12" class="pad0">
        <div>
          <stripe-element-payment
            ref="paymentRef"
            :pk="pk"
            :elements-options="elementsOptions"
            :confirm-params="confirmParams" 

          />
          <button @click="pay">{{$t("Pay Now")}}</button>
        </div>
      </b-col>
      </b-card>

        </b-col>
        <b-col cols="4" lg="4"></b-col>
      </b-row>
      
    </div>
  </template>

  <script>
  import { BModal, BButton, VBModal, BAlert,BCard,BFormRadioGroup, BFormGroup , BRow,BCol} from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { BTabs, BTab } from 'bootstrap-vue'
import { StripeCheckout } from '@vue-stripe/vue-stripe';
import { StripeElementPayment } from '@vue-stripe/vue-stripe';
import store from '@@@/store'
  export default {
    components: {
      BButton,
      BModal,
      BAlert,
      BCard,
      BFormRadioGroup, BFormGroup,BTabs, BTab,BRow,BCol,StripeCheckout,StripeElementPayment,
    },
    mounted () {
    this.generatePaymentIntent();
  },
    data() {
        
    return {
       
        rows :{},
        loading: false,
        userData : JSON.parse(localStorage.getItem('webuserData')),
        value : 0,
        
        pk: 'pk_live_51MeeenCoJKKM5ijQmKc9qzdTnZSPN1iu4dwNf5d2E9GQSCKMppKBL6E32QTRbiitIADOoeUgAFE8tsGQPcsy455D00I9zNJnL9',

        elementsOptions: {
          appearance: {
            theme: 'stripe',
            labels: 'floating',
          }, 
          clientSecret :this.$route.params.name
        },
        confirmParams: {
          return_url: 'https://teerya.com/check_pay_for_wallet/'+this.$route.params.id+'/'+this.$route.params.user_id, // success url
          // return_url: 'http://127.0.0.1:8000/check_pay_for_wallet/'+this.$route.params.id+'/'+this.$route.params.user_id, // success url
        
        },
     
      
    }
  },
  filters: {
    priceformat(number){
           var decimals = store.state['defaults'].decimal;
            var dec_point = store.state['defaults'].delimiter;
         var thousands_sep = '';
         number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
         var n = !isFinite(+number) ? 0 : +number,
             prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
             sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
             dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
             s = '',
             toFixedFix = function (n, prec) {
                 var k = Math.pow(10, prec);
                 return '' + Math.round(n * k) / k;
             };
       
         s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
         if (s[0].length > 3) {
             s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
         }
         if ((s[1] || '').length < prec) {
             s[1] = s[1] || '';
             s[1] += new Array(prec - s[1].length + 1).join('0');
         }

         var currency = store.state['defaults'].currency 

          if(store.state['defaults'].currency_position == 1){
            var rt = s.join(dec)  + ' ' +currency
          }else{
            var rt = currency + ' ' + s.join(dec)  
          }
         return rt;
   
      
       },
    // tConvert :function (time) {
      
    //   time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    //   if (time.length > 1) { 
    //     time = time.slice (1);  
    //     time[5] = +time[0] < 12 ? 'AM' : 'PM'; 
    //     time[0] = +time[0] % 12 || 12; 
    //   }
    //   return time.join (''); 
    // },
    tConvert (date) {
      
      var strTime = date.toLocaleString('en-US', {hour: '2-digit', minute:'2-digit'})
           .replace(/(:\d{2}| [AP]M)$/, "");
      return strTime;
    }
       
      },
  methods: {
    generatePaymentIntent(){
        this.value =this.$route.params.id
    },
    pay () {
      this.$refs.paymentRef.submit();
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
  </style>

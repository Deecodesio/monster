<template>
  <div class="container" style="margin-top:100px;">
    <b-row>
      <b-col cols="12" lg="4"></b-col>
      <b-col cols="12" lg="4">
        <b-card>
          <p class="f-size-2 text-center pb-1">Plan Details</p>
          <table width="100%" style="margin-bottom:40px;">

            <tr>
              <td>{{ $t("Plan Name") }}</td>
              <td>{{ plan.name }}</td>
            </tr>
            <tr>
              <td>{{ $t("Plan Description") }}</td>
              <td>{{ plan.description }}</td>
            </tr>
            <tr>
              <td>{{ $t("validity") }}</td>
              <td>{{ plan.validity }} Days</td>
            </tr>

            <tr>
              <td>
                <p>{{ $t("To Pay") }} </p>
              </td>
              <td>
                {{ setting.value }} {{ plan.price }}
              </td>
            </tr>


          </table>


          <b-col cols="12" class="pad0">
            <div>
              <stripe-element-payment ref="paymentRef" :pk="pk" :elements-options="elementsOptions"
                :confirm-params="confirmParams" />
              <button @click="pay">{{ $t("Pay Now") }}</button>
            </div>
          </b-col>

        </b-card>

      </b-col>
      <b-col cols="4" lg="4"></b-col>
    </b-row>

  </div>
</template>
    
<script>
import {
  BFormCheckbox,
  BRow,
  BCol,
  BCard,
  BImg,
  BCardText,
  BListGroup,
  BListGroupItem,
  BButton,
  BBadge,
  BImgLazy, BInputGroup, BInputGroupAppend, BFormInput
} from 'bootstrap-vue'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { StripeCheckout } from '@vue-stripe/vue-stripe';
import { StripeElementPayment } from '@vue-stripe/vue-stripe';
/* eslint-disable global-require */
export default {
  components: {
    BFormCheckbox,
    BButton,
    BCardText,
    BListGroup,
    BListGroupItem,
    BRow,
    BCol,
    BCard,
    BBadge,
    BImg,
    AppCollapseItem,
    AppCollapse,
    BImgLazy, BInputGroup, BInputGroupAppend, BFormInput, StripeCheckout, StripeElementPayment,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      rows: {},
      plan: {},
      restaurant: {},

      setting: {},

      loading: false,
      pk: 'pk_test_51MeeenCoJKKM5ijQ1J6gaCThlPqrp0Vcs7sLIquinRA1AS4rEtNfBZfH6yyr67ZrEqrQiOMuyd8befLzqTcr7Jtg00Qs6iwBKI', elementsOptions: {
        appearance: {
          theme: 'stripe',
          labels: 'floating',
        },
        clientSecret: this.$route.params.id
      },
      confirmParams: {
        // return_url: 'https://demo.orderi.nl/store/rest_payment_details/'+this.$route.params.trans,
        return_url: 'http://127.0.0.1:8000/store/rest_payment_details/' + this.$route.params.trans,


      },
    }
  },
  created() {
    this.$http.post('/get_plan_details/' + this.$route.params.plan + '/' + this.$route.params.rest_id)
      .then(res => {

        console.log(res)
        this.plan = res.data.plan

        this.restaurant = res.data.restaurant
      })
    this.$http.get('/admin/get_currency')
      .then(res => {

        this.setting = res.data

      })
  },
  methods: {

    generatePaymentIntent() {

      this.$http.post('/pay_for_order/' + this.$route.params.id)
        .then(res => {


          this.rows = res.data.data


        })
    },
    pay() {
      this.$refs.paymentRef.submit();
    },
  },
}
    /* eslint-disable global-require */
</script>
    
<style lang="scss">
@import '~@resources/scss/vue/pages/page-pricing.scss';
</style>
<style scoped>
.pt-10 {
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
    
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
                {{ topay }}
              </td>
            </tr>


          </table>
          <!-- 
      <b-col md="12" class="text-center">
            <div id="coupon" class="cart-bord" style="padding:20px 0px;">

              <b-input-group class="input-group-merge coupons">
                  <b-form-input placeholder="Enter Coupon Code" id="plan_coupon_value" style="height:50px;text-transform:uppercase"/>
                  <b-input-group-append is-text >
                    <b-button variant="primary" id="applied_coupon_identify2" @click="applycoupon()" class="text-yellow mborderw">
                      {{$t("Apply")}}
                        </b-button>
                
                  </b-input-group-append>
                </b-input-group>
                <input type="hidden" id="applied_coupon_Amount">
            </div>
          </b-col> -->

          <b-col cols="12">
            <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" block class="mt-2" variant="outline-success"
              @click="pay()">
              {{ $t("Pay Now") }}

            </b-button>
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
    BImgLazy, BInputGroup, BInputGroupAppend, BFormInput
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      status: 'monthly',
      monthlyPlanShow: true,
      pricing: {},
      plan: {},
      current_plan: 0,
      topay: 0,
      applied_coupon: 0
    }
  },
  created() {
    this.$http.get('/store/get_plan_details/' + this.$route.params.id)
      .then(res => {
        console.log(res)
        this.plan = res.data.plan
        this.topay = this.plan.price
      })

  },
  methods: {
    pay() {
      let data = new FormData();
      data.append('plan', this.$route.params.id);
      data.append('restaurant_id', localStorage.id);
      data.append('topay', this.topay);
      data.append('applied_coupon', this.applied_coupon);

      this.$http.post('/store/pay_for_plan', data)
        .then(res => {
          console.log(res)
          if (res.data.status == true) {

            if (res.data.clientsecret == 0) {

            } else {
              this.$router.push({ name: "restaurant_checkout", params: { plan: res.data.plan, trans: res.data.trans, id: res.data.clientsecret } })
            }

          } else {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: res.data.message,
                icon: 'BellIcon',
                variant: 'danger',
              },
            })
          }
        })

    },
    applycoupon() {
      let plan = this.$route.params.id;
      let user_choosen_coupon = document.getElementById("plan_coupon_value").value.toUpperCase();
      let coup = ''
      this.$http.get('/get_coupons')
        .then(res => {
          var coupons = res.data.plan_coupon
          for (var i = 0; i < coupons.length; i++) {
            if (coupons[i].code == user_choosen_coupon) {
              coup = coupons[i]
              if (plan == coup.plan) {
                this.$http.get('/get_plan_byid/' + plan)
                  .then(res => {
                    var plan_details = res.data.plan
                    if (coup.type == 1) {
                      let discount_value = ((plan_details.price / 100) * coup.value);
                      this.topay = plan_details.price - discount_value
                      this.applied_coupon = coup.id
                      document.getElementById("plan_coupon_value").disabled = true;

                      document.getElementById("applied_coupon_identify2").style.display = "block";
                      this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: "Coupon Applied",
                          icon: 'BellIcon',
                          variant: 'Success',
                        },
                      })
                    } else {
                      let discount_value = (plan_details.price - coup.value);
                      this.topay = plan_details.price - discount_value
                      this.applied_coupon = coup.id
                      document.getElementById("applied_coupon_identify2").style.display = "block";
                      document.getElementById("plan_coupon_value").disabled = true;
                      this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: "Coupon Applied",
                          icon: 'BellIcon',
                          variant: 'Success',
                        },
                      })
                    }
                  })

              } else {
                coup = ''
              }
            }
          }
          if (coup == '') {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: "Invalid Coupon",
                icon: 'BellIcon',
                variant: 'danger',
              },
            })
          }

        })

    },
  },
}
  /* eslint-disable global-require */
</script>
  
<style lang="scss">
@import '~@resources/scss/vue/pages/page-pricing.scss';
</style>
  
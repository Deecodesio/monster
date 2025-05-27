<template>
  <section id="pricing-plan">


    <!-- pricing plan cards -->
    <b-row class="pricing-card">
      <b-col offset-sm-2 sm="10" md="12" offset-lg="2" lg="10" class="mx-auto">
        <b-row>
          <b-col md="4" v-for="(data1, index1) in plans" :key="index1">
            <b-card align="center">
              <b-img-lazy src="/plan.svg" class="mb-2 mt-5" alt="basic svg img" />
              <h3>{{ data1.name }}</h3>
              <b-card-text>
                {{ data1.description }}
              </b-card-text>

              <!-- annual plan -->
              <div class="annual-plan">
                <div class="plan-price mt-2">
                  <sup class="font-medium-1 font-weight-bold text-primary">{{ setting.value }}</sup>
                  <span class="pricing-basic-value font-weight-bolder text-primary">{{ data1.price }}</span>
                  <sub class="pricing-duration text-body font-medium-1 font-weight-bold" v-if="data1.lifetime == 1">/
                    {{ $t("Lifetime") }}</sub>
                  <sub class="pricing-duration text-body font-medium-1 font-weight-bold" v-if="data1.lifetime != 1">/ {{
                    data1.validity }} {{ $t("days") }}</sub>
                </div>

              </div>
              <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" block class="mt-2" variant="outline-success"
                :id="'sel' + data1.id" @click="select_plan(data1.id)">
                {{ $t("Upgrade") }}

              </b-button>

            </b-card>
          </b-col>
          <input type="hidden" id="selected_plan">


        </b-row>
      </b-col>
    </b-row>
    <!--/ pricing plan cards -->




  </section>
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
  BImgLazy
} from 'bootstrap-vue'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
import Ripple from 'vue-ripple-directive'
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
    BImgLazy
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      status: 'monthly',
      monthlyPlanShow: true,
      pricing: {},
      plans: [],
      current_plan: 0,
      setting: {},
      upgradable: false

    }
  },
  created() {
    this.$http.get('/plans')
      .then(res => {
        this.plans = res.data.plans

      })

    this.$http.get('/store/current_plan/' + localStorage.id)
      .then(res => {
        console.log(res)
        this.current_plan = res.data.plan.plan
        var elms2 = document.getElementById('sel' + this.current_plan);
        elms2.innerHTML = "Current Plan"

        if (res.data.plan_details.lifetime != 1) {
          this.upgradable = true
        }

      })
    this.$http.get('/admin/get_currency')
      .then(res => {

        this.setting = res.data

      })
  },
  methods: {
    select_plan(id) {

      this.$router.push({ name: "plan_details", params: { id: id } })

      //         this.$http.get('/store/get_plan_details/' +id)
      //    .then(res => {
      //     console.log(res)


      //     })
    },
  },
}
  /* eslint-disable global-require */
</script>
  
<style lang="scss">
@import '~@resources/scss/vue/pages/page-pricing.scss';
</style>
  
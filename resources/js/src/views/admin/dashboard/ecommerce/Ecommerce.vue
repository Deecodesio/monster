<template>
  <section id="dashboard-ecommerce">
    <b-row class="match-height">
      <!-- <b-col
        xl="4"
        md="6"
      >
        <ecommerce-medal :data="data.congratulations" />
      </b-col> -->
      <b-col
        xl="12"
        md="12"
      >
        <ecommerce-statistics :data="data.statisticsItems" />
      </b-col>
    </b-row>

    <b-row class="match-height">
      <b-col lg="4">
        <b-row class="match-height">
         
          <b-col
            lg="6"
            md="3"
            cols="6"
          >
            <ecommerce-order-chart :data="data.statisticsOrder" />
          </b-col>
       
           <b-col
            lg="6"
            md="3"
            cols="6"
          >
            <ecommerce-profit-chart :data="data.statisticsProfit" />
          </b-col>
         <b-col
            lg="12"
            md="6"
          >
            <ecommerce-earnings-chart :data="data.earningsChart" />
          </b-col>
        </b-row>
      </b-col>

    
      <b-col lg="8">
        <ecommerce-revenue-report :data="data.revenue" />
      </b-col>
     
    </b-row>

    <b-row class="match-height">
      
       <!--<b-col lg="8">
        <ecommerce-company-table :table-data="data.companyTable" />
      </b-col>
    
      <b-col
        lg="4"
        md="6"
      >
        <ecommerce-meetup :data="data.meetup" />
      </b-col>
      
      <b-col
        lg="4"
        md="6"
      >
        <ecommerce-browser-states />
      </b-col>
   
      <b-col
        lg="4"
        md="6"
      >
        <ecommerce-goal-overview :data="data.goalOverview" />
      </b-col>-->
     
      <b-col
        lg="4"
        md="6"
      >
        <ecommerce-transactions :data="data.transactionData" />
      </b-col>
      <b-col
        lg="4"
        md="6"
      >
        <top_restaurants :data="data.top_rest" />
      </b-col>
      <b-col
        lg="4"
        md="6"
      >
        <top_users :data="data.top_users" />
      </b-col>
    </b-row> 
  </section>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import { getUserData } from '@/auth/utils'
import EcommerceMedal from './EcommerceMedal.vue'
import EcommerceStatistics from './EcommerceStatistics.vue'
import EcommerceRevenueReport from './EcommerceRevenueReport.vue'
import EcommerceOrderChart from './EcommerceOrderChart.vue'
import EcommerceProfitChart from './EcommerceProfitChart.vue'
import EcommerceEarningsChart from './EcommerceEarningsChart.vue'
import EcommerceCompanyTable from './EcommerceCompanyTable.vue'
import EcommerceMeetup from './EcommerceMeetup.vue'
import EcommerceBrowserStates from './EcommerceBrowserStates.vue'
import EcommerceGoalOverview from './EcommerceGoalOverview.vue'
import EcommerceTransactions from './EcommerceTransactions.vue'
import top_restaurants from './top_restaurants.vue'
import top_users from './top_users.vue'

export default {
  components: {
    BRow,
    BCol,

    EcommerceMedal,
    EcommerceStatistics,
    EcommerceRevenueReport,
    EcommerceOrderChart,
    EcommerceProfitChart,
    EcommerceEarningsChart,
    EcommerceCompanyTable,
    EcommerceMeetup,
    EcommerceBrowserStates,
    EcommerceGoalOverview,
    EcommerceTransactions,
    top_restaurants,
    top_users,
  },
  data() {
    return {
      data: {},
    }
  },
  created() {

    // if(localStorage.getItem('role') != 1){
    //   this.$router.push({ name: "misc-not-authorized" })
    // }
    // data
    // /ecommerce/data
    // /admin/dashboard_statistics
    let mail = new FormData();
    mail.append('email', localStorage.admin_email);
    this.$http.post('/admin/dashboard_statistics',mail).then(response => {
      this.data = response.data
      console.log(this.data);
      // ? Your admin will return name of logged in user or you might just directly get name of logged in user
      // ? This is just for demo purpose
      const userData = getUserData()
      this.data.congratulations.name = userData.fullName.split(' ')[0] || userData.username
    })
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/dashboard-ecommerce.scss';
@import '~@resources/scss/vue/libs/chart-apex.scss';
</style>

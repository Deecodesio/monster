<template>
  <section id="dashboard-analytics">
    <b-row class="match-height">
     
      <b-col
        xl="12"
        md="12"
      >
        <ecommerce-statistics :data="data.statisticsItems" />
      </b-col>
    </b-row>
    <b-row class="match-height">
      <!-- <b-col
        lg="6"
        md="12"
      >
        <analytics-congratulation :data="data.congratulations" />
      </b-col> -->
      <!-- <b-col
        lg="3"
        sm="6"
      >
        <statistic-card-with-area-chart
          v-if="data.subscribersGained"
          icon="UsersIcon"
          :statistic="kFormatter(data.subscribersGained.analyticsData.subscribers)"
          statistic-title="Subscribers Gained"
          :chart-data="data.subscribersGained.series"
        />
      </b-col> -->
      <!-- <b-col
        lg="3"
        sm="6"
      >
        <statistic-card-with-area-chart
          v-if="data.ordersRecevied"
          icon="PackageIcon"
          color="warning"
          :statistic="kFormatter(data.ordersRecevied.analyticsData.orders)"
          statistic-title="Orders Received"
          :chart-data="data.ordersRecevied.series"
        />
      </b-col> -->
    </b-row>

    <b-row class="match-height">
      <!-- <b-col lg="6">
        <analytics-avg-sessions :data="data.avgSessions" />
      </b-col> -->
      <b-col lg="6">
        <analytics-support-tracker :data="data.supportTracker" />
      </b-col>
      <b-col
        lg="4"
      >
        <ecommerce-transactions :data="data.transactionData" />
      </b-col>
    </b-row>
<!-- 
    <b-row class="match-height">
      <b-col lg="4">
        <analytics-timeline :data="data.timeline" />
      </b-col>
      <b-col lg="4">
        <analytics-sales-radar-chart :data="data.salesChart" />
      </b-col>
      <b-col lg="4">
        <analytics-app-design :data="data.appDesign" />
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12">
        <invoice-list />
      </b-col>
    </b-row> -->
  </section>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'

import StatisticCardWithAreaChart from '@@core/components/statistics-cards/StatisticCardWithAreaChart.vue'
import { kFormatter } from '@@core/utils/filter'
import InvoiceList from '@@/views/apps/invoice/invoice-list/InvoiceList.vue'
import AnalyticsCongratulation from './AnalyticsCongratulation.vue'
import AnalyticsAvgSessions from './AnalyticsAvgSessions.vue'
import AnalyticsSupportTracker from './AnalyticsSupportTracker.vue'
import AnalyticsTimeline from './AnalyticsTimeline.vue'
import AnalyticsSalesRadarChart from './AnalyticsSalesRadarChart.vue'
import AnalyticsAppDesign from './AnalyticsAppDesign.vue'
import EcommerceStatistics from './EcommerceStatistics.vue'
import EcommerceTransactions from './EcommerceTransactions.vue'


export default {
  components: {
    BRow,
    BCol,
    AnalyticsCongratulation,
    AnalyticsAvgSessions,
    StatisticCardWithAreaChart,
    AnalyticsSupportTracker,
    AnalyticsTimeline,
    AnalyticsSalesRadarChart,
    AnalyticsAppDesign,
    InvoiceList,
    EcommerceStatistics,
    EcommerceTransactions,

  },
  data() {
    return {
      data: {},
    }
  },
  created() {
    // /analytics/data
    // /api/dashboard_statistics
    this.$http.get('/store/order_dashboard_statistics')
      .then(response => { 
        this.data = response.data
          console.log(this.data);
        })
  },
  methods: {
    kFormatter,
  },
}
</script>

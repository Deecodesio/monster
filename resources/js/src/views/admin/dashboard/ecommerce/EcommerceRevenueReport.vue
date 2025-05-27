<!-- <template>
  <b-card v-if="data" no-body class="card-revenue-budget">
    <b-row class="match-height">
      <b-col md="12" class="revenue-report-wrapper">
        <div class="d-sm-flex justify-content-between align-items-center mb-3">
          <h4 class="card-title mb-50 mb-sm-0">
            {{ $t('order') }} {{ $t('report') }}
          </h4>
          <div class="d-flex align-items-center">
            <div class="d-flex align-items-center mr-2">
              <span class="bullet bullet-primary svg-font-small-3 mr-50 cursor-pointer" />
              <span>{{ $t('completed') }}</span>
            </div>
            <div class="d-flex align-items-center ml-75">
              <span class="bullet bullet-warning svg-font-small-3 mr-50 cursor-pointer" />
              <span>{{ $t('cancelled') }}</span>
            </div>
          </div>
        </div>
        <vue-apex-charts id="revenue-report-chart" type="bar" height="230" :options="revenueReport.chartOptions"
          :series="data[0].revenueReport[0].series" />
      </b-col>

    </b-row>
  </b-card>
</template>

<script>
import {
  BCard, BRow, BCol, BDropdown, BDropdownItem, BButton,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import { $themeColors } from '@themeConfig'
import Ripple from 'vue-ripple-directive'

export default {
  components: {
    VueApexCharts,
    BDropdown,
    BDropdownItem,
    BCard,
    BButton,
    BRow,
    BCol,
  },
  directives: {
    Ripple,
  },
  props: {
    data: {
      type: Object,
      default: () => { },
    },
  },
  data() {
    return {
      revenue_report: {},
      revenueReport: {
        chartOptions: {
          chart: {
            stacked: true,
            type: 'bar',
            toolbar: { show: false },
          },
          grid: {
            padding: {
              top: -20,
              bottom: -10,
            },
            yaxis: {
              lines: { show: false },
            },
          },
          xaxis: {
            categories: [this.$t('Jan'), this.$t('Feb'), this.$t('Mar'), this.$t('Apr'),this.$t('May') , this.$t('Jun'), this.$t('Jul'),this.$t('Aug') ,this.$t('Sep') ,this.$t('oct') ],
            labels: {
              style: {
                colors: '#6E6B7B',
                fontSize: '0.86rem',
                fontFamily: 'Montserrat',
              },
            },
            axisTicks: {
              show: false,
            },
            axisBorder: {
              show: false,
            },
          },
          legend: {
            show: false,
          },
          dataLabels: {
            enabled: false,
          },
          colors: [$themeColors.primary, $themeColors.warning],
          plotOptions: {
            bar: {
              columnWidth: '17%',
              endingShape: 'rounded',
            },
            distributed: true,
          },
          yaxis: {
            labels: {
              style: {
                colors: '#6E6B7B',
                fontSize: '0.86rem',
                fontFamily: 'Montserrat',
              },
            },
          },
        },
      },
      budgetChart: {
        options: {
          chart: {
            height: 80,
            toolbar: { show: false },
            zoom: { enabled: false },
            type: 'line',
            sparkline: { enabled: true },
          },
          stroke: {
            curve: 'smooth',
            dashArray: [0, 5],
            width: [2],
          },
          colors: [$themeColors.primary, '#dcdae3'],
          tooltip: {
            enabled: false,
          },
        },
      },
    }
  },
}
</script> -->

<template>
  <b-card no-body>
    <b-card-header>
      <div>
        <b-card-title class="mb-1">
          {{ $t('order') }} {{ $t('report') }}
        </b-card-title>
      </div>
    </b-card-header>

    <b-card-body>
      <vue-apex-charts type="area" height="400" :options="data.chartOptions" :series="data.series" />
    </b-card-body>
  </b-card>
</template>

<script>
import {
  BCard, BCardHeader, BCardBody, BCardTitle, BCardSubTitle,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import flatPickr from 'vue-flatpickr-component'
import apexChatData from './apexChartData'

export default {
  components: {
    BCard,
    VueApexCharts,
    BCardHeader,
    BCardBody,
    BCardTitle,
    BCardSubTitle,
    flatPickr,
  },
  props: {
    data: {
      type: Object,
      default: () => { },
    },
  },
  data() {
    return {
      apexChatData,
      rangePicker: ['2019-05-01', '2019-05-10'],
    }
  },
}
</script>

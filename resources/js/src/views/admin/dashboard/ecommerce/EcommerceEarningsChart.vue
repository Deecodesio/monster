<!-- <template>
  <b-card
    v-if="data"
    class="earnings-card"
  >
    <b-row>
      <b-col cols="6">
        <b-card-title class="mb-1">
          {{$t('earnings')}}
        </b-card-title>
        <div class="font-small-2">
          {{$t('this month')}}
        </div>
        <h5 class="mb-1">
         {{data[0].earnings}}
        </h5>
        <b-card-text class="text-muted font-small-2">
          <span class="font-weight-bolder"> {{data[0].per}}%</span><span>  {{data[0].text}}</span>
        </b-card-text>
      </b-col>
      <b-col cols="6">
     
        <vue-apex-charts
          height="120"
          :options="earningsChart.chartOptions"
          :series="data[0].series"
        />
      </b-col>
    </b-row>
  </b-card>
</template>

<script>
import {
  BCard, BRow, BCol, BCardTitle, BCardText,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import { $themeColors } from '@themeConfig'

const $earningsStrokeColor2 = '#28c76f66'
const $earningsStrokeColor3 = '#28c76f33'
export default {
  components: {
    BCard,
    BRow,
    BCol,
    BCardTitle,
    BCardText,
    VueApexCharts,
  },
  props: {
    data: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      earningsChart: {
        chartOptions: {
          chart: {
            type: 'donut',
            toolbar: {
              show: false,
            },
          },
          dataLabels: {
            enabled: false,
          },
          legend: { show: false },
          comparedResult: [2, -3, 8],
          labels: ['Delivered', 'Cancelled', 'Failed'],
          stroke: { width: 0 },
          colors: [$earningsStrokeColor2, $earningsStrokeColor3, $themeColors.success],
          grid: {
            padding: {
              right: -20,
              bottom: -8,
              left: -20,
            },
          },
          plotOptions: {
            pie: {
              startAngle: -10,
              donut: {
                labels: {
                  show: true,
                  name: {
                    offsetY: 15,
                  },
                  value: {
                    offsetY: -15,
                    formatter(val) {
                      // eslint-disable-next-line radix
                      return `${parseInt(val)}%`
                    },
                  },
                  total: {
                    show: true,
                    offsetY: 15,
                    label: 'Delivered',
                    formatter() {
                      return '61.29%'
                    },
                  },
                },
              },
            },
          },
          responsive: [
            {
              breakpoint: 1325,
              options: {
                chart: {
                  height: 100,
                },
              },
            },
            {
              breakpoint: 1200,
              options: {
                chart: {
                  height: 120,
                },
              },
            },
            {
              breakpoint: 1045,
              options: {
                chart: {
                  height: 100,
                },
              },
            },
            {
              breakpoint: 992,
              options: {
                chart: {
                  height: 120,
                },
              },
            },
          ],
        },
      },
    }
  },
}
</script> -->
<template>
  <b-card no-body>


    <!-- {{ data }} -->
    <!-- {{ apexChatData.radialBarChart }} -->
    <b-card-body>

      <b-row>
        <b-col cols="6">
          <b-card-title class="mb-1">
            {{ $t('earnings') }}
          </b-card-title>
          <div class="font-small-2">
            {{ $t('this month') }}
          </div>
          <h5 class="mb-1">
            {{ data[0].earnings }}
          </h5>
          <b-card-text class="text-muted font-small-2">
            <span class="font-weight-bolder"> {{ data[0].per }}%</span><span> {{ data[0].text }}</span>
          </b-card-text>
        </b-col>
        <b-col cols="6">
          <vue-apex-charts type="radialBar" height="300" :options="data[0].chartOptions" :series="data[0].series" />
        </b-col>
      </b-row>

    </b-card-body>
  </b-card>
</template>

<script>
import {
  BCard, BCardTitle, BCardBody, BCardHeader,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import apexChatData from './apexChartData'

export default {
  components: {
    VueApexCharts,
    BCard,
    BCardTitle,
    BCardBody,
    BCardHeader,
  },
  data() {
    return {
      apexChatData,
    }
  },
  props: {
    data: {
      type: Object,
      default: () => { },
    },
  },
}
</script>

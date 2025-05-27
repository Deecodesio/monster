<template>
  <b-row>
    <b-col cols="12">
      <b-card>


        <b-row>
          <b-col md="6">
            <div class="custom-search d-flex justify-content-start">
              <b-button variant="primary" :to="{ name: 'add_table' }">
                <i class="fa-solid fa-plus"></i> {{ $t('add') }} {{ $t('table') }}
              </b-button>
            </div>

          </b-col>
          <b-col md="6">

            <!-- input search -->
            <div class="custom-search d-flex justify-content-end">
              <b-form-group>
                <div class="d-flex align-items-center">
                  <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                  <b-form-input v-model="searchTerm" placeholder="Search" type="text" class="d-inline-block" />
                </div>
              </b-form-group>
            </div>
            <!-- input search -->

          </b-col>
        </b-row>


        <!-- table -->
        <vue-good-table :columns="columns" :line-numbers="true" :rows="rows" :rtl="direction" :search-options="{
          enabled: true,
          externalQuery: searchTerm
        }" :pagination-options="{
  enabled: true,
  perPage: pageLength
}">
          <template slot="table-row" slot-scope="props">


            <!-- Column: Action -->
            <span v-if="props.column.field === 'action'">
              <a id="link" target="_blank" v-bind:href="'/store/print_table/' + props.row.id">
                <feather-icon :id="'link_' + props.row.id" icon="LinkIcon" size="16" class="mx-1" cursor="pointer" />
              </a>

              <b-tooltip :title="$t('link')" :target="'link_' + props.row.id" placement="left"/>

            </span>

            <!-- Column: Common -->
            <span v-else>
              {{ props.formattedRow[props.column.field] }}
            </span>
          </template>

          <!-- pagination -->
          <template slot="pagination-bottom" slot-scope="props">
            <div class="d-flex justify-content-between flex-wrap">
              <div class="d-flex align-items-center mb-0 mt-1">
                <span class="text-nowrap">
                  {{ $t('message.pagelength') }}
                </span>
                <b-form-select v-model="pageLength" :options="['10', '50', '100']" class="mx-1"
                  @input="(value) => props.perPageChanged({ currentPerPage: value })" />
                <span class="text-nowrap "> {{ $t('message.of') }} {{ props.total }} {{ $t('message.pageText2') }}
                </span>
              </div>
              <div>
                <b-pagination :value="1" :total-rows="props.total" :per-page="pageLength" first-number last-number
                  align="right" prev-class="prev-item" next-class="next-item" class="mt-1 mb-0"
                  @input="(value) => props.pageChanged({ currentPage: value })">
                  <template #prev-text>
                    <feather-icon icon="ChevronLeftIcon" size="18" />
                  </template>
                  <template #next-text>
                    <feather-icon icon="ChevronRightIcon" size="18" />
                  </template>
                </b-pagination>
              </div>
            </div>
          </template>
          <div slot="emptystate" style="text-align: center;" v-if="Loading">

            <b-img src="/admin_loader.svg" fluid alt="Scan" />

          </div>
        </vue-good-table>


      </b-card>

    </b-col>
  </b-row>
</template>
  
<script>
import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
  BImg, BMedia, BAvatar, BBadge, BButton, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BCard, BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'

export default {
  components: {
    BRow,
    BCol,

    VueGoodTable,
    BAvatar,
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BCard,
    BTooltip,
    BImg, BMedia, BButton,
  },
  data() {
    return {
      user_info: {},
      pageLength: 10,
      dir: false,
      Loading: true,
      setting: {},
      columns: [


        {
          label: this.$t('table') + ' ' + this.$t('name'),
          field: 'table_name',

        },

        {
          label: this.$t('action'),
          field: 'action',
        },
      ],
      rows: [],
      searchTerm: '',
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {

        1: 'light-primary',
        2: 'light-success',
        3: 'light-danger',
        4: 'light-warning',
        5: 'light-info',

      }

      return status => statusColor[status]
    },

    direction() {
      if (store.state.appConfig.isRTL) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.dir = true
        return this.dir
      }
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.dir = false
      return this.dir
    },
  },
  created() {
    this.user_info.id = localStorage.id;
    this.$http.post('/store/tables', this.user_info)
      .then(res => {
        this.rows = res.data;

        this.Loading = false;
      })


  },
  methods: {
    code(id) {

      let data = new FormData();
      data.append('id', localStorage.id);
      this.$http.post('/store/print_table', data)
        .then(res => {
          if (res.data.status == true) {
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: res.data.message,
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
            this.user_info.id = localStorage.id;
            this.$http.post('/store/product_list', this.user_info)
              .then(res => {
                this.rows = res.data.data
                //   console.log(this.rows);
              })
          }
          else {
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: res.data.message,
                icon: 'CheckIcon',
                variant: 'danger',
              },
            })
          }
        })

    },


  }
}
</script>
  
<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>
  
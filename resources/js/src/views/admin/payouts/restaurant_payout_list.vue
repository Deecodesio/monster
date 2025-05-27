<template>
  <b-row>
    <b-col cols="12">
      <b-card>

        <!-- input search -->
        <div class="custom-search d-flex justify-content-end">
          <b-form-group>
            <div class="d-flex align-items-center">
              <label class="mr-1">{{ $t('message.seachLabel') }}</label>
              <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text" class="d-inline-block" />
            </div>
          </b-form-group>
        </div>

        <!-- table -->
        <vue-good-table :columns="columns" :line-numbers="true" :rows="rows" :rtl="direction" :search-options="{
          enabled: true,
          externalQuery: searchTerm
        }" :pagination-options="{
  enabled: true,
  perPage: pageLength
}">
          <template slot="table-row" slot-scope="props">

            <!-- Column: Name -->
            <div v-if="props.column.field === 'pending_payouts'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.pending_payout }}</span>
            </div>
            <div v-if="props.column.field === 'contacts'" class="text-nowrap">

            <span class="text-nowrap">+{{ props.row.contact_country_calling_code }}{{ props.row.contact }}</span>
            </div>

            <div v-if="props.column.field === 'order_id'" class="text-nowrap">
              <span class="text-nowrap">{{ props.row.order_id }}</span>
              <br>
              <span class="text-nowrap">
                <b-badge :variant="statusVariant(props.row.status)">
                  {{ props.row.status }}
                </b-badge>
              </span>
            </div>

            <!-- Column: Status -->
            <!-- <span v-else-if="props.column.field === 'status'">
          <b-badge :variant="statusVariant(props.row.status)">
            {{ props.row.status }}
          </b-badge>
        </span> -->
            <span v-else-if="props.column.field === 'status'">
              <span v-if="props.row.status === 1">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('success') }}
                </b-badge>

              </span>
              <span v-else>

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('failed') }}
                </b-badge>

              </span>

            </span>

            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">
              <span>
                <b-button v-b-modal.modal-login variant="outline-primary"
                  @click="sendInfo(props.row.pending_payout, props.row.id)">
                  {{ $t('make') }} {{ $t('payment') }}
                </b-button>
              </span>
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
    <!-- modal login-->
    <b-modal ref="my-modal" id="modal-login" cancel-variant="outline-secondary" :ok-title="$t('pay') + ' ' + $t('now')"
      cancel-title="Close" centered :title="$t('make') + ' ' + $t('payment')" @show="resetModal" @hidden="resetModal"
      @ok="reason_add">
      <b-card-text>
        {{ $t('pending amount') }} : {{ topay }}
      </b-card-text>
      <form ref="form" @submit.prevent="reason_add">
        <b-form-group>

          <label for="email">{{ $t('total') }} :</label>
          <b-form-input id="email" type="text" :placeholder="$t('total')" v-model="banner.amount" />
        </b-form-group>
        <b-form-group>
          <label for="password">{{ $t('notes') }}</label>
          <b-form-input type="text" :placeholder="$t('notes')" v-model="banner.notes" />
        </b-form-group>
        <b-col>
          <!-- <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            class="mr-1"
          >
           Save
          </b-button>
          -->
        </b-col>

      </form>
    </b-modal>
  </b-row>

</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
  BImg, BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BCard, BButton, BForm, BModal,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'

import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { codeColumnSearch } from './code'

export default {
  components: {
    BRow,
    BCol,
    BCardCode,
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
    BButton,
    BModal,
    BForm, BImg, BMedia,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      banner: {},
      pageLength: 10,
      dir: false,
      Loading: true,
      codeColumnSearch,
      columns: [
        {
          label: this.$t('Store'),
          field: 'restaurant_name',

        },

        {
          label: this.$t('phone') + ' ' + this.$t('number'),
          field: 'contacts',

        },
        {
          label: this.$t('total') + ' ' + this.$t('orders'),
          field: 'foodrequest_count',

        },
        {
          label: this.$t('total') + ' ' + this.$t('to') + ' ' + this.$t('be') + ' ' + this.$t('paid'),
          field: 'pending_payouts',

        },

        {
          label: this.$t('action'),
          field: 'action',
        },
      ],
      rows: [],
      searchTerm: '',
      topay: '',
      rest_id: '',
      setting: {},
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

    this.$http.get('/admin/payout/restaurant')
      .then(res => {
        this.rows = res.data;
        this.Loading = false;
        if(process.env.MIX_IS_DEMO){
          for(let i=0;i<this.rows.length;i++){
          this.rows[i].phone = "**********"
          this.rows[i].email = "**********"
          this.rows[i].contact = "**********"

        }
        }
      })

    this.$http.get('/admin/get_currency')
      .then(res => {

        this.setting = res.data


      })
  },
  methods: {
    sendInfo(item, id) {
      this.topay = item;
      this.rest_id = id
    },
    reason_add() {
      this.banner.id = this.banner.id
      this.banner.amount = this.banner.amount
      this.banner.description = this.banner.notes
      this.banner.type = "restaurant"
      this.banner.id = this.rest_id





      this.$http
        .post("/admin/add_payout", this.banner)
        .then(
          (response => {

            if (response.data.status == true) {
              this.notes = ''
              this.amount = ''
              this.$refs['my-modal'].hide()
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: response.data.message,
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
              this.$http.get('/admin/payout/restaurant')
                .then(res => {
                  this.rows = res.data
                })
            } else {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: response.data.message,
                  icon: 'CoffeeIcon',
                  variant: 'failure',
                },
              })
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    resetModal() {
      this.banner.notes = ''
      this.banner.amount = ''
    },
  }
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>

<template>
  <b-row>
    <b-col cols="12">
      <b-card>

        <!-- input search -->
        <div class="custom-search d-flex justify-content-end">
          <b-form-group>
            <div class="d-flex align-items-center">
              <label class="mr-1">{{ $t('message.seachLabel') }}</label>
              <b-form-input v-model="searchTerm" placeholder="Search" type="text" class="d-inline-block" />
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
            <div v-if="props.column.field === 'user_names'" class="text-nowrap">
              <span class="text-nowrap" v-if="props.row.device_type == 'android'"><i :id="'andriod' + props.row.id"
                  class="fa-brands fa-android" aria-hidden="true"></i> {{ props.row.user_name }}</span>
              <span class="text-nowrap" v-else-if="props.row.device_type == 'ios'"><i :id="'ios' + props.row.id"
                  class="fa-brands fa-apple" aria-hidden="true"></i> {{ props.row.user_name }}</span>
              <span class="text-nowrap" v-else><i :id="'web' + props.row.id" class="fa-solid fa-desktop"
                  aria-hidden="true"></i> {{ props.row.user_name }}</span>
              <br>
              <span class="text-nowrap">{{ props.row.phone }}</span>
              <b-tooltip v-b-tooltip.hover.v-warning title="Android" :target="'andriod' + props.row.id" />
              <b-tooltip v-b-tooltip.hover.v-warning title="IOS" :target="'ios' + props.row.id" />
              <b-tooltip v-b-tooltip.hover.v-warning title="WEB" :target="'web' + props.row.id" />
            </div>
            <span v-else-if="props.column.field === 'order_status'">

              <b-button v-if="props.row.status == 0" type="submit" variant="outline-primary" class="mr-1"
                @click="Assign(props.row.id)" style="margin-bottom: 2%;">
                {{ $t('accept') }}
              </b-button>
              <b-button v-if="props.row.status == 0" type="submit" variant="outline-danger" class="mr-1"
                @click="Cancel(props.row.id);" style="margin-bottom: 2%;">
                {{ $t('cancel') }}
              </b-button>
              <b-button v-if="props.row.status == 0 && props.row.ScheduledAt == null" type="submit"
                variant="outline-success" class="mr-1" @click="setpop(props.row.id)">
                {{ $t('ready') }} {{ $t('to') }} {{ $t('pickup') }}
              </b-button>




            </span>
            <!-- <div
          v-if="props.column.field === 'delivery_type'"
          class="text-nowrap"
        >
         <span v-if="props.row.delivery_type === 1">
                <b-badge :variant="statusVariant(props.row.status)">
           Delivery
          </b-badge>
          </span>
          <span v-else>
                 <b-badge :variant="statusVariant(props.row.status)">
            Takeaway
          </b-badge>
          </span>
        </div> -->
            <div v-if="props.column.field === 'bill_amounts'" class="text-nowrap">
              <span class="text-nowrap "> {{ setting.value }} {{ props.row.bill_amount }}</span>

              <br>

            </div>
            <div v-if="props.column.field === 'offer_discounts'" class="text-nowrap">
              <span class="text-nowrap "> {{ setting.value }} {{ props.row.offer_discount }}</span>

              <br>

            </div>
            <div v-if="props.column.field === 'wallet'" class="text-nowrap">
              <span class="text-nowrap "> {{ setting.value }} {{ props.row.wallet_amounts }}</span>

              <br>

            </div>
            <div v-if="props.column.field === 'order_id'" class="text-nowrap">
              <span class="text-nowrap font-weight-bold" v-if="props.row.delivery_type === 1"><i
                  :id="'delivery' + props.row.id" class="fa fa-motorcycle" aria-hidden="true"></i> {{ props.row.order_id
                  }}</span>
              <span class="text-nowrap font-weight-bold" v-else><i :id="'takeway' + props.row.id"
                  class="fa-solid fa-person-walking" aria-hidden="true"></i> {{ props.row.order_id }}</span>
              <br>
              <b-tooltip v-b-tooltip.hover.v-warning :title="$t('delivery')" :target="'delivery' + props.row.id" />
              <b-tooltip v-b-tooltip.hover.v-warning t :title="$t('takeaway')" :target="'takeway' + props.row.id" />
            </div>


            <span v-else-if="props.column.field === 'delivery_type'">
              <!-- <b-badge :variant="statusVariant(props.row.is_cod)">
            {{ props.row.is_cod }}
          </b-badge> -->

              <span v-if="props.row.delivery_type === 1">
                <b-badge :variant="statusVariant(props.row.delivery_type)">
                  {{ $t('delivery') }}
                </b-badge>
              </span>
              <span v-else>
                <b-badge :variant="statusVariant(props.row.delivery_type)">
                  {{ $t('takeaway') }}
                </b-badge>
              </span>
            </span>


            <!-- Column: Status -->
            <span v-else-if="props.column.field === 'is_cod'">
              <!-- <b-badge :variant="statusVariant(props.row.is_cod)">
            {{ props.row.is_cod }}
          </b-badge> -->

              <span v-if="props.row.is_cod === 1">
                <b-badge :variant="statusVariant(props.row.is_cod)">
                  {{ $t('cod') }}
                </b-badge>
              </span>
              <span v-else>
                <b-badge :variant="statusVariant(props.row.is_cod)">
                  {{ $t('online_payment') }}
                </b-badge>
              </span>
            </span>
            <span v-else-if="props.column.field === 'view'">
              <feather-icon :id="`invoice-row-${props.row.is_cod}-preview-icon`" icon="EyeIcon" size="16" class="mx-1"
                @click="$router.push({ name: 'view_order', params: { id: props.row.id } })" cursor="pointer" />
              <b-tooltip :title="$t('view') + '' + $t('order')" placement="left"
                :target="`invoice-row-${props.row.is_cod}-preview-icon`" />
            </span>
            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">

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

          <div slot="loadingContent" style="text-align: center;" v-if="Loading">

            <b-img src="/admin_loader.svg" fluid alt="Scan" />

          </div>

        </vue-good-table>


      </b-card>

      <b-modal ref="my-modal" id="modal-code" ok-title="Submit" centered
        :title="$t('Are you sure you want to cancel this order?')" @show="resetModal" @hidden="resetModal"
        @ok="Assign_add">

        <form ref="form" @submit.prevent="Assign_add">
          <b-form-group :label="$t('reason') + ':'">

            <v-select v-model="user_info.reson" :options="this.reson" label="reason" :reduce="sel => sel.reason"
              :placeholder="$t('cancel') + ' ' + $t('reason')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
            <b-form-input type="text" id="reson_id" v-model="user_info.order_id" hidden="hidden" />
          </b-form-group>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
            id="myfunction" hidden="hidden" @click="myFunction">

          </b-button>
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

    </b-col>
  </b-row>
</template>

<script>

import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@@core/components/b-card-code/BCardCode.vue'
import {
  VBTooltip, BModal, VBModal, BAlert, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
  BDropdown, BDropdownItem, BButton, BCard, BTooltip, BImg, BMedia,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@@/store/index'
import { codeColumnSearch } from './code'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'


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
    BButton,
    BCard,
    BTooltip,
    BModal,
    VBModal,
    BAlert,
    VBTooltip,
    BImg, BMedia, vSelect
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      user_info: {},
      reson: [],
      setting: {},
      Loading: true,
      pageLength: 10,
      dir: false,
      codeColumnSearch,
      columns: [
        {
          label: this.$t('order id'),
          field: 'order_id',
          class: 'text-nowrap',
        },
        {
          label: this.$t('view order'),
          field: 'view',
          tdClass: 'align',

        },
        {
          label: this.$t('order') + ' ' + this.$t('status'),
          field: 'order_status',
          tdClass: 'widths',


        },

        {
          label: this.$t('customer name'),
          field: 'user_names',
          class: 'text-nowrap',
        },
        {
          label: this.$t('order type'),
          field: 'delivery_type',

        },
        {
          label: this.$t('delivery people'),
          field: 'rider_name',
          class: 'text-nowrap',
        },
        {
          label: this.$t('Store'),
          field: 'restaurant_name',
          tdClass: 'text-nowrap',

        },
        {
          label: this.$t('date'),
          field: 'ordered_time',
          tdClass: 'text-nowrap',

        },
        {
          label: this.$t('address'),
          field: 'delivery_address',
          tdClass: 'text-nowraps',

        },
        {
          label: this.$t('payment mode'),
          field: 'is_cod',

        },
        {
          label: this.$t('Offer Amount'),
          field: 'offer_discounts',

        },
        {
          label: this.$t('Wallet Amount'),
          field: 'wallet',

        },
        {
          label: this.$t('total'),
          field: 'bill_amounts',

        },
        // {
        //   label: 'Action',
        //   field: 'action',
        // },
        {

          field: 'user_name',
          hidden: true,

        },
        {

          field: 'phone',
          hidden: true,

        },
        {

          field: 'order_id',
          hidden: true,
        },
      ],
      rows: [],
      searchTerm: '',

      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],
    }
  },
  methods: {
    handleDelete(id) {

      this.$http
        .get("/store/new_update", data)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "reason_list" })
            } else {

              this.popToast(response)
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response) {

      const h = this.$createElement
      const vNodesMsg = h('p', { class: ['text-center', 'mb-0'] }, [
        response.data.message
      ])
      this.$bvToast.toast([vNodesMsg], {
        solid: false,
        variant: 'info',
      })
    },


    setpop(id) {
      this.$http.get('/store/assign_request/' + id)
        .then(response => {

          if (response.data.status == true) {
            this.$router.push({ name: "processing" })
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
          } else {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'warning',
              },
            })
          }


        })
    },

    Assign(id) {
      this.$http.get('/store/accept_request/' + id)
        .then(response => {

          if (response.data.status == true) {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
            this.$router.push({ name: "processing" })
          } else {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'warning',
              },
            })
          }


        })

    },

    Cancel(id) {

      this.$refs['my-modal'].show()
      this.$http.get('/store/cancell_resons/')
        .then(res => {
          this.reson = res.data;
        })

      this.user_info.order_id = id;

    },



    Assign_add() {
      var order_id = this.user_info.order_id;
      var reason = this.user_info.reson;
      this.$http.get('/store/cancel_request_reson/' + order_id + '/' + reason)
        .then(response => {

          if (response.data.status == true) {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
            this.$router.push({ name: "cancelled" })


          } else {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'warning',
              },
            })
          }


        })

    }
  },
  computed: {

    statusVariant() {
      const statusColor = {

        1: 'light-success',
        2: 'light-warning',
        3: 'light-danger',
        0: 'light-warning',
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
    this.$http.post('/store/Orders_list/' + this.$route.name + '/' + this.user_info.id)
      .then(res => {

        this.rows = res.data
        this.Loading = false;
        if (process.env.MIX_IS_DEMO) {
          for (let i = 0; i < this.rows.length; i++) {
            this.rows[i].phone = "**********"
            this.rows[i].email = "**********"
            this.rows[i].contact = "**********"

          }
        }
      })
    window.setInterval(() => {
      this.user_info.id = localStorage.id;
      this.$http.post('/store/Orders_list/' + this.$route.name + '/' + this.user_info.id)
        .then(res => {

          this.rows = res.data
          this.Loading = false;
          if (process.env.MIX_IS_DEMO) {
            for (let i = 0; i < this.rows.length; i++) {
              this.rows[i].phone = "**********"
              this.rows[i].email = "**********"
              this.rows[i].contact = "**********"

            }
          }
        })
    }, 10 * 1000);
    this.$http.get('/store/get_currency')
      .then(res => {

        this.setting = res.data
      })


    // this.$http.get('/store/cancell_resons/')
    //   .then(res => {
    //      this.reson = res.data ;
    //      })


  },


}
</script>

<style lang="css">
@import '~@resources/css/font/fonts/font-awesome/css/all.css';
</style>
<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';

.font-weight-bold {
  font-weight: 900 !important;
}

.text-nowraps {
  min-width: 300px;
}

table.vgt-table td {
  vertical-align: middle;
  color: #6e6b7b;
}

.align {
  text-align: center;
}

.widths {
  min-width: 300px;
  text-align: center;

}
</style>

<template>
  <b-row>
    <b-col cols="12">
      <b-card>

        <!-- input search -->
        <div class="custom-search d-flex justify-content-end">
          <b-form-group>
            <div class="d-flex align-items-center">
              <label class="mr-1">{{ $t('message.seachLabel') }}</label>
              <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text"
                class="d-inline-block" />
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
            <a id="link" hidden="hidden" target="_blank" href="/store/failed"></a>
            <!-- Column: Name -->
            <div v-if="props.column.field === 'user_names'" class="text-nowrap">

              <span class="text-nowrap" v-if="props.row.device_type === 'android'"><i
                  :id="'andriod' + props.row.restaurant_id" class="fa-brands fa-android" aria-hidden="true"></i> {{
                    props.row.user_name
                  }}</span>
              <span class="text-nowrap" v-else-if="props.row.device_type === 'ios'"><i
                  :id="'ios' + props.row.restaurant_id" class="fa-brands fa-apple" aria-hidden="true"></i> {{
                    props.row.user_name
                  }}</span>
              <span class="text-nowrap" v-else><i :id="'web' + props.row.restaurant_id" class="fa-solid fa-desktop"
                  aria-hidden="true"></i> {{ props.row.user_name }}</span>
              <br>
              <span class="text-nowrap">{{ props.row.phone }}</span>
              <b-tooltip v-b-tooltip.hover.v-warning title="Android" :target="'andriod' + props.row.restaurant_id" />
              <b-tooltip v-b-tooltip.hover.v-warning title="IOS" :target="'ios' + props.row.restaurant_id" />
              <b-tooltip v-b-tooltip.hover.v-warning title="WEB" :target="'web' + props.row.restaurant_id" />
            </div>
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
                  :id="'delivery' + props.row.restaurant_id" class="fa fa-motorcycle" aria-hidden="true"></i> {{
                    props.row.order_id
                  }}</span>
              <span class="text-nowrap font-weight-bold" v-else><i :id="'takeway' + props.row.restaurant_id"
                  class="fa-solid fa-person-walking" aria-hidden="true"></i> {{ props.row.order_id }}</span>
              <br>
              <b-tooltip v-b-tooltip.hover.v-warning :title="$t('delivery')"
                :target="'delivery' + props.row.restaurant_id" />
              <b-tooltip v-b-tooltip.hover.v-warning :title="$t('takeaway')"
                :target="'takeway' + props.row.restaurant_id" />
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
              <feather-icon :id="`invoice-row-${props.row.restaurant_id}-preview-icon`" icon="EyeIcon" size="16"
                class="mx-1" @click="$router.push({ name: 'view_order', params: { id: props.row.id } })"
                cursor="pointer" />
              <b-tooltip :title="$t('view') + ' ' + $t('order')" placement="left"
                :target="`invoice-row-${props.row.restaurant_id}-preview-icon`" />
            </span>

            <span v-else-if="props.column.field === 'restaurant_names'">
              {{ props.row.restaurant_name }}
              <br>
              <b-badge :variant="statusVariant(2)">
                {{ props.row.business_name }}
              </b-badge>
            </span>
            <!-- Column: Action_Redirect -->
            <span v-else-if="props.column.field === 'Action'">

              <feather-icon :id="`invoice1-row-${props.row.restaurant_id}-preview-icon`" icon="LogInIcon" size="16"
                class="mx-1" @click="restaurant(props.row.restaurant_id)" cursor="pointer" />
              <b-tooltip :title="'Login to ' + props.row.restaurant_name"
                :target="`invoice1-row-${props.row.restaurant_id}-preview-icon`" />
            </span>
            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">
              <span>
                <b-dropdown variant="link" toggle-class="text-decoration-none" no-caret>
                  <template v-slot:button-content>
                    <feather-icon icon="MoreVerticalIcon" size="16" class="text-body align-middle mr-25" />
                  </template>
                  <b-dropdown-item @click="make(props.row.id)">

                    <a> <i class="fa-solid fa-share"></i> {{ $t('new') }}</a>
                  </b-dropdown-item>
                </b-dropdown>
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
  </b-row>
</template>

<script>

import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
  BImg, BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BButton, BCard, BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'
import { codeColumnSearch } from './code'
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
    BImg, BMedia,
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      setting: {},
      Loading: true,
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
          label: this.$t('action'),
          field: 'Action',

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
          field: 'restaurant_names',
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
          label: this.$t('Wallet'),
          field: 'wallet',

        },
        {
          label: this.$t('total'),
          field: 'bill_amounts',

        },
        {
          label: this.$t('action'),
          field: 'action',
        },
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
    }
  },
  directives: {

    Ripple,
  },
  methods: {
    handleDelete(id) {

      this.$http
        .get("/admin/new_update", data)
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

    restaurant(id) {
      // let hostname = location.host;
      // alert(hostname);
      // window.location.href = '/store/new';

      this.$http
        .get("/admin/store_login/" + id)
        .then(
          (response => {
            console.log(response.data);
            if (response.data.status == true) {
              console.log(response.data);
              localStorage.removeItem('store_userData')

              const store_userData = response.data.data[0]
              console.log(store_userData.privilege);
              localStorage.setItem('store_userData', JSON.stringify(store_userData))
              localStorage.id = store_userData.id;
              // this.$ability.update(store_userData.ability)
              //  window.open(
              //  '/store/new',

              //   );

              document.getElementById("link").click();

              // window.open('https://www.codexworld.com', '_blank');
              //  window.location.href = '/store/new';

            } else {

              this.popToast(response)
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    make(id) {
      this.$http
        .get("/admin/new_update/" + id + "/0")
        .then(
          (response => {

            if (response.data.status == true) {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
              this.$http.get('/admin/Orders_list/' + this.$route.name)
                .then(res => {

                  this.rows = res.data
                  console.log(this.rows);

                })

            } else {

              this.popToast(response)
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));

    },
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
    var ad = localStorage.admin_email
    this.$http.get('/admin/Orders_list/' + this.$route.name + '/' + ad)
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

    this.$http.get('/admin/get_currency')
      .then(res => {

        this.setting = res.data
        console.log(this.setting);

      })

  },


}
</script>

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
</style>

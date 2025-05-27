<template>
  <b-row>
    <b-col cols="12">
      <b-card>


        <b-row>
          <b-col md="6">
            <div class="custom-search d-flex justify-content-start">
              <b-button variant="primary" :to="{ name: 'add-driver' }">
                <i class="fa-solid fa-plus"></i> {{ $t('add') }} {{ $t('driver') }}
              </b-button>
            </div>

          </b-col>
          <b-col md="6">

            <!-- input search -->
            <div class="custom-search d-flex justify-content-end">
              <b-form-group>
                <div class="d-flex align-items-center">
                  <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                  <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text" class="d-inline-block" />
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

            <!-- Column: Name -->
            <div v-if="props.column.field === 'image'" class="text-nowrap">
              <b-img :src="props.row.banner_image" rounded fluid id="profile-img" alt="profile photo" />
              <!-- <b-avatar
            :src="props.row.banner_image"
            class="mx-1"
          /> -->
              <!-- <span class="text-nowrap">{{ props.row.fullName }}</span> -->
            </div>

            <div v-if="props.column.field === 'delivery_boy_commisions'" class="text-nowrap">
              <span class="text-nowrap "> {{ setting.value }} {{ props.row.delivery_boy_commision }}</span>

              <br>

            </div>

            <div v-if="props.column.field === 'orders_id'" class="text-nowrap">
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
            <span v-else-if="props.column.field === 'Statuss'">
              <span v-if="props.row.status === 0">

                <b-badge :variant="statusVariant(props.row.status)">

                  {{ $t('order') }} {{ $t('placed') }}
                </b-badge>

              </span>
              <span v-if="props.row.status === 1">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('order') }} {{ $t('accepted') }} {{ $t('by') }} {{ $t('restaurant') }}
                </b-badge>

              </span>
              <span v-if="props.row.status === 2">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('food') }} {{ $t('is') }} {{ $t('being') }} {{ $t('prepared') }}
                </b-badge>

              </span>
              <span v-if="props.row.status === 3">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('delivery') }} {{ $t('boy') }} {{ $t('started') }} {{ $t('towards') }} {{ $t('restaurant') }}
                </b-badge>

              </span>

              <span v-if="props.row.status === 4">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('delivery') }} {{ $t('boy') }} {{ $t('reached') }} {{ $t('restaurant') }}
                </b-badge>

              </span>
              <span v-if="props.row.status === 5">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('started') }} {{ $t('towards') }} {{ $t('customer') }}
                </b-badge>

              </span>

              <span v-if="props.row.status === 6">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('food') }} {{ $t('delivered') }}
                </b-badge>

              </span>
              <span v-if="props.row.status === 7">

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('cash') }} {{ $t('received') }}, {{ $t('order') }} {{ $t('completed') }}
                </b-badge>

              </span>

            </span>

            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">
              <feather-icon :id="`invoice-row-${props.row.id}-preview-icon`" icon="EditIcon" size="16" class="mx-1"
                cursor="pointer" @click="$router.push({ name: 'edit_banner', params: { id: props.row.banner_id } })" />
              <b-tooltip :title="$t('edit') + ' ' + $t('banner')" :target="`invoice-row-${props.row.id}-preview-icon`" />

              <feather-icon :id="`invoice-row-delete-preview-icon`" icon="Trash2Icon" size="16" class="mx-1"
                cursor="pointer" @click="showMsgBoxTwo(props.row.banner_id, props.row.restaurant_name)" />
              <b-tooltip :title="$t('delete') + ' ' + $t('banner')" :target="`invoice-row-delete-preview-icon`" />
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
  BImg, BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BCard, BButton, BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'
import Prism from 'vue-prism-component'
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

    BButton, BTooltip,
    Prism, BImg, BMedia,
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

        },

        {
          label: this.$t('driver') + ' ' + this.$t('name'),
          field: 'driver_name',

        },
        {
          label: this.$t('customer') + ' ' + this.$t('name'),
          field: 'customer_name',

        },
        {
          label: this.$t('restaurant') + ' ' + this.$t('name'),
          field: 'restaurant_name',

        },
        {
          label: this.$t('delivery') + ' ' + this.$t('address'),
          field: 'delivery_address',

        },
        {
          label: this.$t('delivery') + ' ' + this.$t('charge'),
          field: 'delivery_boy_commisions',

        },
        {
          label: this.$t('status'),
          field: 'Statuss',

        },
        // {
        //   label: 'Action',
        //   field: 'action',
        // },
      ],
      rows: [],
      searchTerm: '',
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {

        1: 'light-success',
        2: 'light-success',
        3: 'light-success',
        4: 'light-success',
        5: 'light-success',
        6: 'light-success',
        7: 'light-success',

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

    if (this.$route.params.id) {

      this.$http.get('/admin/view_delivery_boy_order_details/' + this.$route.params.id)
        .then(res => {
          this.rows = res.data;
          this.Loading = false;
        })

      this.$http.get('/admin/get_currency')
        .then(res => {

          this.setting = res.data

        })
    }
  },
  methods: {

    showMsgBoxTwo(id, res_name) {

      this.boxTwo = ''
      this.$bvModal
        .msgBoxConfirm('Please confirm that you want to delete ' + res_name + ' banner.', {
          title: 'Please Confirm',
          size: 'sm',
          okVariant: 'primary',
          okTitle: 'Yes',
          cancelTitle: 'No',
          cancelVariant: 'outline-secondary',
          hideHeaderClose: false,
          centered: true,
        })
        .then(value => {
          if (value == true) {
            this.$http.get('/admin/delete_banner/' + id)
              .then(res => {

                if (res.data.status == true) {
                  this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: this.$t(res.data.message),
                      icon: 'CheckIcon',
                      variant: 'success',
                    },
                  })
                  this.$http.get('/admin/banner_list')
                    .then(res => { this.rows = res.data })
                }


              })
          }
        })
    },

  }
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';

#profile-img {

  width: 75px;
}

table.vgt-table td {
  vertical-align: middle;
  color: #6e6b7b;
}
</style>

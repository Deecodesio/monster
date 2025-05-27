<template>
  <b-row>
    <b-col cols="12">
      <b-card>




        <b-row>
          <b-col md="6">
            <div class="custom-search d-flex justify-content-start">
              <b-button variant="primary" :to="{ name: 'add_vehicle' }">
                <i class="fa-solid fa-plus"></i>{{ $t('add') }} {{ $t('vehicle') }}
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
            <div v-if="props.column.field === 'vehicle_images'" class="text-nowrap">
              <b-img :src="props.row.vehicle_image" rounded fluid id="vehicles-img" alt="vehicle photo" />

            </div>

            <div v-if="props.column.field === 'rc_images'" class="text-nowrap">
              <b-img :src="props.row.rc_image" rounded fluid id="vehicles-img" alt="rc photo" />

            </div>

            <div v-if="props.column.field === 'insurance_images'" class="text-nowrap">
              <b-img :src="props.row.insurance_image" rounded fluid id="vehicles-img" alt="insurance photo" />

            </div>

            <div v-if="props.column.field === 'order_id'" class="text-nowrap">
              <span class="text-nowrap">{{ props.row.order_id }}</span>
              <br>
              <span class="text-nowrap"> <b-badge :variant="statusVariant(props.row.status)">
                  {{ props.row.status }}
                </b-badge></span>
            </div>

            <!-- Column: Status -->
            <!-- <span v-else-if="props.column.field === 'status'">
          <b-badge :variant="statusVariant(props.row.status)">
            {{ props.row.status }}
          </b-badge>
        </span> -->
            <span v-else-if="props.column.field === 'status'">
              <b-button v-if="props.row.status === 1" type="submit" variant="outline-success" class="mr-1"
                @click="changedefault(props.row.id)">
                {{ $t('active') }}
              </b-button>
              <b-button v-if="props.row.status === 2" type="submit" variant="outline-warning" class="mr-1"
                @click="changedefault(props.row.id)">
                {{ $t('inactive') }}
              </b-button>

            </span>

            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">
              <feather-icon :id="`invoice-row-${props.row.id}-preview-icon`" icon="EditIcon" size="16" class="mx-1"
                cursor="pointer" @click="$router.push({ name: 'edit_vehicle', params: { id: props.row.id } })" />
              <b-tooltip placement="left" :title="$t('edit') + ' ' + $t('vehicle')" :target="`invoice-row-${props.row.id}-preview-icon`" />
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
  BImg, BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
  BDropdown, BDropdownItem, BCard, BButton, BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'
import { codeColumnSearch } from './code'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
    BTooltip,
    BImg, BMedia,
    BButton,
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      codeColumnSearch,
      Loading: true,
      columns: [
        {
          label: this.$t('vehicle'),
          field: 'vehicle_name',

        },

        {
          label: this.$t('vehicle') + ' ' + this.$t('number'),
          field: 'vehicle_no',

        },
        {
          label: this.$t('vehicle') + ' ' + this.$t('Image'),
          field: 'vehicle_images',

        },
        {
          label: this.$t('RC') + ' ' + this.$t('number'),
          field: 'rc_no',

        },
        {
          label: this.$t('RC') + ' ' + this.$t('Image'),
          field: 'rc_images',

        },
        {
          label: this.$t('RC') + ' ' + this.$t('expiry') + ' ' + this.$t('date'),
          field: 'rc_expiry_date',

        },
        {
          label: this.$t('insurance') + ' ' + this.$t('number'),
          field: 'insurance_no',

        },
        {
          label: this.$t('insurance') + ' ' + this.$t('Image'),
          field: 'insurance_images',

        },
        {
          label: this.$t('insurance') + ' ' + this.$t('expiry') + ' ' + this.$t('date'),
          field: 'insurance_expiry_date',

        },
        {
          label: this.$t('status'),
          field: 'status',
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

        1: 'light-success',
        0: 'light-success',
        3: 'light-danger',
        2: 'light-warning',
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
    this.$http.get('/admin/vehicles_list')
      .then(res => {
        this.rows = res.data;
        this.Loading = false;

      })
  },


  methods: {


    changedefault(id) {
      this.$http.get('/admin/vehicle_status/' + id)
        .then(res => {
          if (res.data.message === "Status Active") {
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t(res.data.message),
                icon: 'CheckIcon',
                variant: 'success',
              },
            })

          }
          else {
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t(res.data.message),
                icon: 'HeartIcon',
                variant: 'danger',
              },
            })
          }

          this.$http.get('/admin/vehicles_list')
            .then(res => { this.rows = res.data })
        })

    },

  }
}

</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';

#vehicles-img {

  width: 65px;
}

table.vgt-table td {
  vertical-align: middle;
  color: #6e6b7b;
}
</style>

<template>




  <b-row>
    <b-col cols="12">
      <b-card>


        <b-row>
          <b-col md="6">
            <div class="custom-search d-flex justify-content-start">
              <b-button variant="primary" :to="{ name: 'add-driver' }">
                <i class="fa-solid fa-plus"></i>{{ $t('add') }} {{ $t('driver') }}
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
            <div v-if="props.column.field === 'profile_pics'" class="text-nowrap">
              <b-img :src="props.row.profile_pic" rounded fluid id="profiles-img" alt="profile photo" />

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
                  {{ $t('active') }}
                </b-badge>

              </span>
              <span v-else>

                <b-badge :variant="statusVariant(props.row.status)">
                  {{ $t('inactive') }}
                </b-badge>

              </span>

            </span>

            <span v-else-if="props.column.field === 'Status'">
              <b-button v-if="props.row.status === 1" type="submit" variant="outline-success" class="mr-1"
                @click="changedefault(props.row.id)">
                {{ $t('active') }}
              </b-button>
              <b-button v-if="props.row.status === 0" type="submit" variant="outline-warning" class="mr-1"
                @click="changedefault(props.row.id)">
                {{ $t('inactive') }}
              </b-button>


            </span>

            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">

<b-button
v-ripple.400="'rgba(113, 102, 240, 0.15)'"
variant="outline-primary"
class="btn-icon"
@click="$router.push({ name: 'deliveryboy_list', params: { id: props.row.id } })"
:id="`invoice3-row-${props.row.id}-preview-icon`"
>
<feather-icon :id="`invoice3-row-${props.row.id}-preview-icon`" icon="ListIcon"
 cursor="pointer" style="color: #6e6b7b;"/>
</b-button>
<b-tooltip  :title="$t('view') + ' ' + $t('list')" :target="`invoice3-row-${props.row.id}-preview-icon`" placement="left"/>


<b-button
v-ripple.400="'rgba(113, 102, 240, 0.15)'"
variant="outline-primary"
class="btn-icon"
@click="$router.push({ name: 'view_driver', params: { id: props.row.id } })"
:id="`invoice-row3-${props.row.id}-preview-icon`"
>
<feather-icon :id="`invoice-row3-${props.row.id}-preview-icon`" icon="EyeIcon"
 cursor="pointer" style="color: #6e6b7b;"/>
</b-button>
<b-tooltip :title="$t('view') + ' ' + $t('details')" :target="`invoice-row3-${props.row.id}-preview-icon`" placement="left"/>

<b-button
v-ripple.400="'rgba(113, 102, 240, 0.15)'"
variant="outline-primary"
class="btn-icon"
@click="$router.push({ name: 'edit-driver', params: { id: props.row.id } })"
:id="`invoice-row-${props.row.id}-preview-icon`" 
>
<feather-icon :id="`invoice-row-${props.row.id}-preview-icon`"  icon="EditIcon"
 cursor="pointer"  style="color: #6e6b7b;"/>
</b-button>
<b-tooltip :title="$t('edit') + ' ' + $t('driver')" :target="`invoice-row-${props.row.id}-preview-icon`" placement="left"/>

<b-button
v-ripple.400="'rgba(113, 102, 240, 0.15)'"
variant="outline-primary"
class="btn-icon"
@click="showMsgBoxTwo(props.row.id, props.row.name)"
:id="`invoice-row-delete-${props.row.id}-preview-icon`"
>
<feather-icon :id="`invoice-row-delete-${props.row.id}-preview-icon`"  icon="Trash2Icon"
 cursor="pointer" style="color: #6e6b7b;"/>
</b-button>
<b-tooltip :title="$t('delete') + ' ' + $t('driver')" :target="`invoice-row-delete-${props.row.id}-preview-icon`" placement="left"/>

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
  BImg, BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BCard,
  BCardHeader, BCardTitle, BCardText, BButton, BCardBody, BMediaAside, BMediaBody, BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BRow,
    BTooltip,
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
    BButton,
    BDropdownItem,
    BCard, BImg,
    BCardHeader, BCardTitle, BCardText, BCardBody, BMedia, BMediaAside, BMediaBody,
  },
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      Loading: true,
      driver: {},
      columns: [
        {
          label: this.$t('driver') + ' ' + this.$t('id'),
          field: 'partner_id',

        },

        {
          label: this.$t('name'),
          field: 'name',

        },

        {
          label: this.$t('phone') + ' ' + this.$t('number'),
          field: 'phone',

        },
        {
          label: this.$t('status'),
          field: 'Status',

        },
        {
          label: this.$t('picture'),
          field: 'profile_pics',

        },

        {
          label: this.$t('action'),
          field: 'action',
          tdClass: 'text-nowraps',
        },
      ],
      rows: [],
      searchTerm: '',
    }
  },
  methods: {

    showMsgBoxTwo(id, res_name) {
      this.driver.id = id

      this.boxTwo = ''
      this.$bvModal
        .msgBoxConfirm('Please confirm that you want to delete ' + res_name + '', {
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
            this.$http.post('/admin/delete_delivery_partner', this.driver)
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
                  this.$http.get('/admin/driver_list')
                    .then(res => {

                      this.rows = res.data.data

                    })
                }


              })
          }
        })
    },

    changedefault(id) {
      this.$http.get('/admin/rider-status-toggle/' + id)
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

          this.$http.get('/admin/driver_list')
            .then(res => {

              this.rows = res.data.data

            })

        })

    },

  },
  computed: {
    statusVariant() {
      const statusColor = {

        1: 'light-success',
        2: 'light-success',
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

    this.$http.get('/admin/driver_list')
      .then(res => {

        this.rows = res.data.data;
        this.Loading = false;

      })
  },

}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';

#profiles-img {

  width: 75px;
}
.text-nowraps {
  min-width: 18rem;
}
</style>

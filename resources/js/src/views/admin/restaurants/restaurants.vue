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
            <a id="link" hidden="hidden" target="_blank" href="/store/dashboard"></a>
            <!-- Column: Name -->
            <div v-if="props.column.field === 'fullName'" class="text-nowrap">
              <b-avatar :src="props.row.avatar" class="mx-1" />
              <span class="text-nowrap">{{ props.row.fullName }}</span>
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
        </span>  -->
            <span v-else-if="props.column.field === 'status'">
              <b-button v-if="props.row.status === 1" type="submit" variant="outline-success" class="mr-1"
                @click="changedefault(props.row.id)">
                {{ $t('active') }}
              </b-button>
              <b-button v-if="props.row.status === 0" type="submit" variant="outline-warning" class="mr-1"
                @click="changedefault(props.row.id)">
                {{ $t('inactive') }}
              </b-button>

            </span>

            <!-- Column: Action_Redirect -->
            <span v-else-if="props.column.field === 'login'">

              <feather-icon :id="`invoice1-row-${props.row.id}-preview-icon`" icon="LogInIcon" size="16" class="mx-1"
                @click="restaurant(props.row.id)" cursor="pointer" />
              <b-tooltip v-b-tooltip.hover.v-warning :title="'Login to ' + props.row.restaurant_name"
                :target="`invoice1-row-${props.row.id}-preview-icon`" placement="left" />

              <a id="wlink" target="_blank" style="color:#6e6b7b"
                :href="'/restaurant/' + props.row.id + '/' + props.row.restaurant_name"><feather-icon
                  :id="`invoice3-row_view-${props.row.id}-preview-icon`" icon="GlobeIcon" size="16" class="mx-1"
                  cursor="pointer" /></a>
              <b-tooltip v-b-tooltip.hover.v-warning title="Open Web View "
                :target="`invoice3-row-${props.row.id}-preview-icon`" placement="left" />
            </span>

            <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">

              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" class="btn-icon"
                @click="$router.push({ name: 'restaurant_view', params: { id: props.row.id } })"
                :id="`invoice-row_view-${props.row.id}-preview-icon`">
                <feather-icon :id="`invoice-row_view-${props.row.id}-preview-icon`" icon="EyeIcon" cursor="pointer"
                  style="color: #6e6b7b;" />
              </b-button>
              <b-tooltip placement="left" :title="$t('view') + ' ' + $t('order')"
                :target="`invoice-row_view-${props.row.id}-preview-icon`" />


              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" class="btn-icon"
                @click="$router.push({ name: 'edit_restaurant', params: { id: props.row.id } })"
                :id="`invoice-row_edit-${props.row.id}-preview-icon`">
                <feather-icon :id="`invoice-row_edit-${props.row.id}-preview-icon`" icon="EditIcon" cursor="pointer"
                  style="color: #6e6b7b;" />
              </b-button>
              <b-tooltip placement="left" :title="$t('edit') + ' ' + $t('Store')"
                :target="`invoice-row_edit-${props.row.id}-preview-icon`" />

              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" class="btn-icon"
                @click="showMsgBoxTwo(props.row.id, props.row.restaurant_name)"
                :id="`invoice-row-delete-${props.row.id}-preview-icon`">
                <feather-icon :id="`invoice-row-delete-${props.row.id}-preview-icon`" icon="Trash2Icon" cursor="pointer"
                  style="color: #6e6b7b;" />
              </b-button>
              <b-tooltip placement="left" :title="$t('delete') + ' ' + $t('Store')"
                :target="`invoice-row-delete-${props.row.id}-preview-icon`" />

            </span>
            <span v-else-if="props.column.field === 'rating'" class="text-nowrap">
              <b-form-rating no-border :value="props.row.rating" readonly variant="warning" inline precision="2" />
            </span>
            <span v-else-if="props.column.field === 'contacts'" class="text-nowrap">
              +{{ props.row.contact_country_calling_code }}{{ props.row.contact }}
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
  BImg, BMedia, BButton, VBTooltip, BTooltip, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BCard, BFormRating,
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
    BCard,
    BFormRating,
    VBTooltip,
    BTooltip, BImg, BMedia, BButton,
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      driver: {},
      Loading: true,
      codeColumnSearch,
      columns: [
        {
          label: this.$t('name'),
          field: 'restaurant_name',

        },
        {
          label: this.$t('direct') + ' ' + this.$t('login'),
          field: 'login',

        },
        {
          label: this.$t('Business') + ' ' + this.$t('name'),
          field: 'business_name',

        },
        {
          label: this.$t('email'),
          field: 'email',

        },
        {
          label: this.$t('address'),
          field: 'address',
          tdClass: 'address',
        },
        {
          label: this.$t('contact'),
          field: 'contacts',

        },
        {
          label: this.$t('ratings'),
          field: 'rating',

        },
        {
          label: this.$t('status'),
          field: 'status',

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
  directives: {

    Ripple,
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

    var ud = localStorage.admin_email
    this.$http.get('/admin/restaurant_list/' + ud)
      .then(res => {
        this.rows = res.data;
        console.log(this.rows)
        this.Loading = false;
        if (process.env.MIX_IS_DEMO) {
          for (let i = 0; i < this.rows.length; i++) {
            this.rows[i].phone = "**********"
            this.rows[i].email = "**********"
            this.rows[i].contact = "**********"

          }
        }
      })
  },

  methods:
  {

    restaurant(id) {

      this.$http
        .get("/admin/store_login/" + id)
        .then(
          (response => {

            if (response.data.status == true) {

              localStorage.removeItem('store_userData')

              const store_userData = response.data.data[0]

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


    changedefault(id) {
      this.$http.get('/admin/restaurant_status/' + id)
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
          var ud = localStorage.admin_email
          this.$http.get('/admin/restaurant_list/' + ud)
            .then(res => { this.rows = res.data })

        })

    },



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
            this.$http.post('/admin/delete_restaurant', this.driver)
              .then(res => {

                if (res.data.status == true) {
                  var ud = localStorage.admin_email
                  this.$http.get('/admin/restaurant_list/' + ud)
                    .then(res => { this.rows = res.data })
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


              })
          }
          else {
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t(res.data.message),
                icon: 'CheckIcon',
                variant: 'warning',
              },
            })

          }
        })
    },




  },
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';

.text-nowraps {
  min-width: 15rem;
}

.address {
  min-width: 25rem;
}
</style>

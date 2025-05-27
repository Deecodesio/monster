<template>
  <section class="invoice-preview-wrapper">

    <b-alert variant="danger" :show="req_details === undefined">
      <h4 class="alert-heading">
        Error fetching Order details
      </h4>
      <div class="alert-body">
        No Order found with this Order id. Check
        <b-link class="alert-link" :to="{ name: 'delivered' }">
          Order List
        </b-link>
        for other orders.
      </div>
    </b-alert>

    <b-row v-if="req_details" class="invoice-preview">
      <b-col cols="12" xl="9" md="8">
        <b-card no-body class="invoice-preview-card">
          <b-card-body class="invoice-padding pb-0">
            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
              <div>
                <div class="logo-wrapper">
                  <h3 class="text-primary invoice-logo">
                    {{ rows[0].name }}
                  </h3>
                </div>
                <p class="card-text mb-25" v-if="req_details.order_status == -3">
                  {{ $t('order') }} {{ $t('status') }} : <b-badge pill variant="danger">{{ order_status }}</b-badge>
                </p>
                <p class="card-text mb-25" v-else>
                  {{ $t('order') }} {{ $t('status') }} : {{ order_status }}
                </p>
                <p class="card-text mb-25">
                  {{ $t('Ordered') }} {{ $t('on') }} : {{ req_details.ordered_time }}
                </p>
                <p class="card-text mb-25" v-if="req_details.takeaway_code">
                  <b-form>
                    <b-row>
                      <b-col md="6">
                        <b-form-group>
                          <b-form-input v-model="track.takeaway_code" :placeholder="($t('enter') + ' ' + $t('code'))"
                            type="number" />
                        </b-form-group>
                      </b-col>
                      <b-col cols="6">
                        <b-button variant="primary" type="submit" @click.prevent="takeaway">
                          {{ $t('complete') }} {{ $t('order') }}
                        </b-button>
                      </b-col>
                    </b-row>
                  </b-form>
                </p>
              </div>
              <div class="mt-md-0 mt-2">
                <h4 class="invoice-title">
                  {{ $t('order') }} {{ $t('id') }}
                  <span class="invoice-number">#{{ req_details.order_id }}</span>
                </h4>
                <div class="invoice-date-wrapper" v-if="pharmacy == 1">
                  <a :href="req_details.req_image" id="download" style="display: none;" download></a>
                  <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" class="mb-75" block
                    @click="pres_download" v-if="req_details.req_image">
                    {{ $t('Prescription Download') }}
                  </b-button>
                </div>
              </div>
            </div>
          </b-card-body>
          <hr class="invoice-spacing">
          <b-card-body v-if="req_details" class="invoice-padding pt-0">
            <b-row class="invoice-spacing">
              <b-col cols="12" xl="6" class="p-0">
                <h6 class="mb-2">
                  {{ $t('customer') }} {{ $t('Details') }} :
                </h6>
                <h6 class="mb-25">
                  {{ req_details.user_name }}
                </h6>
                <p class="card-text mb-25">
                  {{ req_details.delivery_address }}
                </p>
                <p class="card-text mb-25">
                  {{ req_details.user_phone }}
                </p>
                <p class="card-text mb-0">
                  {{ req_details.user_email }}
                </p>
              </b-col>
              <b-col xl="6" cols="12" class="p-0">
                <h6 class="mb-2">
                  {{ $t('Store') }} {{ $t('Details') }} :
                </h6>
                <h6 class="mb-25">
                  {{ req_details.restaurant_name }}
                </h6>
                <p class="card-text mb-25">
                  {{ req_details.address }}
                </p>
                <p class="card-text mb-25">
                  +{{ req_details.contact_country_calling_code }}{{ req_details.contact }}
                </p>
                <p class="card-text mb-0">
                  {{ req_details.restaurants_email }}
                </p>
              </b-col>
            </b-row>
          </b-card-body>
          <!-- selectable -->

          <b-table ref="ordertable" selectable :select-mode="selectMode" :items="food_details" :fields="fields" responsive
            @row-selected="onRowSelected" selected-variant="strikeout">
            <template #cell(selected)="{ rowSelected }">
              <template v-if="rowSelected">
                <i class="feather icon-disc primary"></i>
              </template>

              <template v-else>
                <i class="feather icon-circle"></i>
              </template>
            </template>

            <template #cell(taskDescription)="data">
              <b-card-text class="font-weight-bold mb-25" v-if="data.item.image">
                <b-img :src="url1 ? url1 : data.item.image" rounded fluid id="view-img" alt="product photo" />
              </b-card-text>
              <b-card-text class="font-weight-bold mb-25">
                {{ data.item.Name }}
              </b-card-text>
              <b-card-text class="text-nowrap">
                {{ data.item.description }}
              </b-card-text>
            </template>
            <template #cell(image)="data">
              <b-img :src="data.item.image" rounded fluid id="view-img" alt="product photo" />
            </template>
            <template #cell(action)="data">

              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" class="btn-icon"
                v-b-tooltip.hover.top="'Mark as In Stock'" v-if="isRowSelected(data.index)">
                <i class="fa-solid fa-check" @click="unselect_row(data)"></i>
              </b-button>

              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" class="btn-icon"
                v-b-tooltip.hover.top="'Mark as Out of Stock'" v-if="!isRowSelected(data.index)">
                <i class="fa-solid fa-xmark" @click="select_row(data)"></i>
              </b-button>
            </template>
            <template #cell(price)="data">
              <b-card-text class="text-nowrap">
                {{ setting.value }} {{ data.item.price }}
              </b-card-text>
            </template>
            <template #cell(addons)="data">
              <b-card-text class="text-nowrap">
                {{ data.item.add_ons }}
              </b-card-text>
            </template>
            <template #cell(size)="data">
              <b-card-text class="text-nowrap">
                {{ data.item.size }}
              </b-card-text>
            </template>
          </b-table>

          <b-card-body class="invoice-padding pb-0">
            <b-row>
              <b-col cols="12" md="6" class="mt-md-0 mt-3" order="2" order-md="1">
                <b-card-text class="mb-0">
                  <span class="font-weight-bold"></span>
                  <span class="ml-75"></span>
                </b-card-text>
              </b-col>
              <b-col cols="12" md="6" class="mt-md-6 d-flex justify-content-end" order="1" order-md="2">
                <div class="invoice-total-wrapper">
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('item') }} {{ $t('total') }} :
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.item_total }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('Packing') }} {{ $t('charge') }}
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.restaurant_packaging_charge }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('delivery') }} {{ $t('Fee') }}
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.delivery_charge }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('tax') }} :
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.rtax }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('Tips') }} :
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.tips }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('total') }} {{ $t('discount') }}:
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.offer_discount }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('Wallet') }} (-):
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.wallet_amounts }}
                    </p>
                  </div>
                  <hr class="my-50">
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{ $t('total') }} :
                    </p>
                    <p class="invoice-total-amount">
                      {{ setting.value }} {{ req_details.bill_amount }}
                    </p>
                  </div>
                </div>
              </b-col>
            </b-row>
          </b-card-body>
          <hr class="invoice-spacing">
        </b-card>
      </b-col>
      <b-col cols="12" md="4" xl="3" class="invoice-actions">
        <b-card>
          <b-button v-if="req_details.order_status == 0 && !req_details.takeaway_code" variant="outline-primary"
            class="mb-75" block @click="Assign(req_details.request_id)">
            {{ $t('accept') }}
          </b-button>
          <b-button v-if="req_details.order_status == 0" variant="outline-success" class="mb-75" block
            @click="setpop(req_details.request_id)">
            {{ $t('ready') }} {{ $t('to') }} {{ $t('pickup') }}
          </b-button>
          <b-button v-if="req_details.order_status == 1 && !req_details.takeaway_code" variant="outline-primary"
            class="mb-75" block @click="Assignm(req_details.request_id)">
            {{ $t('assign') }}
          </b-button>
          <b-button v-if="req_details.order_status == 1 && !req_details.takeaway_code" variant="outline-success"
            class="mb-75" block @click="Assignmm(req_details.request_id)">
            {{ $t('assign') }} {{ $t('manually') }}
          </b-button>
          <b-button v-if="req_details.order_status < 1" variant="outline-danger" class="mb-75" block
            @click="Cancel(req_details.request_id);">
            {{ $t('cancel') }}
          </b-button>
          <b-button v-if="req_details.order_status < 3 && req_details.order_status != -3"
            v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" class="mb-75" block @click="revise">
            {{ $t('Revise Order') }}
          </b-button>
        </b-card>
        <b-card>
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" class="mb-75" block
            @click="printInvoice">
            A7 {{ $t('Print') }}
          </b-button>
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" class="mb-75" block
            @click="printInvoice">
            A4 {{ $t('Print') }}
          </b-button>
        </b-card>
        <b-card
          v-if="selected.length && req_details.order_status != -3 && req_details.is_revised != 1 && req_details.order_status < 3">
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" class="mb-75" block
            @click="outofstock()">
            <feather-icon icon="ShoppingCartIcon" class="mr-50" />
            <span class="align-middle"> {{ $t('Mark as Out Of Stock') }}</span>
          </b-button>
        </b-card>
      </b-col>
    </b-row>
    <b-modal ref="my-modal" id="modal-code" ok-title="Submit" centered
      :title="$t('Are you sure you want to cancel this order?')" @ok="Assign_add">
      <form ref="form" @submit.prevent="Assign_add">
        <b-form-group :label="$t('reason') + ':'">
          <v-select v-model="user_info.reson" :options="this.reson" label="reason" :reduce="sel => sel.reason"
            :placeholder="$t('cancel') + ' ' + $t('reason')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          <b-form-input type="text" id="reson_id" v-model="user_info.order_id" hidden="hidden" />
        </b-form-group>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" id="myfunction"
          hidden="hidden">
        </b-button>
        <b-col>
        </b-col>
      </form>
    </b-modal>
    <b-modal ref="my-modal1" id="modal-code" ok-title="Submit" centered :title="$t('assign') + ' ' + $t('driver')"
      @ok="Assign_add2">
      <form ref="form" @submit.prevent="Assign_add2">
        <b-form-group>
          <v-select v-model="user_info.delivery_boy" :options="this.delivery_boy" label="name" :reduce="sel => sel.id"
            :placeholder="$t('driver') + ' ' + $t('List')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          <b-form-input type="text" id="order_id" v-model="user_info.delivery_boy_order_id" hidden="hidden" />
        </b-form-group>
        <b-col>
        </b-col>
      </form>
    </b-modal>
    <invoice-sidebar-send-invoice />
    <invoice-sidebar-add-payment />
  </section>
</template>
<script>
import { ref, onUnmounted } from '@vue/composition-api'
import store from '@@/store'
import router from '@@/router'
import {
  BImg, BMedia, BRow, BCol, BCard, BCardBody, BTableLite, BCardText, BFormSelect,
  BButton, BAlert, BLink, VBToggle, BBadge, BFormGroup, BTooltip, BFormInput, BFormFile, BTable, VBTooltip
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
export default {
  directives: {
    Ripple,
    'b-toggle': VBToggle,
    'b-tooltip': VBTooltip,
  },
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BTableLite,
    BCardText,
    BButton,
    BAlert,
    BLink,
    BBadge,
    BImg, BMedia,
    BFormSelect,
    BFormGroup,
    BTooltip,
    BFormInput, BFormFile, vSelect, BTable,
  },
  data() {
    return {
      req_details: [],
      food_details: [],
      order_status: [],
      url1: null,
      setting: {},
      rows: {},
      track: {},
      user_info: {},
      reson: [],
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],
      delivery_boy: [],
      pharmacy: '',
      layout: '',
      selectMode: 'multi',
      fields: ['image', 'action', 'Name', 'description', 'size', 'addons', 'quantity', 'price'],
      selected: [],
      marked: []
    }
  },
  created() {
    console.log(this.$route.params.id);

    this.$http.get('/store/get_currency')
      .then(res => {
        this.setting = res.data
      })
    this.$http.get('/store/get_logo')
      .then(res => {
        this.rows = res.data;
      })


    this.$http.get('/store/view_order/' + this.$route.params.id)
      .then(res => {
        this.req_details = res.data.message
        this.pharmacy = res.data.pharmacy
        this.layout = res.data.layout
        this.food_details = res.data.food
        this.order_status = res.data.order_status
      })
    this.$http.get('/store/view_order/' + this.$route.params.id)
      .then(res => {
        if (this.food_details && this.food_details.length) {
          for (var i = 0; i < this.food_details.length; i++) {
            if (this.food_details[i].detail_status == -1) {
              this.$refs['ordertable'].selectRow(i)
            }
          }
        }
        console.log(this.selected)
      })


    window.setInterval(() => {
      this.$http.get('/store/view_order/' + this.$route.params.id)
        .then(res => {
          if (this.food_details.length != res.data.food.length || this.order_status != res.data.order_status) {
            this.$swal({
              title: 'Order has been revised',
              allowOutsideClick: true,
              customClass: {
                confirmButton: 'btn btn-primary',
              },
              buttonsStyling: false,
            })
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                this.req_details = res.data.message
                this.pharmacy = res.data.pharmacy
                this.layout = res.data.layout
                this.food_details = res.data.food
                this.order_status = res.data.order_status
              })
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                if (this.food_details && this.food_details.length) {
                  for (var i = 0; i < this.food_details.length; i++) {
                    if (this.food_details[i].detail_status == -1) {
                      this.$refs['ordertable'].selectRow(i)
                    }
                  }
                }
                console.log(this.selected)
              })
          }
        })
    }, 10 * 1000);
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
  },
  methods: {
    isRowSelected(row) {
      return this.$refs['ordertable'].isRowSelected(row)
    },
    unselect_row(row) {
      console.log(row)
      this.$refs['ordertable'].unselectRow(row.index)
      this.marked[row.index] == 0
    },
    select_row(row) {
      console.log(row)
      this.$refs['ordertable'].selectRow(row.index)
      this.marked[row.index] == 1
    },
    outofstock() {
      this.$bvModal
        .msgBoxConfirm('Please confirm that you want to Mark these items as Out of Stock', {
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
            console.log(this.selected)

            let items = new FormData();
            items.append("order_id", this.$route.params.id);
            items.append("items", JSON.stringify(this.selected));
            this.$http.post("/store/out_of_stock", items)
              .then((response) => {
                this.$toast({
                  component: ToastificationContent,
                  position: 'bottom-right',
                  props: {
                    title: response.data.message,
                    icon: 'CheckIcon',
                    variant: 'success',
                  },
                })

                this.$http.get('/store/view_order/' + this.$route.params.id)
                  .then(res => {
                    this.req_details = res.data.message
                    this.pharmacy = res.data.pharmacy
                    this.layout = res.data.layout
                    this.food_details = res.data.food
                    this.order_status = res.data.order_status


                  })
                this.$http.get('/store/view_order/' + this.$route.params.id)
                  .then(res => {
                    if (this.food_details && this.food_details.length) {
                      for (var i = 0; i < this.food_details.length; i++) {
                        if (this.food_details[i].detail_status == -1) {
                          this.$refs['ordertable'].selectRow(i)
                        }
                      }
                    }
                    console.log(this.selected)


                  })
              })
              .catch((error) => console.log(error))
              .finally(() => (this.loading = false));
          }

        })



    },
    onRowSelected(items) {
      console.log(this.selected)

      this.selected = items
      console.log(this.selected)

    },
    pres_download() {
      document.getElementById("download").click();
    },
    Assignmm(id) {
      this.$refs['my-modal1'].show()
      this.user_info.delivery_boy_order_id = id;
      this.$http.get('/store/assignRiderList/' + localStorage.id + '/' + id)
        .then(res => {
          this.delivery_boy = res.data
        })
    },
    Assignm(id) {
      this.$http.get('/store/assign_request/' + id)
        .then(response => {
          if (response.data.status == true) {
            this.user_info.id = localStorage.id;
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                console.log(res)
                this.req_details = res.data.message
                this.pharmacy = res.data.pharmacy
                this.layout = res.data.layout
                this.food_details = res.data.food
                this.order_status = res.data.order_status
              })
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
    Assign_add2() {
      let data = new FormData();
      var order_id = this.user_info.delivery_boy_order_id;
      var delivery_id = this.user_info.delivery_boy;
      this.$http.get('/store/assign_driver_manually/' + order_id + '/' + delivery_id)
        .then(response => {
          if (response.data.status == true) {
            this.user_info.id = localStorage.id;
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                console.log(res)
                this.req_details = res.data.message
                this.pharmacy = res.data.pharmacy
                this.layout = res.data.layout
                this.food_details = res.data.food
                this.order_status = res.data.order_status
              })
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
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                console.log(res)
                this.req_details = res.data.message
                this.pharmacy = res.data.pharmacy
                this.layout = res.data.layout
                this.food_details = res.data.food
                this.order_status = res.data.order_status
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
    setpop(id) {
      this.$http.get('/store/assign_request/' + id)
        .then(response => {
          if (response.data.status == true) {
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                console.log(res)
                this.req_details = res.data.message
                this.pharmacy = res.data.pharmacy
                this.layout = res.data.layout
                this.food_details = res.data.food
                this.order_status = res.data.order_status
              })
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
            this.$http.get('/store/view_order/' + this.$route.params.id)
              .then(res => {
                console.log(res)
                this.req_details = res.data.message
                this.pharmacy = res.data.pharmacy
                this.layout = res.data.layout
                this.food_details = res.data.food
                this.order_status = res.data.order_status
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
    revise() {
      this.$router.push({ name: "revise_order", params: { id: this.$route.params.id } })
    },
    printInvoice() {
      window.print()
    },
    takeaway() {
      this.$http
        .get("/store/takeaway_complete/" + this.req_details.request_id + '/' + this.track.takeaway_code)
        .then(
          (response => {
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
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~@resources/scss/base/pages/app-invoice.scss";
</style>
<style>
.table-strikeout {
  text-decoration: line-through;
}
</style>
<style lang="scss">
@media print {
  body {
    background-color: transparent !important;
  }

  nav.header-navbar {
    display: none;
  }

  .main-menu {
    display: none;
  }

  .header-navbar-shadow {
    display: none !important;
  }

  .content.app-content {
    margin-left: 0;
    padding-top: 2rem !important;
  }

  footer.footer {
    display: none;
  }

  .card {
    background-color: transparent;
    box-shadow: none;
  }

  .customizer-toggle {
    display: none !important;
  }

  // Invoice Specific Styles
  .invoice-preview-wrapper {
    .row.invoice-preview {
      .col-md-8 {
        max-width: 100%;
        flex-grow: 1;
      }

      .invoice-preview-card {
        .card-body:nth-of-type(2) {
          .row {
            >.col-12 {
              max-width: 50% !important;
            }

            .col-12:nth-child(2) {
              display: flex;
              align-items: flex-start;
              justify-content: flex-end;
              margin-top: 0 !important;
            }
          }
        }
      }
    }

    // Action Right Col
    .invoice-actions {
      display: none;
    }
  }
}
</style>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#view-img {
  width: 9.92rem;
}
</style>

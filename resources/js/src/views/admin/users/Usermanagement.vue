<template>
  <b-row>
    <b-col cols="12">
      <!-- <div v-if="loading" class="vgt-loading vgt-center-align">
      <div class="spinner-grow" role="status">
 <span class="sr-only">Loading...</span>
 </div>
     </div> -->

        <b-card>

          <!-- input search -->
          <!-- <div class="custom-search d-flex justify-content-end">
            <b-form-group>
              <div class="d-flex align-items-center">
                <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text" class="d-inline-block" />
              </div>
            </b-form-group>
          </div> -->
          <!-- Top bar with search on left and Add button on right -->
             <b-row style="margin-bottom: 1rem;">
             <b-col md="8" style="display: flex">
                        <div class="custom-search justify-content-start">
                            <b-button
                                variant="primary"
                                :to="{ name: 'add-consumer' }"
                            >
                                <i class="fa-solid fa-plus"></i>{{ $t("add") }}
                                {{ $t("consumer") }}
                            </b-button>
                        </div>
          </b-col>
            <b-col md="4">
                        <!-- input search -->
                        <div class="custom-search d-flex justify-content-end">
            <b-form-group class="mb-0">
              <div class="d-flex align-items-center">
                <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text"
                  class="d-inline-block" />
              </div>
            </b-form-group>
            </div>
            </b-col>  
          </b-row>
          <!-- <div class="d-flex justify-content-between align-items-center mb-1">


          <b-button variant="primary" @click="$router.push({ name: 'add-consumer' })">
  <span style="font-size: 1.5em;">+</span> Add Consumer
</b-button>


        

          </div> -->

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
              <div v-if="props.column.field === 'amounts'" class="text-nowrap">

                <!-- <span class="text-nowrap" v-if="(props.row.amount == null)">{{ setting.value }} 0</span> -->
                <span class="text-nowrap" v-if="(props.row.wallet_amount == null)">{{ setting.value }} 0</span>
                <!-- <span class="text-nowrap" v-else>{{ setting.value }} {{ props.row.amount }}</span> -->
                <span class="text-nowrap" v-else>{{ setting.value }} {{ props.row.wallet_amount }}</span>
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
              <!-- Column: Action -->
              <span v-else-if="props.column.field === 'action'">

                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-warning"
                  @click="verify(props.row.code_id)" v-if="props.row.wall_status == 0 && props.row.topup_code !== ''">
                  {{ $t('verify') }} {{ $t('code') }}
                </b-button>

                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-success"
                  @click="setpop(props.row.id)" v-else>
                  {{ $t('add') }} {{ $t('wallet') }}
                </b-button>
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-success"
                  @click="$router.push({ name: 'user_detail', params: { id: props.row.id } })">
                  {{ $t('View') }} {{ $t('User') }}
                </b-button>
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


    <b-modal ref="my-modal1" id="modal-wallet" ok-title="Submit" centered
      :title="$t('enter') + ' ' + $t('the') + ' ' + $t('amount') + ' ' + $t('topup')" @show="resetModal"
      @hidden="resetModal" @ok="add_wallet">

      <form ref="form" @submit.prevent="add_wallet">
        <b-form-group>
          <b-form-input type="number" :placeholder="$t('amount')" v-model="Wallet.amount" />
          <b-form-input id="modval1" type="number" v-model="Wallet.id" hidden="hidden" />
        </b-form-group>

        <b-col>

        </b-col>

      </form>
    </b-modal>



    <b-modal ref="my-modal" id="modal-code" ok-title="Submit" centered :title="$t('verify') + ' ' + $t('OTP')"
      @show="resetModal1" @hidden="resetModal1" @ok="verify_otp">

      <form ref="form" @submit.prevent="verify_otp">
        <b-form-group>
          <b-form-input type="number" :placeholder="$t('OTP')" v-model="Wallet.otp" />
          <b-form-input id="code_id" type="number" v-model="Wallet.id" hidden="hidden" />
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
import Usermanagement from './Usermanagement.vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
  BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem,
  BCard, BSpinner, BModal, BButton, VBModal, BImg, BMedia,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'
import { codeColumnSearch } from './code'
import Ripple from 'vue-ripple-directive'
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
    BSpinner,
    BModal, BButton, VBModal,
    BImg, BMedia,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      setting: {},
      codeColumnSearch,
      Wallet: {},
      columns: [
        {
          label: this.$t('name'),
          field: 'name',

        },

        {
          label: this.$t('email'),
          field: 'email',

        },
        {
          label: this.$t('phone') + ' ' + this.$t('number'),
          field: 'phone',

        },
        {
          label: this.$t('login') + ' ' + this.$t('type'),
          field: 'device_type',

        },
        {
          label: this.$t('wallet'),
          field: 'amounts',

        },

        {
          label: this.$t('action'),
          field: 'action',
        },
      ],
      rows: [],
      searchTerm: '',
      variants: ['warning', 'success', 'dark'],
      // isLoading: true,
      Loading: true
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

    this.$http.get('/admin/user_list')
      .then(res => {
        this.rows = res.data;
        this.Loading = false;
        if (process.env.MIX_IS_DEMO) {
          for (let i = 0; i < this.rows.length; i++) {
            this.rows[i].phone = "**********"
            this.rows[i].email = "**********"
          }
        }
      })

    this.$http.get('/admin/get_currency')
      .then(res => {

        this.setting = res.data


      })
  },
  methods: {
    goToAddConsumer() {
      this.$router.push({ name: 'add-consumer' })
    },
    add_wallet() {
      // this.Wallet.id = document.getElementById("modval1").value;
      this.Wallet.amount = this.Wallet.amount;

      this.$http
        .post("/admin/topupWallet", this.Wallet)
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
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
              this.$http.get('/admin/user_list')
                .then(res => {
                  this.rows = res.data;
                  this.Loading = false;
                })
              this.Wallet.amount = '';
              this.Wallet.otp = '';
              this.Wallet.id = '';

            } else {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
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
      this.Wallet.amount = ''
    },

    verify_otp() {
      this.Wallet.otp = this.Wallet.otp
      this.$http
        .post("/admin/walletVerify", this.Wallet)
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
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
              this.$http.get('/admin/user_list')
                .then(res => {
                  this.rows = res.data;
                  this.Loading = false;
                })
              this.Wallet.amount = '';
              this.Wallet.otp = '';
              this.Wallet.id = '';
            } else {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
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
    resetModal1() {
      this.Wallet.amount = ''
    },

    setpop(id) {
      this.$refs['my-modal1'].show();
      //  this.setpop1(id);
      this.Wallet.id = id;
    },
    // setpop1(id)
    // {

    //   this.Wallet.id= id;

    // },

    verify(id) {
      this.$refs['my-modal'].show();

      this.Wallet.id = id;
    }

  }
}
</script>

<style lang="scss">
.b-button.btn-danger {
  background-color: #E01764;
  /* similar to your pinkish-red */
  border-color:#E01764;
  color: #fff;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 6px;
}

@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>

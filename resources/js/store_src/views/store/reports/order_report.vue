<template>
  <b-row>
    <b-col cols="12">
      <b-card>

        <b-row>
          <b-col md="1" style="display: flex;margin-bottom: 8px;">
            <div class="custom-search d-flex justify-content-start" style="margin-left: 1%;">
            <a :href="this.path" id="download" style="display: none;" download></a>
              <b-button variant="primary" v-b-modal.modal-default>
                 {{ $t('Export') }}
              </b-button>
            </div>

          </b-col>
          <b-col md="7" style="display: flex;margin-bottom: 8px;">
            <div class="custom-search d-flex justify-content-start" style="margin-left: 1%;">
              <b-button variant="primary" v-b-modal.modal-datefilter>
                 {{ $t('Date Filter') }}
              </b-button>
            </div>

          </b-col>
      <b-col md="4">
             <!-- input search -->
        <div class="custom-search d-flex justify-content-end">
          <b-form-group>
            <div class="d-flex align-items-center">
              <label class="mr-1">{{ $t('message.seachLabel') }}</label>
              <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text" class="d-inline-block" />
            </div>
          </b-form-group>
        </div>
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
            <div v-if="props.column.field === 'restaurant_commisions'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.restaurant_commision }}</span>
            </div>
            <div v-if="props.column.field === 'delivery_boy_commisions'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.delivery_boy_commision }}</span>
            </div>
            <div v-if="props.column.field === 'admin_commisions'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.admin_commision }}</span>
            </div>
            <div v-if="props.column.field === 'offer_discounts'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.offer_discount }}</span>
            </div>
            <div v-if="props.column.field === 'taxs'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.tax }}</span>
            </div>
            <div v-if="props.column.field === 'item_totals'" class="text-nowrap">

              <span class="text-nowrap">{{ setting.value }} {{ props.row.item_total }}</span>
            </div>


            <div v-if="props.column.field === 'order_id'" class="text-nowrap">
              <span class="text-nowrap" style="font-weight: bold;">{{ props.row.order_id }}</span>
              <br>
              <!-- <span class="text-nowrap">  <b-badge :variant="statusVariant(props.row.status)">
            {{ props.row.status }}
          </b-badge></span> -->


              <!-- <span v-if="props.row.status === 7">
                <b-badge :variant="statusVariant(props.row.is_cod)">
           Delivered
          </b-badge>
          </span>
          <span v-else-if="props.row.status === 10">
                 <b-badge :variant="statusVariant(props.row.is_cod)">
            New
          </b-badge>
          </span>
        <span v-else>
                 <b-badge :variant="statusVariant(props.row.is_cod)">
            Cancelled
          </b-badge>
          </span> -->

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
              <span>
                <b-dropdown variant="link" toggle-class="text-decoration-none" no-caret>
                  <template v-slot:button-content>
                    <feather-icon icon="MoreVerticalIcon" size="16" class="text-body align-middle mr-25" />
                  </template>
                  <b-dropdown-item>
                    <feather-icon icon="Edit2Icon" class="mr-50" />
                    <span>Edit</span>
                  </b-dropdown-item>
                  <b-dropdown-item>
                    <feather-icon icon="TrashIcon" class="mr-50" />
                    <span>Delete</span>
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

        <template #code>
          {{ codeColumnSearch }}
        </template>
      </b-card>

    </b-col>
    <b-modal
      id="modal-default"
      centered
      :title="$t('Export Option')"
      hide-footer
    >
    <b-form >
      <b-row>
        <b-col md="2">

        </b-col>
        <b-col md="8">
         <label class="btn btn-outline-primary tiplabel tiphide tiphide f-size-0 tipsactive" :id="'addresstype'+1"  name="export" type="button" style="margin-right: 20px; margin-left: 10px; padding: 10px; "  > <input type="radio" class="checktips" hidden name="addresstype"   @change="delivery_type(1)"  value="1" checked>{{$t("CSV")}}</label>

         <label class="btn btn-outline-primary tiplabel tiphide  f-size-0" :id="'addresstype'+2"  name="export" type="button" style="margin-right: 20px; margin-left: 10px; padding: 10px; "  > <input type="radio" class="checktips" hidden name="addresstype"  @change="delivery_type(2)"  value="2">{{$t("PDF")}}</label>

         <label class="btn btn-outline-primary tiplabel tiphide  f-size-0" :id="'addresstype'+3"  name="export" type="button" style="margin-right: 20px; margin-left: 10px; padding: 10px; "  > <input type="radio" class="checktips" hidden name="addresstype"  @change="delivery_type(3)"  value="3">{{$t("Excel")}}</label>
</b-col>
                      
                        <b-col md="2">

</b-col>
                      </b-row>
                      <b-button class="mt-3"  variant="primary"   @click.prevent="export_download">{{$t('Download')}}</b-button>
      </b-form>
    </b-modal>

    <b-modal
      id="modal-datefilter"
      centered
      title="Date Filter"
      hide-footer
      ref="order-modal"
    >
    <b-form >
      <b-row>
        <b-col md="6">
          <b-form-group>
        <h5 class="font-weight-bold">
          {{$t('From')}}
        </h5>
        <b-form-datepicker
      id="From-datepicker"
      v-model="From_Date"
      class="mb-1"
      reset-button
      locale="en"
    />
      </b-form-group>
        </b-col>   
       <b-col md="6">
        <b-form-group>
        <h5 class="font-weight-bold">
          {{$t('To')}}
        </h5>
        <b-form-datepicker
      id="To-datepicker"
      v-model="To_Date"
      class="mb-1"
      reset-button
      locale="en"
    />
      </b-form-group>
       </b-col>
       <b-col md="6">
      
       </b-col>
       <b-col md="6" >
        <b-button class="mt-3"  variant="primary"   @click.prevent="filterdate" style="float: right;">{{$t('Filter')}}</b-button>

       </b-col>
                      </b-row>
      </b-form>
    </b-modal>
  </b-row>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@@core/components/b-card-code/BCardCode.vue'
import {
  BImg, BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
   BDropdown, BDropdownItem,BButton,BModal, VBModal,BFormDatepicker
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@@/store/index'
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
    BDropdownItem, BImg, BMedia,BButton,BModal, VBModal,BFormDatepicker
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      codeColumnSearch,
      Loading: true,
      path:'',
      columns: [
        {
          label: this.$t('order id'),
          field: 'order_id',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Order Id',
          },
        },

        {
          label: this.$t('date'),
          field: 'ordered_time',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Date',
          },
        },
        {
          label: this.$t('customer') + ' ' + this.$t('name'),
          field: 'customer_name',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Customer Name',
          },
        },
        {
          label: this.$t('customer') + ' ' + this.$t('phone') + ' ' + this.$t('number'),
          field: 'phone',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Customer Phone Number',
          },
        },
        {
          label: this.$t('driver') + ' ' + this.$t('name'),
          field: 'delivery_boy_name',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Driver Name',
          },
        },
        {
          label: this.$t('driver') + ' ' + this.$t('phone') + ' ' + this.$t('number'),
          field: 'delivery_boy_phone',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Driver Phone Number',
          },
        },
        {
          label: this.$t('Store'),
          field: 'restaurant_name',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Restaurant',
          },
        },
        {
          label: this.$t('amount'),
          field: 'item_totals',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Area',
          },
        },
        {
          label: this.$t('tax'),
          field: 'taxs',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Tax',
          },
        },
        {
          label: this.$t('offer'),
          field: 'offer_discounts',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Offer',
          },
        },
        {
          label: this.$t('admin') + ' ' + this.$t('commission'),
          field: 'admin_commisions',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Admin Commission',
          },
        },
        {
          label: this.$t('driver') + ' ' + this.$t('commission'),
          field: 'delivery_boy_commisions',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Payouts Completed',
          },
        },
        {
          label: this.$t('restaurant') + ' ' + this.$t('commission'),
          field: 'restaurant_commisions',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Restaurant Commission',
          },
        },
        {
          label: this.$t('status'),
          field: 'status',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Status',
          },
        },

      ],
      rows: [],
      searchTerm: '',
      user_info: {},
      setting: {},
      From_Date:'',
      To_Date:'',
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
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  created() {
    this.user_info.id = localStorage.id;
    this.$http.post('/store/restaurant_report', this.user_info)
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
      this.$http.get('/store/get_currency')
      .then(res => {

        this.setting = res.data
      })
  },

  methods:
  {
    delivery_type(id)
    {
     
      var elements = document.getElementsByName("export");
       elements.forEach((element) => {
         element.classList.remove("tipsactive");
         });
         document.getElementById("addresstype"+id).classList.add("tipsactive");
    },

    filterdate()
{

  if(this.From_Date)
  {
    this.$refs["order-modal"].hide();
    let data = new FormData();
      data.append('from_date', this.From_Date);
      data.append('to_date', this.To_Date);
      data.append('id', localStorage.id);
      
    this.$http.post('/store/Date_Filter',data)
      .then(res => {
        this.rows = res.data;
        this.Loading = false;
      })
  }
 

},
    export_download()
    {
      var dc = document.querySelector('input[name="addresstype"]:checked').value;

     
      if(dc == 1)
      {
       
        this.user_info.from_date = this.From_Date;
      this.user_info.to_date = this.To_Date;
      this.$http.post('/store/order_csv', this.user_info)
   .then(res => {
 
    
     if (res.data.status == true) {
       this.path = res.data.path;
 
       this.$toast({
         component: ToastificationContent,
         position: 'bottom-right',
         props: {
           title: this.$t(res.data.message),
           icon: 'CheckIcon',
           variant: 'success',
         },
       })
      var i = 0;
     window.setInterval(() => {
       if(i === 0)
       {
         this.call();
       }
      i++
     },2*1000);
       
     }
     else {
       this.$toast({
         component: ToastificationContent,
         position: 'bottom-right',
         props: {
          title: this.$t(res.data.message),
           icon: 'CheckIcon',
           variant: 'danger',
         },
       })
     }
   })
 
 
      }
      else if(dc == 2)
      {
        this.user_info.from_date = this.From_Date;
      this.user_info.to_date = this.To_Date;
      
      this.$http.post('/store/order_pdf', this.user_info)
   .then(res => {
 
    
     if (res.data.status == true) {
       this.path = res.data.path;
 
       this.$toast({
         component: ToastificationContent,
         position: 'bottom-right',
         props: {
          title: this.$t(res.data.message),
           icon: 'CheckIcon',
           variant: 'success',
         },
       })
      var i = 0;
     window.setInterval(() => {
       if(i === 0)
       {
         this.call();
       }
      i++
     },2*1000);
       
     }
     else {
       this.$toast({
         component: ToastificationContent,
         position: 'bottom-right',
         props: {
          title: this.$t(res.data.message),
           icon: 'CheckIcon',
           variant: 'danger',
         },
       })
     }
   })
 

      }
      else
      {
        this.user_info.from_date = this.From_Date;
      this.user_info.to_date = this.To_Date;
      
      this.$http.post('/store/order_excel', this.user_info)
   .then(res => {
 
    
    
 
    if (res.data.status == true) {
       this.path = res.data.path;
 
       this.$toast({
         component: ToastificationContent,
         position: 'bottom-right',
         props: {
          title: this.$t(res.data.message),
           icon: 'CheckIcon',
           variant: 'success',
         },
       })
       var i = 0;
     window.setInterval(() => {
       if(i === 0)
       {
         this.call();
       }
      i++
     },2*1000);
       
     }
     else {
       this.$toast({
         component: ToastificationContent,
         position: 'bottom-right',
         props: {
          title: this.$t(res.data.message),
           icon: 'CheckIcon',
           variant: 'danger',
         },
       })
     }
     
    
       })
    
   
 
 

      }

    },


 
call()
{
  document.getElementById("download").click();
}
  }
}
</script>
<style>
 .tipsactive{
       border-color: #62C061  !important;
         background-color: #62C061 !important;
         color: white;
     }
     </style>
<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>

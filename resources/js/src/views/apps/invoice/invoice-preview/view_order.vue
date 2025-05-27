<template>
  <section class="invoice-preview-wrapper">

    <!-- Alert: No item found -->
    <b-alert
      variant="danger"
      :show="req_details === undefined"
    >
      <h4 class="alert-heading">
        Error fetching Order details
      </h4>
      <div class="alert-body">
        No Order found with this Order id. Check
        <b-link
          class="alert-link"
          :to="{ name: 'delivered'}"
        >
          Order List
        </b-link>
        for other orders.
      </div>
    </b-alert>

    <b-row
      v-if="req_details"
      class="invoice-preview"
    >

      <!-- Col: Left (Invoice Container) -->
      <b-col
        cols="12"
        xl="9"
        md="8"
      >
        <b-card
          no-body
          class="invoice-preview-card"
        >
          <!-- Header -->
          <b-card-body class="invoice-padding pb-0">

            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">

              <!-- Header: Left Content -->
              <div>
                <div class="logo-wrapper">
                  <!-- <logo /> -->
                  <h3 class="text-primary invoice-logo">
                    {{rows[0].name}}
                  </h3>
                </div>
                <p class="card-text mb-25" v-if="req_details.order_status == -3">
                  {{ $t('order') }} {{ $t('status') }} : <b-badge pill variant="danger">{{ order_status }}</b-badge>
                </p>
                <p class="card-text mb-25" v-else>
                  {{ $t('order') }} {{ $t('status') }} : {{ order_status }}
                </p>
                <p class="card-text mb-25">
                  {{$t('Ordered')}} {{$t('on')}}  :  {{ req_details.ordered_time }}
                </p>
              </div>

              <!-- Header: Right Content -->
              <div class="mt-md-0 mt-2">
                <h4 class="invoice-title">
                  {{$t('order')}} {{$t('id')}}
                  <span class="invoice-number">#{{ req_details.order_id }}</span>
                </h4>
                <div class="invoice-date-wrapper">
                  <!-- <p class="invoice-date-title">
                    Ordered Time
                  </p>
                  <p class="invoice-date">
                   {{ req_details.created_at }}
                  </p> -->
                </div>
               
              </div>
            </div>
          </b-card-body>

          <!-- Spacer -->
          <hr class="invoice-spacing">

          <!-- Invoice Client & Payment Details -->
          <b-card-body
            v-if="req_details"
            class="invoice-padding pt-0"
          >
            <b-row class="invoice-spacing">

              <!-- Col: Invoice To -->
              <b-col
                cols="12"
                xl="6"
                class="p-0"
              >
                <h6 class="mb-2">
                  {{$t('customer')}} {{$t('Details')}} :
                 
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

              <!-- Col: Payment Details -->
              <b-col
                xl="6"
                cols="12"
               class="p-0"
              >
              <h6 class="mb-2">
                {{$t('Store')}} {{$t('Details')}} :
                 
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

          <!-- Invoice Description: Table -->
          <b-table-lite
            responsive
            :items="food_details"
            :fields="['image','Name', 'description','size','addons','quantity', 'price']"

          >
          <!-- <template #cell(image)="data">
            {{data.item.image}}
            <b-card :img-src="data.item.image"></b-card>
            <b-img
             :src="data.item.image"
            rounded
            fluid
            id="view-img"
            alt="product photo"
           
          ></b-img>
          </template> -->
            <template #cell(taskDescription)="data">
              <!-- {{data.item.image}} -->
              <!-- <b-card-text class="font-weight-bold mb-25" v-if="data.item.image">
                <b-img
             :src="url1? url1:data.item.image"
            rounded
            fluid
            id="view-img"
            alt="product photo"
           
          />
              </b-card-text>  -->
              <!-- <b-card-text class="font-weight-bold mb-25">
             
                <b-card :img-src="data.item.image"></b-card>
                <b-img
             :src="data.item.image"
            rounded
            fluid
            id="view-img"
            alt="product photo"
           
          ></b-img>
              </b-card-text> -->
              <b-card-text class="font-weight-bold mb-25">
                {{ data.item.Name }}
              </b-card-text>
              <b-card-text class="text-nowrap">
                {{ data.item.description }}
              </b-card-text>
             
            </template>
            <template #cell(image) ="data">
                <b-img
             :src="data.item.image"
            rounded
            fluid
            id="view-img"
            alt="product photo"
          />
              </template>
              <template #cell(price) ="data">
                <b-card-text class="text-nowrap">
                  {{setting.value}} {{ data.item.price }}
              </b-card-text>
              </template>
              <template #cell(addons) ="data">
                <b-card-text class="text-nowrap">
                   {{ data.item.add_ons }}
              </b-card-text>
              </template>
              <template #cell(size) ="data">
                <b-card-text class="text-nowrap">
                   {{ data.item.size }}
              </b-card-text>
              </template>
          </b-table-lite>

          <!-- Invoice Description: Total -->
          <b-card-body class="invoice-padding pb-0">
            <b-row>
 <b-col
                cols="12"
                md="6"
                class="mt-md-0 mt-3"
                order="2"
                order-md="1"
              >
                <b-card-text class="mb-0">
                  <span class="font-weight-bold"></span>
                  <span class="ml-75"></span>
                </b-card-text>
              </b-col>
           
              <!-- Col: Total -->
              <b-col
                cols="12"
                md="6"
                class="mt-md-6 d-flex justify-content-end"
                order="1"
                order-md="2"
              >
                <div class="invoice-total-wrapper">
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{$t('item')}} {{$t('total')}} :
                     
                    </p>
                    <p class="invoice-total-amount">
                       {{setting.value}} {{ req_details.item_total }}
                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                   
                    {{$t('Packing')}} {{$t('charge')}} 
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}} {{ req_details.restaurant_packaging_charge }}

                    </p>
                  </div>
                   <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{$t('delivery')}} {{$t('Fee')}} 
               
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}}  {{ req_details.delivery_charge }}

                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{$t('tax')}} :
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}}  {{ req_details.rtax }}

                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                     {{$t('Tips')}} :
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}}  {{ req_details.tips }}

                    </p>
                  </div>
                    <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{$t('total')}} {{$t('discount')}}:
                      
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}}  {{ req_details.offer_discount }}

                    </p>
                  </div>
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{$t('Wallet')}}(-):
                      
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}}  {{ req_details.wallet_amounts }}

                    </p>
                  </div>
                  <hr class="my-50">
                  <div class="invoice-total-item">
                    <p class="invoice-total-title">
                      {{$t('total')}} :
                    </p>
                    <p class="invoice-total-amount">
                      {{setting.value}}  {{ req_details.bill_amount }}

                    </p>
                  </div>
                </div>
              </b-col>
            </b-row>
          </b-card-body>

          <!-- Spacer -->
          <hr class="invoice-spacing">

          <!-- Note -->
          <!-- <b-card-body class="invoice-padding pt-0">
            <span class="font-weight-bold">Note: </span>
            <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
              projects. Thank You!</span>
          </b-card-body> -->
        </b-card>
      </b-col>

      <!-- Right Col: Card -->
      <b-col
        cols="12"
        md="4"
        xl="3"
        class="invoice-actions"
      >
        <b-card>

       

          <!-- Button: DOwnload -->
          <b-button
            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
            variant="outline-secondary"
            class="mb-75"
            block
            @click="printInvoice_pos"
          >
            A7 {{$t('Print')}}
          </b-button>

          <!-- Button: Print -->
          <b-button
            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
            variant="outline-secondary"
            class="mb-75"
            block
            @click="printInvoice"
          >
          A4 {{$t('Print')}}
          </b-button>

        

          
        </b-card>
      </b-col>
    </b-row>

    <invoice-sidebar-send-invoice />
    <invoice-sidebar-add-payment />
  </section>
</template>

<script>
import { ref, onUnmounted } from '@vue/composition-api'
import store from '@/store'
import router from '@/router'
import {
  BImg,BMedia, BRow, BCol, BCard, BCardBody, BTableLite, BCardText, BButton, BAlert, BLink, VBToggle,BBadge,
} from 'bootstrap-vue'
// import Logo from '@core/layouts/components/Logo.vue'
import Ripple from 'vue-ripple-directive'

export default {
  directives: {
    Ripple,
    'b-toggle': VBToggle,
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
    BImg,BMedia,

  },
  data() {
    return {
      req_details:[],
      food_details:[],
     order_status:[],
     setting:{},
     rows:{},
     url1:null,
    }
  },
  
  created() {
    console.log(this.$route.params.id);
    this.$http.get('/admin/view_order/'+this.$route.params.id)
      .then(res => {
          
          this.req_details = res.data.message


            this.food_details = res.data.food
            this.order_status = res.data.order_status

          
          })

          this.$http.get('/admin/get_currency')
      .then(res => {
       
         this.setting = res.data 
         console.log(this.setting);
         
         })
           
         this.$http.get('/admin/get_logo')
    .then(res => { 
      this.rows = res.data;
      
    
    })
           
  },
  computed:{
     statusVariant() {
      const statusColor = {
       
        1       : 'light-primary',
        2       : 'light-success',
        3       : 'light-danger',
        4       : 'light-warning',
        5       : 'light-info',
        
      }

      return status => statusColor[status]
    },
  },
  methods:{
    printInvoice(){
      window.print()
    },
    printInvoice_pos(){
      this.$router.push({ name: "print_order"  ,params: { id: this.$route.params.id}})
    }

  }
}
</script>

<style lang="scss" scoped>
@import "~@resources/scss/base/pages/app-invoice.scss";
</style>

<style lang="scss">
@media print {

  // Global Styles
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
              > .col-12 {
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
   width: 5rem;
}
.invoice-preview .invoice-total-wrapper[data-v-23c5dc40], .invoice-edit .invoice-total-wrapper[data-v-23c5dc40], .invoice-add .invoice-total-wrapper[data-v-23c5dc40] {
    width: 100%;
    max-width: 20rem;
}
</style>

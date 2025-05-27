<template>
    <section class="invoice-preview-wrapper">
  
     
  
      
  <div id="invoice-POS">

<center id="top">
  <!-- <div class="logo"></div> --><h2>  {{ req_details.restaurant_name }}</h2>
 <!--  <div class="info"> 
    
  </div> --><!--End Info-->
</center><!--End InvoiceTop-->

<div id="mid">
  <div class="info">
   <!--  <h2>Contact Info</h2> -->
    <p style="text-align: center;"> 
        <span style="color:#000">Address :</span> <span style="font-size: .8em;">{{ req_details.address }}</span><br>
        <span style="color:#000">Email   :</span> <span style="font-size: .8em;"> {{ req_details.restaurants_email }}</span><br>
        <span style="color:#000">Phone   :</span> <span style="font-size: .8em;">  {{ req_details.contact }}</span><br>
        <span style="color:#000">  {{rows[0].name}} Order Number   :</span> <span style="font-size: .8em;">{{ req_details.order_id }}</span><br>
    </p>
  </div>
</div><!--End Invoice Mid-->

<div id="bot">
    <div id="table">
        <table>
            <tr class="tabletitle">
                <td class="item"><h2>Item</h2></td>
                <td class="Hours"><h2>Qty</h2></td>
                <td class="Rate"><h2>Sub Total</h2></td>
            </tr>

           
                
                <tr class="service" v-for="option in food_details">
                    <td class="tableitem">
                        <p class="itemtext">
                            <span style="color: #000">{{ option.Name }}</span> <br>
                            <div v-if="option.sizeName">
                                {{ option.sizeName }}
                            </div>
                          
                                {{ option.addonNames }}
                           
                        </p></td>
                    <td class="tableitem"><p class="itemtext">{{ option.quantity }}</p></td>
                    <td class="tableitem"><p class="itemtext">{{setting.value}} {{ option.price }}</p></td>
                </tr>
           

            <tr class="tabletitle">
                <td></td>
                <td class="Rate"><h2>Bill Amount</h2></td>
                <td class="payment"><h2>{{setting.value}} {{ req_details.bill_amount }}</h2></td>
            </tr>

        </table>
    </div><!--End Table-->

    <div id="legalcopy">
        <p class="legal" style="text-align:center;"><strong>Thank you for your business!</strong> 
        </p>
    </div>

</div>
</div>
    </section>
    
       

       

          
     
  </template>
  
  <script>
  import { ref, onUnmounted } from '@vue/composition-api'
  import store from '@/store'
  import router from '@/router'
  import {
    BRow, BCol, BCard, BCardBody, BTableLite, BCardText, BButton, BAlert, BLink, VBToggle,
  } from 'bootstrap-vue'
  import Logo from '@core/layouts/components/Logo.vue'
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
  
      Logo,
     
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
  
    this.$http.get('/admin/print_order_bill/'+this.$route.params.id)
      .then(res => {
          
          this.req_details = res.data.message


            this.food_details = res.data.food
            this.order_status = res.data.order_status
          
          
          })

          this.$http.get('/admin/get_currency')
      .then(res => {
       
         this.setting = res.data 
       
         
         })
           
         this.$http.get('/admin/get_logo')
    .then(res => { 
      this.rows = res.data;
      
    
    })
   
     
  },

  mounted() {
    this.call();
    document.onreadystatechange = () => {
  if (document.readyState == "complete") {
   
    console.log('Page completed with image and files!')
    
    // fetch to next page or some code
  }
}
  },
  methods:
  {
  call()
  {
    window.print();
  }
  },


}
window.console = window.console || function(t) {};
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
 
  }
  </script>
  
 


  
  <style lang="scss" scoped>
  @import "~@resources/scss/base/pages/app-invoice.scss";
  </style>
   <style>
   @media print {
       .page-break { display: block; page-break-before: always; }
   }
         #invoice-POS {
     /* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */
     padding: 2mm;
     margin: 0 0;
     width: 44mm;
     background: #FFF;
   }
   #invoice-POS ::selection {
     background: #f31544;
     color: #FFF;
   }
   #invoice-POS ::moz-selection {
     background: #f31544;
     color: #FFF;
   }
   #invoice-POS h1 {
     font-size: 1.5em;
     color: #222;
   }
   #invoice-POS h2 {
     font-size: .9em;
   }
   #invoice-POS h3 {
     font-size: 1.2em;
     font-weight: 300;
     line-height: 2em;
   }
   #invoice-POS p {
     font-size: .7em;
     color: #666;
     line-height: 1.2em;
   }
   #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
     /* Targets all id with 'col-' */
     border-bottom: 1px solid #EEE;
   }
   
   #invoice-POS #bot {
     min-height: 50px;
   }
   #invoice-POS #top .logo {
     height: 60px;
     width: 60px;
     /*background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;*/
     background-size: 60px 60px;
   }
   #invoice-POS .clientlogo {
     float: left;
     height: 60px;
     width: 60px;
     /*background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;*/
     background-size: 60px 60px;
     border-radius: 50px;
   }
   #invoice-POS .info {
     display: block;
     margin-left: 0;
   }
   #invoice-POS .title {
     float: right;
   }
   #invoice-POS .title p {
     text-align: right;
   }
   #invoice-POS table {
     width: 100%;
     border-collapse: collapse;
   }
   #invoice-POS .tabletitle {
     font-size: .5em;
     background: #EEE;
   }
   #invoice-POS .service {
     border-bottom: 1px solid #EEE;
   }
   #invoice-POS .item {
     width: 24mm;
   }
   #invoice-POS .itemtext {
     font-size: .6em;
   }
   #invoice-POS #legalcopy {
     margin-top: 5mm;
   }
   
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
  
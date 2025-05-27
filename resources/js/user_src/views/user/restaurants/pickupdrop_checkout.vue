<template>
    <div class="container"  style="margin-top:100px;">
       <b-row>
        <b-col cols="12"  lg="3"></b-col>
        <b-col cols="12" lg="6">
          
          <b-card class="">

        <b-tabs
          vertical
          content-class="col-12 col-md-9 mt-1 mt-md-0 "
          pills
          nav-wrapper-class="col-md-3 col-12"
          nav-class="nav-left"
        >
      
          <!-- general tab -->
          <b-tab active>
      
            <!-- title -->
            <template #title>
             
              <span class="font-weight-bold"><i class="fa-solid fa-sack-dollar"></i> {{$t("Pay")}}</span>
            </template>
      
              <b-card  v-if="isloading">
                <div class="text-l" >
                
                  <lottie-player src="/animations/procpay.json"  background="#FFFDFD"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>
                
                </div>
              </b-card>
              <b-card  v-if="!isloading">
                  <h4>{{$t("Pay")}} {{$t("Online")}} / {{$t("Cash-on-Delivery")}}</h4>
                  <p>{{$t("If you choose Cash-on-Delivery, Please keep exact change handy to help us serve you better")}}</p>
                  <b-row v-if="is_cod">
                    <div class="col-4"> 
                        <label for="isCoDPayment">{{$t("Cash-on-Delivery")}}</label>
                    </div>
                    <div class="col-2"> 
                        <input type="radio" name="isCoDPayment" id="isCoDPayment" value="1" checked>&nbsp;
                    </div>
                  </b-row>
                  <b-row v-if="is_online">
                    <div class="col-4"> 
                        <label for="isCoDPayment">{{$t("Pay")}} {{$t("Online")}}</label>
                    </div>
                    <div class="col-2"> 
                        <input type="radio" name="isCoDPayment" id="isOnlinePayment" value="0" >&nbsp;
                    </div>
                  </b-row>
                  <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  block
                  variant="primary"
                  @click="paynow()"
                  >
                  {{$t("Pay Now")}}
                  </b-button>
              </b-card>
          </b-tab>
          




        </b-tabs>
      </b-card>

        </b-col>
         <b-col cols="12" lg="3"></b-col>
       </b-row>
      
    </div>
  </template>
  
  <script>
  import { BModal, BButton, VBModal, BAlert,BCard,BFormRadioGroup, BFormGroup , BRow,BCol} from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { BTabs, BTab } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
  export default {
    components: {
      BButton,
      BModal,
      BAlert,
      BCard,
      BFormRadioGroup, BFormGroup,BTabs, BTab,BRow,BCol
    },
    data() {
    return {
        options: ['COD','Pay Online'],
        isloading : false,
        is_cod : false,
        is_online : false,

      
    }
  },
  directives: {
  
    Ripple,
  },
  created(){
    this.$http.post('/getpaymentmethods')
                .then(res => {
                  
                  if(res.data.cod == 1){
                    this.is_cod = true;
                  }

                  if(res.data.online == 1){
                    this.is_online = true;
                  }

                });

    
  },
   
    methods: {
        paynow(){

          if(localStorage.getItem('webuserData')){
            var is_cod = document.querySelector('input[name="isCoDPayment"]:checked').value;
            this.isloading = true
            const cart = JSON.parse(localStorage.getItem("CART_DETAILS"));
            let userData = JSON.parse(localStorage.getItem('webuserData'))
           const bill = this.$store.state['deliware_cart'].total_Details;
           var tax =   localStorage.getItem('tot_tax');

            let data = new FormData(); 
            data.append('item_total', bill.bill);
            data.append('gst', 0);
            data.append('tips', 0);
            data.append('offer_discount',  parseFloat(bill.discount));
            // data.append('delivery_type', parseFloat(cart.invoice.delivery_charge_type));
            data.append('delivery_type', 1);
            data.append('delivery_charge', parseFloat(bill.delivery_charge));
            data.append('paid_type', 1);
            data.append('restaurant_id', cart.restaurant_id);
            data.append('restaurant_packaging_charge',0);
            data.append('bill_amount', parseFloat(bill.bill) );
            data.append('is_cod', is_cod);
            data.append('device_type', 'WEB');
            data.append('user_id', userData.id);

            data.append('pickup_name', cart.cart_details.pickup_name);
            data.append('pickup_phone', cart.cart_details.pickup_phone);
            data.append('drop_name', cart.cart_details.drop_name);
            data.append('drop_phone', cart.cart_details.drop_phone);
            data.append('pickup_lng',cart.cart_details.from_lng);
            data.append('pickup_lat', cart.cart_details.from_lat );
            data.append('pickup_address', cart.cart_details.pickup_address);
            data.append('drop_address', cart.cart_details.drop_address);
            data.append('to_lat', cart.cart_details.to_lat);
            data.append('to_lng',cart.cart_details.to_lng);
            data.append('to_id', cart.cart_details.to_id);
            data.append('from_id',cart.cart_details.from_id);
            data.append('image',cart.cart_details.image);
            data.append('pickup_inst',cart.cart_details.pickup_inst);
            data.append('drop_inst',cart.cart_details.drop_inst);
            data.append('distance',cart.cart_details.distance);
            data.append('instruction',cart.cart_details.instruction);
            data.append('carrier',cart.cart_details.carrier);
            data.append('package_type',cart.cart_details.package_type);
            data.append('tax',tax);



            this.$http.post('/paynow_pickuporder',data)
                .then(res => {
                    this.isloading = false
                    if(res.data.status == true){

                      if (res.data.data.checkout_url != null) {
                        this.$router.push({ name: "pay_for_order", params: { id: res.data.data.checkout_url,name:res.data.data.ClientSecret} })
                       
                      } else if (res.data.redirect_url) {
                        alert(res.message);
                        window.location.href = res.data.redirect_url;
                      } else {
                        this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: res.data.message,
                            icon: 'HeartIcon',
                            variant: 'success',
                          },
                        })
                        localStorage.setItem("CART_DETAILS", "[]");
                        localStorage.setItem("TOTAL", "[]");
                        localStorage.setItem("DELIVERY_LIMITS", "[]");
                        localStorage.setItem("DISTANCE1", "[]");
                        localStorage.setItem("DELIVERY_TYPE", "[]");
                        localStorage.setItem("DELIVERY_BON", "[]");
                        var bill = {};
                        this.$store.commit('deliware_cart/UPDATE_TOTAl_DETAILS', bill)
                        this.$store.commit('deliware_cart/UPDATE_CART_DETAILS', bill)
                        this.$router.push({ name: "pickupdrop_trackorder", params: { id: res.data.data.order_id} })
                        
                      }

                    }
                    if(res.data.status == true){
                    
                 
                }else{
                  
                  this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: res.data.message,
                            icon: 'UserIcon',
                            variant: 'danger',
                          },
                        })
                }
                
                })
        }else{
                      this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Please Login to Continue",
                            icon: 'HeartIcon',
                            variant: 'danger',
                          },
                        })
        }


        

        }
      
    },
  }
  </script>
  <style scoped>
  .pt-10{
    padding-top: 10rem !important;
  }
  </style>
  
<template>
 <b-row  style="margin-top:100px;">
<b-col cols="12">
  <div >
   <div class="pb-2" >
    <b-img src="/pickupdrop.svg" fluid alt="Scan" />
    </div>
  </div>
  <div class="container">
   




  <b-row >
   <b-col md="7">



    <b-tabs v-model="tabIndex">
      <validation-observer ref="simpleRules">
    <b-tab >
    
      <b-form @submit.prevent>
      <b-row>
        <b-col md="6">
          <b-form-group
          :label="$t('Pickup Location')+'*'"
            style="font-weight: bold;color: #62C061;"
          >
          <validation-provider
              #default="{ errors }"
              :name="$t('Pickup Location')+'*'"
              rules="required"
            >
            <b-input-group class="input-group-merge"  style="border: 1px solid #62C061;border-radius: 0.357rem;">
               <b-input-group-prepend is-text>
               <b-img src="/loaction_icon.svg" style="max-width: 71%;">
               </b-img>
               </b-input-group-prepend>
            <b-form-input
            v-model="selectaddress"
              type="button"
              :placeholder="$t('Search Pickup Location')"
             
              @click="checklogin()"
              :state="errors.length > 0 ? false:null"
            />
          </b-input-group>
            <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group
          :label="$t('Drop Location')+'*'"
            style="font-weight: bold;color: #62C061;"
          >
          <validation-provider
              #default="{ errors }"
              :name="$t('Drop Location')"
              rules="required"
            >
            <b-input-group class="input-group-merge"  style="border: 1px solid #62C061;border-radius: 0.357rem;">
               <b-input-group-prepend is-text>
               <b-img src="/loaction_icon.svg" style="max-width: 71%;">
               </b-img>
               </b-input-group-prepend>
            <b-form-input
            v-model="selectaddressdrop"
            type="button"
            :placeholder="$t('Search Drop Location')"
          
            @click="checklogindrop()"
            :state="errors.length > 0 ? false:null"
            />
          </b-input-group>
            <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="12">
            <b-form-group
            :label="$t('Package Type')+'*'"
            style="font-weight: bold;color: #62C061;">
       
        <v-select
          v-model="distance.package"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
          :options="option"
          label="name"
          :reduce="sel => sel.id"
          style="border: 1px solid #62C061;border-radius: 0.357rem;"
          :placeholder="$t('Select')+' '+$t('Package Type')" 
        />
      </b-form-group>
        </b-col>

        <b-col md="12">
          <b-form-group>
            <label  style="font-weight: bold;color: #62C061;font-size: inherit;">{{$t('Select Carrier')}}*</label>
          </b-form-group>
          

        </b-col >
        <b-col md="12" style="display: flex;">
             <b-form-group  v-for="(data,index) in carrier"  :key="index" style="margin-left: 2%;">
               
                       <label class="btn btn-secondary1 tiplabel tiphide  deli f-size-0 tipsactive"  name="carrier" :id="'carrier'+data.id" type="button" v-if="index == 0" > 
                         <input type="radio" class="checktips" hidden name="carrier_type"   @change="delivery_type(data.id)" :value="data.id" checked>
                         <span style="color:#62C061;"> 
                         <b-img :src="data.image" fluid alt="Scan" style="width:70%;min-height: 60px;" />
                        
                         <sapn style="font-weight: bold;">{{data.package_name}}</sapn>
                         <br>
                         <sapn >{{data.start_range}}</sapn>
                         
                         </span>
                     </label>
                     <label class="btn btn-secondary1 tiplabel tiphide  deli f-size-0" name="carrier"   :id="'carrier'+data.id" type="button" v-else > 
                         <input type="radio" class="checktips" hidden  name="carrier_type"   @change="delivery_type(data.id)" :value="data.id">
                         <span style="color:#62C061;"> 
                         <b-img :src="data.image" fluid alt="Scan" style="width:70%;min-height: 60px;" />
                         <sapn style="    font-weight: bold;">{{data.package_name}}</sapn> <br>
                         <sapn >{{data.start_range}}</sapn>
                         
                         </span>
                     </label>
                      <span v-if="index+1 == lastindex"></span>
                      <sapn v-else></sapn>
            </b-form-group>
           </b-col>
        <b-col md="12">
          <b-form-group
          :label="$t('Any instruction for deliver partner(optional)')"
            style="font-weight: bold;color: #62C061;"
          >
            <b-form-textarea
            v-model="distance.instruction"
            :placeholder="$t('Instructions for delivery partner')"
           rows="2"
           style="border: 1px solid #62C061;"
          />
          </b-form-group>
        </b-col>
        <b-col
          md="12"
          
        >
        <b-form-group
        :label="$t('Product list(optional)')"
            style="font-weight: bold;color: #62C061;"
          >
          <b-form-file
          @change="onFileChange1"
          :placeholder="$t('Choose a file or drop it here...')"
      drop-placeholder="Drop file here..."
      style="border: 1px solid #62C061;border-radius: 0.357rem;"
    />
          </b-form-group>
        </b-col>
  
        <!-- submit and reset -->
        <b-col md="12">
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            style="width: 100%;margin-bottom: 4%;font-weight: bold;font-size: 16px;"
           v-if="isdefault"
           @click="tabIndex++"
          >
          {{$t('Next')}}
          </b-button>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            style="width: 100%;margin-bottom: 4%;font-weight: bold;font-size: 16px;"
           v-if="!isdefault"
           @click="checklogin_signup()"
          >
          {{$t('Sign in & Pay')}}
          </b-button>
          
        </b-col>
      </b-row>
  
    </b-form>
    </b-tab>
    <b-tab >
     <b-form @submit.prevent>
    <b-row>
      <b-col md="12">
      <label  style="font-weight: bold;color: #62C061;font-size: inherit;font-size: 20px;">{{$t('Pickup Details')}}</label>
       
      </b-col>
      <!-- first name -->
      <b-col md="12">
        <b-form-group
        :label="$t('Name')"
        >
        <validation-provider
              #default="{ errors }"
              :name="$t('Pickup Name')"
              rules="required"
            >
          <b-input-group class="input-group-merge clr">
            <b-input-group-prepend is-text>
              <feather-icon icon="UserIcon" />
            </b-input-group-prepend>
            <b-form-input
            v-model="rows_pickup.name"
            :placeholder="$t('Name')"
              :state="errors.length > 0 ? false:null"
            />
          </b-input-group>
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
      </b-col>
      <b-col md="12">
        <b-form-group
        :label="$t('Phone Number')"
        >
        <validation-provider
              #default="{ errors }"
              :name="$t('Pickup Mobile')"
              rules="required"
            >
          <b-input-group class="input-group-merge clr">
            <b-input-group-prepend is-text>
              <feather-icon icon="SmartphoneIcon" />
            </b-input-group-prepend>
            <b-form-input
            v-model="rows_pickup.phone"
          
              type="number"
              :placeholder="$t('Mobile')"
              :state="errors.length > 0 ? false:null"
            />
          </b-input-group>
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
      </b-col>

       <!-- first name -->
       <b-col md="12">
        <b-form-group
        :label="$t('Instruction')"
         
        >
         
           
            <b-form-textarea
            v-model="rows_pickup.instruction"
            :placeholder="$t('Instruction')"
           rows="2"
           class="clr"
          />
        
        </b-form-group>
      </b-col>

      <b-col md="12">
      <label  style="font-weight: bold;color: #62C061;font-size: inherit;font-size: 20px;">{{$t('Drop Details')}}</label>
       
      </b-col>
     <b-col md="12">
        <b-form-group
        :label="$t('Name')"
        >
        <validation-provider
              #default="{ errors }"
              :name="$t('Drop Name')"
              rules="required"
            >
          <b-input-group class="input-group-merge clr">
            <b-input-group-prepend is-text>
              <feather-icon icon="UserIcon" />
            </b-input-group-prepend>
            <b-form-input
            
            v-model="rows_drop.name"
            :placeholder="$t('Name')"
              :state="errors.length > 0 ? false:null"
            />
          </b-input-group>
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
      </b-col>

      <!-- mobile -->
     

      <!-- mobile -->
      <b-col md="12">
        <b-form-group
        :label="$t('Phone Number')"
         
        >
        <validation-provider
              #default="{ errors }"
              :name="$t('Drop Mobile')"
              rules="required"
            >
          <b-input-group class="input-group-merge clr">
            <b-input-group-prepend is-text>
              <feather-icon icon="SmartphoneIcon" />
            </b-input-group-prepend>
            <b-form-input
            v-model="rows_drop.phone_number"
              type="number"
              :placeholder="$t('Mobile')"
              :state="errors.length > 0 ? false:null"
            />
          </b-input-group>
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
      </b-col>

 

      <!-- first name -->
      <b-col md="12">
        <b-form-group
        :label="$t('Instruction')"
          
        >
         
            
            <b-form-textarea
            v-model="rows_drop.instruction"
            :placeholder="$t('Instruction')"
           rows="2"
           class="clr"
          />
         
        </b-form-group>
      </b-col>
      <!-- reset and submit -->
      <b-col md="4">
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            style="width: 100%;margin-bottom: 4%;font-weight: bold;font-size: 16px;"
           @click="tabIndex--"
          >
          {{$t('Previous')}}
          </b-button>
        </b-col>
        <b-col md="4">
         
          
        </b-col>
        <b-col md="4">
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            style="width: 100%;margin-bottom: 4%;font-weight: bold;font-size: 16px;"
           @click="checklogin_signup()"
          >
          {{$t('Proceed')}}
          </b-button>
      
          
        </b-col>
    </b-row>
  </b-form>
    </b-tab>
  </validation-observer>
  
  </b-tabs>


   </b-col>
   <b-col md="5">
     <h2>{{$t('Get Your App Today')}}</h2>
     <b-card
              class="cat-card2"
                >
                <b-row>
                  <b-col
                        
                        lg="4"
                        md="6"
                       sm="12"
                      >
                      <b-img src="/a2.svg" fluid alt="Scan" />
                  </b-col>
                  <b-col
                        
                        lg="8"
                        md="6"
                        sm="12"
                        
                      >
                      <b-nav
              vertical
            >
              <b-nav-item >
                <b-img src="/tick.svg" fluid alt="Scan" />   <b class="f-size-1">{{$t("Get")}} {{$t("Live Order Tracking")}}</b>
              </b-nav-item>
              <b-nav-item class="mb-4"> <b-img src="/tick.svg" fluid alt="Scan" /> <b class="f-size-1">{{$t("Get")}} {{$t("Latest Feature Updates")}}</b></b-nav-item>
              


            </b-nav>
                  </b-col>
              </b-row>
            </b-card>

            <div>
              <b-img src="/bikebanner.png" fluid alt="Scan" />

            </div>


   </b-col>

    </b-row>

    <b-sidebar
        id="sidebar-address-pickup"
        bg-variant="white"
        right
        backdrop
        shadow
        ref="a_sidebar">
            <slider_address :prop="send_address" @clicked-show-detail-pickup="check"/>
            
      </b-sidebar>

      <b-sidebar
        id="sidebar-address-drop"
        bg-variant="white"
        right
        backdrop
        shadow
        ref="a_sidebar">
            <slider_address :prop="send_address" @clicked-show-detail-drop="checkdrop"/>
            
      </b-sidebar>

      <b-sidebar
        id="sidebar-cart"
        bg-variant="white"
        right
        backdrop
        shadow
        ref="a_sidebar">
            <cart_page :prop="cart_details" @clicked-show-detail-drop="checkdrop"/>
            
      </b-sidebar>

  </div>
  <loginmodal  v-model="modalOpen"></loginmodal>
  
</b-col>
</b-row>

 
  
      

   
  </template>
  
  <script>
  import {
    BRow,
  BCol,
  BAvatar, BListGroup, BListGroupItem, BFormFile, BCardText, BImg, BMedia,
   BButton,BFormSpinbutton, BCard,BFormRadio, BInputGroup, BFormInput,
    BInputGroupAppend,BSidebar,VBToggle, BFormGroup,BForm,BCardBody,BNav,
    BNavItem,BFormTextarea,
    BTab, BTabs,BButtonGroup,BInputGroupPrepend,
  } from 'bootstrap-vue'
  import Ripple from 'vue-ripple-directive'
  import vSelect from 'vue-select'
  import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
  import slider_address from './slider_address.vue'
  import cart_page from './cart_page.vue'
  import loginmodal from './loginmodal.vue'
  import store from '@@@/store'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email } from '@validations'

  
  export default {
    components: {
      BAvatar,
    BListGroup,
    BListGroupItem,
    BCardText,
    BRow,
    BCol,
    BImg,
    BMedia, 
    BButton,
    BFormSpinbutton,
    BCard,vSelect,
    BFormFile,
    BFormRadio,
    BInputGroup, BFormInput, BInputGroupAppend, BSidebar,
    BFormGroup,BForm,BCardBody,BNav,BNavItem,
    slider_address,BFormTextarea,
    loginmodal,
    cart_page,
    VuePerfectScrollbar, ValidationProvider,
    ValidationObserver,
    BTab, BTabs,BButtonGroup,BInputGroupPrepend,
    
    },
    directives: {
      'b-toggle': VBToggle,
      Ripple,
    },
    data() {
    return {
        rows:{},
        option: [],
        carrier : {},
        lastindex:{},
        send_address:{},
        modalOpen: false,
        isdefault:false,
        selectaddress: '',
        selectaddressdrop:'',
        distance:{},
        tabIndex: 0,
        required,
        rows_pickup:{},
        rows_drop:{},
        cart_details:{},
        res_details:{},
        userData: JSON.parse(localStorage.getItem('webuserData')),
        promocodes :{},
        
    }
},

created() {

    this.$http.get('/get_product_list')
      .then(res => {
          this.option = res.data
         
          })

          this.$http.get('/get_carrier')
      .then(res => {
          this.carrier = res.data;
          this.lastindex = this.carrier.length;

          })

          if(localStorage.getItem('webuserData')){
        var user = JSON.parse(localStorage.getItem('webuserData'))
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
      
        this.$http.get('/get_address/'+lat+'/'+lng)
        .then(res => {
                this.rows = res.data.data
                localStorage.setItem('address',  this.rows);
            })


            this.$http.get('/get_near_address/'+lat+'/'+lng+'/'+user.id)
              .then(res => {
                if(res.data.status == true){
                  this.isdefault = true
                }
              })
      }
      

  },

methods: {
    
  onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.distance.image = reader.result;
      };
     
    },

    delivery_type(type){
      
   
       var elements = document.getElementsByName("carrier");
       elements.forEach((element) => {
         element.classList.remove("tipsactive");
         });
         document.getElementById("carrier"+type).classList.add("tipsactive");
          this.distance.carrier = type;

        //   if(localStorage.getItem('webuserData')){
        //   let user = JSON.parse(localStorage.getItem("webuserData"));
        //   let data = new FormData();
        //   data.append('from_lat',this.distance.fromlat);
        //   data.append('from_lng', this.distance.fromlng);
        //   data.append('to_lat', this.distance.tolat);
        //   data.append('to_lng', this.distance.tolng);
        //   data.append('carrier', type);
        //   this.$http.post('/get_delivery_res/',data)
        //   .then(res => {
           
        //       this.res_details = res.data.data
        //       console.log(  this.res_details);
              
        //       })
        //   this.$root.$emit('bv::toggle::collapse', 'sidebar-address-pickup')
        //   // this.$refs['a_sidebar'].show()
        // }else{
        //   this.$toast({
        //                   component: ToastificationContent,
        //                   position: 'bottom-right',
        //                   props: {
        //                     title: "Please Login to Continue",
        //                     icon: 'UserIcon',
        //                     variant: 'danger',
        //                   },
        //                 })
        //                 this.modalOpen = !this.modalOpen;
        // }
      // },





         },
      checklogin()
      {
        if(localStorage.getItem('webuserData')){
          let user = JSON.parse(localStorage.getItem("webuserData"));
          this.$http.get('/get_delivery_address/'+user.id)
          .then(res => {
           
              this.send_address = res.data.data
              
              })
          this.$root.$emit('bv::toggle::collapse', 'sidebar-address-pickup')
          // this.$refs['a_sidebar'].show()
        }else{
          this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Please Login to Continue",
                            icon: 'UserIcon',
                            variant: 'danger',
                          },
                        })
                        this.modalOpen = !this.modalOpen;
        }
      },


      checklogindrop()
      {
      
        if(localStorage.getItem('webuserData')){
          let user = JSON.parse(localStorage.getItem("webuserData"));
          this.$http.get('/get_delivery_address/'+user.id)
          .then(res => {
           
              this.send_address = res.data.data
              
              })
          this.$root.$emit('bv::toggle::collapse', 'sidebar-address-drop')
          // this.$refs['a_sidebar'].show()
        }else{
          this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Please Login to Continue",
                            icon: 'UserIcon',
                            variant: 'danger',
                          },
                        })
                        this.modalOpen = !this.modalOpen;
        }
      },


      checklogin_signup()
      {
        this.$refs.simpleRules.validate().then(success => {
        if (success) {
          if(localStorage.getItem('webuserData')){
          let user = JSON.parse(localStorage.getItem("webuserData"));
          if(document.querySelector('input[name="carrier_type"]:checked')){
          var dc = document.querySelector('input[name="carrier_type"]:checked').value;
        }else{
          var dc = 1;
        }
          let data = new FormData();
          data.append('business_id', 3);
          data.append('user_id', user.id);
          data.append('from_lat',this.distance.fromlat);
          data.append('from_lng', this.distance.fromlng);
          data.append('to_lat', this.distance.tolat);
          data.append('to_lng', this.distance.tolng);
          data.append('from_id',this.distance.fromid);
          data.append('to_id', this.distance.toid);

          data.append('pickup_address', this.selectaddress);
          data.append('drop_address', this.selectaddressdrop);
          data.append('carrier', dc);
          data.append('instruction',this.distance.instruction);
          data.append('image',this.distance.image);
          data.append('package_type',this.distance.package);

          data.append('pickup_name', this.rows_pickup.name);
          data.append('pickup_phone', this.rows_pickup.phone);
          data.append('pickup_inst', this.rows_pickup.instruction);
          data.append('drop_name',this.rows_drop.name);
          data.append('drop_phone', this.rows_drop.phone_number);
          data.append('drop_inst',this.rows_drop.instruction);
         
          this.$http.post('/get_distance_calculate',data)
          .then(res => {
           
            if(res.data == 0)
            {
              this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                              title: "Pickup and Drop Same Address",
                              icon: '',
                              variant: 'danger',
                            },
                          })

            }
            else
            {

              this.$http.post('/pickup_drop_checkout',data)
              .then(res => {
           
              this.cart_details = res.data.restaurants;
              this.promocodes = res.data.restaurants.promocode;
              localStorage.setItem("DELIVERY_BON", this.cart_details.invoice.DeliveryChargeBasedOn);
              localStorage.setItem("DELIVERY_TYPE", this.cart_details.invoice.delivery_charge_type);
              localStorage.setItem("TAX", this.cart_details.invoice.tax);
              localStorage.setItem("IS_TAX_INCLUSIVE1", this.cart_details.is_tax_inclusive);
              localStorage.setItem("DISTANCE1", this.cart_details.distance);
              localStorage.setItem("DELIVERY_LIMITS",JSON.stringify(this.cart_details.invoice.delivery_charge_limits));
              localStorage.setItem("CART_DETAILS",JSON.stringify(this.cart_details));
              store.commit('deliware_cart/UPDATE_CART_DETAILS',  JSON.stringify(this.cart_details))
              this.load();
              })
              this.$root.$emit('bv::toggle::collapse', 'sidebar-cart')
            }
              })
         
          // this.$refs['a_sidebar'].show()
          }else{
            this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                              title: "Please Login to Continue",
                              icon: 'UserIcon',
                              variant: 'danger',
                            },
                          })
                          this.modalOpen = !this.modalOpen;
          }
        }
        else
        {
          this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Please fill required fields",
                            icon: 'UserIcon',
                            variant: 'danger',
                          },
                        })
        }
      })
        
      },

      load()
      {
        let total_price = 0;
        let is_tax = localStorage.getItem('IS_TAX_INCLUSIVE1');
        let tax = localStorage.getItem('TAX');
        let tot_tax = 0;
        let  total_price1 = 0;
        var  delivery_charge_calc = this.calc_delivery_charge(0);
        var offer_discount = 0;
        total_price = delivery_charge_calc === 0 ? delivery_charge_calc : (delivery_charge_calc - parseFloat(offer_discount) ).toFixed(2);
        tot_tax = parseFloat((total_price / 100) * tax);
             
               if (is_tax == 0) {
                total_price1 = total_price + tot_tax;
               } else {
                  total_price1 = total_price; 
               }
       
       
        var bill = {'delivery_charge':delivery_charge_calc,'discount':offer_discount,'bill':total_price,'tax':tot_tax};
       localStorage.setItem("TOTAL", bill);
        store.commit('deliware_cart/UPDATE_TOTAl_DETAILS',  bill)
        localStorage.setItem('tot_tax', tot_tax);
        this.loadcoupon1();
      },
      loadcoupon1(){
  let avail_res_coupon = [];
  for (let i = 0; i < this.promocodes.length; i++) {
            
            avail_res_coupon.push({
                couponcode: this.promocodes[i].code,
                couponvalue: this.promocodes[i].value,
                coupontype: this.promocodes[i].type,
                couponmaxamount: this.promocodes[i].max_amount,
                couponminamount: this.promocodes[i].min_amount,
                couponcount: this.promocodes.length,
                coupontype: this.promocodes[i].offer_type,

            })
        }

        localStorage.setItem("RES_COUPON", JSON.stringify(avail_res_coupon));
},
      check: function (value) {
        this.$root.$emit('bv::toggle::collapse', 'sidebar-address-pickup')
        var user = JSON.parse(localStorage.getItem('webuserData'))
        this.isdefault = true
        this.selectaddress = value.address
        this.distance.fromlat = value.lat
        this.distance.fromlng = value.lng
        this.distance.fromid = value.id

        this.$http.get('/get_address_details_pickup/'+this.distance.fromid)
              .then(res => {
               
                  this.rows_pickup = res.data;
              })

    },

    checkdrop: function (value) {
        this.$root.$emit('bv::toggle::collapse', 'sidebar-address-drop')
        var user = JSON.parse(localStorage.getItem('webuserData'))
        this.isdefault = true
        this.selectaddressdrop = value.address
        this.distance.tolat = value.lat
        this.distance.tolng = value.lng
        this.distance.toid = value.id

        this.$http.get('/get_address_details_drop/'+this.distance.toid)
              .then(res => {
               
                  this.rows_drop = res.data;
              })
    },

    calc_delivery_charge(total_price) {
   
    
   
   var DC = JSON.parse(localStorage.getItem('DELIVERY_LIMITS'));
   if (DC) {
     var delivery_charge = typeof DELIVERY_CHARGE !== "undefined" ? DELIVERY_CHARGE : DC;
   } else {
     var delivery_charge = [];
   }
   
   var DELIVERY_CHARGE_TYPE = JSON.parse(localStorage.getItem('DELIVERY_TYPE'));
 
   var delivery_charge_type = parseFloat(typeof DELIVERY_CHARGE_TYPE !== "undefined" ? DELIVERY_CHARGE_TYPE : 0);
   
   var delivery_charge_calc = 0; // Assigned 0 because if the list is empty loop will not execute.
   var deliver_charge_mode = JSON.parse(localStorage.getItem('DELIVERY_BON'));
   var deliver_distance = localStorage.getItem('DISTANCE1');
   
   var total = 0;
   var tot = 0;
   var delivery_charge_calc1 = 0;
   var delivery_charge_calc2 = 0;
   var delivery_charge_calc3 = 0;
   var dc_rate = 0;
   var dc_total = 0;
   for (let each_delivery_charge of delivery_charge) {
     
     if (deliver_charge_mode == 1) {
       if (
         total_price >= each_delivery_charge.Minimum &&
         (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum)
       ) {
         delivery_charge_calc = parseFloat(each_delivery_charge.Value);
         delivery_charge_type = each_delivery_charge.Type;
       }
     }
     //km
     if (deliver_charge_mode == 2) {
       //loop deliver charge type
   
       var dc = deliver_distance;
       //var dc = deliver_distance - each_delivery_charge.Maximum;
              if (
                dc >= each_delivery_charge.Minimum &&
                (each_delivery_charge.Maximum == null || dc <= each_delivery_charge.Maximum) && each_delivery_charge.Type == 1
              ) {
                var delivery_charge_calc1 = parseFloat(each_delivery_charge.Value);
                delivery_charge_calc3 += delivery_charge_calc1;
                delivery_charge_type = each_delivery_charge.Type;
              }
            for (var i = 1; i <= deliver_distance; i++) {
              if (i <= each_delivery_charge.Maximum && i >= each_delivery_charge.Minimum && each_delivery_charge.Type == 3) {
                var total = each_delivery_charge.Value * 1;
                tot += total;
                var delivery_charge_calc2 = parseFloat(tot);
              } else {
                if (i == each_delivery_charge.Maximum) {
                  var dc_rate = each_delivery_charge.Value;
                }
              }
              var dckm_rate = parseFloat(dc_rate) + parseFloat(delivery_charge_calc2);
            }
       // calculat tot have diff type 
              if(dckm_rate){
                var delivery_charge_calc = parseFloat(delivery_charge_calc3) + parseFloat(dckm_rate);
            }else{
            var delivery_charge_calc = parseFloat(delivery_charge_calc3);
            }
   
       dc_total += delivery_charge_calc;
     }
   
   }
   
   //If calculation type is percentage, then calculate the percentage
   
        if (deliver_charge_mode == 1 && delivery_charge_type == 2) {
          delivery_charge_calc = parseFloat((total_price / 100) * delivery_charge_calc);
        }
   
      delivery_charge_calc = parseFloat(parseFloat(delivery_charge_calc).toFixed(2));
     
      return (delivery_charge_calc);
  }
    },
  }
  </script>
  <style scoped>
  .text-l{
    text-align: left;
  }
  .deli {
      margin-right: 10px;
      margin-left: 0px;
      padding: 10px;
      height: auto;
      background-color: #fff !important;
      width: 120px;
      border: 4px solid #62C061;
      border-radius: 10px;
        padding: 10px;
  } 
  .take {
      margin-right: 0px;
      margin-left: 10px;
      padding: 10px;
      height: auto;
      background-color: #fff !important;
      width: 120px;
      border: 4px solid #62C061;
      border-radius: 10px;
      margin-right: 20px;  padding: 10px;
  } 
  
  .tipsactive{
    border-color: #67c266 !important;
      background-color: #fff !important;
  }
  .tipsactive1{
    border-color: #62C061 !important;
    background-color: #62C061 !important;
  }
  .skin-2 .num-in {
    background: #FFFFFF;
    box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.15);
    border-radius: 25px;
    height: 28px;
    width: 80px;
    float: left;
  }
  
  .skin-2 .num-in span {
    width: 40%;
    display: block;
    height: 28px;
    float: left;
    position: relative;
  }
  
  .skin-2 .num-in span:before, .skin-2 .num-in span:after {
    content: '';
    position: absolute;
    background-color: #62C061;
    height: 2px;
    width: 10px;
    top: 50%;
    left: 50%;
    margin-top: -1px;
    margin-left: -5px;
  }
  
  .skin-2 .num-in span.plus:after {
    transform: rotate(90deg);
  }
  
  .skin-2 .num-in input {
      float: left;
      width: 20%;
      height: 28px;
      border: none;
      text-align: center;
  }
  .b-sidebar > .b-sidebar-header .close {
      font-size: 2rem;
  }
  .cat-card {
    background: #F4FFFD;
    border-color: #62C061;
    border-style: solid;
}
.cat-card2 {
    background: #f1fff1;

}
.f-size-1 {
    font-size: 17px !important;
}
.custom-file-label::after {
    padding: -6.562rem 1rem ;
    background-color: #6ac369 !important;
    width: 24% !important;
    text-align: center !important;
    color: white !important;
    font-size: 18px !important;
}
.nav-tabs {
  display: none !important;
}
  </style>
  <style>
 .nav-tabs {
     display: none !important;
   }
   .btn-secondary1 {
       border-color: #e0dfe1  !important;
       background-color: #b4b4b2  !important;
   }
   .clr{
     border: 1px solid #390053;
     border-radius: 0.357rem;
   }
</style>
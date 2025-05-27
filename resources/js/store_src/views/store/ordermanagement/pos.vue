<template>
    <div>
        <b-row>
            <b-col lg="2" md="2" cols="12">
                <div class="text-r side sticky-top3 dsp">
                    <div @click="scrollMeTo(0, 0)" id="0">All</div>
                    <div
                        v-for="(data2, index) in rows_detail2"
                        :key="index"
                        @click="scrollMeTo(data2.menu_id, rows[0].id)"
                        :id="data2.menu_name"
                    >
                        {{ data2.menu_name }}
                    </div>
                </div>
            </b-col>
            <b-col lg="6" md="6" cols="12">
                <vue-autosuggest
                    v-model="query"     
                    :suggestions="suggestions"
                    :input-props="inputProps"
                    :section-configs="sectionConfigs"
                    :render-suggestion="renderSuggestion"
                    :limit="10"
                    @input="fetchResults"
                />
                <div v-if="isLoading" class="ecommerce-searchbar mt-1">
                    <b-row>
                       
                        <b-col cols="2">
                            <b-img src="/loader.svg" fluid alt="Scan" />
                        </b-col>
                      
                    </b-row>
                </div>
                <div class="ecommerce-searchbar mt-1" v-if="!isLoading">
                    <b-row>
                        <b-col
                            cols="12"
                            v-for="(data2, index2) in rows_detail"
                            :key="index2"
                            id="products"
                        >
                            <h3>{{ data2.menu_name }}</h3>
                            <div :id="data2.menu_name">
                                <b-row>
                                    <b-col  xxl="4"   lg="6"  md="12"  cols="12"  class="col-xxl-4"  v-for="(data3, index3) in data2.items" :key="index3">
                                        <b-card class="rounded3 ">
                                            <b-row>
                                                <b-col  cols="5" md="4" lg="5" xxl="3">
                                                <b-img
                                                    class="bor"
                                                    :src="data3.image"
                                                    blank-color="#ccc"
                                                    width="80"
                                                    @click="
                                                        open_image(
                                                            data3.image,
                                                            data3.name
                                                        )
                                                    "
                                                />
                                            </b-col>
                                            <b-col  cols="7" md="8" lg="7" xxl="9">
                                                <h5 class="media-heading" style="height: 40px;">
                                                    <div v-if="data3.is_veg">
                                                        {{ data3.name }}
                                                        <b-img
                                                            src="/veg.svg"
                                                            fluid
                                                            alt="Scan"
                                                            style="width: 12px"
                                                        />
                                                    </div>
                                                    <div v-else>
                                                        {{ data3.name }}
                                                        <b-img
                                                            src="/non_veg.svg"
                                                            fluid
                                                            alt="Scan"
                                                            style="width: 12px"
                                                        />
                                                    </div>
                                                </h5>
                                          
                                                <b-col v-if="isopen" cols="12" class="text-r pad0">
                                                    <h4 class="media-heading text-primary">{{ setting.value }} {{ data3.price }}</h4>
                                                    <b-button  v-ripple.400=" 'rgba(113, 102, 240, 0.15)'" variant="outline-primary" class="btn-icon rounded-circle add_to_cart"
                                                        :id=" 'food_add_' + data3.food_id + 'r'" :data-price=" data3.price"
                                                        @click=" addcart( data3.price, data3.item_tax, data3.name, data3.food_id, data3.is_veg,  data3.add_ons, data3.food_quantity, data3.image) ">
                                                        <feather-icon icon="PlusIcon"/>
                                                    </b-button>

                                                    <div class="num-block skin-2" :id="'food_control_'+data3.food_id+'r'" style="display:none">
                                                    <div class="num-in">
                                                        <span class="minus dis" :id="'food_minus_'+data3.food_id"  @click="removefromcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"></span>
                                                            <input type="text" class="in-num" value="1" readonly=""  :id="'food_qty_'+data3.food_id+'r'" >
                                                        <span class="plus" :id="'food_plus_'+data3.food_id" @click="addcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"></span>
                                                    </div>
                                                </div>
                                                </b-col>
                                            </b-col>
                                        </b-row>
                                        </b-card>
                                    </b-col>
                                </b-row>
                            </div>
                        </b-col>
                    </b-row>
                </div>
            </b-col>
            <b-col lg="4" md="4" cols="12">
                <h3>{{ $t("Customer") }}</h3>
                <vue-autosuggest   v-model="query1"  :suggestions="suggestions1" :input-props="inputProps1"  :section-configs="sectionConfigs1"  :render-suggestion="renderSuggestion1"  :limit="10"  @input="fetchResults1" />

                <div style="border:2px solid #e6e6e6; padding: 10px; margin:20px 0px; border-radius: 10px;cursor: pointer;" @click="showModal">
                <i class="fa-solid fa-circle-plus" style="font-size: 12px;"></i> <span  style="font-size: 12px;" >{{$t("Add")}} {{$t("New")}} {{$t("User")}}</span>
                </div>

                <b-card class="rounded3">
                <div class="text-r side sticky-top3 dsp">
                   
                    <h3>{{ $t("Cart") }}</h3>
                    <div v-for="(data, index) in cart_items" :key="index">
                        <b-row>
                            <b-col cols="12" style="padding: 1.25rem">
                                <b-media vertical-align="top">
                                    <template #aside v-if="data.food_image">
                                        <b-img
                                            class="bor"
                                            :src="data.food_image"
                                            blank-color="#ccc"
                                            width="40"
                                        />
                                    </template>
                                    <h5 class="media-heading" style="height: 40px;">
                                        <div v-if="data.isveg">
                                            <b-img
                                                src="/veg.svg"
                                                fluid
                                                alt="Scan"
                                                style="width: 12px"
                                            />
                                            {{ data.name }}
                                        </div>
                                        <div v-else>
                                            <b-img
                                                src="/non_veg.svg"
                                                fluid
                                                alt="Scan"
                                                style="width: 12px"
                                            />
                                            {{ data.name }}
                                        </div>
                                    </h5>
                                    <b-card-text
                                        class="mb-0 textp"
                                        v-if="data.addonsName"
                                    >
                                        {{ $t("Addons") }} :
                                        {{ data.addonsName }}
                                    </b-card-text>
                                    <b-card-text
                                        class="mb-0 textp"
                                        v-if="data.sizeName"
                                    >
                                        {{ $t("Size") }} : {{ data.sizeName }}
                                    </b-card-text>
                                    <b-row class="pt-0">
                                        <b-col cols="12">
                                            <h4
                                                class="media-heading text-primary"
                                            >
                                                {{ setting.value }}
                                                {{
                                                    data.price +
                                                    data.addonsPrice
                                                }}
                                            </h4>
                                            <div  >
                                                <div
                                                    class="num-block skin-2"
                                                    :id=" 'food_control_' + data.id + 'c' ">
                                                    <div class="num-in">
                                                        <span class="minus dis" :id="'food_minus_' + data.id" @click="removefromcart(
                                                                    data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index )"></span>
                                                        
                                                        <input type="text" class="in-num" value="1" readonly="" :id="'food_qty_' + data.id +  'c' " />
                                                        <span class="plus" :id="  'food_plus_' +     data.id  "  @click="  addcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index )"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </b-col>

                                       
                                    </b-row>
                                </b-media>
                            </b-col>
                        </b-row>
                    </div>
                    <div id="bill" class="pt-1 cart-bord">
                        <h4>{{ $t("Bill Details") }}</h4>
                        <table width="100%">
                            <tr>
                                <td>{{ $t("Item Total") }}</td>
                                <td>
                                    {{ setting.value }}
                                    {{
                                        citem_total | price_format_final
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $t("Packaging Charge") }}</td>
                                <td>
                                    {{ setting.value }}
                                    {{
                                      cpackaging_charge
                                            | price_format_final
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $t("Discount") }}</td>
                                <td>
                                    {{ setting.value }}
                                    {{
                                       cdiscount | price_format_final
                                    }}
                                </td>
                            </tr>
                            
                           
                            <tr id="tips_bill">
                                <td>
                                    {{ $t("Tax") }}
                                    
                                </td>
                                <td>
                                    {{ setting.value }}
                                    {{
                                       ctax  | price_format_final
                                    }} 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>{{ $t("Bill Total") }} </b>
                                </td>
                                <td>
                                    <b
                                        >
                                        {{ setting.value }}
                                        {{
                                           cbill
                                                | price_format_final
                                        }}
                                        </b
                                    >
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                <div id="order_type" class="" style="margin-bottom: 20px" v-if="business_type == 1">
                    <h4>{{$t("Order Type")}}</h4>
                    <label class="btn btn-outline-secondary tiplabel tiphide  deli f-size-0 tipsactive1" id="dineinp" type="button"  > <input type="radio" class="checktips" hidden name="d_type" id="delivery"  @change="o_type_sel(1)" value="1" checked><span style="color:#282828;"><i class="fa-solid fa-utensils"></i> {{$t("Dine In")}}</span></label>

             
                    <label class="btn btn-outline-secondary tiplabel tiphide take f-size-0 " id="takeawayp" type="button"> <input type="radio" class="checktips" hidden name="d_type" id="takeaway" @change="o_type_sel(2)" value="2"><span style="color:#282828;"><i class="fa-solid fa-person-walking"></i> {{$t("Take Away")}}</span></label>
                </div>
                   
                <div id="payment_type" class="cart-bord" style="margin-bottom: 20px">
                     <h4>{{$t("Payment Type")}}</h4>
                    <label class="btn btn-outline-secondary tiplabel tiphide  deli f-size-0 tipsactive1" id="cashp" type="button"  > <input type="radio" class="checktips" hidden name="d_type" id="delivery"  @change="payment_type_sel(1)" value="1" checked><span style="color:#282828;"><i class="fa-solid fa-money-bill"></i> {{$t("Cash")}}</span></label>

             
                    <label class="btn btn-outline-secondary tiplabel tiphide take f-size-0 " id="walletp" type="button"> <input type="radio" class="checktips" hidden name="d_type" id="takeaway" @change="payment_type_sel(2)" value="2"><span style="color:#282828;"><i class="fa-solid fa-wallet"></i> {{$t("Wallet")}}</span></label>
                </div>
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        @click="create_order()"
                        style="width:100%;">
                        Create Order
                    </b-button>
                </div>
                </b-card>
            </b-col>
        </b-row>
        <b-modal
                id="modal-addons"
                ok-only
                ok-variant="success"
                ok-title="Accept"
                modal-class="modal-success"
                centered
                hide-footer
                :title="$t('Customize')"
                ref="addon-modal"
              >
              
              <div id="addonsModalBody" class="text-center">
                <table v-if="add_ons.length" width="80%">
                  <tr><td style="font-size:16px;"><b>{{$t("Addons")}}</b></td></tr>
                  <tr v-for="(data4,index4) in add_ons"
                    :key="index4">
                   
                    <td width="20%"  ALIGN="center">
                      <input type="checkbox" :name="'addonsOfItem_'+itemid"  :id="'addon_'+itemid+'_'+data4.id" :data-name="data4.name" :data-price="data4.price" :value="data4.id" >  
                    </td>
                   
                    <td >
                      <label :for="'addon_'+itemid+'_'+data4.id" style="font-size:16px;"> {{data4.name}} </label>
                      
                    </td>
                    <td>
                      <label :for="'addon_'+itemid+'_'+data4.id" style="font-size:16px;">     {{ setting.value }} {{data4.price}} </label>
                    </td>
                   
                  </tr>
                </table>
                <table v-if="food_quantity.length" width="80%" style="margin-top:30px;">
                  <tr><td style="font-size:16px;"><b>{{$t("Sizes")}}</b><br></td></tr>
                  <tr  v-for="(data5,index5) in food_quantity"
                    :key="index5">

                    <td width="20%" v-if="data5.is_default">
                      <input type="radio" :name="'sizesOfItem_'+itemid"  :id="'size_'+itemid+'_'+data5.id" :data-name="data5.name" :data-price="data5.price"  checked >  
                    </td>
                    <td width="20%" ALIGN="center" v-else>
                      <input type="radio" :name="'sizesOfItem_'+itemid"  :id="'size_'+itemid+'_'+data5.id" :data-name="data5.name" :data-price="data5.price" >  
                    </td>
                    <td >
                      <label :for="'size_'+itemid+'_'+data5.id" style="font-size:16px;"> {{data5.name}} </label>
                      
                    </td>
                    <td>
                      <label :for="'size_'+itemid+'_'+data5.id" style="font-size:16px;">    {{ setting.value }} {{data5.price}} </label>
                    </td>
                  </tr>
                </table>
                
                <b-button class="mt-2" variant="primary" id="addCustomizedProduct" @click="addCustomizedProduct(selected)" style="width:100%;">{{$t("Add")}}</b-button>
              </div>


      </b-modal>

      <b-modal
      id="modal-login"
      cancel-variant="outline-secondary"
      ok-title="Login"
      cancel-title="Close"
      centered
      title="Add Customer"
      hide-footer   
      ref="my-modal"
    >
    <validation-observer ref="simpleRules">
      <b-form>
        <b-col md="12">
          <b-form-group>
            <label>Name</label>
            <validation-provider
              #default="{ errors }"
              rules="required"
              name="First Name"
            >
              <b-form-input
                v-model="cust.name"
                :state="errors.length > 0 ? false:null"
                placeholder="Name"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group>
            <label>Phone Number</label>
            <validation-provider
              #default="{ errors }"
              rules="required|integer"
              name="Number"
            >
              <b-form-input
                v-model="cust.phone"
                :state="errors.length > 0 ? false:null"
                placeholder="Phone Number"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group>
            <label>Email</label>
            <validation-provider
              #default="{ errors }"
              name="Email"
              rules="required|email"
            >
              <b-form-input
                v-model="cust.email"
                :state="errors.length > 0 ? false:null"
                placeholder="Email"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-button
            variant="primary"
            type="submit"
            @click.prevent="add_customer"
          >
            Submit
          </b-button>
        </b-col>
      </b-form>
      </validation-observer>
    </b-modal>
    </div>
    
</template>

<script>
import {
    BCard,
    BCardBody,
    BForm,
    BInputGroup,
    BFormInput,
    BCardText,
    BInputGroupPrepend,
    BTabs,
    BTab,
    BImg,
    BRow,
    BCol,
    BAvatar,
    BNav,
    BBadge,
    BNavItem,
    BMedia,
    BButton,
    BModal,
    VBModal,
    BFormSpinbutton,
     BLink,  
     BFormGroup,
} from "bootstrap-vue";
import VueSlider from "vue-slider-component";
import { useWindowScroll } from "@vueuse/core";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Ripple from "vue-ripple-directive";
import store from "@@/store";
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import { VueAutosuggest } from 'vue-autosuggest'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
        BButton,
        BForm,
        BCard,
        BRow,
        BCol,
        BAvatar,
        BCardBody,
        BInputGroup,
        BFormInput,
        BCardText,
        BInputGroupPrepend,
        BTabs,
        BTab,
        BImg,
        BNav,
        BNavItem,
        BBadge,
        VueSlider,
        BMedia,
        BModal,
        VBModal,
        BFormSpinbutton,
        VueAutosuggest,
        Swiper,
        SwiperSlide,
        BLink,
        VuePerfectScrollbar,
        required,
        confirmed,
        password,
        email,
        min,
        integer,
        url,
        alpha,
        between,
        digits,
        length,
        alphaDash,
        BFormGroup,
    },
    data() {
        return {
            rest_id: localStorage.id,
            order_details :{},
            rows: {},
            rows_detail: {},
            rows_detail2: {},
            isLoading: true,
            isfav: false,
            address: "",
            cart: JSON.parse(localStorage.getItem("store_cart")),
            add_ons: {},
            food_quantity: {},
            itemid: "",
            selected: {},
            quantity: [],
            cart_items: [],
            promocodes: {},
            top_selling: {},
            isopen: true,
            modal_image: "",
            modal_title: "",
            setting: {},
            ctax:0,
            cdc:0,
            ctips:0,
            cbill:0,
            cdiscount:0,
            cdelivery_charge:0,
            cpackaging_charge:0,
            citem_total:0,
            pos_customer : -1,
            payment_type : 1,
            order_type : 1,
       business_type:{},
            datasuggest: [],
      datasuggest2: [],
            cust:{},
      query: '',
      query1: '',
      suggestions: [],
      suggestions1: [],
      selected1: null,
      selected2: null,
      inputProps: {
        id: 'autosuggest__input',
        class: 'form-control ht-6',
        placeholder: "Search Products ",
      },
      sectionConfigs: {
       
        products: {
          limit: 20,  
          label: 'Products',
          onSelected: selected1 => {
           
            this.selected1 = selected1.item
            this.go_to_product(selected1.item)
          },
        },
      },
      inputProps1: {
        id: 'autosuggest__input',
        class: 'form-control ht-6',
        placeholder: "Search Customer ",
      },
      sectionConfigs1: {
       
       Users: {
         limit: 20,  
         label: 'Users',
         onSelected: selected2 => {
          
           this.selected2 = selected2.item
           this.go_to_user(selected2.item)
         },
       },
     },
      limit: 10,
            swiperOptions: {
                breakpoints: {
                    320: {
                        slidesPerView: 1.2,
                        spaceBetween: 30,
                    },
                    420: {
                        slidesPerView: 2.2,
                        spaceBetween: 30,
                    },

                    768: {
                        slidesPerView: 3.2,
                        spaceBetween: 30,
                    },
                    1200: {
                        slidesPerView: 4.2,
                        spaceBetween: 30,
                    },
                },

                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            },
        };
    },
    mounted() {},

    directives: {
        "b-modal": VBModal,
        Ripple,
    },
    filters: {
      price_format_final :function (price) {
       let number = price,
            decimals = 2,
            dec_point = ".",
            thousands_sep = ''
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);



       
      }
    },
    methods: {
        add_customer() {
        this.$refs.simpleRules.validate().then(success => {
            if (success) {
                this.$http
                .post("/store/add_customer", this.cust)
                .then(
                    (response => {
                        
                            if(response.data.status == true){
                                this.pos_customer = response.data.data.id
                                this.query1 = response.data.data.name


                                this.$toast({
                                    component: ToastificationContent,
                                    position: "bottom-right",
                                    props: {
                                        title: response.data.message,
                                        icon: "UserIcon",
                                        variant: "success",
                                    },
                                });
                                this.$refs['my-modal'].hide()
                            }else{
                                this.$toast({
                                    component: ToastificationContent,
                                    position: "bottom-right",
                                    props: {
                                        title: response.data.message,
                                        icon: "UserIcon",
                                        variant: "danger",
                                    },
                                });
                            }
                      
                      } )                  
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
            }
        })
        },
        showModal() {
       
       
           this.$refs['my-modal'].show()
      
        },
        o_type_sel(otype){
            this.order_type = otype
            if(otype == 1){
                this.cpackaging_charge = 0;
                document.getElementById("dineinp").classList.add("tipsactive1");
                document.getElementById("takeawayp").classList.remove("tipsactive1");
                this.loadcart()

            }
            if(otype == 2){
                document.getElementById("takeawayp").classList.add("tipsactive1");
                document.getElementById("dineinp").classList.remove("tipsactive1");
                this.loadcart()


            }
        },
        payment_type_sel(ptype){
            this.payment_type = ptype
            if(ptype == 1){
                document.getElementById("cashp").classList.add("tipsactive1");
                document.getElementById("walletp").classList.remove("tipsactive1");

            }
            if(ptype == 2){
                document.getElementById("walletp").classList.add("tipsactive1");
                document.getElementById("cashp").classList.remove("tipsactive1");

            }
        },
        go_to_user(user){
          
           
            
          this.pos_customer = user.id

        },
        go_to_product(product){
          
           
            
        this.$http.get('/store/search_products_from_name/'+this.rest_id +'/'+product.name)
            .then(res => { 
            
                   
                    this.rows_detail = res.data.items
                    this.loadcart()

            })

        },
        scrollMeTo(refName,id) {
      this.isLoading=true
      if(refName == 0){
        this.$http.get( "/store/get_food_list_by_menu_pos/" +
                    this.rest_id 
                   )
        .then(res => {
          this.rows_detail = res.data.items
          this.rows_detail2 = res.data.items
          this.isLoading=false
        
          var li = document.querySelectorAll('.side div');
            li.forEach(li => {
            li.classList.remove("active");
            })
            this.isLoading=false
            var box = document.querySelector('[id="' + refName + '"]') ;
            box.classList.add("active");
            const rootEle = document.documentElement
            rootEle.scrollTo({
              top: 0,
              behavior: 'smooth',
            })
          })
      }else{
        this.$http.get('/get_food_list_by_menu_id/'+refName+'/'+this.rest_id)
        .then(res => {
       
          
            this.rows_detail = res.data.items
           
            var li = document.querySelectorAll('.side div');
            li.forEach(li => {
            li.classList.remove("active");
            })
            this.isLoading=false
            var box = document.querySelector('[id="' + this.rows_detail[0].menu_name + '"]') ;
            box.classList.add("active");
            const rootEle = document.documentElement
            rootEle.scrollTo({
              top: 0,
              behavior: 'smooth',
            })
            })
      }
      
    
    },
        fetchResults(text) {
      const { query } = this
      if (query) {
        this.suggestions = []
      }
     

      this.$http.get('/store/search_products/'+this.rest_id +'/'+text)
        .then(res => { 
          
          this.datasuggest2 = res.data.food_list

        })

       
      const filteredPages = this.datasuggest2
      this.suggestions.push({ name: 'products', data: filteredPages })
     
    },
    renderSuggestion(suggestion) {
   
      if (suggestion.name === 'products') {
        const page = suggestion.item
        return (
          <div class="d-flex align-items-center">
             <b-avatar src={page.img} class="mr-50"></b-avatar>
            <div class="detail">
              <b-card-text class="mb-0">{page.name}</b-card-text>
              <small class="text-muted">{page.restaurant_name}</small>
            </div>
          </div>
        )
      }
      return false
    },
    fetchResults1(text) {
      const { query1 } = this
      if (query1) {
        this.suggestions1 = []
      }
     

      this.$http.get('/store/search_users/'+text)
        .then(res => { 
          this.datasuggest = res.data.user_list

        })

       
      const filteredPages1 = this.datasuggest

      this.suggestions1.push({ name: 'Users', data: filteredPages1 })
     
    },
    renderSuggestion1(suggestion1) {
      if (suggestion1.name === 'Users') {
        const page1 = suggestion1.item
        return (
          <div class="d-flex align-items-center">
             <b-avatar src={page1.img} class="mr-50"></b-avatar>
            <div class="detail">
              <b-card-text class="mb-0">{page1.name}</b-card-text>
              <small class="text-muted">{page1.phone}</small>
            </div>
          </div>
        )
      }
      return false
    },
    create_order(){
        
            localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
            const cart = JSON.parse(localStorage.getItem("store_cart"));
            if (cart.length === 0) return alert('No items in the cart');
            var item_total = cart.reduce(function(o, c, i) {
			return ((cart[i].quantity * (cart[i].price + cart[i].addonsPrice)) + o)
		    }, 0);
            let data = new FormData(); 
         
            data.append('user_id', this.pos_customer);
            data.append('restaurant_id', this.rest_id);


            data.append('food_id', cart.map(function(item) {
				return item.id
			}).join(','));
            data.append('food_price', cart.map(function(item) {
				return item.price
			}).join(','));
            data.append('food_qty', cart.map(function(item) {
				return item.quantity
			}).join(','));
            data.append('item_total', item_total);
            data.append('addons', cart.map(function(item) {
				return item.addons
			}).join('-'));
            data.append('sizes', cart.map(function(item) {
				return item.sizes
			}).join('-'));
          
           
            data.append('paid_type',  this.payment_type);
           
            data.append('gst', parseFloat(this.ctax));
            data.append('tips', 0);
            data.append('offer_discount',  parseFloat(this.cdiscount));
            data.append('delivery_charge',0);
            data.append('restaurant_packaging_charge', parseFloat(this.cpackaging_charge));
            data.append('bill_amount', parseFloat(this.cbill) );
            data.append('device_type', 'WEB');
            data.append('is_cod', 1);
            this.$http.post('/store/create_pos',data)
                .then(res => {
                    console.log(res)
                    console.log(res.data.data.order_id)
                    console.log(res.data.message)


                   if(res.data.status == true){
                    this.$toast({
                                component: ToastificationContent,
                                position: "bottom-right",
                                props: {
                                    title: res.data.message,
                                    icon: "UserIcon",
                                    variant: "success",
                                },
                            });
                            this.$router.push({ name: 'view_order', params: { id: res.data.data.order_id } });
                   }else{
                    this.$toast({
                                component: ToastificationContent,
                                position: "bottom-right",
                                props: {
                                    title: res.data.message,
                                    icon: "UserIcon",
                                    variant: "danger",
                                },
                            });
                   }
                console.log(res)
                })



        },
        open_image(image, title) {
            this.$refs["image-modal"].show();
            this.modal_image = image;
            this.modal_title = title;
        },

        addcart: function (
            price,
            item_tax,
            name,
            food_id,
            is_veg,
            add_ons,
            food_quantity,
            food_image
        ) {
            localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
      
            this.itemid = food_id;
            let id = food_id,
                restaurant = this.rows[0].id,
                isveg = is_veg,
                restaurant_name = this.rows[0].name,
                restaurant_address = this.rows[0].address,
                restaurant_image = this.rows[0].image,
                taxperc = parseFloat(item_tax),
                cart = JSON.parse(localStorage.getItem("store_cart"));
            price = parseFloat(price);

            this.selected.id = id;
            this.selected.restaurant = restaurant;
            this.selected.isveg = isveg;
            this.selected.restaurant_name = restaurant_name;
            this.selected.restaurant_address = restaurant_address;
            this.selected.restaurant_image = restaurant_image;
            this.selected.taxperc = taxperc;
            this.selected.price = price;
            this.selected.add_ons = add_ons;
            this.selected.food_quantity = food_quantity;
            this.selected.food_image = food_image;
            this.selected.name = name;

            if (add_ons.length > 0 || food_quantity.length > 0) {
                this.add_ons = add_ons;
                this.food_quantity = food_quantity;
                this.$refs["addon-modal"].show();
            } else {
                if (cart.length && cart[0].restaurant != restaurant) {
                    let ok = confirm("The items previously will be deleted");
                    if (ok) {
                        cart = [];
                     
                    } else return;
                }
                let foodIndex = cart.findIndex(function (food, index) {
                    return food.id == id;
                });
                if (foodIndex !== -1) {
                    cart[foodIndex].quantity++;
                    cart[foodIndex].addons += " ";
                    cart[foodIndex].sizes += " ";
                } else {
                    cart.push({
                        id: id,
                        name: name,
                        isveg: isveg,
                        price: parseFloat(parseFloat(price).toFixed(2)),
                        quantity: 1,
                        restaurant: restaurant,
                        addons: "",
                        sizes: "",
                        addonsName: "",
                        addonsPrice: 0,
                        sizeName: "",
                        restaurant_name: restaurant_name,
                        restaurant_address: restaurant_address,
                        restaurant_image: restaurant_image,
                        sizePrice: 0,
                        taxperc: taxperc,
                        food_image: food_image,
                    });
                }
                localStorage.setItem("store_cart", JSON.stringify(cart));
                let cartn = JSON.parse(localStorage.getItem("store_cart"));
              
                this.loadcart();
            }
        },
        number_format(number, decimals, dec_point, thousands_sep) {
            // Strip all characters but numerical ones.
            number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep =
                    typeof thousands_sep === "undefined" ? "," : thousands_sep,
                dec = typeof dec_point === "undefined" ? "." : dec_point,
                s = "",
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return "" + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || "").length < prec) {
                s[1] = s[1] || "";
                s[1] += new Array(prec - s[1].length + 1).join("0");
            }
            return s.join(dec);
        },
       
        loadcart() {

            localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
            var DELIVERY_CHARGE = 0;
            var offer_discount = parseFloat(this.rows[0].offer_discount);
            var packaging_charge = parseFloat(this.rows[0].packaging_charge);
           
            let cart = JSON.parse(localStorage.getItem("store_cart"));
            var tips = parseFloat(this.order_details.tax);
            let total_price = 0;
            let total_item = 0;
            let tot_tax = 0;
            let is_tax = parseFloat(this.rows[0].tax);
            let tot_amt = 0;
            let item_amount_total = 0;
            let restaurant_packaging_charge = 0;

            cart.forEach(function (item, i) {
                    if (document.querySelectorAll('[id="food_control_' + item.id + 'r"]')) {
                        var elms = document.querySelectorAll('[id="food_control_' + item.id + 'r"]');

                        for (var i = 0; i < elms.length; i++) {
                            elms[i].style.display = "block";
                        }
                        var elms2 = document.querySelectorAll(
                            '[id="food_add_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms2.length; i++) {
                            elms2[i].style.display = "none";
                        }

                        var elms3 = document.querySelectorAll('[id="food_qty_' + item.id + 'r"]');
                        var elms4 = document.querySelectorAll('[id="food_qty_' + item.id + 'c"]');
                        for (var i = 0; i < elms3.length; i++) {
                            elms3[i].value = item.quantity;
                        }
                        for (var i = 0; i < elms4.length; i++) {
                            elms4[i].value = item.quantity;
                        }
                      
                    }

                    total_price += parseFloat(
                        (
                            item.quantity *
                            (item.price + item.addonsPrice)
                        ).toFixed(2)
                    );
                    total_item++;
                    let thisAmount = parseFloat(
                        (
                            (item.price + item.addonsPrice) *
                            item.quantity
                        ).toFixed(2)
                    );
                    tot_tax += parseFloat((thisAmount / 100) * item.taxperc);
                    localStorage.setItem("tot_tax", tot_tax);
                    if (is_tax == 0) {
                        tot_amt = total_price + tot_tax;
                    } else {
                        tot_amt = total_price;
                    }

                    item_amount_total += thisAmount;
                });
                if(this.order_type == 1){
                    restaurant_packaging_charge = 0;
                }else{
                    restaurant_packaging_charge = parseFloat(
                    (item_amount_total / 100) * packaging_charge
                );
                }
               
                var  delivery_charge_calc = 0;
                var tips = 0;
                total_price =
                    tot_amt === 0
                        ? tot_amt
                        : (
                              tot_amt +
                              restaurant_packaging_charge +
                              DELIVERY_CHARGE
                          ).toFixed(2);

                var bill = {
                    item_total: tot_amt,
                    packaging_charge: restaurant_packaging_charge,
                    delivery_charge: 0,
                    discount: offer_discount,
                    bill: total_price,
                    tips: 0,
                    dc: 0,
                    tax: tot_tax,
                };
                this.citem_total = tot_amt
                this.cpackaging_charge= restaurant_packaging_charge
                console.log(this.cpackaging_charge)
                this.cdelivery_charge= DELIVERY_CHARGE
                this.cdiscount= offer_discount
                this.cbill= total_price
                this.ctips=tips
                this.cdc=DELIVERY_CHARGE
                this.ctax=tot_tax

                this.cart_items = JSON.parse(localStorage.getItem("store_cart"))
            // localStorage.setItem("DC", JSON.stringify(DELIVERY_CHARGE));

            // var offer_discount = parseFloat(this.rows[0].offer_discount);
            // var DELIVERY_CHARGE_TYPE = this.rows[0].delivery_charge_type;
            // localStorage.setItem("DELIVERY_CHARGE_TYPE", DELIVERY_CHARGE_TYPE);

            // var packaging_charge = parseFloat(this.rows[0].packaging_charge);
            // localStorage.setItem("RES_PACK_CHARGE", packaging_charge);

            // var DELIVERY_CHARGE_BASEDON = this.rows[0].DeliveryChargeBasedOn;
            // localStorage.setItem(
            //     "DC_BON",
            //     JSON.stringify(DELIVERY_CHARGE_BASEDON)
            // );

            // localStorage.setItem("IS_TAX_INCLUSIVE", this.rows[0].tax);

            // localStorage.setItem("cart", localStorage.getItem("store_cart") || "[]");
            // let cart = JSON.parse(localStorage.getItem("store_cart"));
            // var lat = localStorage.getItem("latitude");
            // var lng = localStorage.getItem("longitude");
            // if (localStorage.getItem("webuserData")) {
            //     this.$http
            //         .get(
            //             "/get_checkout_details/" +
            //                 cart[0].restaurant +
            //                 "/" +
            //                 lat +
            //                 "/" +
            //                 lng +
            //                 "/" +
            //                 this.userData.id
            //         )
            //         .then((res) => {

            //             if (res.data.status == true) {
            //                 localStorage.setItem("DISTANCE", res.data.distance);
            //             } else {
            //                 this.$toast({
            //                     component: ToastificationContent,
            //                     position: "bottom-right",
            //                     props: {
            //                         title: res.data.message,
            //                         icon: "UserIcon",
            //                         variant: "danger",
            //                     },
            //                 });
            //             }
            //         });
            // }
            // var tips = 0;

            // let total_price = 0;
            // let total_item = 0;
            // let tot_tax = 0;
            // let is_tax = localStorage.getItem("IS_TAX_INCLUSIVE");
            // let tot_amt = 0;
            // let item_amount_total = 0;
            // let restaurant_packaging_charge = 0;
            // if (packaging_charge == 0) {
            //     packaging_charge = JSON.parse(
            //         localStorage.getItem("RES_PACK_CHARGE")
            //     );
            // }
            // if (cart.length === 0) {
            // } else {
            //     cart.forEach(function (item, i) {
            //         if (
            //             document.querySelectorAll(
            //                 '[id="food_control_' + item.id + 'r"]'
            //             )
            //         ) {
            //             var elms = document.querySelectorAll(
            //                 '[id="food_control_' + item.id + 'r"]'
            //             );

            //             for (var i = 0; i < elms.length; i++) {
            //                 elms[i].style.display = "block";
            //             }
            //             var elms2 = document.querySelectorAll(
            //                 '[id="food_add_' + item.id + 'r"]'
            //             );
            //             for (var i = 0; i < elms2.length; i++) {
            //                 elms2[i].style.display = "none";
            //             }

            //             var elms3 = document.querySelectorAll(
            //                 '[id="food_qty_' + item.id + 'r"]'
            //             );
            //             for (var i = 0; i < elms3.length; i++) {
            //                 elms3[i].value = item.quantity;
            //             }
            //             // document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
            //             // document.getElementById('food_add_' + item.id+'r').style.display = 'none';

            //             // document.getElementById('food_qty_' + item.id+'r').value = item.quantity;
            //         }

            //         total_price += parseFloat(
            //             (
            //                 item.quantity *
            //                 (item.price + item.addonsPrice)
            //             ).toFixed(2)
            //         );
            //         total_item++;
            //         let thisAmount = parseFloat(
            //             (
            //                 (item.price + item.addonsPrice) *
            //                 item.quantity
            //             ).toFixed(2)
            //         );
            //         tot_tax += parseFloat((thisAmount / 100) * item.taxperc);
            //         localStorage.setItem("tot_tax", tot_tax);
            //         if (is_tax == 0) {
            //             tot_amt = total_price + tot_tax;
            //         } else {
            //             tot_amt = total_price;
            //         }

            //         item_amount_total += thisAmount;
            //     });
            //     restaurant_packaging_charge = parseFloat(
            //         (item_amount_total / 100) * packaging_charge
            //     );
            //     var dc = 1;
            //     if (dc == 1) {
            //         var delivery_charge_calc =
            //             this.calc_delivery_charge(tot_amt);
            //     } else {
            //         var delivery_charge_calc = 0;
            //         var tips = 0;
            //     }
            //     var offer_discount = 0;
            //     total_price =
            //         tot_amt === 0
            //             ? tot_amt
            //             : (
            //                   tot_amt +
            //                   restaurant_packaging_charge +
            //                   delivery_charge_calc
            //               ).toFixed(2);
            //     localStorage.setItem("tot_amt", total_price);
            //     // var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':0,'bill':total_price};
            //     var bill = {
            //         item_total: tot_amt,
            //         packaging_charge: restaurant_packaging_charge,
            //         delivery_charge: delivery_charge_calc,
            //         discount: offer_discount,
            //         bill: total_price,
            //         tips: tips,
            //         dc: dc,
            //         tax: tot_tax,
            //     };
            //     localStorage.setItem("bill", bill);
            //     store.commit("deliware_cart/UPDATE_CART_TOTAL", bill);
            //     this.loadcoupon();
            // }
        },

        calc_delivery_charge(total_price) {
            var DC = JSON.parse(localStorage.getItem("DC"));
            if (DC) {
                var delivery_charge =
                    typeof DELIVERY_CHARGE !== "undefined"
                        ? DELIVERY_CHARGE
                        : DC;
            } else {
                var delivery_charge = [];
            }

            var DELIVERY_CHARGE_TYPE = JSON.parse(
                localStorage.getItem("DELIVERY_CHARGE_TYPE")
            );
            var delivery_charge_type = parseFloat(
                typeof DELIVERY_CHARGE_TYPE !== "undefined"
                    ? DELIVERY_CHARGE_TYPE
                    : 0
            );

            var delivery_charge_calc = 0; // Assigned 0 because if the list is empty loop will not execute.
            var deliver_charge_mode = JSON.parse(
                localStorage.getItem("DC_BON")
            );
            var deliver_distance = localStorage.getItem("DISTANCE");
            var total = 0;
            var tot = 0;
            var delivery_charge_calc1 = 0;
            var delivery_charge_calc2 = 0;
            var delivery_charge_calc3 = 0;
            var dc_rate = 0;
            var dc_total = 0;
            for (let each_delivery_charge of delivery_charge) {
                // Total price should be greater than minimum AND (less than maximum or maximum can be null(infinity))

                //price
                /*if (deliver_charge_mode == 1 &&
                total_price >= each_delivery_charge.Minimum &&
                (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum) && delivery_charge_type == 1 removed this dctype
                ) */
                if (deliver_charge_mode == 1) {
                    if (
                        total_price >= each_delivery_charge.Minimum &&
                        (each_delivery_charge.Maximum == null ||
                            total_price <= each_delivery_charge.Maximum)
                    ) {
                        delivery_charge_calc = parseFloat(
                            each_delivery_charge.Value
                        );
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
                        (each_delivery_charge.Maximum == null ||
                            dc <= each_delivery_charge.Maximum) &&
                        each_delivery_charge.Type == 1
                    ) {
                        var delivery_charge_calc1 = parseFloat(
                            each_delivery_charge.Value
                        );
                        delivery_charge_calc3 += delivery_charge_calc1;
                        delivery_charge_type = each_delivery_charge.Type;
                    }
                    for (var i = 1; i <= deliver_distance; i++) {
                        if (
                            i <= each_delivery_charge.Maximum &&
                            i >= each_delivery_charge.Minimum &&
                            each_delivery_charge.Type == 3
                        ) {
                            var total = each_delivery_charge.Value * 1;
                            tot += total;
                            var delivery_charge_calc2 = parseFloat(tot);
                        } else {
                            if (i == each_delivery_charge.Maximum) {
                                var dc_rate = each_delivery_charge.Value;
                            }
                        }
                        var dckm_rate =
                            parseFloat(dc_rate) +
                            parseFloat(delivery_charge_calc2);
                    }
                    // calculat tot have diff type
                    if (dckm_rate) {
                        var delivery_charge_calc =
                            parseFloat(delivery_charge_calc3) +
                            parseFloat(dckm_rate);
                    } else {
                        var delivery_charge_calc = parseFloat(
                            delivery_charge_calc3
                        );
                    }

                    dc_total += delivery_charge_calc;
                }
            }

            //If calculation type is percentage, then calculate the percentage

            if (deliver_charge_mode == 1 && delivery_charge_type == 2) {
                delivery_charge_calc = parseFloat(
                    (total_price / 100) * delivery_charge_calc
                );
            }

            delivery_charge_calc = parseFloat(
                parseFloat(delivery_charge_calc).toFixed(2)
            );
            return delivery_charge_calc;
        },

        addCustomizedProduct(selected) {
            localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");

            let id = selected.id,
                restaurant = selected.restaurant,
                isveg = selected.isveg,
                restaurant_name = selected.restaurant_name,
                restaurant_address = selected.restaurant_address,
                restaurant_image = selected.restaurant_image,
                taxperc = selected.taxperc,
                name = selected.name,
                food_image = selected.food_image,
                cart = JSON.parse(localStorage.getItem("store_cart"));
            let price = parseFloat(selected.price);

            let selectedSize = undefined;
            let selectedSizeName = undefined;
            let selectedSizePrice = "";
            if (
                document.querySelector(
                    'input[name="sizesOfItem_' + id + '"]:checked'
                )
            ) {
                selectedSize = document.querySelector(
                    'input[name="sizesOfItem_' + id + '"]:checked'
                ).value;
                selectedSizeName = document
                    .querySelector(
                        'input[name="sizesOfItem_' + id + '"]:checked'
                    )
                    .getAttribute("data-name");
                selectedSizePrice = document
                    .querySelector(
                        'input[name="sizesOfItem_' + id + '"]:checked'
                    )
                    .getAttribute("data-price");
            }

            selectedSize = selectedSize == undefined ? "" : selectedSize;

            selectedSizePrice = isNaN(selectedSizePrice)
                ? 0
                : selectedSizePrice;
            if (selectedSizePrice > 0) {
                price = selectedSizePrice;
            }

            let selectedAddons = [];
            let selectedAddonsName = [];
            let selectedAddonsPrice = 0;

            document
                .querySelectorAll(
                    'input[name="addonsOfItem_' + id + '"]:checked'
                )
                .forEach(function (index, value) {
                    selectedAddons.push(index.value);
                    selectedAddonsName.push(index.getAttribute("data-name"));
                    selectedAddonsPrice += parseFloat(
                        index.getAttribute("data-price")
                    );
                });
            let selectedAddonsString = selectedAddons.join(",");
            selectedAddonsName = selectedAddonsName.join(", ");

            selectedAddonsPrice = parseFloat(selectedAddonsPrice);
            if (cart.length && cart[0].restaurant != restaurant) {
                let ok = confirm("The items previously will be deleted");
                if (ok) {
                    cart = [];
                    store.commit(
                        "deliware_cart/UPDATE_CART_ITEMS_COUNT",
                        store.state["deliware_cart"].cartItemsCount - 1
                    );
                } else return;
            }
            if (cart.length != 0) {
                if (
                    typeof selectedSizeName == "undefined" &&
                    selectedAddonsName != ""
                ) {
                    let food_isPresent = cart.some(function (el) {
                        return el.id === id;
                    });
                    let food_addon_isPresent = cart.some(function (el) {
                        return el.addonsName === selectedAddonsName;
                    });

                    if (
                        food_isPresent === true &&
                        food_addon_isPresent === true
                    ) {
                        cart = localStorage.getItem("store_cart");
                        cart = JSON.parse(cart);
                        for (var i = 0; i < cart.length; i++) {
                            if (
                                cart[i].addonsName === selectedAddonsName &&
                                cart[i].id === id
                            ) {
                                cart[i].quantity = cart[i].quantity + 1;
                                break;
                            }
                        }
                    } else {
                        cart.push({
                            id: id,
                            name: name,
                            isveg: isveg,
                            price: parseFloat(parseFloat(price).toFixed(2)),
                            quantity: 1,
                            restaurant: restaurant,
                            addons: selectedAddonsString,
                            sizes: selectedSize,
                            addonsName: selectedAddonsName,
                            addonsPrice: selectedAddonsPrice,
                            sizeName: selectedSizeName,
                            restaurant_name: restaurant_name,
                            restaurant_address: restaurant_address,
                            restaurant_image: restaurant_image,
                            sizePrice: selectedSizePrice,
                            taxperc: taxperc,
                            food_image: food_image,
                        });
                    }
                } else {
                    if (selectedSizeName != "" && selectedAddonsName != "") {
                        let food_isPresent = cart.some(function (el) {
                            return el.id === id;
                        });

                        let food_addon_isPresent = cart.some(function (el) {
                            return el.addonsName === selectedAddonsName;
                        });
                        let food_size_isPresent = cart.some(function (el) {
                            return el.sizeName === selectedSizeName;
                        });
                        if (
                            food_isPresent === true &&
                            food_addon_isPresent === true &&
                            food_size_isPresent == true
                        ) {
                            cart = localStorage.getItem("store_cart");
                            cart = JSON.parse(cart);
                            for (var i = 0; i < cart.length; i++) {
                                let temp_loop_count = i + 1;
                                if (
                                    cart[i].addonsName === selectedAddonsName &&
                                    cart[i].sizeName === selectedSizeName &&
                                    cart[i].id === id
                                ) {
                                    cart[i].quantity = cart[i].quantity + 1;
                                    break;
                                }
                                if (temp_loop_count == cart.length) {
                                    cart.push({
                                        id: id,
                                        name: name,
                                        isveg: isveg,
                                        price: parseFloat(
                                            parseFloat(price).toFixed(2)
                                        ),
                                        quantity: 1,
                                        restaurant: restaurant,
                                        addons: selectedAddonsString,
                                        sizes: selectedSize,
                                        addonsName: selectedAddonsName,
                                        addonsPrice: selectedAddonsPrice,
                                        sizeName: selectedSizeName,
                                        restaurant_name: restaurant_name,
                                        restaurant_address: restaurant_address,
                                        restaurant_image: restaurant_image,
                                        sizePrice: selectedSizePrice,
                                        taxperc: taxperc,
                                        food_image: food_image,
                                    });
                                    break;
                                }
                            }
                        } else {
                            cart.push({
                                id: id,
                                name: name,
                                isveg: isveg,
                                price: parseFloat(parseFloat(price).toFixed(2)),
                                quantity: 1,
                                restaurant: restaurant,
                                addons: selectedAddonsString,
                                sizes: selectedSize,
                                addonsName: selectedAddonsName,
                                addonsPrice: selectedAddonsPrice,
                                sizeName: selectedSizeName,
                                restaurant_name: restaurant_name,
                                restaurant_address: restaurant_address,
                                restaurant_image: restaurant_image,
                                sizePrice: selectedSizePrice,
                                taxperc: taxperc,
                                food_image: food_image,
                            });
                        }
                    }
                    if (selectedSizeName != "" && selectedAddonsName === "") {
                        let food_isPresent = cart.some(function (el) {
                            return el.id === id;
                        });
                        let food_size_isPresent = cart.some(function (el) {
                            return el.sizeName === selectedSizeName;
                        });
                        if (
                            food_isPresent === true &&
                            food_size_isPresent == true
                        ) {
                            cart = localStorage.getItem("store_cart");
                            cart = JSON.parse(cart);
                            for (var i = 0; i < cart.length; i++) {
                                let temp_loop_count = i + 1;
                                if (
                                    cart[i].addonsName === selectedAddonsName &&
                                    cart[i].sizeName === selectedSizeName &&
                                    cart[i].id === id
                                ) {
                                    cart[i].quantity = cart[i].quantity + 1;
                                    break;
                                }
                                if (temp_loop_count == cart.length) {
                                    cart.push({
                                        id: id,
                                        name: name,
                                        isveg: isveg,
                                        price: parseFloat(
                                            parseFloat(price).toFixed(2)
                                        ),
                                        quantity: 1,
                                        restaurant: restaurant,
                                        addons: selectedAddonsString,
                                        sizes: selectedSize,
                                        addonsName: selectedAddonsName,
                                        addonsPrice: selectedAddonsPrice,
                                        sizeName: selectedSizeName,
                                        restaurant_name: restaurant_name,
                                        restaurant_address: restaurant_address,
                                        restaurant_image: restaurant_image,
                                        sizePrice: selectedSizePrice,
                                        taxperc: taxperc,
                                        food_image: food_image,
                                    });
                                    break;
                                }
                            }
                        } else {
                            cart.push({
                                id: id,
                                name: name,
                                isveg: isveg,
                                price: parseFloat(parseFloat(price).toFixed(2)),
                                quantity: 1,
                                restaurant: restaurant,
                                addons: selectedAddonsString,
                                sizes: selectedSize,
                                addonsName: selectedAddonsName,
                                addonsPrice: selectedAddonsPrice,
                                sizeName: selectedSizeName,
                                restaurant_name: restaurant_name,
                                restaurant_address: restaurant_address,
                                restaurant_image: restaurant_image,
                                sizePrice: selectedSizePrice,
                                taxperc: taxperc,
                                food_image: food_image,
                            });
                        }
                    }
                }
            } else {
                cart.push({
                    id: id,
                    name: name,
                    isveg: isveg,
                    price: parseFloat(parseFloat(price).toFixed(2)),
                    quantity: 1,
                    restaurant: restaurant,
                    addons: selectedAddonsString,
                    sizes: selectedSize,
                    addonsName: selectedAddonsName,
                    addonsPrice: selectedAddonsPrice,
                    sizeName: selectedSizeName,
                    restaurant_name: restaurant_name,
                    restaurant_address: restaurant_address,
                    restaurant_image: restaurant_image,
                    sizePrice: selectedSizePrice,
                    taxperc: taxperc,
                    food_image: food_image,
                });
            }
            localStorage.setItem("store_cart", JSON.stringify(cart));
            let cartn = JSON.parse(localStorage.getItem("store_cart"));

            store.commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", cartn.length);
            store.commit("deliware_cart/UPDATE_CART_ITEMS", cartn);
            this.$refs["addon-modal"].hide();
            this.loadcart();
        },

        removefromcart: function (
            price,
            item_tax,
            name,
            food_id,
            is_veg,
            add_ons,
            food_quantity,
            food_image
        ) {
            localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
            this.itemid = food_id;
            let id = food_id,
                cart = JSON.parse(localStorage.getItem("store_cart"));
            price = parseFloat(price);

            let foodIndex = cart.findIndex(function (food, index) {
                return food.id == id;
            });

            if (foodIndex !== -1) {
                if (cart[foodIndex].quantity > 0) cart[foodIndex].quantity--;
                if (cart[foodIndex].quantity <= 0) {
                    cart.splice(foodIndex, 1);
                    var elms = document.querySelectorAll(
                        '[id="food_control_' + id + 'r"]'
                    );

                    for (var i = 0; i < elms.length; i++) {
                        elms[i].style.display = "none";
                    }
                    var elms2 = document.querySelectorAll(
                        '[id="food_add_' + id + 'r"]'
                    );

                    for (var i = 0; i < elms2.length; i++) {
                        elms2[i].style.display = "inline-block";
                    }

                    // document.getElementById('food_control_' + id +'r').style.display = 'none';
                    // document.getElementById('food_add_' + id +'r').style.display = 'inline-block';
                }
            }

            localStorage.setItem("store_cart", JSON.stringify(cart));

            let cartn = JSON.parse(localStorage.getItem("store_cart"));

            store.commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", cartn.length);
            store.commit("deliware_cart/UPDATE_CART_ITEMS", cartn);

            this.loadcart();
        },
    },

    created() {
        this.$store.commit("verticalMenu/UPDATE_VERTICAL_MENU_COLLAPSED", true);
        this.$http.get("/store/get_currency").then((res) => {
            this.setting = res.data;
        });

        localStorage.removeItem("store_cart");
        this.$http.get('/store/single_restaurant/'+ this.rest_id )
          .then(res => {
          
              this.rows = res.data.restaurants
              if(this.rows[0].is_open != 1){
                  this.isopen =  false
              }
              this.promocodes = res.data.promocodes
              console.log(this.rows)
           
              })
       
        this.$http
            .get(
                "/store/get_food_list_by_menu_pos/" +
                    this.rest_id 
                   
            )
            .then((res) => {
                this.rows_detail = res.data.items;
               this.business_type =res.data.business_type;
                this.rows_detail2 = res.data.items;
                this.isLoading = false;
            });

    },
    destroyed() {
        this.$store.commit(
            "verticalMenu/UPDATE_VERTICAL_MENU_COLLAPSED",
            false
        );
    },
};
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-autosuggest.scss';
</style>
<style lang="scss">
@import "~@resources/scss/vue/pages/page-faq.scss";
</style>
<style scoped>
.nav-link {
    color: black;
}
.text-yellow {
    color: #ffbd00;
}
.text-white {
    color: white;
}
</style>
<style lang="scss">
@import "~@resources/scss/vue/libs/vue-slider.scss";
</style>

<style lang="scss" scoped>
.swiper-slide {
    width: 20%;
    height: 100%;
}

.rounded3 {
    cursor: pointer;
}
.categories-radio-group,
.brands-radio-group,
.price-range-defined-radio-group {
    ::v-deep > .custom-control {
        margin-bottom: 0.75rem;
    }
}
.count-number-input {
    width: 40px;
    text-align: center;
    margin: 0 -4px;
    background: #f4fffd;
    border: 1px solid #82868b !important;
    color: #282828;
    height: 24px;
    border-radius: 0px;
    vertical-align: bottom;
}
.b-sidebar {
    min-width: 420px !important;
}
.btn-secondary {
    border-color: #fff !important;
    background-color: transparent !important;
}
.cart-bord {
    border-top: 1px solid #e6e6e6;
    padding: 20px 0px;
    border-bottom: 1px solid #e6e6e6;
    
}
.sidebar-shop {
    cursor: pointer;
}
.bor {
    border-radius: 10px;
}
.side {
    padding: 0px 12px 0px 0px;
    line-height: 3;
    cursor: pointer;
}
.s-border {
    border-right: 2px solid #e6e6e6;
    min-height: 400px;
}

.active {
    background-color: #f4fffd;
    border-right: 4px solid rgb(0 160 130 / 70%);
    padding: 0px 30px 0px 0px;
    font-weight: bold;
}
.restaurant-detailed-header {
    bottom: 0;
    left: 0;
    padding: 65px 0 17px;
    position: absolute;
    right: 0;
    margin-bottom: 40px;
}
.bg-color {
    background-color: #000 !important;
}
.overlay {
    background-color: #000;
    /* position: absolute; */
    opacity: 0.5;
}
.mborderw {
    font-family: sans-serif;
    color: #fff;
    padding: 10px;
    border-radius: 30px !important;
    border: 2px solid #fff;
    margin: 0px 5px;
    text-align: center;
}
.mborderw2 {
    width: 40px;
    font-family: sans-serif;
    color: #fff;
    padding: 10px;
    border-radius: 30px !important;
    border: 2px solid #fff;
    margin: 0px 5px;
    text-align: center;
}
.navbar-sticky .app-content {
    padding: 0px;
}

.pad0 .card-body {
    padding: 0rem !important;
}
.sticky-top3 {
    position: -webkit-sticky;
    position: sticky;
    z-index: 0;
    top: 100px;
    overflow-y: scroll;
    /*max-height: 600px;
    margin-bottom: 50px;*/
}
.textp {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* number of lines to show */
    line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 36px;
}
.textp2 {
    color: #e6e6e6;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* number of lines to show */
    line-clamp: 2;
    -webkit-box-orient: vertical;
}

.btn-icon {
    padding: 5px;
}
.btn-scroll-to-top {
    background-color: #ffbd00 !important;
    border-radius: 100px;
    border: 1px solid #ffbd00 !important;
    color: #000;
    display: inline-block;
    padding: 20px;
}
.swiper-button-prev,
.swiper-button-next {
    color: #00a082;
}
.skin-2 .num-in {
    background: #ffffff;
    box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.15);
    border-radius: 25px;
    height: 30px;
    width: 80px;
    float: left;
}

.skin-2 .num-in span {
    width: 40%;
    display: block;
    height: 30px;
    float: left;
    position: relative;
}

.skin-2 .num-in span:before,
.skin-2 .num-in span:after {
    content: "";
    position: absolute;
    background-color: #667780;
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
    height: 30px;
    border: none;
    text-align: center;
}
.pad0{padding-left: 0px; padding-right: 0px;}
</style>
<style scoped>
.swiper-button-next,
.swiper-container-rtl .swiper-button-prev {
    right: 10px;
    left: auto;
    /* top: -30px; 
    margin-top: -68px;*/
    /* z-index: 999999; */
    background-color: #f4fffd90;
    border-radius: 10px;
    border: 2px solid #00a082;
    top: 45%;
}
.swiper-button-prev,
.swiper-container-rtl .swiper-button-next {
    left: 10px;
    right: auto;
    background-color: #f4fffd90;
    border-radius: 10px;
    border: 2px solid #00a082;
    top: 45%;
}
.tipsactive1{
  border-color: #ffbd00 !important;
  background-color: #ffbd00 !important;
}
</style>

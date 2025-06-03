<template>
  <div class="container" style="padding: 0px 20px;">
    <div class=" mt-1 text-center"
      style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;"
      v-if="!cart.length">
      <!-- <h4>No Items in Cart</h4> -->
      <lottie-player src="/animations/basket.json" background="#FFFDFD" speed="1" style="width: 300px; height: 300px;"
        loop autoplay></lottie-player>
      <h4 class="text-center">{{ $t("GOOD FOOD IS ALWAYS COOKING") }}</h4>
      <P class="text-center">{{ $t("Your Cart is empty, Add something from the Menu") }}</P>
    </div>
    <div class="text-l" v-if="cart.length">
      <h4>{{ $t("Your Order") }}</h4>

      <div class="media-list">
        <b-media vertical-align="top" style="padding: 30px 0px 30px 0px;">
          <template #aside v-if="cart[0].restaurant_image">
            <b-img class="bor shadow-sm" :src="cart[0].restaurant_image" blank-color="#ccc" width="100" />
          </template>
          <h4 class="media-heading">
            {{ cart[0].restaurant_name }}
          </h4>
          <b-card-text class="mb-0 textp">
            <feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{ cart[0].restaurant_address }}
          </b-card-text>

        </b-media>
      </div>
      <div id="delivery_type" class="text-center">
        <label class="btn btn-secondary tiplabel tiphide  deli f-size-0 tipsactive" id="delivery" type="button"> <input
            type="radio" class="checktips" hidden name="d_type" id="delivery" @change="delivery_type(1)" value="1"
            checked><b-img src="/deliveryman.png" fluid alt="Scan" style="width:70%;" /><span style="color:#282828;">{{
              $t("Delivery") }}</span></label>

        OR
        <label class="btn btn-secondary tiplabel tiphide take f-size-0 " id="takeaway" type="button"> <input
            type="radio" class="checktips" hidden name="d_type" id="takeaway" @change="delivery_type(2)" value="2"><span
            style="color:#282828;"><b-img src="/walk.png" fluid alt="Scan" style="width:70%;" />
            {{ $t("Take Away") }}</span></label>
      </div>
      <div id="takeaway_address" style="display:none;  padding-top: 20px;" class="cart-bord">
        <h4>{{ $t("Take Away") }} {{ $t("From") }}</h4>
        <p id="demo"><feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{ cart[0].restaurant_address }}
        </p>
      </div>
      <div id="delivery_address" style="display:block; padding-top: 20px;" class="cart-bord">

        <h4>{{ $t("DeliveringTo") }}</h4>
        <p id="demo"><feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{ rows }}</p>


        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="checklogin()" variant="flat-primary"
          style="padding:0px;">
          <i class="fa-solid fa-circle-plus"></i> <span class="f-size-0"><b> {{ $t("Add") }} {{ $t("New") }}
              {{ $t("Address") }}</b></span>
        </b-button>
        <b-sidebar id="sidebar-address" bg-variant="white" right backdrop shadow ref="a_sidebar">
          <address_sidebar :prop="send_address" @clicked-show-detail="check" />
        </b-sidebar>
      </div>

      <div v-for="(data, index) in $store.state['deliware_cart'].cart_items" :key="index">
        <b-row>
          <b-col cols="12" style="padding: 1.25rem;">
            <b-media vertical-align="top">
              <template #aside v-if="data.food_image">
                <b-img class="bor" :src="data.food_image" blank-color="#ccc" width="70" />
              </template>
              <h4 class="media-heading">
                <div v-if="data.isveg">
                  <b-img src="/veg.svg" fluid alt="Scan" style="width: 12px;" /> {{ data.name }}
                </div>
                <div v-else>
                  <b-img src="/non_veg.svg" fluid alt="Scan" style="width: 12px;" /> {{ data.name }}
                </div>

              </h4>
              <b-card-text class="mb-0 textp" v-if="data.addonsName">
                {{ $t("Addons") }} : {{ data.addonsName }}
              </b-card-text>
              <b-card-text class="mb-0 textp" v-if="data.sizeName">
                <!-- {{ $t("Size") }} : {{ data.sizeName }} -->
                <b-row>
                  <!-- {{ $t("Color") }} : -->
                  <div class="circleBase type1" :style="{ background: data.sizeName }">
                  </div>
                </b-row>
              </b-card-text>
              <b-row class="pt-3">
                <b-col cols="6">
                  <h4 class="media-heading text-primary">
                    {{ $store.state['defaults'].currency }} {{ data.price + data.addonsPrice | priceformat }}
                  </h4>
                </b-col>

                <b-col cols="6">

                  <div style="">
                    <!-- <span class="count-number float-right" :id="'food_control_'+data.id+'c'" >
                         <div class="input-group">
                             <span class="input-group-btn">
                               <button :id="'food_minus_'+data.id" class="btn btn-outline-secondary quantity-left-minus  remove_from_cart btn-sm left dec" 
                                 @click="removefromcart(data.price,data.taxperc,data.name,data.id,data.isveg,data.addons,data.sizes,data.food_image,index)"> <i class="fa-solid fa-minus"></i></button>
                             </span>
                             <input :id="'food_qty_'+data.id+'c'"  class="form-control input-number count-number-input" type="text" :value="data.quantity" readonly="">
                             <span class="input-group-btn">
                               <button :id="'food_plus_'+data.id" class="btn btn-outline-secondary quantity-right-plus add_to_cart btn-sm right inc" 
                                 @click="addcart(data.price,data.taxperc,data.name,data.id,data.isveg,data.addons,data.sizes,data.food_image,index)"> <i class="fa-solid fa-plus"></i> </button>
                             </span>
                         </div>
                       </span> -->


                    <div class="num-block skin-2" :id="'food_control_' + data.id + 'c'">
                      <div class="num-in">
                        <span class="minus dis" :id="'food_minus_' + data.id"
                          @click="removefromcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index)"></span>
                        <input type="text" class="in-num" value="1" readonly="" :id="'food_qty_' + data.id + 'c'">
                        <span class="plus" :id="'food_plus_' + data.id"
                          @click="addcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index)"></span>
                      </div>
                    </div>
                  </div>
                </b-col>




              </b-row>
            </b-media>
          </b-col>
        </b-row>
      </div>
      <div id="coupon" class="cart-bord" style="padding:20px 0px;">
        <!-- <h4>Coupon</h4> -->

        <b-input-group class="input-group-merge coupons">
          <b-form-input placeholder="Enter Coupon Code" id="checkout_coupon_value"
            style="height:50px;text-transform:uppercase" />
          <b-input-group-append is-text>
            <b-button variant="primary" id="applied_coupon_identify" @click="applycoupon()"
              class="text-yellow mborderw">
              {{ $t("Apply") }}
            </b-button>

          </b-input-group-append>
        </b-input-group>
        <input type="hidden" id="applied_coupon_Amount">
      </div>
      <div id="tips" class="pt-0 " style="
    padding-bottom:20px;
    border-bottom: 1px solid #e6e6e6;
    margin-top: 20px;">
        <h4>{{ $t("Tips") }}</h4>
        <small>{{ tips_text }}</small>
        <b-row>
          <div v-for="(data, index) in tips" :key="index">

            <label class="btn btn-primary tiplabel tiphide  f-size-0 " :id="'tips' + data" type="button"
              style="margin-right: 20px; margin-left: 10px; padding: 10px; "> <input type="radio" class="checktips"
                hidden name="tips" :id="'tips' + data" @change="addtips(data)" :value=data>{{
                  $store.state['defaults'].currency
              }}
              {{ data | priceformat }}</label>
          </div>
          <label class="btn btn-primary tiplabel tiphide  f-size-0 " id="ctips" type="button"
            style="margin-right: 20px; margin-left: 10px; padding: 10px; " @click="showtip()"> {{ $t("Other") }}</label>

          <b-form-input placeholder="Enter Tips Amount" id="cutomtip" @input="addctips()"
            onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
            style="display: none;height:50px; margin: 20px;" />
          <input type="hidden" id="selected_tips">
        </b-row>
      </div>

      <div id="bill" class="pt-4" style="margin-bottom:50px">
        <h4>{{ $t("Bill Details") }}</h4>
        <table width="100%">

          <tr>
            <td>{{ $t("Item Total") }}</td>
            <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.item_total |
              priceformat }}</td>
          </tr>
          <tr v-if="$store.state['deliware_cart'].cart_total.packaging_charge > 0">
            <td>{{ $t("Packaging Charge") }}</td>
            <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.packaging_charge |
              priceformat }}</td>
          </tr>
          <tr v-if="$store.state['deliware_cart'].cart_total.discount > 0">
            <td>{{ $t("Discount") }}</td>
            <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.discount |
              priceformat
              }}
            </td>
          </tr>
          <tr id="dcharge_bill" v-if="$store.state['deliware_cart'].cart_total.delivery_charge > 0">
            <td>{{ $t("Delivery Charge") }}</td>
            <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.delivery_charge |
              priceformat }}</td>
          </tr>
          <tr id="tips_bill" v-if="$store.state['deliware_cart'].cart_total.tips > 0">
            <td>{{ $t("Tips") }}</td>
            <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.tips | priceformat
              }}
            </td>
          </tr>
          <tr id="tips_bill" v-if="$store.state['deliware_cart'].cart_total.tax > 0">
            <td>{{ $t("Tax") }} <span>{{ tax($store.state['defaults'].tax) }}</span></td>
            <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.tax | priceformat }}
            </td>
          </tr>
          <tr>
            <td>
              <b>{{ $t("Bill Total") }} </b>
            </td>
            <td>
              {{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.bill | priceformat }}
            </td>
          </tr>


        </table>
      </div>

      <div id="proceed" class="pb-3">
        <b-col md="12">
          <div class="demo-inline-spacing "
            style="position: fixed;
        z-index: 9999;  left: 10px;           bottom: 0;          width: 100%;          background-color:#fff;padding: 10px;">
            <b-button style="height:50px;" class="f-size-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'" block
              variant="primary" @click="checkout()" v-if="isdefault">
              {{ $t("Proceed") }}
            </b-button>
            <b-button style="height:50px;" class="f-size-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              @click="checklogin()" v-if="!isdefault">
              Select Address
            </b-button>

          </div>
        </b-col>

      </div>
      <loginmodal v-model="modalOpen"></loginmodal>

    </div>
  </div>
</template>

<script>
import {
  BRow,
  BCol,
  BAvatar, BListGroup, BListGroupItem, BCardText, BImg, BMedia, BButton, BFormSpinbutton, BCard, BFormRadio, BInputGroup, BFormInput, BInputGroupAppend, BSidebar, VBToggle,
} from 'bootstrap-vue'
import store from '@@@/store'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import Ripple from 'vue-ripple-directive'
import address_sidebar from './address_sidebar.vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import loginmodal from './loginmodal.vue'
import axios from '@axios'

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
    BCard,
    VuePerfectScrollbar,
    BFormRadio,
    BInputGroup, BFormInput, BInputGroupAppend, BSidebar,
    address_sidebar,
    loginmodal,
  },
  directives: {
    'b-toggle': VBToggle,
    Ripple,
  },
  computed: {

    tax() {
      const taxtext = {


        0: '(excl)',
        1: '(incl)',

      }

      return tax => taxtext[tax]
    },


  },
  props: {
    cart: {
      type: Array,
      default: () => { },
    },

  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem('webuserData')),
      rows: localStorage.getItem('address'),
      rows_detail: {},
      rows_detail2: {},
      isLoading: true,
      isfav: false,
      address: '',

      add_ons: {},
      food_quantity: {},
      itemid: '',
      selected: {},
      quantity: [],
      tips: [10, 20, 30],
      isdefault: false,
      send_address: {},
      modalOpen: false,
      tips_text: ''
    }
  },


  filters: {
    priceformat(number) {
      var decimals = store.state['defaults'].decimal;
      var dec_point = store.state['defaults'].delimiter;
      var thousands_sep = '';
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

      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);


    },
    price_format_final: function (price) {
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
  created() {
    this.$http.get('/get_tips')
      .then(res => {
        this.tips = res.data.tips
        this.tips_text = res.data.tips_text
      })

    if (localStorage.getItem("cart") && JSON.parse(localStorage.getItem('cart')).length > 0) {
      this.loadcart()
    }

    if (localStorage.getItem('webuserData')) {
      var user = JSON.parse(localStorage.getItem('webuserData'))
      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');

      this.$http.get('/get_address/' + lat + '/' + lng)
        .then(res => {
          this.rows = res.data.data
          localStorage.setItem('address', this.rows);
        })


      this.$http.get('/get_near_address/' + lat + '/' + lng + '/' + user.id)
        .then(res => {
          if (res.data.status == true) {
            this.isdefault = true
          }
        })
    }

  },
  methods: {
    check: function (value) {
      this.$root.$emit('bv::toggle::collapse', 'sidebar-address')
      var user = JSON.parse(localStorage.getItem('webuserData'))
      this.isdefault = true
      // var lat = localStorage.getItem('latitude');
      // var lng = localStorage.getItem('longitude');
      // this.$http.get('/get_near_address/'+lat+'/'+lng+'/'+user.id)
      //       .then(res => {
      //         console.log(res)
      //         if(res.data.status == true){
      //           this.isdefault = true
      //         }
      //       })
      this.rows = value
      this.loadcart()
    },
    close_sidebar() {
      this.$refs.sidebar.hide();
    },
    checklogin() {
      if (localStorage.getItem('webuserData')) {
        let user = JSON.parse(localStorage.getItem("webuserData"));
        this.$http.get('/get_delivery_address/' + user.id)
          .then(res => {

            this.send_address = res.data.data


          })
        this.$root.$emit('bv::toggle::collapse', 'sidebar-address')
        // this.$refs['a_sidebar'].show()
      } else {
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
    checkout() {
      if (localStorage.getItem('webuserData')) {
        let cart = JSON.parse(localStorage.getItem("cart"));
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
        this.$http.get('/checkradius/' + cart[0].restaurant + '/' + lat + '/' + lng)
          .then(res => {
            if (res.data.status == true) {

              this.$router.push({ name: 'checkout' });

            } else {
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: "Restaurant Does not Come Under your Location",
                  icon: 'UserIcon',
                  variant: 'danger',
                },
              })
            }
          })
      } else {
        this.$toast({
          component: ToastificationContent,
          position: 'bottom-right',
          props: {
            title: "Please Login to Continue",
            icon: 'UserIcon',
            variant: 'danger',
          },
        })
      }
    },
    delivery_type(type) {
      if (type == 1) {
        document.getElementById("takeaway_address").style.display = "none";
        document.getElementById("delivery_address").style.display = "block";
        document.getElementById("delivery").classList.add("tipsactive");
        document.getElementById("takeaway").classList.remove("tipsactive");

      } else {
        document.getElementById("takeaway_address").style.display = "block";
        document.getElementById("delivery_address").style.display = "none";
        document.getElementById("takeaway").classList.add("tipsactive");
        document.getElementById("delivery").classList.remove("tipsactive");
      }
      this.loadcart()
    },
    applycoupon() {



      if (localStorage.getItem("avail_res_coupon") === null) {
        alert("Invalid Coupon Code");
      } else {
        let restaurant_coupon_codes = JSON.parse(localStorage.getItem("avail_res_coupon"));
        let user_choosen_coupon = document.getElementById("checkout_coupon_value").value.toUpperCase();
        let temp;

        let total_cart_amount = parseFloat(localStorage.getItem('tot_amt'));
        for (let i = 0; i < restaurant_coupon_codes.length; i++) {

          let temp_loop_count = i + 1;
          if (restaurant_coupon_codes[i].couponcode == user_choosen_coupon) {
            if (restaurant_coupon_codes[i].coupontype == 0) {
              if (total_cart_amount >= restaurant_coupon_codes[i].couponminamount) {
                let offer_value = restaurant_coupon_codes[i].couponvalue;
                let max_discount_price = restaurant_coupon_codes[i].couponmaxamount;
                let final_total_price = "0";
                let discount_value = "0";
                if (max_discount_price != "0") {

                  let discount_value = ((total_cart_amount / 100) * offer_value);
                  localStorage.setItem('discount_value', discount_value);
                  document.getElementById("applied_coupon_Amount").value = discount_value;
                  let final_total_price = total_cart_amount - discount_value;
                  max_discount_price = restaurant_coupon_codes[i].couponmaxamount;
                  if (discount_value > max_discount_price) {
                    discount_value = max_discount_price;
                    localStorage.setItem('discount_value', discount_value);
                    document.getElementById("applied_coupon_Amount").value = discount_value;

                    let final_total_price = total_cart_amount - discount_value;
                    document.getElementById("checkout_coupon_value").value = user_choosen_coupon;

                    document.getElementById("applied_coupon_identify").style.display = "none";
                    document.getElementById("checkout_coupon_value").disabled = true;


                    break;
                  } else {

                    document.getElementById("applied_coupon_identify").style.display = "none";
                    document.getElementById("checkout_coupon_value").disabled = true;
                    document.getElementById("checkout_coupon_value").value = user_choosen_coupon;


                    break;
                  }
                } else {

                  $(".applied_coupon_identify").css("display", "block");
                  document.getElementById("applied_coupon_identify").style.display = "none";
                  document.getElementById("checkout_coupon_value").value = user_choosen_coupon;


                  break;
                }
              }
              else {
                alert("Cart amount must be greater than minimum amount");
              }
            } else if (restaurant_coupon_codes[i].coupontype == 1) {
              let offer_value = restaurant_coupon_codes[i].couponvalue;
              localStorage.setItem('discount_value', offer_value);
              document.getElementById("applied_coupon_Amount").value = offer_value;

              let max_discount_price = restaurant_coupon_codes[i].couponmaxamount;

              let final_total_price = total_cart_amount - offer_value;

              document.getElementById("applied_coupon_identify").style.display = "block";
              document.getElementById("checkout_coupon_value").disabled = true;
              document.getElementById("checkout_coupon_value").value = user_choosen_coupon;



              break;
            }
          }

          if (temp_loop_count == restaurant_coupon_codes.length) {
            alert("Invalid Coupon Code");
            break;
          }


        }
        this.loadcart()

      }
    },
    showtip() {
      document.getElementById("cutomtip").style.display = 'block';
      document.getElementById('ctips').classList.add("tipsactive1");

    },
    addctips() {

      var old = document.getElementById("selected_tips").value;
      if (document.getElementById('tips' + old)) {
        document.getElementById('tips' + old).classList.remove("tipsactive1");
      }

      var amt = document.getElementById("cutomtip").value;
      document.getElementById("selected_tips").value = amt;
      localStorage.setItem("tips", amt);
      document.getElementById('ctips').classList.add("tipsactive1");

      this.loadcart()
    },
    addtips(amt) {
      document.getElementById("cutomtip").style.display = 'none';
      document.getElementById("cutomtip").value = '';

      document.getElementById('ctips').classList.remove("tipsactive1");

      var old = document.getElementById("selected_tips").value;
      if (document.getElementById('tips' + old)) {
        document.getElementById('tips' + old).classList.remove("tipsactive1");
      }


      document.getElementById("selected_tips").value = amt;
      document.getElementById('tips' + amt).classList.add("tipsactive1");
      localStorage.setItem("tips", amt);

      this.loadcart()

    },
    addcart: function (price, item_tax, name, food_id, is_veg, add_ons, food_quantity, food_image, key) {
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
      let cart = JSON.parse(localStorage.getItem("cart"));

      this.itemid = food_id
      let id = food_id,
        restaurant = cart[0].restaurant,
        isveg = is_veg,
        restaurant_name = cart[0].name,
        restaurant_address = cart[0].restaurant_name,
        restaurant_image = cart[0].restaurant_image,
        taxperc = parseFloat(item_tax);



      let foodIndex = cart.findIndex(function (food, index) {
        return food.id == id;
      });
      if (key !== -1) {
        cart[key].quantity++;
        cart[key].addons += ' ';
        cart[key].sizes += ' ';
      } else {
        cart.push({
          id: id,
          name: name,
          isveg: isveg,
          price: parseFloat(parseFloat(price).toFixed(2)),
          quantity: 1,
          restaurant: restaurant,
          addons: '',
          sizes: '',
          addonsName: '',
          addonsPrice: 0,
          sizeName: '',
          restaurant_name: restaurant_name,
          restaurant_address: restaurant_address,
          restaurant_image: restaurant_image,
          sizePrice: 0,
          taxperc: taxperc,
          food_image: food_image,
        })
      }
      localStorage.setItem("cart", JSON.stringify(cart));
      let cartn = JSON.parse(localStorage.getItem("cart"));
      store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
      store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)

      this.loadcart()



    },
    number_format(number, decimals, dec_point, thousands_sep) {
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

      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
    },
    async loadcart() {
      let user = JSON.parse(localStorage.getItem("webuserData"));
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
      var DELIVERY_CHARGE = localStorage.getItem('DC');
      localStorage.getItem('DC', JSON.stringify(DELIVERY_CHARGE));

      var DELIVERY_CHARGE_TYPE = localStorage.getItem('DELIVERY_CHARGE_TYPE');


      var packaging_charge = localStorage.getItem('RES_PACK_CHARGE');


      var DELIVERY_CHARGE_BASEDON = localStorage.getItem('DC_BON');

      let cart = JSON.parse(localStorage.getItem("cart"));
      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');

      if (localStorage.getItem('webuserData')) {
        this.$http.get('/get_checkout_details/' + cart[0].restaurant + '/' + lat + '/' + lng + '/' + user.id)
          .then(res => {
            localStorage.setItem('DISTANCE', res.data.distance);
          })
      }
      // this.$http.get('/checkradius/'+cart[0].restaurant+'/'+lat+'/'+lng)
      //     .then(res => {
      //       if(res.data.status == true){
      //       }else{
      //         this.$toast({
      //                     component: ToastificationContent,
      //                     position: 'bottom-right',
      //                     props: {
      //                       title: "Restaurant Does not Come Under your Location",
      //                       icon: 'UserIcon',
      //                       variant: 'danger',
      //                     },
      //                   })
      //       }
      //     })

      // var offer_discount =localStorage.getItem('discount_value') || 0;
      if (document.getElementById("applied_coupon_Amount")) {
        var offer_discount = document.getElementById("applied_coupon_Amount").value || 0;

      } else {
        var offer_discount = 0;
      }


      let total_price = 0;
      let total_item = 0;
      let tot_tax = 0;
      let is_tax = localStorage.getItem('IS_TAX_INCLUSIVE');
      let tot_amt = 0;
      let item_amount_total = 0;
      let restaurant_packaging_charge = 0;

      if (packaging_charge == 0) {
        packaging_charge = JSON.parse(localStorage.getItem('RES_PACK_CHARGE'));

      }
      if (cart.length === 0) {

      } else {
        cart.forEach(function (item, i) {
          if (document.getElementById('food_control_' + item.id + 'r')) {

            var elms = document.querySelectorAll('[id="food_control_' + item.id + 'r"]');

            for (var i = 0; i < elms.length; i++) {
              elms[i].style.display = 'block';
            }

            var elms2 = document.querySelectorAll('[id="food_add_' + item.id + 'r"]');

            for (var i = 0; i < elms2.length; i++) {
              elms2[i].style.display = 'none';
            }

            var elms3 = document.querySelectorAll('[id="food_qty_' + item.id + 'r"]');

            for (var i = 0; i < elms3.length; i++) {
              elms3[i].value = item.quantity;
            }

            //  document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
            //  document.getElementById('food_add_' + item.id+'r').style.display = 'none';
            //  document.getElementById('food_qty_' + item.id+'r').value = item.quantity;

          }
          if (document.getElementById('food_qty_' + item.id + 'c')) {

            var elms4 = document.querySelectorAll('[id="food_qty_' + item.id + 'c"]');

            for (var i = 0; i < elms4.length; i++) {
              elms4[i].value = item.quantity;
            }
            //  document.getElementById('food_qty_' + item.id+'c').value = item.quantity;
          }



          total_price += parseFloat((item.quantity * (item.price + item.addonsPrice)).toFixed(2));
          total_item++;
          let thisAmount = parseFloat(((item.price + item.addonsPrice) * item.quantity).toFixed(2));
          tot_tax += parseFloat((thisAmount / 100) * item.taxperc);
          localStorage.setItem('tot_tax', tot_tax);
          if (is_tax == 0) {
            tot_amt = total_price + tot_tax;
          } else {
            tot_amt = total_price;
          }

          item_amount_total += thisAmount;

        })
        restaurant_packaging_charge = parseFloat((item_amount_total / 100) * packaging_charge);
        if (document.querySelector('input[name="d_type"]:checked')) {
          var dc = document.querySelector('input[name="d_type"]:checked').value;
        } else {
          var dc = 1;
        }

        if (dc == 1) {
          var delivery_charge_calc = await this.calc_delivery_charge(tot_amt, cart[0].restaurant);
          if (document.getElementById("selected_tips")) {
            var tips = document.getElementById("selected_tips").value || 0;
            document.getElementById("tips").style.display = "block";
          } else {
            var tips = 0;
          }


        } else {
          document.getElementById("tips").style.display = "none";

          var delivery_charge_calc = 0;
          var tips = 0;

        }

        total_price = tot_amt === 0 ? tot_amt : (tot_amt - parseFloat(offer_discount) + restaurant_packaging_charge + delivery_charge_calc + parseFloat(tips)).toFixed(2);
        localStorage.setItem('tot_amt', total_price);
        var bill = { 'item_total': tot_amt, 'packaging_charge': restaurant_packaging_charge, 'delivery_charge': delivery_charge_calc, 'discount': offer_discount, 'bill': total_price, 'tips': tips, 'dc': dc, 'tax': tot_tax };
        localStorage.setItem('bill', bill);
        store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)

      }


    },

    removefromcart: function (price, item_tax, name, food_id, is_veg, add_ons, food_quantity, food_image, key) {
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
      this.itemid = food_id
      let id = food_id,

        cart = JSON.parse(localStorage.getItem("cart"));
      price = parseFloat(price);

      let foodIndex = cart.findIndex(function (food, index) {
        return food.id == id;
      });

      if (key !== -1) {
        if (cart[key].quantity > 0)
          cart[key].quantity--;
        if (cart[key].quantity <= 0) {
          cart.splice(key, 1);
          if (document.getElementById('food_control_' + id + 'r')) {


            var elms = document.querySelectorAll('[id="food_control_' + id + 'r"]');

            for (var i = 0; i < elms.length; i++) {
              elms[i].style.display = 'none';
            }
            var elms2 = document.querySelectorAll('[id="food_add_' + id + 'r"]');

            for (var i = 0; i < elms2.length; i++) {
              elms2[i].style.display = 'inline-block';
            }

            //  document.getElementById('food_control_' + id + 'r').style.display = 'none';
            //  document.getElementById('food_add_' + id+'r').style.display = 'inline-block';
          }

        }
      }

      localStorage.setItem("cart", JSON.stringify(cart));

      let cartn = JSON.parse(localStorage.getItem("cart"));

      store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
      store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)

      this.loadcart()
    },

    loadcart2() {
      var sbill = localStorage.getItem('bill');
      var bill = { 'item_total': sbill.tot_amt, 'packaging_charge': sbill.restaurant_packaging_charge, 'delivery_charge': sbill.delivery_charge_calc, 'discount': sbill.offer_discount, 'bill': sbill.total_price, 'tips': sbill.tips, 'dc': sbill.dc };

      store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)
    },

    calc_delivery_charge(total_price, rest_id) {
      return new Promise(function (resolve, reject) {
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
        axios.get('/calc_delivery_charge/' + rest_id + '/' + lat + '/' + lng + '/' + total_price).then(
          (response) => {
            var result = response.data.delivery_charge
            console.log('Processing Request');
            resolve(result);
          },
          (error) => {
            reject(error);
          }
        );
      });
    }
  }

}
</script>

<style scoped>
.text-l {
  text-align: left;
}

.deli {
  margin-right: 10px;
  margin-left: 0px;
  padding: 10px;
  height: 100px;
  background-color: #fff !important;
  width: 110px;
  border: 4px solid #ffbd00;
  border-radius: 10px;
  padding: 10px;
}

.take {
  margin-right: 0px;
  margin-left: 10px;
  padding: 10px;
  height: 100px;
  background-color: #fff !important;
  width: 110px;
  border: 4px solid #ffbd00;
  border-radius: 10px;
  margin-right: 20px;
  padding: 10px;
}

.tipsactive {
  border-color: #ffbd00 !important;
  background-color: #fff !important;
}

.tipsactive1 {
  border-color: #ffbd00 !important;
  background-color: #ffbd00 !important;
}

.circleBase {
  border-radius: 50%;
  cursor: pointer
    /* remove if you don't care about IE8 */
}

.type1 {
  width: 30px;
  height: 30px;
  background: yellow;
}
</style>
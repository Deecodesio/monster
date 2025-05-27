<template >
    <div class="container pt-1" >
    
       <div class="text-l" >
         <h4>{{$t("Your Order")}}</h4>

       <div id="delivery_type" class="text-center">
        <h4>{{$t("Delivery Time")}}</h4>
         <label class="btn btn-secondary tiplabel tiphide  deli f-size-0 tipsactive" id="delivery" type="button"  > <input type="radio" class="checktips" hidden name="d_type" id="delivery"  @change="delivery_type(1)" value="1" checked><span style="color:#282828;">{{$t("Delivery Now")}}</span></label>
   
         OR 
         <label class="btn btn-secondary tiplabel tiphide take f-size-0 " id="takeaway" type="button"> <input type="radio" class="checktips" hidden name="d_type" id="takeaway" @change="delivery_type(2)" value="2"><span style="color:#282828;">{{$t("Shedule Later")}}</span></label>
       </div>
   
       <div id="delivery_address" style="display:block;  padding-top: 20px;border-bottom: none;border-top: none;" class="cart-bord">
        
           <h4>{{$t("Address")}}</h4>
           <br>
           <h4>{{$t("Pickup")}}</h4>
           <p id="demo"><feather-icon           icon="MapPinIcon"          size="16"       class="ico-color2" /> {{prop.cart_details.pickup_address}}</p>
           <br>
           <h4>{{$t("Drop")}}</h4>
           <p id="demo"><feather-icon           icon="MapPinIcon"          size="16"       class="ico-color2" /> {{prop.cart_details.drop_address}}</p>
          
       </div>
       <div class="media-list"  >
        <b-media vertical-align="top" style="padding: 30px 0px 30px 0px;">
          <template #aside >
            <b-img class="bor shadow-sm"
            :src="prop.cart_details.image"
              blank-color="#ccc"
              width="100"/>
          </template>
          <h4 class="media-heading">
            {{prop.cart_details.package_name}}
          </h4>
          <b-card-text class="mb-0 textp">
            {{prop.cart_details.instruction}}
          </b-card-text>
                      
        </b-media>
    </div>


    <!-- <div class="media-list"  >
        <b-media vertical-align="top" style="padding: 30px 0px 30px 0px;">
          <template #aside >
            <b-img class="bor shadow-sm"
              
              blank-color="#ccc"
              width="100"
                        
            />
          </template>
          <h4 class="media-heading">
           
          </h4>
          <b-card-text class="mb-0 textp">
            <feather-icon icon="MapPinIcon" size="16" class="ico-color2" />  
          </b-card-text>
                      
        </b-media>
    </div> -->
       <div id="coupon" class="cart-bord" style="padding:20px 0px;">
         <!-- <h4>Coupon</h4> -->
   
         <b-input-group class="input-group-merge coupons">
             <b-form-input placeholder="Enter Coupon Code" id="checkout_coupon_value" style="height:50px;text-transform:uppercase"/>
             <b-input-group-append is-text >
               <b-button variant="primary" id="applied_coupon_identify" @click="applycoupon()" class="text-yellow mborderw">
                 {{$t("Apply")}}
                   </b-button>
            
             </b-input-group-append>
           </b-input-group>
           <input type="hidden" id="applied_coupon_Amount1">
       </div>
      
        <div id="bill" class="pt-4" style="margin-bottom:50px">
         <h4>{{$t("Bill Details")}}</h4>
         <table width="100%">
           
          
           <tr id="dcharge_bill">
             <td>{{$t("Delivery Charge")}}</td>
             <td>{{$store.state['defaults'].currency}} {{$store.state['deliware_cart'].total_Details.delivery_charge | priceformat}}</td>
           </tr>
           <tr>
          <td>{{$t("Discount")}}</td>
          <td>{{$store.state['defaults'].currency}} {{$store.state['deliware_cart'].total_Details.discount | priceformat}}</td>
        </tr>
        <tr id="tips_bill">
          <td>{{$t("Tax")}} <span >{{tax($store.state['defaults'].tax)}}</span></td>
          <td>{{$store.state['defaults'].currency}}  {{$store.state['deliware_cart'].total_Details.tax | priceformat}}</td>
        </tr>
           <tr>
             <td>
               <b>{{$t("Bill Total")}} </b>
             </td>
             <td>
               <b>{{$store.state['defaults'].currency}} {{$store.state['deliware_cart'].total_Details.bill | priceformat}}</b>
             </td>
           </tr>
           
         </table>
       </div> 
   
       <div id="proceed"  class="pb-3">
         <b-col md="12">
         <div class="demo-inline-spacing " style="position:fixed;left:10px;bottom:0;width:100%;background-color:#fff;padding: 10px;">
       <b-button
           style="height:50px;" class="f-size-2"
           v-ripple.400="'rgba(255, 255, 255, 0.15)'"
           block
           variant="primary"
           @click="checkout()"
         >
         {{$t("Proceed")}}
       </b-button>
       
     </div>
       </b-col>
        
       </div>
  
       
     </div>
   </div>
   
   </template>
   
   <script>
   import { 
     BRow,
     BCol,
     BAvatar, BListGroup, BListGroupItem, BCardText, BImg, BMedia, BButton,BFormSpinbutton, BCard,BFormRadio, BInputGroup, BFormInput, BInputGroupAppend,BSidebar,VBToggle,
   } from 'bootstrap-vue'
   import store from '@@@/store'
   import VuePerfectScrollbar from 'vue-perfect-scrollbar'
   import Ripple from 'vue-ripple-directive'
   import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
   
   
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
    
     },
     directives: {
       'b-toggle': VBToggle,
       Ripple,
     },
     computed: {
       
       tax() {
         const taxtext = {
          
          
           0       : '(excl)',
           1       : '(incl)',
           
         }
   
         return tax => taxtext[tax]
       },
       
      
     },
     props: {
         cart: {
           type: Array,
           default: () => {},
         },
         prop: {
      
      default: () => {},
    },
       },
   
     
     data() {
       return {
         userData: JSON.parse(localStorage.getItem('webuserData')),
         rows: localStorage.getItem('address'),
         rows_detail: {},
         rows_detail2:{},
         isLoading: true,
         isfav:false,
         address:'',
         add_ons:{},
         food_quantity:{},
         itemid:'',
         selected:{},
         quantity:[],
         tips : [10,20,30],
         isdefault:false,
         send_address:{},
         modalOpen: false,
         cart_details1:JSON.parse(localStorage.getItem('CART_DETAILS')),
       }
     },
     created(){
       
       // var user = JSON.parse(localStorage.getItem('DELIVERY_BON'))
       // var user = JSON.parse(localStorage.getItem('DELIVERY_TYPE'))
       // var user = JSON.parse(localStorage.getItem('DELIVERY_LIMITS'))
      },
     
       filters: {
         priceformat(number){
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
      
       methods:{
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
         close_sidebar(){
           this.$refs.sidebar.hide();
         },
         checklogin(){
           if(localStorage.getItem('webuserData')){
             let user = JSON.parse(localStorage.getItem("webuserData"));
             this.$http.get('/get_delivery_address/'+user.id)
             .then(res => {
              
                 this.send_address = res.data.data
                 
                 
                 })
             this.$root.$emit('bv::toggle::collapse', 'sidebar-address')
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
         checkout(){
           if(localStorage.getItem('webuserData')){
             let cart = JSON.parse(localStorage.getItem("CART_DETAILS"));
             this.$http.post('/checkradius1',cart)
             .then(res => {
              
               if(res.data.status == true){
   
                 this.$router.push({ name: 'pickupdrop_checkout'});
   
               }else{
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
           }
         },
         delivery_type(type){
             if(type == 1){
               document.getElementById("delivery").classList.add("tipsactive");
               document.getElementById("takeaway").classList.remove("tipsactive");
             }else{
               document.getElementById("takeaway").classList.add("tipsactive");
               document.getElementById("delivery").classList.remove("tipsactive");
             }
         },
         error(err) {
           this.$toast({
                             component: ToastificationContent,
                             position: 'bottom-right',
                             props: {
                               title: err,
                               icon: 'UserIcon',
                               variant: 'danger',
                             },
                           })
       },
       success() {
         this.$toast({
                             component: ToastificationContent,
                             position: 'bottom-right',
                             props: {
                               title: "Coupon Applied",
                               icon: 'UserIcon',
                               variant: 'success',
                             },
                           })
       },
         applycoupon(){
           
         
           if (JSON.parse(localStorage.getItem("RES_COUPON")).length === 0) {
             var err ="Invalid Coupon Code!";
             this.error(err)
          
       } else {
           let restaurant_coupon_codes = JSON.parse(localStorage.getItem("RES_COUPON"));
           let user_choosen_coupon = document.getElementById("checkout_coupon_value").value.toUpperCase();
           let temp;
           
           let total_cart_amount = parseFloat( localStorage.getItem('TOTAL'));
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
                               localStorage.setItem('discount_val', discount_value);
                               document.getElementById("applied_coupon_Amount1").value = discount_value;
                               let final_total_price = total_cart_amount - discount_value;
                               max_discount_price = restaurant_coupon_codes[i].couponmaxamount;
                               if (discount_value > max_discount_price) {
                                   discount_value = max_discount_price;
                                   localStorage.setItem('discount_val', discount_value);
                                   document.getElementById("applied_coupon_Amount1").value = discount_value;
   
                                   let final_total_price = total_cart_amount - discount_value;
                                   document.getElementById("checkout_coupon_value").value = user_choosen_coupon;
                                   
                                   document.getElementById("applied_coupon_identify").style.display = "block";
                                   document.getElementById("checkout_coupon_value").disabled = true;
                                  
                                   this.success()
   
                                   break;
                               } else {
                                   localStorage.setItem('discount_val', discount_value);
                                   document.getElementById("applied_coupon_Amount1").value = discount_value;
                                   document.getElementById("applied_coupon_identify").style.display = "block";
                                   document.getElementById("checkout_coupon_value").disabled = true;
                                   document.getElementById("checkout_coupon_value").value = user_choosen_coupon;
                                
                                   this.success()
   
                                   
                                   break;
                               }
                           } else {
                            
                               localStorage.setItem('discount_val', discount_value);
                               document.getElementById("applied_coupon_Amount1").value = discount_value;
                               document.getElementById("applied_coupon_identify").style.display = "block";
                               document.getElementById("checkout_coupon_value").disabled = true;
                               document.getElementById("checkout_coupon_value").value = user_choosen_coupon;
   
                               this.success()
                              
                               break;
                           }
                       }
                       else {
                           var err = "Cart amount must be greater than minimum amount";
                           this.error(err)
                       }
                   } else if (restaurant_coupon_codes[i].coupontype == 1) {
                       let offer_value = restaurant_coupon_codes[i].couponvalue;
                       localStorage.setItem('discount_val',offer_value);
                       document.getElementById("applied_coupon_Amount1").value = offer_value;
   
                       let max_discount_price = restaurant_coupon_codes[i].couponmaxamount;
                     
                       let final_total_price = total_cart_amount - offer_value;
   
                       document.getElementById("applied_coupon_identify").style.display = "block";
                       document.getElementById("checkout_coupon_value").disabled = true;
                       document.getElementById("checkout_coupon_value").value = user_choosen_coupon;
   
                       this.success()
   
                       break;
                   }
               }
   
               if (temp_loop_count == restaurant_coupon_codes.length) {
                 var err ="Invalid Coupon Code!";
                 this.error(err)
                   break;
               }
   
   
           }
          
           this.loadcart1()
       }
         },
         loadcart1()
         {
          let total_price = 0;
          if(document.getElementById("applied_coupon_Amount1")){
          var offer_discount =document.getElementById("applied_coupon_Amount1").value || 0;

        }else{
          var offer_discount = 0;
        }

          var  delivery_charge_calc = this.calc_delivery_charge(0);
          total_price = delivery_charge_calc === 0 ? delivery_charge_calc : (delivery_charge_calc - parseFloat(offer_discount) ).toFixed(2);
        var bill = {'delivery_charge':delivery_charge_calc,'discount':offer_discount,'bill':total_price};
       localStorage.setItem("TOTAL", bill);
        store.commit('deliware_cart/UPDATE_TOTAl_DETAILS',  bill)
         },
         showtip(){
            document.getElementById("cutomtip").style.display = 'block';
           document.getElementById('ctips').classList.add("tipsactive1");
   
         },
         addctips(){
          
           var old = document.getElementById("selected_tips").value;
           if(document.getElementById('tips'+old)){
             document.getElementById('tips'+old).classList.remove("tipsactive1");
           }
         
           var amt = document.getElementById("cutomtip").value;
           document.getElementById("selected_tips").value = amt;
           localStorage.setItem("tips",amt);
           document.getElementById('ctips').classList.add("tipsactive1");
   
           this.loadcart()
         },
         addtips(amt){
           document.getElementById("cutomtip").style.display = 'none';
           document.getElementById("cutomtip").value = '';
         
           document.getElementById('ctips').classList.remove("tipsactive1");
          
           var old = document.getElementById("selected_tips").value;
           if(document.getElementById('tips'+old)){
             document.getElementById('tips'+old).classList.remove("tipsactive1");
           }
           
          
           document.getElementById("selected_tips").value = amt;
           document.getElementById('tips'+amt).classList.add("tipsactive1");
           localStorage.setItem("tips",amt);
          
           this.loadcart()
   
         },
         
         addcart: function (price , item_tax,name,food_id,is_veg,add_ons,food_quantity,food_image,key) {
        
         localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
         let cart = JSON.parse(localStorage.getItem("cart"));
   
       this.itemid = food_id
       let  id = food_id,
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
                       food_image:food_image,
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
       loadcart(){
         let user = JSON.parse(localStorage.getItem("webuserData"));
     localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
     var DELIVERY_CHARGE =  localStorage.getItem('DC');
     localStorage.getItem('DC', JSON.stringify(DELIVERY_CHARGE));
   
     var DELIVERY_CHARGE_TYPE =  localStorage.getItem('DELIVERY_CHARGE_TYPE');
    
   
     var packaging_charge =  localStorage.getItem('RES_PACK_CHARGE');
    
   
     var DELIVERY_CHARGE_BASEDON = localStorage.getItem('DC_BON');
     
         let cart = JSON.parse(localStorage.getItem("cart"));
         var lat = localStorage.getItem('latitude');
         var lng = localStorage.getItem('longitude');
         if(localStorage.getItem('webuserData')){
           this.$http.get('/get_checkout_details/'+cart[0].restaurant+'/'+lat+'/'+lng+'/'+user.id)
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
           if(document.getElementById("applied_coupon_Amount1")){
             var offer_discount =document.getElementById("applied_coupon_Amount1").value || 0;
   
           }else{
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
     if(cart.length === 0){
   
     }else{
       cart.forEach(function (item,i) {
               if(document.querySelectorAll('[id="food_control_' + item.id + 'r"]')){
   
                 var elms = document.querySelectorAll('[id="food_control_' + item.id + 'r"]');
   
                         for(var i = 0; i < elms.length; i++) {
                           elms[i].style.display='block';
                         }
   
                 var elms2 = document.querySelectorAll('[id="food_add_' + item.id + 'r"]');
   
                         for(var i = 0; i < elms2.length; i++) {
                           elms2[i].style.display='none';
                         }
   
                 var elms3 = document.querySelectorAll('[id="food_qty_' + item.id + 'r"]');
   
                         for(var i = 0; i < elms3.length; i++) {
                           elms3[i].value = item.quantity;
                         }
   
   
                 // document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
                 // document.getElementById('food_add_' + item.id+'r').style.display = 'none';
                 // document.getElementById('food_qty_' + item.id+'r').value = item.quantity;
   
               }
               if(document.querySelectorAll('[id="food_qty_' + item.id + 'c"]')){
                 var elms4 = document.querySelectorAll('[id="food_qty_' + item.id + 'c"]');
   
                         for(var i = 0; i < elms4.length; i++) {
                           elms4[i].value = item.quantity;
                         }
                 // document.getElementById('food_qty_' + item.id+'c').value = item.quantity;
               }
   
               
   
               total_price += parseFloat((item.quantity * (item.price + item.addonsPrice)).toFixed(2));
               total_item ++;
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
           if(document.querySelector('input[name="d_type"]:checked')){
             var dc = document.querySelector('input[name="d_type"]:checked').value;
           }else{
             var dc = 1;
           }
           
           if (dc == 1) {
             var  delivery_charge_calc = this.calc_delivery_charge(tot_amt);
             if(document.getElementById("selected_tips")){
               var tips = document.getElementById("selected_tips").value || 0;
               document.getElementById("tips").style.display = "block";
             }else{
               var tips = 0;
             }
             
   
           } else {
             document.getElementById("tips").style.display = "none";
   
             var  delivery_charge_calc = 0;
             var tips =  0;
               
           }
   
           total_price = tot_amt === 0 ? tot_amt : (tot_amt - parseFloat(offer_discount)  + restaurant_packaging_charge + delivery_charge_calc + parseFloat(tips)).toFixed(2);
           localStorage.setItem('tot_amt', total_price);
           var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':offer_discount,'bill':total_price,'tips':tips,'dc':dc,'tax':tot_tax};
           localStorage.setItem('bill', bill);
           store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)
   
     }
   
   },
   
   removefromcart: function (price , item_tax,name,food_id,is_veg,add_ons,food_quantity,food_image,key) {
     localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
     this.itemid = food_id
      let  id = food_id,
          
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
                       if(document.getElementById('food_control_' + id + 'r')){
   
                         var elms = document.querySelectorAll('[id="food_control_' + id + 'r"]');
   
                         for(var i = 0; i < elms.length; i++) {
                           elms[i].style.display='none';
                         }
                         var elms2 = document.querySelectorAll('[id="food_add_' + id + 'r"]');
   
                         for(var i = 0; i < elms2.length; i++) {
                           elms2[i].style.display='inline-block';
                         }
   
   
                         // document.getElementById('food_control_' + id + 'r').style.display = 'none';
                         // document.getElementById('food_add_' + id+'r').style.display = 'inline-block';
                       }
                       
                   }
               }
   
               localStorage.setItem("cart", JSON.stringify(cart));
   
               let cartn = JSON.parse(localStorage.getItem("cart"));
               
                   store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
                   store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
                   
                   this.loadcart()
   },
     
   loadcart2(){
           var sbill = localStorage.getItem('bill');
           var bill = {'item_total':sbill.tot_amt,'packaging_charge':sbill.restaurant_packaging_charge,'delivery_charge':sbill.delivery_charge_calc,'discount':sbill.offer_discount,'bill':sbill.total_price,'tips':sbill.tips,'dc':sbill.dc};
           
           store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)
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
 }
    
   }
   </script>
   
   <style lang="scss">
   @import '~@resources/scss/vue/libs/vue-sweetalert.scss';
   
   </style>
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
       border: 4px solid #62c061;
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
       border: 4px solid #62c061;
       border-radius: 10px;
       margin-right: 20px;  padding: 10px;
   } 
   
   .tipsactive{
     border-color: #62c061 !important;
       background-color: #fff !important;
   }
   .tipsactive1{
     border-color: #62c061 !important;
     background-color: #62c061 !important;
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
     background-color: #00A082;
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
   </style>
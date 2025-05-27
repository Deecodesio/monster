<template>
    <div class="container">
        <div class="mt-1 text-center"
            style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60vh;"
            v-if="!cart.length">
            <lottie-player src="/animations/basket.json" background="#FFFDFD" speed="1.2"
                style="width: 300px; height: 300px;" loop autoplay></lottie-player>
            <h4>{{ $t("Your cart is empty") }}</h4>
            <p>{{ $t("The products that you will order will appear here") }}</p>
        </div>
        <div class="text-l" v-if="cart.length">
            <!-- <h4>{{ $t("My") }}</h4> -->
            <!-- <div class="media-list"  >
         <b-media vertical-align="top" style="padding: 30px 0px 30px 0px;">
          <template #aside v-if="cart[0].restaurant_image">
            <b-img class="bor shadow-sm"
              :src="cart[0].restaurant_image"
              blank-color="#ccc"
              width="100"

            />
                  </template>
<h4 class="media-heading">
    {{cart[0].restaurant_name}}
</h4>
<b-card-text class="mb-0 textp">
    <feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{cart[0].restaurant_address}}
</b-card-text>

</b-media>
</div> -->
            <!-- <div id="delivery_type" class="text-center">
                <label class="btn btn-secondary tiplabel tiphide  deli f-size-0 tipsactive" id="delivery" type="button">
                    <input type="radio" class="checktips" hidden name="d_type" id="delivery" @change="delivery_type(1)"
                        value="1" checked><b-img src="/deliveryman.png" fluid alt="Scan" style="width:70%;" /><span
                        style="color:#282828;">{{
                            $t("Delivery") }}</span></label>

                OR
                <label class="btn btn-secondary tiplabel tiphide take f-size-0 " id="takeaway" type="button"> <input
                        type="radio" class="checktips" hidden name="d_type" id="takeaway" @change="delivery_type(2)"
                        value="2"><span style="color:#282828;"><b-img src="/walk.png" fluid alt="Scan"
                            style="width:70%;" />
                        {{ $t("Take Away") }}</span></label>
            </div> -->
            <!-- <div class="wrap">
                <div class="del_pickup_options">
                    <div :class="{ 'del_option_active': delivery_type === 1 }" class="del_option"
                        @click="select_delivery_type(1)">
                        {{ $t("Delivery") }}
                    </div>
                    <div :class="{ 'del_option_active': delivery_type === 2 }" class="del_option"
                        @click="select_delivery_type(2)">
                        {{ $t("Takeaway") }}
                    </div>
                </div>
            </div> -->

            <!-- <div class="wrap">
                <div class="del_pickup_options">
                    <div :class="{ del_option_active: delivery_type === 1 }" @click="select_delivery_type(1)">
                        {{ $t("Delivery") }}
                    </div>
                    <div :class="{ del_option_active: delivery_type === 2 }" @click="select_delivery_type(2)">
                        {{ $t("Takeaway") }}
                    </div>
                </div>
            </div> -->
            <div class="toggle-container">
                <div :class="['toggle-option', { 'toggle-active': delivery_type === 1 }]"
                    @click="select_delivery_type(1)">
                    Delivery
                </div>
                <div :class="['toggle-option', { 'toggle-active': delivery_type === 2 }]"
                    @click="select_delivery_type(2)">
                    Takeaway
                </div>
            </div>



            <div id="takeaway_address" style="padding-top: 20px;" class="cart-bord" v-if="delivery_type == 2">
                <h4>{{ $t("Take Away") }} {{ $t("From") }}</h4>
                <p id="demo"><feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{
                    cart[0].restaurant_address }}</p>
            </div>
            <div id="delivery_address" style="display:block;  padding-top: 20px;" class="cart-bord"
                v-if="delivery_type == 1">
                <h4>{{ $t("DeliveringTo") }}</h4>
                <p id="demo"><feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{ rows }}</p>
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="flat-primary" style="padding:0px;"
                    @click="checklogin()">
                    <i class="fa-solid fa-circle-plus"></i> <span class="f-size-0">{{ $t("Add") }} {{ $t("New") }}
                        {{ $t("Address") }}</span>
                </b-button>
                <b-sidebar id="sidebar-address" bg-variant="white" right backdrop shadow width="130%" ref="a_sidebar">
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
                                    <b-img src="/veg.svg" fluid alt="Scan" style="width: 12px;"
                                        v-if="data.pharmacy_id == 0" />
                                    {{ data.name }}
                                </div>
                                <div v-else>
                                    <b-img src="/non_veg.svg" fluid alt="Scan" style="width: 12px;"
                                        v-if="data.pharmacy_id == 0" />
                                    {{ data.name }}
                                </div>
                            </h4>
                            <b-card-text class="mb-0 textp" v-if="data.addonsName">
                                {{ $t("Addons") }} : {{ data.to_display }}
                            </b-card-text>
                            <b-card-text class="mb-0 textp" v-if="data.sizeName">
                                <!-- <b-row>

                  <div class="circleBase type1" :style="{ background: data.sizeName }">
                  </div>
                </b-row> -->
                                {{ data.sizeName }}
                            </b-card-text>
                            <b-row class="pt-3">
                                <b-col cols="4">
                                    <h4 class="media-heading text-primary">
                                        {{ $store.state['defaults'].currency }} {{ data.price + data.addonsPrice |
                                            priceformat }}
                                    </h4>
                                </b-col>
                                <b-col cols="8">
                                    <!-- <div style="max-width:105px; float: right;">
                                        <div class="num-block skin-2" :id="'food_control_' + data.id + 'c'">
                                            <div class="num-in">
                                                <span class="minus dis" :id="'food_minus_' + data.id"
                                                    @click="removefromcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index)"></span>
                                                <input type="text" class="in-num" :value="data.quantity" readonly=""
                                                    :id="'food_qty_' + data.id + 'c'">
                                                <span class="plus" :id="'food_plus_' + data.id"
                                                    @click="addcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index)"></span>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div style="max-width: 120px; float: right;">
                                        <div class="num-block styled-qty" :id="'food_control_' + data.id + 'c'">
                                            <div class="num-in">


                                                <span class="icon-btn" :id="'food_minus_' + data.id"
                                                    @click="removefromcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>


                                                <span class="quantity-text" :id="'food_qty_' + data.id + 'c'">
                                                    {{ data.quantity }}
                                                </span>


                                                <span class="icon-btn" :id="'food_plus_' + data.id"
                                                    @click="addcart(data.price, data.taxperc, data.name, data.id, data.isveg, data.addons, data.sizes, data.food_image, index)">
                                                    <i class="fas fa-plus"></i>
                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-media>
                    </b-col>
                </b-row>
            </div>
            <!-- <div id="prescription" class="cart-bord" style="padding:20px 0px;"
                v-if="$store.state['deliware_cart'].pharmacy == 1">
                <h4>{{ $t('Prescription') }}</h4>
                <b-form-file v-model="prescription.image" placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..." @change="onFileChange1" style="z-index: 0;" />
            </div> -->
            <!-- <div id="coupon" class="cart-bord" style="padding:20px 0px;border-bottom: none;border-top: none;">
                <b-input-group 
                class="input-group-merge coupons">
                    <b-form-input placeholder="Enter Coupon Code" 
                    id="checkout_coupon_value"
                        style="height:50px;
                        text-transform:uppercase" />
                        <b-input-group-append is-text>
                            <b-button variant="primary"
                            id="applied_coupon_identify"
                            @click="applycoupon()"
                                class="text-yellow mborderw" v-if="!clear">
                                {{ $t("Apply") }}
                            </b-button>
                        <b-button variant="primary" id="applied_coupon_identify" @click="clearcoupon()"
                            class="text-yellow mborderw" v-if="clear">
                            {{ $t("Clear") }}
                        </b-button>
                    </b-input-group-append>
                </b-input-group>
                <input type="hidden" id="applied_coupon_Amount">
            </div> -->
            <div id="coupon" class="cart-bord">
                <b-input-group class="coupons">
                    <b-form-input placeholder="Enter Coupon Code" id="checkout_coupon_value" class="coupon-input" />
                    <b-input-group-append is-text>
                        <b-button variant="primary" id="applied_coupon_identify" @click="applycoupon()"
                            class="apply-btn" v-if="!clear">
                            {{ clear ? "Clear" : "Apply" }}
                        </b-button>
                        <!-- <b-button variant="primary" id="applied_coupon_identify" click="clearcoupon()" class="apply-btn"
                            v-if="clear">
                            {{ ("Clear") }}
                        </b-button> -->
                    </b-input-group-append>
                </b-input-group>
                <input type="hidden" id="applied_coupon_Amount" />
            </div>
            <div class="section-top">

            </div>

            <div class="section-bottom">

            </div>

            <!-- <div id="tips" class="pt-0" style="
                    
                    margin-top: 20px;
                " v-if="tips_enable && delivery_type == 1">
                <b-row>
                    <b-col cols="1">
                        <i class="fa-solid fa-gift"></i>
                    </b-col>
                    <b-col cols="11" class="pad-l0">
                        <h4>{{ $t("Please Tip Your Valet") }}</h4>
                        <small>{{ tips_text }}</small>
                    </b-col>
                </b-row>
                <b-row style="padding-left: 35px; padding-top: 20px">
                    <div v-for="(data, index) in tips" :key="index">
                        <label class="btn btn-primary tiplabel tiphide f-size-0" :id="'tips' + data" type="button"
                            style="margin-left: 10px; padding: 10px">
                            <input type="radio" class="checktips" hidden name="tips" :id="'tips' + data"
                                @change="addtips(data)" :value="data" />
                            {{ data | priceformat }}</label>
                    </div>
                    <label class="btn btn-primary tiplabel tiphide f-size-0" id="ctips" type="button"
                        style="margin-left: 10px; padding: 10px" @click="showtip()">
                        {{ $t("Other") }}</label>
                    <b-form-input :placeholder="$t('Enter Tips Amount')" id="cutomtip" @input="addctips()"
                        onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
                        style="display: none; height: 50px" />
                    <input type="hidden" id="selected_tips" />
                </b-row>
            </div> -->
            <!-- <div id="bill" class="pt-4" style="margin-bottom:50px">
                <h4>{{ $t("Bill Details") }}</h4>
                <table width="100%">
                    <tr>
                        <td>{{ $t("Item Total") }}</td>
                        <td>{{ $store.state['defaults'].currency }} {{
                            $store.state['deliware_cart'].cart_total.item_total |
                            priceformat }}</td>
                    </tr>
                    <tr v-if="$store.state['deliware_cart'].cart_total.packaging_charge > 0">
                        <td>{{ $t("Packaging Charge") }}</td>
                        <td>{{ $store.state['defaults'].currency }} {{
                            $store.state['deliware_cart'].cart_total.packaging_charge |
                            priceformat }}</td>
                    </tr>
                    <tr v-if="$store.state['deliware_cart'].cart_total.discount > 0">
                        <td>{{ $t("Discount") }}</td>
                        <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.discount
                            |
                            priceformat
                        }}
                        </td>
                    </tr>

                    <tr id="dcharge_bill" v-if="$store.state['deliware_cart'].cart_total.delivery_charge > 0">
                        <td>{{ $t("Delivery Charge") }}</td>
                        <td>{{ $store.state['defaults'].currency }} {{
                            $store.state['deliware_cart'].cart_total.delivery_charge |
                            priceformat }}</td>
                    </tr>
                    <tr id="tips_bill" v-if="tips_enable">
                        <td>{{ $t("Tips") }}</td>
                        <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.tips |
                            priceformat
                            }}
                        </td>
                    </tr>
                    <tr id="tips_bill" v-if="$store.state['deliware_cart'].cart_total.tax > 0">
                        <td>{{ $t("Tax") }} <span>{{ tax($store.state['defaults'].tax) }}</span></td>
                        <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.tax |
                            priceformat }}
                        </td>
                    </tr>
                    <tr id="wallet_bill" v-if="$store.state['deliware_cart'].cart_total.wallet > 0">
                        <td>{{ $t("Wallet") }}(-)</td>
                        <td>{{ $store.state['defaults'].currency }} {{ $store.state['deliware_cart'].cart_total.wallet |
                            priceformat
                            }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>{{ $t("Bill Total") }} </b>
                        </td>
                        <td>
                            <b>{{ $store.state['defaults'].currency }}</b>
                        </td>
                    </tr>
                </table>
            </div> -->
            <div id="proceed" class="pb-3">
                <b-col md="12">
                    <div class="demo-inline-spacing " style="position: fixed; 
                        left: 10px;
                        bottom: 0;
                        width: 100%;
                        background-color:#fff;
                        padding: 10px;">
                        <div class="subtotal-box">
                            <span class="subtotal-label">Subtotal</span>
                            <b class="subtotal-amount">
                                {{ $store.state['defaults'].currency }}
                                {{ $store.state['deliware_cart'].cart_total.bill | priceformat }}
                            </b>
                        </div>
                        <b-button style="height:50px; background-color: #FF006B;" class="f-size-2"
                            v-ripple.400="'#FF006B'" block variant="primary" @click="checkout()"
                            v-if="isdefault && is_deliverable">
                            {{ $t("Proceed to Checkout") }}
                        </b-button>
                        <b-button style="height:50px;" class="f-size-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'" block
                            variant="primary" v-if="!isdefault && is_deliverable" @click="checklogin()">
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
    BAvatar, BListGroup, BListGroupItem, BCardText, BImg, BMedia, BButton, BFormSpinbutton,
    BCard, BFormRadio, BInputGroup, BFormInput, BInputGroupAppend, BSidebar, VBToggle, BFormFile, BBadge,
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
        loginmodal, BFormFile, BBadge,
    },
    directives: {
        'b-toggle': VBToggle,
        Ripple,
    }, methods: {
        select_delivery_type(type) {
            this.delivery_type = type;
        },
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
            tips_text: '',
            pharmacy_file: [],
            prescription: {},
            tips_enable: false,
            clear: false,
            is_deliverable: false,
            delivery_type: 1
        }
    },
    watch: {
        cart: function () {
            let cart = JSON.parse(localStorage.getItem('cart'))
            var lat = localStorage.getItem('latitude');
            var lng = localStorage.getItem('longitude');
            this.$http.get('/validate_pincode/' + lat + '/' + lng + '/' + cart[0].restaurant)
                .then(res => {
                    this.is_deliverable = res.data.is_deliverable
                })
        },
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
        let cart = JSON.parse(localStorage.getItem('cart'))
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
        this.$http.get('/validate_pincode/' + lat + '/' + lng + '/' + cart[0].restaurant)
            .then(res => {
                this.is_deliverable = res.data.is_deliverable
            })
        this.$http.get("/get_tips").then((res) => {
            if (res.data.tips_enable == 1) {
                this.tips_enable = true;
            } else {
                this.tips_enable = false;
            }
            this.tips = res.data.tips;
            this.tips_text = res.data.tips_text;
        });
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
        enquire() {
            if (localStorage.getItem('webuserData')) {
                localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
                const cart = JSON.parse(localStorage.getItem("cart"));
                var p_image = localStorage.getItem("pres_image");
                if (cart.length === 0) return alert('No items in the cart');
                var item_total = cart.reduce(function (o, c, i) {
                    return ((cart[i].quantity * (cart[i].price + cart[i].addonsPrice)) + o)
                }, 0);
                let userData = JSON.parse(localStorage.getItem('webuserData'))

                const bill = this.$store.state['deliware_cart'].cart_total;

                var lat = localStorage.getItem('latitude');
                var lng = localStorage.getItem('longitude');
                let data = new FormData();
                data.append('food_id', cart.map(function (item) {
                    return item.id
                }).join(','));
                data.append('food_price', cart.map(function (item) {
                    return item.price
                }).join(','));
                data.append('food_qty', cart.map(function (item) {
                    return item.quantity
                }).join(','));
                data.append('item_total', item_total);
                data.append('addons', cart.map(function (item) {
                    return item.addons
                }).join('-'));
                data.append('addonsqty', cart.map(function (item) {
                    return item.addonsqty
                }).join('-'));
                data.append('sizes', cart.map(function (item) {
                    return item.sizes
                }).join('-'));
                data.append('user_id', userData.id);
                data.append('lat', lat);
                data.append('lng', lng);


                this.$http.post('/enquire_now', data)
                    .then(res => {

                        this.$swal({
                            title: 'Thank You for your Enquiry',
                            text: 'Our Team will contact you shortly',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                            },
                            buttonsStyling: false,
                        })
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
                this.modalOpen = !this.modalOpen;
            }

        },
        onFileChange1(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.prescription.image = reader.result;
                localStorage.setItem('pres_image', this.prescription.image);
            };
        },
        check: function (value) {
            this.$root.$emit('bv::toggle::collapse', 'sidebar-address')
            var user = JSON.parse(localStorage.getItem('webuserData'))
            this.isdefault = true
            // var lat = localStorage.getItem('latitude');
            // var lng = localStorage.getItem('longitude');
            // this.$http.get('/get_near_address/'+lat+'/'+lng+'/'+user.id)
            //       .then(res => {
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
                            var is_cod = 0;
                            this.isloading = true
                            localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
                            const cart = JSON.parse(localStorage.getItem("cart"));
                            var p_image = localStorage.getItem("pres_image");
                            if (cart.length === 0) return alert('No items in the cart');
                            var item_total = cart.reduce(function (o, c, i) {
                                return ((cart[i].quantity * (cart[i].price + cart[i].addonsPrice)) + o)
                            }, 0);
                            let userData = JSON.parse(localStorage.getItem('webuserData'))
                            const bill = this.$store.state['deliware_cart'].cart_total;
                            let amt = parseFloat(bill.bill);
                            if (amt == 0) {
                                let data = new FormData();
                                data.append('food_id', cart.map(function (item) {
                                    return item.id
                                }).join(','));
                                data.append('food_price', cart.map(function (item) {
                                    return item.price
                                }).join(','));
                                data.append('food_qty', cart.map(function (item) {
                                    return item.quantity
                                }).join(','));
                                data.append('item_total', item_total);
                                data.append('addons', cart.map(function (item) {
                                    return item.addons
                                }).join('-'));
                                data.append('addonsqty', cart.map(function (item) {
                                    return item.addonsqty
                                }).join('-'));
                                data.append('sizes', cart.map(function (item) {
                                    return item.sizes
                                }).join('-'));
                                data.append('gst', parseFloat(localStorage.getItem('tax')));
                                data.append('tips', parseFloat(bill.tips));
                                data.append('offer_discount', parseFloat(bill.discount));
                                data.append('delivery_type', this.delivery_type);
                                data.append('delivery_charge', parseFloat(bill.delivery_charge));
                                data.append('paid_type', 1);
                                data.append('restaurant_id', cart[0].restaurant);
                                data.append('restaurant_packaging_charge', parseFloat(bill.packaging_charge));
                                data.append('bill_amount', parseFloat(bill.bill));
                                data.append('is_cod', is_cod);
                                // data.append('coupon_code', 'DEAL15');
                                data.append('device_type', 'WEB');
                                data.append('user_id', userData.id);
                                data.append('pharmacy_image', p_image ? p_image : '');
                                this.$http.post('/paynow', data)
                                    .then(res => {
                                        this.isloading = false
                                        if (res.data.status == true) {
                                            if (res.data.data.checkout_url != null) {
                                                this.$router.push({ name: "pay_for_order", params: { id: res.data.data.checkout_url, name: res.data.data.ClientSecret } })
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
                                                localStorage.setItem("cart", "[]");
                                                localStorage.setItem("bill", "[]");
                                                localStorage.setItem("pres_image", "[]");
                                                localStorage.setItem("WALLET", "");
                                                let cartn = JSON.parse(localStorage.getItem("cart"));
                                                // localStorage.setItem("pharmacy_file","[]");
                                                this.$store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
                                                this.$store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
                                                var bill = {};
                                                this.$store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)
                                                this.$router.push({ name: "trackorder", params: { id: res.data.data.order_id } })
                                            }
                                        }
                                        else {
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
                            }
                            else {
                                if (store.state['deliware_cart'].pharmacy == 0) {
                                    this.$router.push({ name: 'checkout' });
                                }
                                else {
                                    // if (this.prescription.image) {
                                    this.$router.push({ name: 'checkout' });
                                    // }
                                    // else {
                                    //   this.$toast({
                                    //     component: ToastificationContent,
                                    //     position: 'bottom-right',
                                    //     props: {
                                    //       title: this.$t("Please Upload Prescription File"),
                                    //       icon: 'UserIcon',
                                    //       variant: 'danger',
                                    //     },
                                    //   })
                                    // }
                                }
                            }
                        } else {
                            this.$toast({
                                component: ToastificationContent,
                                position: 'bottom-right',
                                props: {
                                    title: "Store Does not Come Under your Location",
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
        select_delivery_type(type) {
            this.delivery_type = type

            this.loadcart()
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
        clearcoupon() {
            document.getElementById("applied_coupon_identify").style.display = "block";
            document.getElementById("checkout_coupon_value").disabled = false;
            document.getElementById("checkout_coupon_value").value = "";
            this.coupon_applied = 0
            this.cleared = true
            localStorage.removeItem("discount_value");
            localStorage.removeItem("coupon");
            document.getElementById("applied_coupon_Amount").value = 0;
            this.clear = false;
            this.loadcart()
        },
        applycoupon() {
            let cart = JSON.parse(localStorage.getItem("cart"));
            let user_choosen_coupon = document.getElementById("checkout_coupon_value").value.toUpperCase();

            const items = [];

            cart.forEach(item => {
                const addonIDs = item.addons ? item.addons.split(",").map(Number) : [];
                const addonQuantities = item.addonsqty ? item.addonsqty.split(",").map(Number) : [];

                const formattedItem = {
                    AddonIDs: addonIDs,
                    FoodID: item.id,
                    Quantity: item.quantity,
                    SizeID: parseInt(item.sizes),
                    addons_quantity: addonQuantities
                };

                items.push(formattedItem);
            });
            let data = new FormData();
            data.append('CouponCode', user_choosen_coupon);
            data.append('Items', JSON.stringify(items));
            data.append('RestaurantID', cart[0].restaurant);

            this.$http.post('/api/ApplyCoupon', data)
                .then(res => {

                    if (res.data.status == false) {
                        this.error(res.data.message)
                    } else {
                        localStorage.setItem('discount_value', res.data.OfferAmount);
                        localStorage.setItem('coupon', user_choosen_coupon);
                        localStorage.setItem('code_id', res.data.code_id);
                        this.coupon_applied = 1
                        this.cleared = false
                        this.success()
                        this.loadcart()
                    }

                })




            // let total_cart_amount = parseFloat(localStorage.getItem('tot_amt'));
            // console.log(user_choosen_coupon)
            // if (user_choosen_coupon == "") {
            //   this.error("Coupon Code Not Found")
            // } else {
            //   this.$http.get('/apply_coupon/' + cart[0].restaurant + '/' + total_cart_amount + '/' + user_choosen_coupon + '/' + 1315)
            //     .then(res => {
            //       console.log(res)
            //       if (res.data.status == false) {
            //         this.error(res.data.message)
            //       } else {
            //         localStorage.setItem('discount_value', res.data.OfferAmount);
            //         localStorage.setItem('coupon', user_choosen_coupon);

            //         this.coupon_applied = 1
            //         this.cleared = false
            //         this.success()
            //         this.loadcart()
            //       }
            //     })
            // }
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
            let cart = JSON.parse(localStorage.getItem("cart"));
            let p_id = JSON.parse(localStorage.getItem("pharmacy_id"));
            if (p_id == 1) {
                for (let i = 0; i < cart.length; i++) {
                    let cart = JSON.parse(localStorage.getItem("cart"));
                    if (cart[i].isveg == 1) {
                        this.pharmacy_file = 1;
                        break;
                    } else {
                        this.pharmacy_file = 0;
                    }
                }
                localStorage.setItem("pharmacy_file", this.pharmacy_file);
                store.commit('deliware_cart/UPDATE_PHARMACY_FILE', this.pharmacy_file)
            }
            var DELIVERY_CHARGE = localStorage.getItem('DC');
            localStorage.getItem('DC', JSON.stringify(DELIVERY_CHARGE));
            var DELIVERY_CHARGE_TYPE = localStorage.getItem('DELIVERY_CHARGE_TYPE');
            var packaging_charge = localStorage.getItem('RES_PACK_CHARGE');
            var DELIVERY_CHARGE_BASEDON = localStorage.getItem('DC_BON');
            // let cart = JSON.parse(localStorage.getItem("cart"));
            var lat = localStorage.getItem('latitude');
            var lng = localStorage.getItem('longitude');
            if (localStorage.getItem('webuserData')) {
                this.$http.get('/get_checkout_details/' + cart[0].restaurant + '/' + lat + '/' + lng + '/' + user.id)
                    .then(res => {
                        localStorage.setItem('DISTANCE', res.data.distance);
                    })
            }
            this.$http.get('/validate_pincode/' + lat + '/' + lng + '/' + cart[0].restaurant)
                .then(res => {
                    this.is_deliverable = res.data.is_deliverable
                })
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
            let wallet = localStorage.getItem('WALLET');
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
                    if (document.querySelectorAll('[id="food_control_' + item.id + 'r"]')) {
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
                        // document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
                        // document.getElementById('food_add_' + item.id+'r').style.display = 'none';
                        // document.getElementById('food_qty_' + item.id+'r').value = item.quantity;
                    }
                    if (document.querySelectorAll('[id="food_qty_' + item.id + 'c"]')) {
                        var elms4 = document.querySelectorAll('[id="food_qty_' + item.id + 'c"]');
                        for (var i = 0; i < elms4.length; i++) {
                            elms4[i].value = item.quantity;
                        }
                        // document.getElementById('food_qty_' + item.id+'c').value = item.quantity;
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

                var dc = this.delivery_type
                if (dc == 1) {
                    var delivery_charge_calc = await this.calc_delivery_charge(tot_amt, cart[0].restaurant);
                    if (document.getElementById("selected_tips")) {
                        var tips = document.getElementById("selected_tips").value || 0;
                    } else {
                        var tips = 0;
                    }
                } else {
                    var delivery_charge_calc = 0;
                    var tips = 0;
                }
                total_price = tot_amt === 0 ? tot_amt : (tot_amt - parseFloat(offer_discount) + restaurant_packaging_charge + delivery_charge_calc + parseFloat(tips)).toFixed(2);
                localStorage.setItem('tot_amt', total_price);
                if (total_price < parseFloat(wallet)) {
                    wallet = total_price;
                    total_price = 0;
                }
                else {
                    total_price = total_price - wallet;
                }
                var bill = { 'item_total': tot_amt, 'packaging_charge': restaurant_packaging_charge, 'delivery_charge': delivery_charge_calc, 'discount': offer_discount, 'bill': total_price, 'tips': tips, 'dc': dc, 'tax': tot_tax, 'wallet': wallet };
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

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>
<style scoped>
.container {

    background-color: rgb(255, 255, 255);
}

.toggle-container {
    display: flex;
    background-color: #FF81B633;
    border-radius: 9999px;
    width: fit-content;
    margin: 20px auto;
}

.toggle-option {
    padding: 10px 24px;
    border-radius: 9999px;
    font-weight: bold;
    font-size: 16px;
    color: #FF006B;
    background-color: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-active {
    background-color: #FF006B;
    color: white;
}

.text-l {
    text-align: left;
}

.deli {
    margin-right: 10px;
    margin: 0px;
    padding: 10px;
    height: auto;
    background-color: #fff !important;
    width: 120px;
    border: 4px solid #ffbd00;
    border-radius: 10px;
    padding: 10px;
}

.take {
    margin-right: 0px;
    margin: 10px;
    padding: 10px;
    height: auto;
    background-color: #fff !important;
    width: 120px;
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

.b-sidebar>.b-sidebar-header .close {
    font-size: 2rem; 
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

.subtotal-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: 700;
    width: 95%;
    height: 50px;

    background-color: #fff;
}

.subtotal-label,
.subtotal-amount {
    color: #000;
}

.cart-bord {
    padding: 20px 0;
    border-top: none;
    border-bottom: none;
}

.coupons {
    border: 1px solid #e0e0e0;
    overflow: hidden;
    display: flex;
    border: none;

}

.coupon-input {
    border-right: none !important;
    border: 1px solid #E0E0E0;
    border-radius: 8px 0 0 8px;
    padding-left: 20px;
    font-size: 16px;
    height: 50px;
    background-color: #fff;
    box-shadow: none !important;
    outline: none;
    color: #333;
    flex: 1;
}

.coupon-input::placeholder {
    font-size: 18px;
    font-weight: 400;
    color: #818181;
}

.apply-btn {
    background-color: #FF006B;
    border-radius: 0 8px 8px 0;

    border-left: none;
    color: #fff;
    padding: 0 25px;
    font-weight: 500;
    height: 50px;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 0;
}

.apply-btn:hover {
    background-color: #FF006B;
}

/* REMOVE Bootstrap default border/shadow */
.b-input-group-append,
.input-group-append,
.input-group-text {
    border: none !important;
    box-shadow: none !important;
    background: transparent;
    padding: 0 !important;
    margin: 0 !important;
}

.styled-qty .num-in {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.icon-btn {
    width: 32px;
    height: 32px;
    border: 2px solid #f4396f;
    border-radius: 50%;
    color: #f4396f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.icon-btn:hover {
    background-color: #f4396f;
    color: #fff;
}

.quantity-text {
    font-size: 16px;
    font-weight: 500;
    min-width: 20px;
    text-align: center;
}

.text-l {
    height: 1000px;
    overflow-y: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;

}

.text-l::-webkit-scrollbar {
    display: none;
}
 

</style>

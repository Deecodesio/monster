<template>
    <div style="margin-top:110px;min-height: 100vh;">
        <div class="container" style="margin-top: 40px">
            <b-row>
                <b-col cols="12" lg="2"></b-col>
                <b-col cols="12" lg="4">
                    <b-card class="">
                        <div class="checkout_summary">
                            <div class="checkout_summary_header">
                                <div class="checkout_summary_header_title">{{ $t("Bill Summary") }}</div>
                            </div>
                            <!-- <div class="del_pickup_options mt-2 mb-2">
                                <div :class="{
                                    'del_option_active': $store.state.deliware_cart.cart_total.dc === 1
                                }" class="del_option">
                                    {{ $t("Delivery") }}
                                </div>
                                <div :class="{ 'del_option_active': $store.state.deliware_cart.cart_total.dc === 2 }"
                                    class="del_option">
                                    {{ $t("Takeaway") }}
                                </div>
                            </div> -->
                            <!-- <pre>{{ $store.state.deliware_cart.cart_total }}</pre> -->
                            <div class="toggle-container">
                                <!-- <div :class="['toggle-option', { 'toggle-active': $store.state.deliware_cart.cart_total.dc === 1 }]"
                                  @click="select_delivery_type(1)"> -->
                                <div :class="['toggle-option', { 'toggle-active': delivery_type === 1 }]">
                                   
                                    Delivery
                                </div>
                                <!-- <div :class="['toggle-option', { 'toggle-active': $store.state.deliware_cart.cart_total.dc === 2 }]"
                                @click="select_delivery_type(2)"> -->
                                <div :class="['toggle-option', { 'toggle-active': delivery_type === 2  }]">
                                    
                                    Takeaway
                                </div>
                            </div>

                            <div v-for="(data, index) in $store.state['deliware_cart'].cart_items" :key="index">
                                <b-row>
                                    <b-col cols="12" style="padding: 1.25rem;">
                                        <b-media vertical-align="top">
                                            <template #aside v-if="data.food_image">
                                                <b-img class="bor" :src="data.food_image" blank-color="#ccc"
                                                    width="70" />
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
                                                {{ data.sizeName }}
                                            </b-card-text>
                                            <b-row class="pt-3">
                                                <b-col cols="4">
                                                    <h4 class="media-heading text-primary">
                                                        {{ $store.state['defaults'].currency }} {{ data.price +
                                                            data.addonsPrice |
                                                            priceformat }}
                                                    </h4>
                                                </b-col>
                                                <b-col cols="8">
                                                    <div style="max-width:105px; float: right;">
                                                        <h4> Qty : {{ data.quantity }}</h4>
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </b-media>
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="cart_seperator" v-if="$store.state.deliware_cart.cart_total.discount > 0">
                            </div>

                            <div class="checkout_coupon_section mt-2 mb-2"
                                v-if="$store.state.deliware_cart.cart_total.discount > 0">


                                <div class="coupon_left">
                                    <b-img src="/monster/coupon.svg"></b-img>
                                    <div class="checkout_coupon_section_title">{{ $t("Coupon Applied") }}</div>
                                </div>
                                <div class="coupon_right">
                                    <div class="checkout_coupon_section_title">{{ $t("Code:") }}{{
                                        $store.state["deliware_cart"].cart_total.offer_code }}</div>
                                </div>
                            </div>
                            <div class="checkout_bill_section mt-2">
                                <table width="100%" cellspacing="5" cellpadding="5">
                                    <tr>
                                        <td style="font-weight: 700; font-size: 16px;color: black;">{{ $t("Item Total")
                                            }}</td>
                                        <td style="float: right; font-weight: 700; font-size: 16px;color: black;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ $store.state["deliware_cart"].cart_total.item_total | priceformat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 16px;">{{ $t("Packaging Charge") }}</td>
                                        <td style="float: right ; font-size: 16px;color:black; ">
                                            {{ $store.state["defaults"].currency }}
                                            {{ $store.state["deliware_cart"].cart_total.packaging_charge | priceformat
                                            }}
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td>{{ $t("Discount") }}</td>
                                        <td style="float: right;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ $store.state["deliware_cart"].cart_total.discount | priceformat }}
                                        </td>
                                    </tr> -->
                                    <tr id="dcharge_bill">
                                        <td style="font-size: 16px;">{{ $t("Delivery Charge") }}</td>
                                        <td style="float: right; font-size: 16px;color: black;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ $store.state["deliware_cart"].cart_total.delivery_charge | priceformat }}
                                        </td>
                                    </tr>
                                    <tr id="tips_bill" v-if="$store.state['deliware_cart'].cart_total.tips">
                                        <td style="font-size: 16px; ">{{ $t("Tips") }}</td>
                                        <td style="float: right; font-size: 16px;color: black;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ $store.state["deliware_cart"].cart_total.tips | priceformat }}
                                        </td>
                                    </tr>
                                    <tr id="cgst_bill">
                                        <td style="font-size: 16px; ">
                                            CGST
                                            <span>{{ tax($store.state["defaults"].tax) }}</span>
                                        </td>
                                        <td style="float: right ; font-size: 16px;color: black;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ ($store.state["deliware_cart"].cart_total.tax / 2) | priceformat }}
                                        </td>
                                    </tr>
                                    <tr id="sgst_bill">
                                        <td style="font-size: 16px; ">
                                            SGST
                                            <span>{{ tax($store.state["defaults"].tax) }}</span>
                                        </td>
                                        <td style="float: right ; font-size: 16px;color: black;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ ($store.state["deliware_cart"].cart_total.tax / 2) | priceformat }}
                                        </td>
                                    </tr>
                                    <!-- <tr id="tips_bill">
                                        <td style="font-size: 16px; ">
                                            {{ $t("Tax") }}
                                            <span>{{ tax($store.state["defaults"].tax) }}</span>
                                        </td>
                                        <td style="float: right ; font-size: 16px;color: black;">
                                            {{ $store.state["defaults"].currency }}
                                            {{ $store.state["deliware_cart"].cart_total.tax | priceformat }}
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td>
                                            <b style="font-weight: 700; font-size: 16px;color: black;">{{ $t("Bill Total") }} </b>
                                        </td>
                                        <td style="float: right;font-size: 16px;color: black;">
                                            <b>{{ $store.state["defaults"].currency }}
                                                {{ $store.state["deliware_cart"].cart_total.bill | priceformat }}
                                            </b>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="primary" block
                                style="width: 100%; height: 44px; font-size: 20px; font-weight: 700;margin-top: 16px;"
                                @click="proceed_to_payment()">
                                {{ $t("Proceed to Payment") }}
                            </b-button>

                        </div>
                    </b-card>
                </b-col>
                <b-col cols="12" lg="4">
                    <b-card class="" v-if="is_proceed_payment">
                        <b-card v-if="isloading">
                            <div class="text-l">
                                <lottie-player src="/animations/procpay.json" background="#FFFDFD" speed="1"
                                    style="width: 300px; height: 300px" loop autoplay></lottie-player>
                            </div>
                        </b-card>
                        <b-card v-if="!isloading">
                            <h4>
                                {{ $t("Pay") }} {{ $t("Online") }} /
                                {{ $t("Cash-on-Delivery") }}
                            </h4>
                            <p>
                            </p>
                            <b-row v-if="is_cod" style="padding: 10px;cursor:pointer" @click="placeOrder(1)">
                                <b-col cols="12">
                                    <b-row style="border: 1px solid;border-radius: 4px;padding: 10px;">
                                        <b-col cols="2">
                                            <i class="fa-solid fa-money-bills" style="float:left"></i>
                                        </b-col>
                                        <b-col cols="8">
                                            <span>{{ $t("Cash-on-Delivery") }}</span>
                                        </b-col>
                                        <b-col cols="2">
                                            <i class="fa-solid fa-arrow-right" style="float:right"></i>
                                        </b-col>
                                    </b-row>
                                    <div>
                                    </div>
                                </b-col>
                            </b-row>
                            <b-row v-if="is_online" style="padding: 10px;cursor:pointer" @click="placeOrder(0)">
                                <b-col cols="12">
                                    <b-row style="border: 1px solid;border-radius: 4px;padding: 10px;">
                                        <b-col cols="2">
                                            <i class="fa-solid fa-credit-card" style="float:left"></i>
                                        </b-col>
                                        <b-col cols="8">
                                            <span>{{ $t("Pay") }} {{ $t("Online") }}</span>
                                        </b-col>
                                        <b-col cols="2">
                                            <i class="fa-solid fa-arrow-right" style="float:right"></i>
                                        </b-col>
                                    </b-row>
                                    <div>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-card>
                    </b-card>
                    <b-card class="" v-else>
                        <div class="checkout_map" id="checkout_map"></div>
                        <div class="checkout_slot_address_section mt-2">
                            <div class="checkout_slot_address_section_left">
                                <div class="checkout_del_address">
                                    {{ $t("Delivery Address") }}
                                </div>
                                <p id="demo"><feather-icon icon="MapPinIcon" size="16" class="ico-color2" /> {{
                                    user_address.address }}
                                </p>

                                <div class="checkout_del_address mt-1">
                                    {{ $t("Preferred Time Frame For Delivery") }}
                                </div>

                                <div class="slots_section mt-1">
                                    <b-tabs content-class="mt-2" align="center">
                                        <b-tab v-for="(data, index) in schedule_options" :key="index">
                                            <template #title>
                                                <div class="slot-title">
                                                    <div class="slot-day">{{ data.day }}</div>
                                                    <div class="slot-date">{{ data.date }}</div>
                                                </div>
                                            </template>
                                            <div>
                                                <b-row class="justify-content-center">
                                                    <b-col v-for="(slot_data, slot_index) in data.slots"
                                                        :key="slot_index" cols="auto" class="mb-2">
                                                        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                                            :variant="(selected_day == data.date && selected_time == data.converted_slots[slot_index]) ? 'primary' : 'outline-primary'"
                                                            class="slot-btn"
                                                            @click="select_slot(data.date, data.converted_slots[slot_index])">
                                                            {{ slot_data }}
                                                        </b-button>
                                                    </b-col>
                                                </b-row>
                                            </div>
                                        </b-tab>
                                    </b-tabs>
                                </div>
                            </div>
                        </div>
                    </b-card>
                </b-col>
                <b-col cols="12" lg="2"></b-col>
            </b-row>
        </div>
    </div>
</template>
<script>
import { BModal, BButton, VBModal, BAlert, BCard, BFormRadioGroup, BFormGroup, BRow, BCol, BMedia, BImg, BTabs, BTab, BCardText } from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import banner from '@@@/views/user/users/banner.vue'
import store from "@@@/store";
import axios from '@axios'

export default {
    components: {
        BButton,
        BModal,
        BAlert,
        BCard,
        BFormRadioGroup, BFormGroup, BRow, BCol, banner, BMedia, BImg, BTabs, BTab, BCardText
    },
    data() {
        return {
            options: ['COD', 'Pay Online'],
            isloading: false,
            is_cod: false,
            is_online: false,
            schedule_options: [],
            is_proceed_payment: false,
            user_address: {},
            selected_day: null,
            selected_time: null,
            show_payment_success: false,
            delivery_type: 1,

        }
    },
    directives: {

        Ripple,
    },
    //  methods: {
    //     select_delivery_type(type) {
    //         this.delivery_type = type;
    //     },

    // },
    Select_delivery_type(type) {
        this.delivery_type = type

        this.loadcart()
    },
    filters: {
        priceformat(number) {
            var decimals = store.state["defaults"].decimal;
            var dec_point = store.state["defaults"].delimiter;
            var thousands_sep = "";
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
    },
    computed: {
        tax() {
            const taxtext = {
                0: "(excl)",
                1: "(incl)",
            };

            return (tax) => taxtext[tax];
        },
    },
    mounted() {
         const saved = localStorage.getItem('delivery_type');
  this.delivery_type = saved ? parseInt(saved) : 1;
  console.log('Delivery type:', this.delivery_type);
  },
    created() {
        if (
            localStorage.getItem("cart") &&
            JSON.parse(localStorage.getItem("cart")).length > 0
        ) {
            this.loadcart();
        }
        this.$http.post('/getpaymentmethods')
            .then(res => {
                if (res.data.cod == 1) {
                    this.is_cod = true;
                }
                if (res.data.online == 1) {
                    this.is_online = true;
                }
            });
        if (store.state['defaults'] && store.state['defaults'].username) {
            var user = store.state['defaults'].username

            this.$http.get(`/get_delivery_address/${user.id}`)
                .then(res => {
                    if (res.data.data.length) {
                        let defaultAddress = res.data.data.find(address => address.is_default == 1);
                        console.log(defaultAddress);

                        if (defaultAddress) {
                            console.log("Default Address Found:", defaultAddress);
                            this.user_address = defaultAddress;
                            this.initmap();
                        }
                    }
                })
                .catch(error => {
                    console.error("Error fetching delivery address:", error);
                });
        }

        this.fetch_scheduele_sl0ts()

    },

    methods: {
           select_delivery_type(type) {
            this.delivery_type = type;
             this.loadcart();
        },
        proceed_to_payment() {
            if (this.selected_day != null && this.selected_time != null) {
                this.is_proceed_payment = true
            } else {
                this.$toast({
                    component: ToastificationContent,
                    position: "bottom-right",
                    props: {
                        title: "Please select your preferred delivery time frame before placing the order.",
                        icon: "AlertTriangleIcon",
                        variant: "warning",
                    },
                });
            }

        },
        select_slot(date, time) {
            this.selected_day = date
            this.selected_time = time
        },
        fetch_scheduele_sl0ts() {
            const cart = JSON.parse(localStorage.getItem("cart"));
            const restaurant = cart[0].restaurant
            this.$http.get(`/api/get_schedule_slots/?restaurant_id=${restaurant}`)
                .then(res => {
                    this.schedule_options = res.data.data
                })
                .catch(error => {
                    console.error("Error fetching delivery address:", error);
                });
        },
        initmap() {
            console.log("here");

            const lat = this.user_address.lat
            const lng = this.user_address.lng
            const element = document.getElementById("checkout_map");
            const mapoptions = {
                zoom: 18,
                disableDefaultUI: true,
                styles: [
                    {
                        featureType: "administrative",
                        elementType: "geometry",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                    {
                        featureType: "poi",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },

                    {
                        featureType: "road",
                        elementType: "labels.icon",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                    {
                        featureType: "transit",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                ],
                center: new google.maps.LatLng(lat, lng)
            };
            const map = new google.maps.Map(element, mapoptions);
            var lati = lat;
            var long = lng;
            var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                draggable: false
            });
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
                store.commit(
                    "deliware_cart/UPDATE_PHARMACY_FILE",
                    this.pharmacy_file
                );
            }
            var DELIVERY_CHARGE = localStorage.getItem("DC");
            localStorage.getItem("DC", JSON.stringify(DELIVERY_CHARGE));
            var DELIVERY_CHARGE_TYPE = localStorage.getItem(
                "DELIVERY_CHARGE_TYPE"
            );
            var packaging_charge = 0; // localStorage.getItem('RES_PACK_CHARGE');
            var DELIVERY_CHARGE_BASEDON = localStorage.getItem("DC_BON");
            // let cart = JSON.parse(localStorage.getItem("cart"));
            var lat = localStorage.getItem("latitude");
            var lng = localStorage.getItem("longitude");
            if (localStorage.getItem("webuserData")) {
                this.$http
                    .get(
                        "/get_checkout_details/" +
                        cart[0].restaurant +
                        "/" +
                        lat +
                        "/" +
                        lng +
                        "/" +
                        user.id
                    )
                    .then((res) => {
                        localStorage.setItem("DISTANCE", res.data.distance);
                    });
            }
            this.$http
                .get(
                    "/validate_pincode/" +
                    lat +
                    "/" +
                    lng +
                    "/" +
                    cart[0].restaurant
                )
                .then((res) => {
                    this.is_deliverable = res.data.is_deliverable;
                });
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
                var offer_discount =
                    document.getElementById("applied_coupon_Amount").value || 0;
            } else {
                var offer_discount = 0;
            }
            let wallet = localStorage.getItem("WALLET");
            let total_price = 0;
            let total_item = 0;
            let tot_tax = 0;
            let is_tax = localStorage.getItem("IS_TAX_INCLUSIVE");
            let tot_amt = 0;
            let item_amount_total = 0;
            let restaurant_packaging_charge = 0;

            if (packaging_charge == 0) {
                packaging_charge =
                    cart.length !== 0
                        ? cart[0].restaurant_packaging_charge || 0
                        : 0;
                //  JSON.parse(localStorage.getItem('RES_PACK_CHARGE'));
            }
            if (cart.length === 0) {
            } else {
                cart.forEach(function (item, i) {
                    console.log("taxperc", item.taxperc);
                    if (
                        document.querySelectorAll(
                            '[id="food_control_' + item.id + 'r"]'
                        )
                    ) {
                        var elms = document.querySelectorAll(
                            '[id="food_control_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms.length; i++) {
                            elms[i].style.display = "block";
                        }
                        var elms2 = document.querySelectorAll(
                            '[id="food_add_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms2.length; i++) {
                            elms2[i].style.display = "none";
                        }
                        var elms3 = document.querySelectorAll(
                            '[id="food_qty_' + item.id + 'r"]'
                        );
                        for (var i = 0; i < elms3.length; i++) {
                            elms3[i].value = item.quantity;
                        }
                        // document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
                        // document.getElementById('food_add_' + item.id+'r').style.display = 'none';
                        // document.getElementById('food_qty_' + item.id+'r').value = item.quantity;
                    }
                    if (
                        document.querySelectorAll(
                            '[id="food_qty_' + item.id + 'c"]'
                        )
                    ) {
                        var elms4 = document.querySelectorAll(
                            '[id="food_qty_' + item.id + 'c"]'
                        );
                        for (var i = 0; i < elms4.length; i++) {
                            elms4[i].value = item.quantity;
                        }
                        // document.getElementById('food_qty_' + item.id+'c').value = item.quantity;
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
                    tot_tax += parseFloat(
                        (thisAmount / 100) * (item.taxperc || 0)
                    );
                    localStorage.setItem("tot_tax", tot_tax);
                    // if (is_tax == 0) {
                    //     tot_amt = total_price + tot_tax;

                    // } else {
                    tot_amt = total_price;

                    // }
                    item_amount_total += thisAmount;
                });
                console.log("tot_amt175*********", tot_amt);
                restaurant_packaging_charge = parseFloat(
                    (item_amount_total / 100) * packaging_charge
                ); // Refer above - Commanded for temporary

                var dc = this.delivery_type;
                if (dc == 1) {
                    var delivery_charge_calc = await this.calc_delivery_charge(
                        tot_amt,
                        cart[0].restaurant
                    );
                    if (document.getElementById("selected_tips")) {
                        var tips =
                            document.getElementById("selected_tips").value || 0;
                    } else {
                        var tips = 0;
                    }
                } else {
                    var delivery_charge_calc = 0;
                    var tips = 0;
                }
                total_price =
                    tot_amt === 0
                        ? tot_amt
                        : (
                            tot_amt -
                            parseFloat(offer_discount) +
                            tot_tax +
                            restaurant_packaging_charge +
                            delivery_charge_calc +
                            parseFloat(tips)
                        ).toFixed(2);
                localStorage.setItem("tot_amt", total_price);
                if (total_price < parseFloat(wallet)) {
                    wallet = total_price;
                    total_price = 0;
                } else {
                    total_price = total_price - wallet;
                }
                var bill = {
                    item_total: tot_amt,
                    packaging_charge: restaurant_packaging_charge,
                    delivery_charge: delivery_charge_calc,
                    discount: offer_discount,
                    bill: total_price,
                    tips: tips,
                    dc: dc,
                    tax: tot_tax,
                    wallet: wallet,
                };
                localStorage.setItem("bill", bill);
                store.commit("deliware_cart/UPDATE_CART_TOTAL", bill);
            }
        },
        
        calc_delivery_charge(total_price, rest_id) {
            return new Promise(function (resolve, reject) {
                var lat = localStorage.getItem("latitude");
                var lng = localStorage.getItem("longitude");
                axios
                    .get(
                        "/calc_delivery_charge/" +
                        rest_id +
                        "/" +
                        lat +
                        "/" +
                        lng +
                        "/" +
                        total_price
                    )
                    .then(
                        (response) => {
                            var result = response.data.delivery_charge;
                            console.log("Processing Request");
                            resolve(result);
                        },
                        (error) => {
                            reject(error);
                        }
                    );
            });
        },
        async placeOrder(pay_type) {
            try {
                this.isloading = true;
                console.log("Placing order with payment type:", this.isloading);
                const userData = JSON.parse(localStorage.getItem("webuserData"));
                let bill, items, bookingDetails = {}, RestaurantID, tax, tips, DeliveryMethod;


                const cart = JSON.parse(localStorage.getItem("cart") || "[]");
                if (!cart.length) {
                    alert("No items in the cart");
                    this.isloading = false;
                    return;
                }
                const deliCart = this.$store.state["deliware_cart"];
                bill = deliCart.cart_total;
                items = cart.map(item => ({
                    FoodID: item.id,
                    Quantity: item.quantity,
                    SizeID: item.sizes || 0,
                    // AddonIDs: item.addons || [],
                    AddonIDs: [],
                    addons_quantity: item.addonsqty || []
                }));
                RestaurantID = cart[0].restaurant;
                tax = parseFloat(localStorage.getItem("tot_tax") || 0);
                tips = parseFloat(bill.tips || 0);
                DeliveryMethod = this.$store.state.deliware_cart.cart_total.dc === 1 ? "delivery" : "takeaway"

                const orderData = {
                    Address: this.user_address.address,
                    CookingNotes: localStorage.getItem("instructions") || "",
                    CreatedThrough: "web",
                    CustomerID: userData.id,
                    DeliveryCharge: bill.delivery_charge, // 0.0,
                    DeliveryMethod: DeliveryMethod,
                    Items: items,
                    Booking_details: bookingDetails,
                    Latitude: parseFloat(this.user_address.lat),
                    Longitude: parseFloat(this.user_address.lng),
                    OfferAmount: bill.discount,
                    OfferCode: bill.offer_code,
                    PaymentMethod: pay_type === 1 ? "delivery" : "online",
                    RestaurantID: RestaurantID,
                    WalletAmount: 0.0,
                    client_secret: "",
                    payment_id: "",
                    tax,
                    tips,
                    ScheduledAt: this.selected_day,
                    ScheduledTime: this.selected_time,
                    provider_id: 0
                };

                const response = await axios.post("/api/CreateOrder", orderData);

                if (response.data.status) {
                    if (pay_type === 1) {
                        this.confirm_order(response.data.OrderID);
                    } else {
                        this.$router.push({ name: "pay_for_order_razorpay", params: { id: response.data.OrderID } })
                    }
                } else {
                    this.pop_error_toast(response.data.message);
                    throw new Error(response.data.message || "Failed to place order");
                }
            } catch (error) {
                console.error("Order placement error:", error);
                this.$toast({
                    component: ToastificationContent,
                    position: "bottom-right",
                    props: {
                        title: "Order failed",
                        icon: "AlertCircleIcon",
                        variant: "danger",
                    },
                });
            } finally {
                this.isloading = false;
                console.log("Placing order with payment type end:", this.isloading);
            }
        },

        pop_error_toast(msg) {
            this.$swal({
                title: 'Error!',
                text: this.$t(msg),
                icon: 'error',
                customClass: { confirmButton: 'btn btn-primary' },
                buttonsStyling: false,
            });
        },
        async confirm_order(order_id) {
            const orderData = {
                OrderID: order_id
            };
            const response = await axios.post("/api/ConfirmOrder", orderData);
            if (response.data.status) {
                this.show_success();
                this.clear_cart();
                setTimeout(() => {
                    this.$router.push({ name: "trackorder", params: { id: order_id } });
                }, 2000);
            }
        },
        show_success() {
            this.show_payment_success = !this.show_payment_success
        },
        clear_cart() {
            let cart = []
            localStorage.setItem('delivery_type', null);
            localStorage.setItem('cart', JSON.stringify(cart));
            this.$store.commit('deliware_cart/UPDATE_CART_ITEMS', cart);
            this.$store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cart.length);
        },
        paynow(pay_type) {
            if (localStorage.getItem('webuserData')) {
                var is_cod = pay_type;
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
                data.append('gst', parseFloat(bill.tax));
                data.append('tips', parseFloat(bill.tips));
                data.append('offer_discount', parseFloat(bill.discount));
                data.append('delivery_type', parseFloat(bill.dc));
                data.append('delivery_charge', parseFloat(bill.delivery_charge));
                data.append('paid_type', 1);
                data.append('restaurant_id', cart[0].restaurant);
                data.append('restaurant_packaging_charge', parseFloat(bill.packaging_charge));
                data.append('bill_amount', parseFloat(bill.bill));
                data.append('is_cod', is_cod);
                data.append('device_type', 'WEB');
                data.append('user_id', userData.id);
                data.append('pharmacy_image', p_image ? p_image : '');
                this.$http.post('/paynow', data)
                    .then(res => {
                        console.log(res)
                        this.isloading = false
                        if (res.data.status == true) {
                            if (res.data.data.checkout_url != null) {
                                this.$router.push({ name: "pay_for_order_razorpay", params: { id: res.data.data.checkout_url } })
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
                                let cartn = JSON.parse(localStorage.getItem("cart"));
                                this.$store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
                                this.$store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
                                var bill = {};
                                this.$store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)
                                this.$router.push({ name: "trackorder", params: { id: res.data.data.order_id } })
                            }
                        }
                        if (res.data.status == true) {
                        } else {
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
            } else {
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
.pt-10 {
    padding-top: 10rem !important;
}

#checkout_map {
    height: 250px;
    width: 100%;
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
</style>

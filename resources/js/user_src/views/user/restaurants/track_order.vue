<template>
    <div class="pt-11" style="background-color:#FFFFFF !important;">
        <b-card style="margin-top: 80px !important;background-color:  white !important; box-shadow: none;">
            <b-row>
                <b-col lg="6" md="12" cols="12">
                    <div id="map" style="width: 100%; height: 650px"></div>
                </b-col>
                <b-col lg="6" md="12" cols="12">
                    <!-- <div class="bg-white p-4 shadow-lg mb-2">
                    <div class="mb-2"><small>{{$t("Order")}} #{{ orderstatus.order_id }}</small></div>
                    <h6 class="mb-1 mt-1"><a href="#" class="text-black">{{ orderstatus.restaurant_name }}
                        </a>
                    </h6>

                    <p class="text-gray mb-0"> {{ orderstatus.item_count }} Items | {{$store.state['defaults'].currency}} {{ orderstatus.item_total | price_format_final }}</p>
                </div>
                <div class="bg-white p-4 shadow-lg">
               <div class="osahan-track-order-detail po">
                  <h5 class="mt-0 mb-3">{{$t("Order")}} {{$t("Details")}}</h5>
                  <div class="row">
                     <div class="col-md-5">
                        <small>From</small>
                        <h6 class="mb-1 mt-1"><a href="#" class="text-black"><i class="fa-solid fa-utensils"></i>  {{ orderstatus.restaurant_name }}
                           </a>
                        </h6>
                        <p class="text-gray mb-5">{{ orderstatus.restaurant_address }}</p>
                        <small>{{$t("DeliveringTo")}}</small>
                        <h6 class="mb-1 mt-1"><span class="text-black"><i class="fa-solid fa-location-pin"></i> Home
                           </span>
                        </h6>
                        <p class="text-gray mb-0">{{ orderstatus.door }},
                           {{ orderstatus.landmark }},
                           {{ orderstatus.delivery_address }}
                        </p>
                     </div>
                     <div class="col-md-7">
                        <div class="mb-2"><small><i class="fa-solid fa-list"></i> {{ orderstatus.item_count }}{{$t("Items")}}</small></div>

                        <div v-for="(data,index) in orderstatus.item_list"
                        :key="index"
                        >
                        <p class="mb-2">


                           {{ data.food_name }} x {{ data.food_quantity }} ={{ data.individual_price }}
                           <span class="float-right text-secondary">{{$store.state['defaults'].currency}} {{ data.item_price | price_format_final }}</span>
                        </p>
                        </div>
                        <hr class="hrcolor">
                        <table width="100%" class="float-right">

                            <tr>
                            <td>{{$t("Sub Total")}}</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.item_total | price_format_final }}</td>
                            </tr>
                            <tr>
                            <td>{{$t("Packaging Charge")}}</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.restaurant_packaging_charge | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr>
                            <td>{{$t("Delivery Charge")}}</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.delivery_charge | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr >
                            <td>{{$t("Tips")}}</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.tips | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr >
                            <td>{{$t("Tax")}}</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.tax | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr >
                            <td>{{$t("Discount")}}</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.offer_discount | price_format_final }} <small>(-)</small></td>
                            </tr>
                            <tr>
                            <td>
                                <p>{{$t("Total Bill")}} </p>
                            </td>
                            <td>
                                {{$store.state['defaults'].currency}}  {{ orderstatus.item_total + orderstatus.restaurant_packaging_charge + orderstatus.delivery_charge + orderstatus.tips +orderstatus.tax - orderstatus.offer_discount  | price_format_final}}
                            </td>
                            </tr>
                            <tr>
                            <td>

                            </td>
                            <td>
                                <p class="mb-0 text-info "><small>{{ orderstatus.is_cod ? "Cash-on-Delivery": "Online Payment"}}</small></p>
                            </td>
                            </tr>
                        </table>
                     </div>

                  </div>
               </div>
            </div>
            <div class="bg-white p-4 shadow-lg mt-2">
                <div class="row text-center">
                    <div class="col">
                        <i class="fa-solid fa-list-check text-success"></i>
                        <p class="mt-1 font-weight-bold text-dark mb-0">{{orderstatus.status}}</p>

                    </div>

                </div>
            </div> -->
                    <section class="invoice-preview-wrapper">
                        <!-- Alert: No item found -->
                        <b-alert variant="danger" :show="req_details === undefined">
                            <h4 class="alert-heading">
                                {{ $t("Error fetching Order details") }}
                            </h4>
                        </b-alert>

                        <b-row v-if="req_details" class="invoice-preview">
                            <!-- Col: Left (Invoice Container) -->
                            <b-col cols="12" xl="12" md="12">
                                <b-card no-body class="invoice-preview-card">
                                    <!-- Header -->
                                    <b-card-body class="invoice-padding pb-0">
                                        <div
                                            class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                            <!-- Header: Left Content -->
                                            <div>
                                                <!-- <div class="logo-wrapper"> -->
                                                <p class="card-text mb-25">

                                                    {{ $t("Order Id") }}
                                                    #{{ req_details.order_id }}

                                                </p>
                                                <!-- </div> -->
                                                <p class="card-text mb-25">
                                                    {{ $t("Order Status") }} :
                                                    {{ order_status }}
                                                </p>
                                                <p class="card-text mb-25" v-if="cancell_status">
                                                    {{ $t("Order Cancel Reason") }} :
                                                    {{ cancell_status.detail }}
                                                </p>
                                                <p class="card-text mb-25">
                                                    {{ $t("Ordered On") }} :
                                                    {{ req_details.ordered_time }}
                                                </p>
                                                <p class="card-text mb-25" v-if="req_details.takeaway_code">
                                                    {{ $t("Takeaway Code") }} :
                                                    {{ req_details.takeaway_code }}
                                                </p>
                                            </div>

                                            <!-- Header: Right Content -->
                                            <!-- <div class="mt-md-0 mt-2">
                                                <h4 class="invoice-title">
                                                    {{ $t("Order Id") }}
                                                    <span class="invoice-number"
                                                        >#{{
                                                            req_details.order_id
                                                        }}</span
                                                    >
                                                </h4>
                                                <div
                                                    class="invoice-date-wrapper"
                                                ></div>
                                            </div> -->
                                        </div>
                                    </b-card-body>
                                    <!-- Spacer -->
                                    <hr class="hrcolor" />

                                    <!-- Invoice Client & Payment Details -->
                                    <b-card-body v-if="req_details" class="invoice-padding pt-0">
                                        <b-row class="invoice-spacing">
                                            <!-- Col: Invoice To -->
                                            <b-col cols="12" xl="6" class="p-0">
                                                <p class="mb-2" style="font-weight: 600;size: 16px;">
                                                    {{
                                                        $t("Customer Details")
                                                    }}:
                                                </p>
                                                <h6 class="mb-25">
                                                    {{ req_details.user_name }}
                                                </h6>
                                                <p class="card-text mb-25">
                                                    {{
                                                        req_details.delivery_address
                                                    }}
                                                </p>

                                                <p class="card-text mb-25">
                                                    {{ req_details.user_phone }}
                                                </p>
                                                <p class="card-text mb-0">
                                                    {{ req_details.user_email }}
                                                </p>
                                            </b-col>

                                            <!-- Col: Payment Details -->
                                            <b-col xl="6" cols="12" class="p-0">
                                                <p class="mb-2" style="font-weight: 600;size: 16px;">
                                                    {{
                                                        $t(
                                                            "Store Details"
                                                        )
                                                    }}:
                                                </p>
                                                <h6 class="mb-25">
                                                    {{
                                                        req_details.restaurant_name
                                                    }}
                                                </h6>
                                                <p class="card-text mb-25">
                                                    {{ req_details.address }}
                                                </p>

                                                <p class="card-text mb-25">
                                                    +{{ req_details.contact_country_calling_code }}{{
                                                    req_details.contact }}
                                                </p>
                                                <p class="card-text mb-0">
                                                    {{
                                                        req_details.restaurants_email
                                                    }}
                                                </p>
                                            </b-col>
                                        </b-row>
                                    </b-card-body>

                                    <!-- Invoice Description: Table -->
                                    <!-- <b-table-lite responsive :items="food_details" :fields="[
                                        'Name',
                                        // 'description',
                                        'quantity',
                                        'price',
                                     ]">
                                        <template #cell(taskDescription)="data" >
                                            <b-card-text class="font-weight-bold mb-25">
                                                {{ data.item.Name }}
                                            </b-card-text>
                                            <b-card-text class="text-nowrap">
                                                {{ data.item.description }}
                                            </b-card-text>
                                        </template>
                                    </b-table-lite> -->
                                                        <b-table-lite 
                    responsive 
                    :items="food_details" 
                    :fields="[
                        { key: 'Name', label: 'NAME', thStyle: { color: 'black', background: '#FF81B633', fontWeight: 'bold', fontSize: '16px' },tdClass: ' px-2' },
                         { key: 'quantity', label: 'QUANTITY', thStyle: { color: 'black', background: '#FF81B633', fontWeight: 'bold', fontSize: '16px' }, tdClass: ' px-5' },
                         { key: 'gst', label: 'GST', thStyle: { color: 'black', background: '#FF81B633', fontWeight: 'bold', fontSize: '16px' }, tdClass: ' px-5' },
                         { key: 'igst', label: 'IGST', thStyle: { color: 'black', background: '#FF81B633', fontWeight: 'bold', fontSize: '16px' }, tdClass: ' px-5' },
                        { key: 'price', label: 'PRICE', thStyle: { color: 'black', background: '#FF81B633', fontWeight: 'bold', fontSize: '16px' }, tdClass: ' px-4' },
                    ]">
                    
                    <template #cell(taskDescription)="data" >
                        <b-card-text class="font-weight-bold mb-25 ">
                        {{ data.item.Name }}
                        </b-card-text>
                    </template>
                    </b-table-lite>

                               <!-- Invoice Description: Total -->
                                    <b-card-body class="invoice-padding pb-0" >
                                        
                                                <!-- <b-col cols="12" md="6" class="mt-md-0 mt-3" order="2" order-md="1">
                                                    <b-card-text class="mb-0">
                                                        <span class="font-weight-bold"></span>
                                                        <span class="ml-75"></span>
                                                    </b-card-text>
                                                </b-col> -->

                                            <!-- Col: Total -->
                                 
                                                <div class="invoice-total-wrapper">
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title"  >
                                                            <!-- {{
                                                                $t(
                                                                    "Order Summary"
                                                                )
                                                            }} -->
                                                            {{
                                                                $t(
                                                                    "Item Total"
                                                                )
                                                            }}
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{
                                                                req_details.item_total
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            {{
                                                                $t(
                                                                    "Packing Charge"
                                                                )
                                                            }}
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{
                                                                req_details.restaurant_packaging_charge }}
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            {{
                                                                $t(
                                                                    "Delivery Fee"
                                                                )
                                                            }}
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{
                                                                req_details.delivery_charge }}
                                                        </p>
                                                    </div>
                                                    <!-- <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            {{ $t("Tax") }}
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{ req_details.rtax
                                                            }}
                                                        </p>
                                                    </div> -->
                                                       <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            CGST
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{ req_details.rtax /2
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            SGST
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{ req_details.rtax /2
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            {{ $t("Tips") }}
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{ req_details.tips
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            {{
                                                                $t(
                                                                    "Total Discount"
                                                                )
                                                            }}:
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{
                                                                req_details.offer_discount }}
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">
                                                            {{
                                                                $t(
                                                                    "Wallet"
                                                                )
                                                            }}
                                                             <!-- (-): -->
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{
                                                                req_details.wallet_amounts }}
                                                        </p>
                                                    </div>
                                                    <!-- <hr class="my-50 hrcolor" / > -->
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title" style="font-weight: 600 !important; font-size: 16px !important;">
                                                            {{ $t("Total") }}
                                                        </p>
                                                        <p class="invoice-total-amount">
                                                            {{ $store.state['defaults'].currency }} {{
                                                                req_details.bill_amount
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            
                                    </b-card-body>
                                </b-card>
                            </b-col>
                        </b-row>
                    </section>
                </b-col>
            </b-row>
        </b-card>
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
    BNavItem,
    BAlert,
    BTableLite,
} from "bootstrap-vue";

export default {
    components: {
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
        BAlert,
        BTableLite,
    },
    data() {
        return {
            faqSearchQuery: "",
            faqData: {},
            rows: "",
            appname: "",
            userData: JSON.parse(localStorage.getItem("webuserData")),
            orderstatus: {},
            locations: {},
            icons: {},
            order_status: {},
            food_details: {},
            req_details: {},
            cancell_status: {},
        };
    },
    filters: {
        price_format_final: function (price) {
            let number = price,
                decimals = 2,
                dec_point = ".",
                thousands_sep = "";
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
    },
    mounted() {
        this.track_order();
    },
    created() { },
    methods: {
        initMap() {
            var loc = localStorage.getItem("loc");
            if (loc) {
                var locations = JSON.parse(loc);
                var lat = locations[0][1]; //rider lat
                var lng = locations[0][2];
                var image = "https://www.justbiryani.app/img/ic_pin_bike.png";
                var image1 = "https://www.justbiryani.app/img/ic_user.png";
            } else {
                // var locations = {!! $locations !!};
                var lat = this.orderstatus.restaurant_lat; //rider lat
                var lng = this.orderstatus.restaurant_lng;
                var rest_name = this.orderstatus.restaurant_name;
                var image = this.icons[0];
                var image1 = this.icons[1];
            }

            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            });
            var infowindow = new google.maps.InfoWindow();
            var marker, i;

            var lat_lng = new Array();
            var latlngbounds = new google.maps.LatLngBounds();

            var marker1 = new google.maps.Marker({
                position: new google.maps.LatLng(
                    localStorage.getItem("latitude"),
                    localStorage.getItem("longitude")
                ),
                map: map,
                icon: image,
            });

            var marker2 = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map,
                icon: image1,
            });
            var lat_lng = new Array();
            lat_lng.push(new google.maps.LatLng(localStorage.getItem("latitude"), localStorage.getItem("longitude")));
            lat_lng.push(new google.maps.LatLng(lat, lng));
            var path = new google.maps.MVCArray();
            var service = new google.maps.DirectionsService();
            var poly = new google.maps.Polyline({
                map: map,
                strokeColor: '#E01764'
            });
            for (var i = 0; i < lat_lng.length; i++) {
                if ((i + 1) < lat_lng.length) {
                    var src = lat_lng[i];
                    var des = lat_lng[i + 1];
                    path.push(src);
                    poly.setPath(path);
                    service.route({
                        origin: src,
                        destination: des,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    }, function (result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                                path.push(result.routes[0].overview_path[i]);
                            }
                        }
                    });
                }
            }
        },

        track_order() {
            this.$http
                .get("/view_order/" + this.$route.params.id)
                .then((res) => {
                    this.req_details = res.data.message;
                    this.food_details = res.data.food;
                    this.order_status = res.data.order_status;
                    this.cancell_status = res.data.cancell_status;
                });
            window.setInterval(() => {
                this.$http
                    .get("/view_order/" + this.$route.params.id)
                    .then((res) => {
                        this.req_details = res.data.message;
                        this.food_details = res.data.food;
                        this.order_status = res.data.order_status;
                        this.cancell_status = res.data.cancell_status;
                    });
            }, 10 * 1000);
            this.$http
                .get(
                    "/track_order/" +
                    this.userData.id +
                    "/" +
                    this.$route.params.id
                )
                .then((res) => {
                    this.orderstatus = res.data.order_status[0];
                    this.locations = res.data.locations;
                    this.icons = res.data.icons;
                    this.initMap();
                });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/pages/page-faq.scss";
</style>
<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
@import "~@resources/scss/vue/libs/vue-flatpicker.scss";
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
                        >.col-12 {
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

.hr {
    border-bottom: 1px solid #e6e6e6 !important;
}
</style>
<style scoped>
.nav-link {
    color: black;
}

.ht {
    min-height: 600px;
}

.cat-card {
    background: #f4fffd;
    border-color: #00a082;
    border-style: solid;
}

.cat-card2 {
    background: #c6eee7;
}

.pt-11 {
    padding-top: 6rem !important;
}
.invoice-total-item{
    width:  360px !important ;

}
</style>

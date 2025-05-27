<template>
    <div class="pt-11">
     <b-card>
        <b-row>
            <b-col lg="6" md="12" cols="12">
                <div id="map" style="width: 100%; height: 650px;"></div>
            </b-col>
             <b-col lg="6" md="12" cols="12">
                <div class="bg-white p-4 shadow-lg mb-2">
                    <div class="mb-2"><small>Order #{{ orderstatus.order_id }}</small></div>
                    <h6 class="mb-1 mt-1"><a href="#" class="text-black">{{ orderstatus.restaurant_name }}
                        </a>
                    </h6>

                    <p class="text-gray mb-0"> {{ orderstatus.item_count }} Items | {{$store.state['defaults'].currency}} {{ orderstatus.item_total | price_format_final }}</p>
                </div>
                <div class="bg-white p-4 shadow-lg">
               <div class="osahan-track-order-detail po">
                  <h5 class="mt-0 mb-3">Order Details</h5>
                  <div class="row">
                     <div class="col-md-5">
                        <small>From</small>
                        <h6 class="mb-1 mt-1"><a href="#" class="text-black"><i class="fa-solid fa-utensils"></i>  {{ orderstatus.restaurant_name }}
                           </a>
                        </h6>
                        <p class="text-gray mb-5">{{ orderstatus.restaurant_address }}</p>
                        <small>Deliver To</small>
                        <h6 class="mb-1 mt-1"><span class="text-black"><i class="fa-solid fa-location-pin"></i> Home
                           </span>
                        </h6>
                        <p class="text-gray mb-0">{{ orderstatus.door }},
                           {{ orderstatus.landmark }},
                           {{ orderstatus.delivery_address }}
                        </p>
                     </div>
                     <div class="col-md-7">
                        <div class="mb-2"><small><i class="fa-solid fa-list"></i> {{ orderstatus.item_count }}Items</small></div>
                       
                        <div v-for="(data,index) in orderstatus.item_list"
                        :key="index"
                        >
                        <p class="mb-2">
                           

                           {{ data.food_name }} x {{ data.food_quantity }} ={{ data.individual_price }}
                           <span class="float-right text-secondary">{{$store.state['defaults'].currency}} {{ data.item_price | price_format_final }}</span>
                        </p>
                        </div>
                        <hr>
                        <table width="100%" class="float-right">
        
                            <tr>
                            <td>Sub Total</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.item_total | price_format_final }}</td>
                            </tr>
                            <tr>
                            <td>Packaging Charge</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.restaurant_packaging_charge | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr>
                            <td>Delivery Charge</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.delivery_charge | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr >
                            <td>Tips</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.tips | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr >
                            <td>Tax</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.tax | price_format_final }} <small>(+)</small></td>
                            </tr>
                            <tr >
                            <td>Discount</td>
                            <td>{{$store.state['defaults'].currency}} {{ orderstatus.offer_discount | price_format_final }} <small>(-)</small></td>
                            </tr>
                            <tr>
                            <td>
                                <p>Total Bill </p>
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
            </div>
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
    BNavItem ,
  } from 'bootstrap-vue'
  
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
    BNavItem ,
    },
    data() {
      return {
        faqSearchQuery: '',
        faqData: {},
        rows:'',
        appname:'',
        userData: JSON.parse(localStorage.getItem('webuserData')),
        orderstatus : {},
        locations : {},
        icons :{},
      }
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
    mounted() {
    this.track_order()
    
    
    },
    created() {

    
    },
    methods:{
        initMap(){
           
            var loc =  localStorage.getItem('loc');
            if(loc){
                
            var locations = JSON.parse(loc);
            var lat = locations[0][1]; //rider lat
            var lng = locations[0][2];
            var image = this.icons[0];
            var image1 = this.icons[1];

            }else{
                // var locations = {!! $locations !!};
                var lat = this.orderstatus.restaurant_lat;//rider lat
                var lng = this.orderstatus.restaurant_lng;
                var rest_name = this.orderstatus.restaurant_name;
                var image = this.icons[0];
                var image1 = this.icons[1];

            }
          

            var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: new google.maps.LatLng(lat, lng),
            mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var infowindow = new google.maps.InfoWindow();
            var marker, i;

            var lat_lng = new Array();
            var latlngbounds = new google.maps.LatLngBounds();
           
                
            var marker1 = new google.maps.Marker({
                        position: new google.maps.LatLng(localStorage.getItem('latitude'),localStorage.getItem('longitude')),
                        map: map,
                        icon: image
                   
                    });

            var marker2 = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lng),
                        map: map,
                        icon: image1
                
                    });    
        },

        track_order(){
           

            this.$http.get('/track_order/'+this.userData.id+'/'+this.$route.params.id)
        .then(res => {
                console.log(res)
                this.orderstatus = res.data.order_status[0];
                this.locations = res.data.locations;
                this.icons = res.data.icons;
                console.log(this.icons)
                this.initMap()
            })
            
        }
    }
  }
  </script>
  
  <style lang="scss">
  @import '~@resources/scss/vue/pages/page-faq.scss';
  </style>
  <style scoped>
  .nav-link{
    color:black;
  }
  .ht{
    min-height: 600px;
  }
  .cat-card {
      background: #F4FFFD;
      border-color: #00A082;
      border-style: solid;
  }
  .cat-card2 {
      background: #C6EEE7;
  
  }
  .pt-11{
    padding-top: 6rem !important;
    }
  </style>
  
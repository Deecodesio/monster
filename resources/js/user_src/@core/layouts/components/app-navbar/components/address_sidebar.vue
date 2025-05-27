<template>
    <div class="container">
        <div class="text-l">
            <div>
              <h4>{{ $t("My") }} {{ $t("Address") }}</h4>
            </div>
            <b-row @click="showModal">  
                <b-col cols="2" class="text-center">
                    <i class="fa-solid fa-circle-plus"></i>
                </b-col>
                <b-col cols="8">
                    <h4>{{ $t("Add") }} {{ $t("New") }} {{ $t("Address") }}</h4>
                    <p id="demo" class="hover">{{ rows }}</p>

                </b-col>
            </b-row>
            <div v-if="prop.length">
                <h4>{{ $t("Choose") }} {{ $t("Your") }} {{ $t("Delivery") }} {{ $t("Address") }}</h4>
                <div v-for="(data, index) in prop" :key="index">
                    <b-row @click="setdefault(data.id)" class="hover box">
                        <b-col cols="2" class="text-center">
                            <i class="fa-solid fa-circle-check"></i>
                        </b-col>
                        <b-col cols="8">
                            <div>
                                <h5>{{ data.type | add_type }}</h5>
                                <p>{{ data.address }}</p>

                            </div>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>
        <b-modal id="modal-address" title="Login" ok-only ok-title="Login" cancel-title="Close" hide-footer hide-header
            size="lg" centered ref="my-modal">
            <b-row>
                <b-col lg="12" md="12" sm="12">
                    <div id="gmap"></div>
                </b-col>

                <b-col lg="12" md="12" sm="12">
                    <validation-observer ref="simpleRules">
                        <b-form>
                            <b-row>
                                <b-col md="6" xl="6" class="mb-1">
                                    <b-form-group :label="$t('Name')">
                                        <validation-provider #default="{ errors }" rules="required" name="Name">
                                            <b-form-input id="basicInput" placeholder="Name" v-model="address.name" />
                                            <small class="text-danger">{{ errors[0] }}</small>
                                        </validation-provider>
                                    </b-form-group>
                                </b-col>
                                <b-col md="6" xl="6" class="mb-1">
                                    <b-form-group :label="$t('Apartment & Flat No.')">
                                        <validation-provider #default="{ errors }" rules="required"
                                            name="Apartment & Flat No.">
                                            <b-form-input id="basicInput" placeholder="Apartment & Flat No."
                                                v-model="address.flat_no" />
                                            <small class="text-danger">{{ errors[0] }}</small>
                                        </validation-provider>
                                    </b-form-group>
                                </b-col>
                                <b-col md="6" xl="6" class="mb-1">
                                    <b-form-group :label="$t('Landmark')">
                                        <validation-provider #default="{ errors }" rules="required" name="Landmark">
                                            <b-form-input id="basicInput" placeholder="Landmark"
                                                v-model="address.landmark" />
                                            <small class="text-danger">{{ errors[0] }}</small>
                                        </validation-provider>
                                    </b-form-group>
                                </b-col>
                                <b-col md="6" xl="6" class="mb-1">
                                    <b-form-group :label="$t('Address')">
                                        <validation-provider #default="{ errors }" rules="required" name="Address">
                                            <b-form-input class="ht-1" v-model="rows" placeholder="Enter Address"
                                                id="searchMapInput" />
                                            <small class="text-danger">{{ errors[0] }}</small>
                                        </validation-provider>
                                        <input type="hidden" id="selectedadd">
                                    </b-form-group>
                                </b-col>
                            </b-row>
                            <input type="hidden" id="latitude" name="latitude" :value=lat>
                            <input type="hidden" id="longitude" name="longitude" :value=lng>
                            <b-row>
                                <b-col md="6" xl="6" class="mb-1">
                                    <div>
                                        <div class="d-flex">
                                            <label class="label_class">{{ $t("Add Label") }}</label>
                                        </div>
                                        <div class="demo-inline-spacing ">
                                            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                                :variant="addressType.value == address.type ? 'primary' : 'outline-primary'"
                                                v-for="(addressType, index) in addressTypes" :key="index"
                                                @click="selecttype(addressType.value)">
                                                {{ addressType.label }}
                                            </b-button>
                                        </div>
                                    </div>
                                </b-col>
                                <b-col md="6" class="pad0 pt-1 add_model_btn">
                                    <div class="">
                                        <b-button class="f-size-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'" block
                                            variant="primary" type="submit" @click.prevent="add_address">
                                            {{ $t("Confirm") }} {{ $t("Address") }}
                                        </b-button>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-form>
                    </validation-observer>
                </b-col>
            </b-row>
        </b-modal>
    </div>
</template>

<script>
import {
    BAvatar, BListGroup, BListGroupItem, BCardText, BRow, BModal, VBModal, BButton, BFormInput,
    BCol, VBToggle, BFormGroup, BForm,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@@@/store'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
    required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
export default {
    components: {
        ValidationProvider,
        ValidationObserver,
        BAvatar,
        BListGroup,
        BListGroupItem,
        BCardText,
        BRow,
        BFormGroup,
        BCol, BModal, VBModal, BButton, BFormInput,
        BForm,
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
    },
    directives: {
        'b-toggle': VBToggle,
        Ripple,
    },
    data() {
        return {
            rows: localStorage.getItem('address'),
            address: {
                flat_no: '',
                landmark: '',
                address: '',
                type: 1,
                lat: null,
                lng: null,
            },
            userData: store.state['defaults'].username,
            user: {},
            loading: false,
            lat: localStorage.getItem('latitude'),
            lng: localStorage.getItem('longitude'),
            is_loggedin: this.is_loggedinfunc(),
            addressTypes: [
                { value: 1, label: 'Home' },
                { value: 2, label: 'Work' },
                { value: 3, label: 'Others' }
            ],

        }
    },
    props: {
        prop: {

            default: () => { },
        },

    },
    filters: {
        add_type: function (type) {

            if (type == 1) {
                var ty = "Home";
            }
            if (type == 2) {
                var ty = "Work";
            }
            if (type == 3) {
                var ty = "Others";
            }
            return ty;
        },

    },
    created() {
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
        if (store.state['defaults'].username) {

            //     this.$http.get('/get_delivery_address/'+this.userData.id)
            //   .then(res => {

            //     //   this.address = res.data.data


            //       })

            this.$http.get('/get_address/' + this.lat + '/' + this.lng)
                .then(res => {

                    this.rows = res.data.data

                    localStorage.setItem('address', this.rows);
                })
        }


    },
    watch: {
        is_loggedin: function () {
            localStorage.setItem("isChecked", this.is_loggedin);
        }
    },
    methods: {
        selecttype(addr_type) {
            this.address.type = addr_type
        },
        get_address() {
            var user = store.state['defaults'].username
            this.$http.get('/get_delivery_address/' + user.id)
                .then(res => {

                    this.address = res.data.data


                })
        },
        is_loggedinfunc: function () {
            const stored = localStorage.getItem("webuserData");
            if (stored === null) {
                return true;
            } else {
                return stored == 'true';
            }
        },
        setdefault(id) {
            var user = JSON.parse(localStorage.getItem('webuserData'));
            this.$http.get('/set_delivery_address/' + id + '/' + user.id)
                .then(res => {
                    if (res.data.status == true) {
                        localStorage.setItem('latitude', res.data.address.lat)
                        localStorage.setItem('longitude', res.data.address.lng)
                        localStorage.setItem('address', res.data.address.address)
                        this.$emit('clicked-show-detail', res.data.address.address);
                    }

                })
        },
        add_address() {
            this.$refs.simpleRules.validate().then(success => {
                if (success) {
                    var user = JSON.parse(localStorage.getItem('webuserData'));

                    var sc = document.getElementById('selectedadd').value
                    this.rows = sc
                    var latitude = document.getElementById('latitude').value;
                    var longitude = document.getElementById('longitude').value;
                    let city = new FormData();
                    city.append('address', sc);
                    city.append('lat', latitude);
                    city.append('lng', longitude);
                    city.append('type', this.address.type);
                    city.append('landmark', this.address.landmark);
                    city.append('flat_no', this.address.flat_no);
                    city.append('user_id', user.id);



                    this.$http
                        .post("/add_delivery_address", city)
                        .then(
                            (response => {
                                if (response.data.status == true) {


                                    this.$toast({
                                        component: ToastificationContent,
                                        position: 'bottom-right',
                                        props: {
                                            title: response.data.message,
                                            icon: 'CoffeeIcon',
                                            variant: 'success',
                                        },
                                    })
                                    this.$refs['my-modal'].hide()
                                    this.$http.get('/get_delivery_address/' + user.id)
                                        .then(res => {

                                            this.address = res.data.data
                                            this.$emit('clicked-show-detail', sc);
                                        })
                                } else {

                                    this.$toast({
                                        component: ToastificationContent,
                                        position: 'bottom-right',
                                        props: {
                                            title: response.data.message,
                                            icon: 'CoffeeIcon',
                                            variant: 'failure',
                                        },
                                    })
                                }


                            })
                        )
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                }
            })

        },
        showModal() {

            if (localStorage.getItem('webuserData')) {
                this.$refs['my-modal'].show()
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
            var timer = setInterval(function () {
                if (document.getElementById("gmap")) {
                    document.getElementById("selectedadd").value = localStorage.getItem('address')
                    const element = document.getElementById("gmap");
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
                        center: new google.maps.LatLng(localStorage.getItem('latitude'), localStorage.getItem('longitude'))
                    };
                    const map = new google.maps.Map(element, mapoptions);

                    var lati = document.getElementById('latitude').value;
                    var long = document.getElementById('longitude').value;

                    var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
                    var geocoder = new google.maps.Geocoder();

                    var input = document.getElementById('searchMapInput');

                    var autocomplete = new google.maps.places.Autocomplete(input);
                    autocomplete.bindTo('bounds', map);
                    var infowindow = new google.maps.InfoWindow();
                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        map: map,
                        draggable: true

                    });
                    autocomplete.addListener('place_changed', function () {
                        var place = autocomplete.getPlace();



                        var address = '';
                        if (place.address_components) {
                            address = [
                                (place.address_components[0] && place.address_components[0].short_name || ''),
                                (place.address_components[1] && place.address_components[1].short_name || ''),
                                (place.address_components[2] && place.address_components[2].short_name || '')
                            ].join(' ');
                        }

                        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                        infowindow.open(map, marker);
                        var geocoder = new google.maps.Geocoder();

                        var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
                        geocoder.geocode({
                            'latLng': latlng
                        },
                            function (results, status) {

                                if (results[0]) {
                                    var add = results[0].formatted_address;
                                    document.getElementById('selectedadd').value = results[0].formatted_address;
                                    document.getElementById('latitude').value = place.geometry.location.lat();
                                    document.getElementById('longitude').value = place.geometry.location.lng();

                                } else {
                                    alert("address not found");
                                }

                            }

                        );


                    });
                    google.maps.event.addListener(marker, 'dragend',
                        function (marker) {
                            var latLng = marker.latLng;
                            var currentLatitude = latLng.lat();
                            var currentLongitude = latLng.lng();

                            geocoder.geocode({
                                'latLng': latLng
                            }, function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        document.getElementById('searchMapInput').value = results[0].formatted_address;
                                        document.getElementById('selectedadd').value = results[0].formatted_address;

                                        document.getElementById('latitude').value = currentLatitude;
                                        document.getElementById('longitude').value = currentLongitude;
                                        infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                                        infowindow.open(map, marker);
                                    }
                                }
                            });
                        });
                    clearInterval(timer);
                }
            }, 1000);

        },

    },

}
</script>
<style scoped>
 
#gmap {
    height: 300px;
    width: 100%;
}

.tipsactive {
    border-color: #FF006B !important;
    background-color: #FF006B !important;
}

.f-size-1 {
    color: #ffffff;
    font-size: 14px;
}

.hover {
    cursor: pointer;
}

.box {
    transition: box-shadow .3s;
}

.box:hover {
    box-shadow: 0 0 11px rgba(33, 33, 33, .2);
}

.label_class {
    font-size: inherit;
    line-height: 1.45;
}

.add_model_btn {
    display: flex;
    flex-direction: column-reverse;
}
</style>

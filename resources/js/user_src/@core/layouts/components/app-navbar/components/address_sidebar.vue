<template>
    <div class="main-address-container">
        <div class="address-container">
            <h2 class="page-title">Choose Delivery Address</h2>
            <p class="page-subtitle">Select or add a delivery address for your order</p>

            <!-- Scrollable Address List -->
            <div class="box">
                <div class="address-list">
                    <div v-for="(data, index) in prop" :key="index" class="address-card"
                        :class="{ 'default-address': data.isDefault, 'selected-address': selectedAddress === data.id }"
                        @click="setdefault(data.id)">
                        <div class="address-header">
                            <span class="address-tag" v-if="data.isDefault">DEFAULT</span>
                            <span class="address-type">{{ data.type | add_type }}</span>
                            <button class="edit-btn" @click.stop="editAddress(data)">Edit</button>
                        </div>

                        <div class="address-details">
                            <p>{{ data.address }}</p>
                            <!-- <p>{{ data.city }}, {{ data.state }} {{ data.zipCode }}, {{ data.country }}</p> -->
                            <!-- <p>Contact: {{ data.contactName }} - {{ data.contactPhone }}</p> -->
                        </div>
                    </div>

                    <!-- Add New Address Card -->

                </div>
            </div>
            <b-modal id="modal-address" title="Login" ok-only ok-title="Login" cancel-title="Close" hide-footer
                hide-header size="lg" centered ref="my-modal">
                <b-row>
                    <b-col lg="12" md="12" sm="12">
                        <div id="gmap"></div>
                    </b-col>

                    <div class="d-grid mx-2">
                        <h3 class="modal-title"
                            style="font-family: Quicksand; font-weight: 700; font-size: 20px; margin-top: 15px;">
                            Enter Address
                            Details
                        </h3>

                        <p class="modal-subtitle"
                            style="font-family: Quicksand; font-weight: 400; font-size: 14px; color: #6B7280;margin-top: 15px;">
                            Please provide complete address information for accurate delivery</p>


                        <div class="d-flex">
                            <label class="label_class">Address Type</label>
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

                    <b-col lg="12" md="12" sm="12" class="mt-2">
                        <validation-observer ref="simpleRules">
                            <b-form>
                                <b-row style="margin-bottom: 20px;">
                                    <b-col md="6" xl="6" class="mb-1">
                                        <b-form-group :label="$t('Name')">
                                            <validation-provider #default="{ errors }" rules="required" name="Name">
                                                <b-form-input id="basicInput" placeholder="Name"
                                                    v-model="address.name" />
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

                                    </b-col>
                                    <b-col md="6" class="pad0 pt-1 add_model_btn">
                                        <div class="">
                                            <b-button class="f-size-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'" block
                                                variant="primary" type="submit" @click.prevent="add_address">
                                                {{ $t("Save") }} {{ $t("Address") }}
                                            </b-button>
                                        </div>
                                    </b-col>
                                </b-row>
                            </b-form>
                        </validation-observer>
                    </b-col>
                </b-row>
            </b-modal>
            <div class="add-new-card" @click="showModal">
                <div class="add-new-content">
                    <div class="circle-container">
                        <span class="plus-icon">+</span>
                    </div>
                    <span class="add-new-title">Add New Address</span>
                    <p class="add-new-subtitle">Add a new delivery location</p>
                </div>
            </div>
            <!-- Location Services -->
            <!-- <div class="location-services">
                <p>Enable location services for more accurate delivery tracking</p>
                <button class="enable-btn">Enable</button>
            </div> -->

            <!-- Action Buttons -->
            <!-- <div class="action-buttons">
                <button class="cancel-btn" @click="cancel">Cancel</button>
                <button class="confirm-btn" @click="confirmSelection">Confirm Address</button>
            </div> -->
        </div>
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
.main-address-container {
    background-color: #FFFFFF;
    height: 694px;
    width: 1200px;
    margin: 0 auto;
    border: 2px solid #E5E7EB;
    border-radius: 25px;
    overflow: hidden;
}

.address-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.page-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

.page-subtitle {
    font-size: 16px;
    color: #666;
    margin-bottom: 24px;
}

.box {
    flex: 1;
    overflow-y: auto;
    /* border: 1px solid #e0e0e0; */
    /* border-radius: 8px; */
    padding: 10px;
    margin-bottom: 20px;
    max-height: 400px;
}

.address-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.address-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.address-card:hover {
    border-color: #1976d2;
    background-color: #F0F9FF;
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.2);
}

.default-address {
    border: 2px solid #1976d2;
    background-color: #f5f9ff;
}

.selected-address {
    border: 2px solid #4CAF50;
    background-color: #f0f9f0;
}

.address-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.address-tag {
    background-color: #e0f7fa;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    margin-right: 8px;
    color: #00796b;
}

.address-type {
    font-weight: bold;
    flex-grow: 1;
    text-transform: capitalize;
}

.edit-btn {
    background: none;
    border: none;
    color: #1976d2;
    font-weight: bold;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
}

.edit-btn:hover {
    background-color: #e3f2fd;
}

.address-details {
    line-height: 1.5;
}

.address-details p {
    margin: 4px 0;
    color: #555;
}

.add-new-card {
    border: 2px dashed #E5E7EB;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #FFFFFF;
}
.add-new-card:hover {
    background-color: #f5f5f5;
    border-color: #1976d2;
}

.add-new-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.plus-icon {
    font-size: 24px;
    color: #9CA3AF;
   justify-content: center;
   align-items: center;
}

.add-new-title {
    font-size: 18px;
    font-weight: 700;
    font-family: Quicksand;
    color: #6B7280;
}

.add-new-subtitle {

    font-size: 16px;
    font-weight: 500;
    font-family: Quicksand;
    color: #9CA3AF;
}

.location-services {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background-color: #f9f9f9;
    border-radius: 8px;
    margin-bottom: 20px;
}

.location-services p {
    margin: 0;
    color: #555;
    font-size: 14px;
}

.enable-btn {
    background: none;
    border: 1px solid #1976d2;
    color: #1976d2;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}

.enable-btn:hover {
    background-color: #1976d2;
    color: white;
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: auto;
}

.cancel-btn,
.confirm-btn {
    padding: 12px 24px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

.cancel-btn {
    background: white;
    border: 1px solid #ddd;
    color: #333;
}

.cancel-btn:hover {
    background-color: #f5f5f5;
    border-color: #bbb;
}

.confirm-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
}

.confirm-btn:hover {
    background-color: #388E3C;
}

.confirm-btn:disabled {
    background-color: #a5d6a7;
    cursor: not-allowed;
}

/* Scrollbar styling */
.box::-webkit-scrollbar {
    width: 8px;
    display: none;
}

.box::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.box::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.box::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.address-type-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.circle-container {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background-color: #F3F4F6; 
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 ;
}

</style>
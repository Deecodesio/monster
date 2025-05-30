<!-- <template>
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
</style> -->


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
                            <p>{{ data.city }}, {{ data.state }} {{ data.zipCode }}, {{ data.country }}</p>
                            <p>Contact: {{ data.contactName }} - {{ data.contactPhone }}</p>
                        </div>
                    </div>

                    <!-- Add New Address Card -->

                </div>
            </div>

            <!-- Address Modal -->
            <!-- <b-modal id="address-modal" size="lg" centered hide-footer>
                <template #modal-title>Add New Address</template>
<div class="modal-content">
    <ValidationObserver ref="addressForm">
        <BForm @submit.prevent="add_address">
            <BRow>
                <BCol cols="12">
                    <BFormGroup label="Search Location" label-for="searchMapInput">
                        <BFormInput id="searchMapInput" placeholder="Enter your address" />
                    </BFormGroup>
                    <div id="gmap" style="height: 300px; width: 100%;"></div>
                    <input type="hidden" id="latitude" v-model="address.lat" />
                    <input type="hidden" id="longitude" v-model="address.lng" />
                    <input type="hidden" id="selectedadd" v-model="address.address" />
                </BCol>

                <BCol cols="12" md="6">
                    <BFormGroup label="Flat/Building No." label-for="flat_no">
                        <BFormInput id="flat_no" v-model="address.flat_no" placeholder="e.g., 123, Tower A" />
                    </BFormGroup>
                </BCol>

                <BCol cols="12" md="6">
                    <BFormGroup label="Landmark" label-for="landmark">
                        <BFormInput id="landmark" v-model="address.landmark" placeholder="e.g., Near Central Park" />
                    </BFormGroup>


                </BCol>

                <BCol cols="12">
                    <BFormGroup label="Address Type">
                        <div class="address-type-buttons">
                            <BButton v-for="type in addressTypes" :key="type.value"
                                :variant="address.type === type.value ? 'primary' : 'outline-secondary'"
                                @click="selecttype(type.value)">
                                {{ type.label }}
                            </BButton>
                        </div>
                    </BFormGroup>
                </BCol>

                <BCol cols="12" class="modal-actions">
                    <BButton variant="secondary" @click="$bvModal.hide('address-modal')">
                        Cancel
                    </BButton>
                    <BButton type="submit" variant="primary">
                        Save Address
                    </BButton>
                </BCol>
            </BRow>
        </BForm>
    </ValidationObserver>
</div>
</b-modal> -->
            <b-modal id="address-modal" size="xl" centered hide-footer>

                <h3 class="modal-title" style="font-family: Quicksand; font-weight: 700; font-size: 20px;">Enter Address
                    Details
                </h3>

                <p class="modal-subtitle"
                    style="font-family: Quicksand; font-weight: 400; font-size: 14px; color: #6B7280;">
                    Please provide complete address information for accurate delivery</p>

                <ValidationObserver ref="addressForm" style="margin-top: 20px;">
                    <BForm @submit.prevent="add_address">
                        <!-- Address Type -->
                        <div class="form-section" >
                            <p class="section-title"
                                style="font-family: Quicksand; font-weight: 700; font-size: 16px !important">Address
                                Type</p>

                            <div class="address-type-buttons">
                                <BButton v-for="type in addressTypes" :key="type.value"
                                    :variant="address.type === type.value ? 'primary' : 'outline-secondary'"
                                    @click="selecttype(type.value)">
                                    {{ type.label }}
                                </BButton>
                            </div>

                        </div>

                        <!-- Contact Information -->
                        <div class="form-section" style="margin-top: 30px;font-family: Quicksand; font-weight: 400; font-size: 16px; color: #6B7280;">
                            <h4 class="section-title">Contact Information</h4>
                            <BRow>
                                <BCol md="6">
                                    <BFormGroup label="Full Name*" label-for="fullName"  >
                                        <BFormInput id="fullName" v-model="address.contactName"
                                            placeholder="Enter your full name" required />
                                    </BFormGroup>
                                </BCol>
                                <BCol md="6">
                                    <BFormGroup label="Phone Number*" label-for="phone"  >
                                        <BFormInput id="phone" v-model="address.contactPhone"
                                            placeholder="+91 Enter phone number" type="tel" required />
                                    </BFormGroup>
                                </BCol>
                            </BRow>
                        </div>

                        <!-- Address Details -->
                        <div class="form-section">
                            <h4 class="section-title">Address Details</h4>

                            <BFormGroup label="House/Floor/Building*" label-for="building">
                                <BFormInput id="building" v-model="address.building"
                                    placeholder="Enter house number, building name" required />
                            </BFormGroup>

                            <BFormGroup label="Area/Street/Locality*" label-for="street">
                                <BFormInput id="street" v-model="address.street"
                                    placeholder="Enter area, street or locality" required />
                            </BFormGroup>

                            <BRow>
                                <BCol md="6">
                                    <BFormGroup label="City*" label-for="city">
                                        <BFormInput id="city" v-model="address.city" placeholder="Enter city"
                                            required />
                                    </BFormGroup>
                                </BCol>
                                <BCol md="6">
                                    <BFormGroup label="Pincode*" label-for="pincode" >
                                        <BFormInput id="pincode" v-model="address.zipCode"
                                            placeholder="Enter 6-digit pincode" maxlength="6" required />
                                    </BFormGroup>
                                </BCol>
                            </BRow>
                        </div>

                        <!-- Additional Options -->
                        <!-- <div class="form-section">
                            <h4 class="section-title">Additional Options</h4>

                            <div class="checkbox-group">
                                <input type="checkbox" id="defaultAddress" v-model="address.isDefault" />
                                <label for="defaultAddress">Set as default address</label>
                            </div>

                            <BFormGroup label="Add delivery instructions" label-for="instructions">
                                <BFormTextarea id="instructions" v-model="address.instructions"
                                    placeholder="Enter delivery instructions (e.g., Ring bell, Gate color, Landmark)"
                                    rows="3" />
                            </BFormGroup>
                        </div> -->

                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <BButton class="location-btn" @click="useCurrentLocation">
                                <LocationIcon size="16" class="icon" />
                                Use Current Location
                            </BButton>

                            <div class="modal-buttons" style=" float: right;">
                                <BButton variant="light" @click="$bvModal.hide('address-modal')" style="margin-right: 10px;">
                                    Cancel
                                </BButton>
                                <BButton type="submit" variant="primary">
                                    Save Address
                                </BButton>
                            </div>
                        </div>
                    </BForm>
                </ValidationObserver>
            </b-modal>
            <div class="add-new-card" @click="showModal">
                <div class="add-new-content">
                    <span class="plus-icon">+</span>
                    <span class="add-new-title">Add New Address</span>
                    <p class="add-new-subtitle">Add a new delivery location</p>
                </div>
            </div>
            <!-- Location Services -->
            <div class="location-services">
                <p>Enable location services for more accurate delivery tracking</p>
                <button class="enable-btn">Enable</button>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="cancel-btn" @click="cancel">Cancel</button>
                <button class="confirm-btn" @click="confirmSelection">Confirm Address</button>
            </div>
        </div>
    </div>
</template>

<script>
import {
    BRow, BModal, VBModal, BButton, BFormInput,
    BCol, BFormGroup, BForm,
} from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@@@/store'
import { ValidationObserver } from 'vee-validate'
import { gridLayer } from 'leaflet'

export default {
    components: {
        ValidationObserver,
        BRow,
        BFormGroup,
        BCol, BModal, VBModal, BButton, BFormInput,
        BForm,
    },
    directives: {
        'b-modal': VBModal,
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
            loading: false,
            lat: localStorage.getItem('latitude'),
            lng: localStorage.getItem('longitude'),
            addressTypes: [
                { value: 1, label: 'Home' },
                { value: 2, label: 'Work' },
                { value: 3, label: 'Others' }
            ],
            selectedAddress: null,
            redirectRoute: '/checkout' // Change this to your actual next page route
        }
    },
    props: {
        prop: {
            type: Array,
            default: () => []
        },
    },
    filters: {
        add_type: function (type) {
            const types = { 1: "Home", 2: "Work", 3: "Others" };
            return types[type] || "Unknown";
        },
    },
    created() {
        this.initializeAddresses();
    },
    methods: {
        initializeAddresses() {
            const lat = localStorage.getItem('latitude');
            const lng = localStorage.getItem('longitude');

            if (store.state['defaults'].username && lat && lng) {
                this.$http.get(`/get_address/${lat}/${lng}`)
                    .then(res => {
                        if (res.data && res.data.data) {
                            this.rows = res.data.data;
                            localStorage.setItem('address', JSON.stringify(this.rows));

                            // Set first address as default if none selected
                            if (this.rows.length > 0 && !this.selectedAddress) {
                                this.setdefault(this.rows[0].id);
                            }
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching addresses:", error);
                    });
            }
        },

        selecttype(addr_type) {
            this.address.type = addr_type;
        },

        setdefault(id) {
            this.selectedAddress = id;
            const user = JSON.parse(localStorage.getItem('webuserData'));

            if (!user || !user.id) {
                this.showLoginToast();
                return;
            }

            this.$http.get(`/set_delivery_address/${id}/${user.id}`)
                .then(res => {
                    if (res.data.status === true) {
                        localStorage.setItem('latitude', res.data.address.lat);
                        localStorage.setItem('longitude', res.data.address.lng);
                        localStorage.setItem('address', res.data.address.address);
                        this.$emit('clicked-show-detail', res.data.address.address);
                    }
                })
                .catch(error => {
                    console.error("Error setting default address:", error);
                });
        },

        showModal() {
            if (localStorage.getItem('webuserData')) {
                this.$bvModal.show('address-modal');
                this.initMap();
            } else {
                this.showLoginToast();
            }
        },

        initMap() {
            // Clear any previous timer
            if (this.mapTimer) clearInterval(this.mapTimer);

            this.mapTimer = setInterval(() => {
                if (document.getElementById("gmap")) {
                    clearInterval(this.mapTimer);
                    this.setupGoogleMap();
                }
            }, 100);
        },

        setupGoogleMap() {
            const element = document.getElementById("gmap");
            if (!element) return;

            // Initialize map
            const mapOptions = {
                zoom: 18,
                disableDefaultUI: true,
                styles: [/* your map styles */],
                center: new google.maps.LatLng(
                    localStorage.getItem('latitude') || 13.0827,
                    localStorage.getItem('longitude') || 80.2707
                )
            };

            const map = new google.maps.Map(element, mapOptions);
            const geocoder = new google.maps.Geocoder();
            const input = document.getElementById('searchMapInput');

            // Initialize marker
            const marker = new google.maps.Marker({
                position: mapOptions.center,
                map: map,
                draggable: true
            });

            // Initialize autocomplete
            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            // Setup event listeners
            this.setupMapEvents(map, marker, geocoder, autocomplete);
        },

        setupMapEvents(map, marker, geocoder, autocomplete) {
            // Autocomplete place selection
            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;

                // Update map position
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);

                // Update form fields
                document.getElementById('selectedadd').value = place.formatted_address;
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
            });

            // Marker drag event
            google.maps.event.addListener(marker, 'dragend', (event) => {
                const position = event.latLng;
                geocoder.geocode({ location: position }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        document.getElementById('searchMapInput').value = results[0].formatted_address;
                        document.getElementById('selectedadd').value = results[0].formatted_address;
                        document.getElementById('latitude').value = position.lat();
                        document.getElementById('longitude').value = position.lng();
                    }
                });
            });

            // Map click event
            map.addListener('click', (event) => {
                marker.setPosition(event.latLng);
                geocoder.geocode({ location: event.latLng }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        document.getElementById('searchMapInput').value = results[0].formatted_address;
                        document.getElementById('selectedadd').value = results[0].formatted_address;
                        document.getElementById('latitude').value = event.latLng.lat();
                        document.getElementById('longitude').value = event.latLng.lng();
                    }
                });
            });
        },

        add_address() {
            this.$refs.addressForm.validate().then(success => {
                if (success) {
                    const user = JSON.parse(localStorage.getItem('webuserData'));
                    if (!user || !user.id) {
                        this.showLoginToast();
                        return;
                    }

                    const formData = new FormData();
                    formData.append('address', this.address.address);
                    formData.append('lat', this.address.lat);
                    formData.append('lng', this.address.lng);
                    formData.append('type', this.address.type);
                    formData.append('landmark', this.address.landmark);
                    formData.append('flat_no', this.address.flat_no);
                    formData.append('user_id', user.id);

                    this.$http.post("/add_delivery_address", formData)
                        .then(response => {
                            if (response.data.status) {
                                this.$bvModal.hide('address-modal');
                                this.showSuccessToast(response.data.message);
                                this.initializeAddresses();
                            } else {
                                this.showErrorToast(response.data.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error adding address:", error);
                            this.showErrorToast("Failed to add address");
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            });
        },

        confirmSelection() {
            if (!this.selectedAddress) {
                this.showWarningToast("Please select an address first");
                return;
            }

            // Emit event for parent component
            this.$emit('address-selected', this.selectedAddress);

            // Redirect to next page
            if (this.redirectRoute) {
                this.$router.push(this.redirectRoute);
            }
        },

        cancel() {
            this.$router.go(-1); // Go back to previous page
        },

        showLoginToast() {
            this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                    title: "Please Login to Continue",
                    icon: 'UserIcon',
                    variant: 'danger',
                },
            });
        },

        showSuccessToast(message) {
            this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                    title: message,
                    icon: 'CheckIcon',
                    variant: 'success',
                },
            });
        },

        showErrorToast(message) {
            this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                    title: message,
                    icon: 'XIcon',
                    variant: 'danger',
                },
            });
        },

        showWarningToast(message) {
            this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                    title: message,
                    icon: 'AlertCircleIcon',
                    variant: 'warning',
                },
            });
        }
    }
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
    border: 1px dashed #999;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #fafafa;
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
    color: #1976d2;
    margin-bottom: 8px;
}

.add-new-title {
    font-weight: bold;
    margin-bottom: 4px;
    color: #1976d2;
}

.add-new-subtitle {
    color: #666;
    font-size: 14px;
    margin-top: 0;
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
 
</style>
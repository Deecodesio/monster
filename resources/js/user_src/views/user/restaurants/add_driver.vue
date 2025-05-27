<template>
    <div>
        <div
            class="shadow-sm"
            style="padding: 13px 50px 20px 50px; background-color: #fff; height: 70px"
        >
            <div class="container">
                <b-row>
                    <b-col md="4" style="">
                        <b-img
                            :src="rows_detail"
                            :alt="alt"
                            class="logo_image"
                        />
                    </b-col>
                    <b-col md="8" style="text-align: right;padding: 6px 50px 20px 50px;" >
                      <b-button
                            v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                            @click="$router.push({ name: 'about_us' })"
                            variant="outline-primary"
                            class="dsp"
                            >{{$t("About Us")}}</b-button>
                        <b-button
                            v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                            @click="$router.push({ name: 'contact' })"
                            variant="primary"
                            class="dsp"

                            >{{ $t("Contact") }}</b-button>
                          
                    </b-col>
                </b-row>
            </div>
        </div>
        <!--   <div class="pb-2" style="margin-top:80px;">
          <b-img src="/more1.jpg" fluid alt="Scan" class="imght2"/>
      </div> -->

        <div class="" style="">
            <validation-observer ref="simpleRules">
                <b-row>
                    <b-col md="6" style="" class="rgt-img w-100">
                        <!-- <b-img src="/new-rest.png" fluid alt="Scan" class="imght2" style="float: right;"/> -->
                    </b-col>
                    <b-col md="6" style="padding: 50px">
                        <h4>
                            {{ $t("New") }} {{ $t("Driver") }}
                            {{ $t("Signup") }}
                        </h4>
                        <validation-observer ref="simpleRules">
                            <b-form>
                                <b-row>
                                    <b-col md="6">
                                        <b-form-group>
                                            <label
                                                >{{ $t("Driver") }}
                                                {{ $t("Name") }}</label
                                            >
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="Driver Name"
                                            >
                                                <b-form-input
                                                    v-model="restaurant.name"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="
                                                        $t('Driver') +
                                                        ' ' +
                                                        $t('Name')
                                                    "
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("Email Id") }}</label>
                                            <validation-provider
                                                #default="{ errors }"
                                                name="Email address"
                                                rules="required|email"
                                            >
                                                <b-form-input
                                                    v-model="restaurant.email"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="
                                                        $t('Email Id')
                                                    "
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{
                                                $t("Mobile number")
                                            }}</label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="Mobile number"
                                            >
                                            <VuePhoneNumberInput 
                                                id="phone1"
                                                no-example 
                                                @update="updatePhoneNumber"
                                                v-model="restaurant.phone"
                                                :state="errors[0] ? false : (valid ? true : null)"
                                                :fetch-country=true
                                            >
                                            </VuePhoneNumberInput>
                                              
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("Address") }}</label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="Address"
                                            >
                                                <!-- <b-form-input
                                                    v-model="restaurant.address"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="$t('Address')"
                                                    id="searchMapInput"
                                                /> -->
                                                <b-form-input
                                                    v-model="restaurant.address"
                                                    :state="errors.length > 0 ? false:null"
                                                    :placeholder="$t('Address') "
                                                    id="searchMapInput"
                                                    @input="initMap"
                                                    autocomplete="off"
                                                  />
                                                  <input type="hidden" id="latitude" name="latitude" value="12.9813658" v-model="restaurant.latitude">
                                                  <input type="hidden" id="longitude" name="longitude" value="80.2199712" v-model="restaurant.longitude">
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("Country") }}</label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="Country"
                                            >
                                                <b-form-input
                                                    v-model="restaurant.country"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="$t('Country')"
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("State") }}</label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="State"
                                            >
                                                <b-form-input
                                                    v-model="restaurant.state"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="$t('State')"
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("City") }}</label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="City"
                                            >
                                                <b-form-input
                                                    v-model="restaurant.city"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="$t('City')"
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("Zipcode") }} </label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required"
                                                name="Zipcode "
                                            >
                                                <b-form-input
                                                    v-model="
                                                        restaurant.zippcode
                                                    "
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="$t('Zipcode')"
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-group>
                                            <label>{{ $t("Password") }} </label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required|password"
                                                name="password"
                                                vid="password"
                                            >
                                                <b-form-input
                                                    v-model="
                                                        restaurant.password
                                                    "
                                                    type="password"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="
                                                        $t('Password')
                                                    "
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-group>
                                            <label
                                                >{{ $t("Repeat") }}
                                                {{ $t("Password") }}
                                            </label>
                                            <validation-provider
                                                #default="{ errors }"
                                                rules="required|confirmed:password"
                                                name="Confirm Password"
                                            >
                                                <b-form-input
                                                    v-model="
                                                        restaurant.passwordcon
                                                    "
                                                    type="password"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    :placeholder="
                                                        $t('Repeat') +
                                                        ' ' +
                                                        $t('Password')
                                                    "
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="6">
                <b-form-group :label="$t('Services Type')">
                  <b-form-checkbox-group
                      v-model="restaurant.services"
                      :options="services"
                      value-field="id"
                      text-field="layout_name"
                      class="demo-inline-spacing"
                    />
                 
                 
                  </b-form-group>
                 </b-col>
                 <b-col md="6">
                <b-form-group :label="$t('Gender')">
               <b-form-radio-group v-model="restaurant.gender" :options="online_radio" />
                  </b-form-group>
                 </b-col>
                                    <b-col cols="12">
                                        <div
                                            v-for="item in document_view"
                                            :key="item.id"
                                        >
                                            <b-row
                                                v-if="item.document_for == 1"
                                            >
                                                <b-col md="6">
                                                    <b-form-group
                                                        :label="
                                                            item.document_name
                                                        "
                                                    >
                                                        <b-form-file
                                                            :id="
                                                                'image' +
                                                                item.id
                                                            "
                                                        />

                                                        <b-form-input
                                                            :id="
                                                                'doc_id' +
                                                                item.id
                                                            "
                                                            hidden="hidden"
                                                            :value="item.id"
                                                            :key="item.id"
                                                        />
                                                    </b-form-group>
                                                    <b-form-group>
                                                    </b-form-group>
                                                </b-col>

                                                <b-col
                                                    md="6"
                                                    v-if="
                                                        item.expiry_date_needed ==
                                                        1
                                                    "
                                                >
                                                    <b-form-group
                                                        :label="
                                                            item.document_name
                                                        "
                                                    >
                                                        <b-form-datepicker
                                                            :id="
                                                                'license_expirys' +
                                                                item.id
                                                            "
                                                            locale="en"
                                                            v-model="
                                                                license_expiry[
                                                                    item.id
                                                                ]
                                                            "
                                                            :min="new Date()"
                                                        />
                                                        <b-form-input
                                                            :id="
                                                                'license_expiry' +
                                                                item.id
                                                            "
                                                            hidden="hidden"
                                                            :value="
                                                                license_expiry[
                                                                    item.id
                                                                ]
                                                            "
                                                            :key="item.id"
                                                        />
                                                        <b-form-input
                                                            :id="
                                                                'exp_id' +
                                                                item.id
                                                            "
                                                            hidden="hidden"
                                                            :value="item.id"
                                                            :key="item.id"
                                                        />
                                                    </b-form-group>
                                                </b-col>
                                            </b-row>
                                        </div>
                                    </b-col>
                                    <!-- <vue-recaptcha 
      :sitekey="sitekey"
      @verify="verifyMethod"
      ></vue-recaptcha> -->
                                    <b-col cols="12" class="pt-1">
                                        
                                        <b-button style="font-size:20px; font-weight: 800; padding: 10px 0px; margin-top:40px;"
                                            block
                                              variant="primary"
                                              type="submit"
                                              @click.prevent="validationForm"
                                            
                                            >
                                            {{$t("Submit")}}
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-form>
                        </validation-observer>
                    </b-col>
                </b-row>
            </validation-observer>
        </div>
    </div>
</template>

<script>
  import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
  import { BImg ,BCard,BCardText,BLink,  BRow, BCol,BFormInput, BFormGroup, BForm,BButton,
    BFormSelect,BFormCheckbox ,BFormDatepicker,BFormFile,BFormRadio,BFormRadioGroup, BFormCheckboxGroup } from 'bootstrap-vue'
  import {
    required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
  } from '@validations'
  import { ValidationProvider, ValidationObserver } from 'vee-validate'
  import vSelect from 'vue-select'
  import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { VueRecaptcha } from 'vue-recaptcha'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
  components: {
      Swiper,
      SwiperSlide,
      BImg,
      BCard,
      BCardText,
      BLink,
      BRow,
      BCol,
      ValidationProvider,
  VueRecaptcha ,BFormRadio,BFormRadioGroup, BFormCheckboxGroup,
      ValidationObserver,BFormInput, BFormGroup, BForm, BButton,BFormSelect, vSelect,
      BFormCheckbox,BFormDatepicker, BFormFile,VuePhoneNumberInput,
    },
    data() {
      return {
        rows: {},
        restaurant:{},
        title :'',
        count:'',
        isempty:false,
        name: '',
        packaging_charge: '',
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
        cities:[],
        areas:[],
        cuisines:[],
        selected_check: [],
        document_view:[],
        document:[],
        license_expiry:{},
        invalidc:true,
      sitekey:'6LfvY5kkAAAAAExIq6rolvkqFCYDCbOwESs92dbB',
      rows_detail: '',
      alt : '',
      services:[],
      online_radio: [
        { text: 'Male', value: 1 },
        { text: 'Female', value: 0 },

      ],
      formattedNumber:'',
      phone_country_code: '',
      phone_country_calling_code: '',
      }
    },
    created() {
        this.$http.get('/layout')
            .then(res => {
            
             this.services=res.data;
        })
      this.$http.get('/get_site_logo/')
          .then(res => {
            
              this.rows_detail = res.data.logo

              this.alt = res.data.app_name
           
            
              })
      this.$http.get('/document_view')

      .then(res => {
          
      this.document_view = res.data

      })

  },
  methods:{
    updatePhoneNumber(data) {
      this.formattedNumber = data.nationalNumber
      this.phone_country_calling_code = data.countryCode
      this.phone_country_code = data.countryCallingCode
            },
    initMap() {
 
                      
    var input = document.getElementById('searchMapInput');

    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();



    var address = '';
    if (place.address_components) {
    address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
    ].join(' ');
    }
    localStorage.setItem('latitude', place.geometry.location.lat())
    localStorage.setItem('longitude', place.geometry.location.lng())
    var geocoder;
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
    geocoder.geocode({
    'latLng': latlng
    },
    function(results, status) {

    if (results[0]) {
    var add = results[0].formatted_address;




    } else {
    alert("address not found");
    }

    }

    );



    });
    },
   verifyMethod(){
   this.invalidc = false
 },
   validationForm() {
     this.$refs.simpleRules.validate().then(success => {
       if (success) {
         let data = new FormData();
             data.append('email', this.restaurant.email);
             data.append('password', this.restaurant.password);
             data.append('city', this.restaurant.city);
             data.append('name', this.restaurant.name);
             data.append('phone', this.formattedNumber);
             data.append('state', this.restaurant.state);
             data.append('address', document.getElementById('searchMapInput').value);
             data.append('country', this.restaurant.country);
             data.append('zipcode', this.restaurant.zippcode);
             data.append('gender', this.restaurant.gender);
             data.append('services', this.restaurant.services);
             data.append('phone_country_calling_code', this.phone_country_calling_code);
            data.append('phone_country_code', this.phone_country_code);
         
             this.document_view.forEach(function(number) {
          
          if(number.document_for == 1)
          {
         
            data.append('license[]', document.getElementById('image'+number.id).files[0]);
             data.append('doc_id[]', document.getElementById('doc_id'+number.id).value);  
             if(number.expiry_date_needed == 1)
             {
                   // Apply some style on paragraph
                   data.append('license_expiry[]', document.getElementById('license_expiry'+number.id).value);
                   data.append('exp_id[]', document.getElementById('exp_id'+number.id).value);  
           
             }
           
         }
          });

         this.$http.post('/new-driver',data)
           .then(res => {
               if(res.data.status == true){
               this.$toast({
                 component: ToastificationContent,
                 props: {
                   title: res.data.message,
                   icon: 'BellIcon',
                   variant: 'success',
                 },
               })
               this.$swal({
                  title: res.data.message,
                
                  icon: 'success',
                  customClass: {
                    confirmButton: 'btn btn-primary',
                  },
                  buttonsStyling: false,
                })
                this.restaurant = {}
             }
             else{
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: res.data.message,
                    icon: 'BellIcon',
                    variant: 'danger',
                  },
                })
              }
         
           })
       }
     })
   },

 
 },
};
</script>
<style lang="scss">
@import "~@resources/scss/base/pages/app-ecommerce.scss";
</style>

<style lang="scss" scoped>
.item-view-radio-group ::v-deep {
    .btn {
        display: flex;
        align-items: center;
    }
}
.cat-card {
    border-radius: 20px;
}
h5 {
    font-size: 20px;
    font-weight: 600;
}
h4 {
    font-size: 30px;
    font-weight: 800;
    padding: 20px 0px;
}

hr {
    border: 1px solid #ececec;
    border-radius: 5px;
    background-color: #ececec;
    max-width: 100%;
    margin-inline-start: 0;
}
.form-control {
    height: 3rem;
}
.vs__search,
.vs__search:focus {
    padding: 6px 7px;
}
.rgt-img {
    background-image: url(/new-reg-1.jpg);
    background-size: cover;
    background-position: center;
}
.hover {
    cursor: pointer;
}
</style>

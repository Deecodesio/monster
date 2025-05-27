<template>
  <div>
    <div class="shadow-sm" style="padding: 13px 50px 20px 50px; background-color: #fff; height: 70px">
      <div class="container">
        <b-row>

          <b-col md="4" style=" ">
            <b-img :src=rows_detail :alt=alt class="logo_image" />
          </b-col>
          <b-col md="8" style="text-align: right;padding: 6px 50px 20px 50px;">
            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'about_us' })"
              variant="outline-primary" class="dsp">{{ $t("About Us") }}</b-button>
            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'contact' })"
              variant="primary" class="dsp">{{ $t("Contact") }}</b-button>

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
          <b-col md="6" style="padding: 50px;">
            <h4>{{ $t("New") }} {{ $t("Business") }} {{ $t("Signup") }}</h4>
            <b-form>
              <b-row>
                <b-col md="12">
                  <h5>{{ $t("Store type") }}</h5>
                </b-col>
                <b-col md="6">

                  <b-form-group>
                    <label>{{ $t("Select") }} {{ $t("Business") }}</label>

                    <v-select v-model="restaurant.business_type" @input="onChange1($event)" :options="this.business_type"
                      label="name" :reduce="sel => sel.id" :placeholder="$t('Select') + ' ' + $t('Business')"
                      autocomplete="off" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />


                  </b-form-group>
                </b-col>
                <b-col md="6">

                  <b-form-group v-if="naming">
                    <label>{{ $t("Store") }} {{ $t("Name") }}</label>
                    <validation-provider #default="{ errors }" rules="required" name="Store Name">
                      <b-form-input v-model="restaurant.name" :state="errors.length > 0 ? false : null"
                        :placeholder="$t('Store') + ' ' + $t('Name')" :disabled="disabled" autocomplete="off" />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>

                  <b-form-group v-else>
                    <label>{{ $t("Store") }} {{ $t("Name") }}</label>
                    <validation-provider #default="{ errors }" rules="required" name="Store Name">
                      <b-form-input v-model="restaurant.name" :state="errors.length > 0 ? false : null"
                        :placeholder="$t('Store') + ' ' + $t('Name')" :disabled="disabled" autocomplete="off" />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col md="12">
                  <hr>
                </b-col>
              </b-row>


              <b-row>

                <b-col md="12">
                  <h5>{{ $t("Login Credentials") }}</h5>
                </b-col>
                <b-col md="6">
                  <b-form-group>
                    <label>{{ $t("Email Id") }}</label>
                    <validation-provider #default="{ errors }" name="Email address" rules="required|email">
                      <b-form-input v-model="restaurant.email" :state="errors.length > 0 ? false : null"
                        :placeholder="$t('Email Id')" :disabled="disabled" autocomplete="off" />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

                <b-col md="6">
                  <b-form-group>
                    <label>{{ $t("Password") }} </label>
                    <validation-provider #default="{ errors }" rules="required|password" name="password" vid="password"
                      :disabled="disabled">
                      <b-form-input v-model="restaurant.password" type="password"
                        :state="errors.length > 0 ? false : null" :placeholder="$t('Password')" autocomplete="off" />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col md="12">
                  <hr>
                </b-col>
              </b-row>
              <b-row>
                <b-col md="12">
                  <h5>{{ $t("Your Contacts") }}</h5>
                </b-col>
                <b-col md="6">
                  <b-form-group>
                    <label>{{ $t("Mobile number") }}</label>
                    <validation-provider #default="{ errors }" rules="required" name="Mobile number">
                      <VuePhoneNumberInput id="phone1" no-example @update="updatePhoneNumber" v-model="restaurant.phone"
                        :state="errors[0] ? false : (valid ? true : null)" :fetch-country=true :disabled="disabled">
                      </VuePhoneNumberInput>

                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

                <b-col md="6">

                  <b-form-group>
                    <label>{{ $t("Contact number") }}</label>
                    <validation-provider #default="{ errors }" rules="required" name="Contact number">
                      <VuePhoneNumberInput id="phone1" no-example @update="updatePhoneNumber2"
                        v-model="restaurant.contact" :state="errors[0] ? false : (valid ? true : null)"
                        :fetch-country=true :disabled="disabled">
                      </VuePhoneNumberInput>

                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>


                <b-col md="12">

                  <b-form-group>
                    <label>{{ $t("Address") }}</label>
                    <validation-provider #default="{ errors }" rules="required" name="Address">
                      <b-form-input v-model="restaurant.address" :state="errors.length > 0 ? false : null"
                        :placeholder="$t('Address')" id="searchMapInput" @input="initMap" :disabled="disabled"
                        autocomplete="off" />
                      <input type="hidden" id="latitude" name="latitude" value="25.1987075" v-model="restaurant.latitude">
                      <input type="hidden" id="longitude" name="longitude" value="55.2516959"
                        v-model="restaurant.longitude">
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col md="12">
                  <hr>
                </b-col>
              </b-row>
              <b-row>
                <b-col md="12">
                  <h5>{{ $t("Timing's & Charges") }} </h5>
                </b-col>
                <b-col md="6">
                  <b-form-group>
                    <label>{{ $t("Estimated Delivery Time") }} (Mins) </label>
                    <validation-provider #default="{ errors }" rules="required" name="Estimated Delivery Time (Mins) ">
                      <v-select id="deliveryTime" v-model="restaurant.estimated_delivery_time"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="deliveryTime" label="text"
                        :reduce="sel => sel.text" :disabled="disabled" autocomplete="off" />

                      <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                        {{ errors[0] }}
                      </b-form-invalid-feedback>

                    </validation-provider>
                  </b-form-group>
                </b-col>


                <b-col md="6">

                  <b-form-group>
                    <label>{{ $t("Packaging charge") }} (%)</label>
                    <validation-provider #default="{ errors }" rules="required" name="Packaging charge">
                      <b-form-input v-model="restaurant.packaging_charge" :state="errors.length > 0 ? false : null"
                        :placeholder="$t('Packaging charge')" type="number" :disabled="disabled" autocomplete="off" />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

                <b-col md="12">
                  <hr>
                </b-col>
              </b-row>
              <b-row>
                <b-col md="12">
                  <h5>{{ $t("Select your Area & City") }}</h5>
                </b-col>
                <b-col md="6">
                  <b-form-group>
                    <label>{{ $t("Select") }} {{ $t("City") }}</label>

                    <v-select v-model="restaurant.city" @input="onChange($event)" :options="this.cities" label="city"
                      :reduce="sel => sel.id" :placeholder="$t('Select') + ' ' + $t('City')" id="city_list"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :disabled="disabled" autocomplete="off" />


                  </b-form-group>
                </b-col>


                <b-col md="6">
                  <b-form-group>
                    <label>{{ $t("Select") }} {{ $t("Area") }}</label>

                    <v-select v-model="restaurant.area" :options="this.areas" label="area" :reduce="sel => sel.id"
                      :placeholder="$t('Select') + ' ' + $t('Area')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :disabled="disabled" autocomplete="off" />

                  </b-form-group>
                </b-col>
                <b-col md="6">
                </b-col>


                <b-col md="12">
                  <b-form-group>
                    <label style="width:100%; float: left: padding:10px 0px;">{{ $t("Delivery") }} {{ $t("Type") }}
                    </label>

                    <b-form-checkbox style="margin-top: 2%; width: 25%; float: left;" v-for="option in options_check"
                      :key="option.value" :value="option.value" :id="'del' + option.value" name="delivery_name"
                      :disabled="disabled">{{ option.text }}</b-form-checkbox>

                  </b-form-group>
                </b-col>
                <b-col md="12" v-if="plan_enable">
                  <b-row class="pricing-card">
                    <b-col offset-sm-2 sm="10" md="12" offset-lg="2" lg="10" class="mx-auto">
                      <b-row>
                        <b-col md="6" v-for="(data1, index1) in plans" :key="index1">
                          <b-card align="center">
                            <b-img-lazy src="/plan.svg" class="mb-2 mt-5" alt="basic svg img" />
                            <h3>{{ data1.name }}</h3>
                            <b-card-text>
                              {{ data1.description }}
                            </b-card-text>

                            <!-- annual plan -->
                            <div class="annual-plan">
                              <div class="plan-price mt-2">
                                <sup class="font-medium-1 font-weight-bold text-primary">{{
                                  $store.state['defaults'].currency }}</sup>
                                <span class="pricing-basic-value font-weight-bolder text-primary">{{ data1.price }}</span>
                                <sub class="pricing-duration text-body font-medium-1 font-weight-bold"
                                  v-if="data1.lifetime == 1">/ {{ $t("Lifetime") }}</sub>
                                <sub class="pricing-duration text-body font-medium-1 font-weight-bold"
                                  v-if="data1.lifetime != 1">/ {{ data1.validity }} {{ $t("days") }}</sub>
                              </div>

                            </div>

                            <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" block class="mt-2"
                              variant="outline-success" :id="'sel' + data1.id" @click="select_plan(data1.id)">
                              {{ $t("Select") }}

                            </b-button>
                          </b-card>
                        </b-col>


                      </b-row>
                    </b-col>
                  </b-row>
                  <input type="hidden" id="selected_plan">
                  <input type="hidden" id="selected_plan_price">
                </b-col>


                <b-col cols="12 ">
                  <b-button style="font-size:20px; font-weight: 800; padding: 10px 0px; margin-top:40px;" block
                    variant="primary" type="submit" @click.prevent="validationForm">
                    {{ $t("Submit") }}
                  </b-button>
                </b-col>
                <!-- <b-col md="1">    </b-col>   -->




              </b-row>
            </b-form>
          </b-col>
        </b-row>
      </validation-observer>

    </div>

  </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import {
  BImg, BCard, BCardText, BLink, BRow, BCol, BFormInput, BFormGroup, BForm, BButton, BFormSelect, BFormCheckbox, BImgLazy, BFormRadio,
  BAvatar, BListGroup, BListGroupItem, BMedia, BFormSpinbutton, BInputGroup, BInputGroupAppend, BSidebar, VBToggle,
} from 'bootstrap-vue'
import {
  required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { VueRecaptcha } from 'vue-recaptcha'
import Ripple from 'vue-ripple-directive'
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
    ValidationProvider, VuePhoneNumberInput,
    ValidationObserver, BFormInput, BFormGroup, BForm, BButton, BFormSelect, vSelect, BFormCheckbox,
    VueRecaptcha, BImgLazy, BFormRadio, BAvatar, BListGroup, BListGroupItem, BMedia, BFormSpinbutton, BInputGroup, BInputGroupAppend, BSidebar, VBToggle,

  },
  data() {
    return {
      rows: {},
      restaurant: {},
      title: '',
      count: '',
      isempty: false,
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
      cities: [],
      areas: [],
      cuisines: [],
      business_type: [],
      disabled: true,
      naming: true,
      selected_check: [],
      plans: [],
      options_check: [
        { text: 'Home Delivery', value: 1 },
        { text: 'Pickup', value: 2 },

        { text: 'Schedule order', value: 4 },
      ],
      deliveryTime: [
        { value: '1', text: '15-20' },
        { value: '2', text: '20-30' },
        { value: '3', text: '30-40' },
        { value: '4', text: '40-50' },
        { value: '5', text: '50-60' },

      ],
      invalidc: true,
      sitekey: '6LfvY5kkAAAAAExIq6rolvkqFCYDCbOwESs92dbB',
      rows_detail: '',
      alt: '',
      formattedNumber: '',
      formattedCountryCode: '',
      formattedCountryCallingCode: '',
      formattedNumber2: '',
      formattedCountryCode2: '',
      formattedCountryCallingCode2: '',
      plan_enable: true
    }
  },
  directives: {

    Ripple,
  },
  created() {
    this.$http.get('/get_site_logo/')
      .then(res => {

        this.rows_detail = res.data.logo

        this.alt = res.data.app_name


      })
    this.$http.get('/city_list')
      .then(res => {
        this.cities = res.data

      })


    this.$http.get('/business_type_list')
      .then(res => {
        this.business_type = res.data
      })
    this.$http.get('/plans')
      .then(res => {
        this.plans = res.data.plans
        if (res.data.plan_enable == 1) {
          this.plan_enable = true
        } else {
          this.plan_enable = false
        }
        console.log(this.plans)
      })
  },
  methods: {
    select_plan(id) {
      var elms2 = document.getElementById('sel' + id + '');
      elms2.innerHTML = "Selected"
      var prev = document.getElementById("selected_plan").value
      if (document.getElementById('sel' + prev + '')) {
        var elms3 = document.getElementById('sel' + prev + '');
        elms3.innerHTML = "Select"
      }
      document.getElementById("selected_plan").value = id;
      this.brand.selected_plan = id
    },
    updatePhoneNumber(data) {
      this.formattedNumber = data.nationalNumber
      this.formattedCountryCallingCode = data.countryCallingCode
      this.formattedCountryCode = data.countryCode
    },
    updatePhoneNumber2(data) {
      this.formattedNumber2 = data.nationalNumber
      this.formattedCountryCallingCode2 = data.countryCallingCode
      this.formattedCountryCode2 = data.countryCode
    },
    verifyMethod() {
      this.invalidc = false
    },

    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          let checkboxes = document.querySelectorAll('input[name="cuisines_name"]:checked');

          let values = [];
          checkboxes.forEach((checkbox) => {
            values.push(checkbox.value);
          });
          this.restaurant.cuisines = values;

          let checkboxes1 = document.querySelectorAll('input[name="delivery_name"]:checked');

          let values1 = [];
          checkboxes1.forEach((checkbox) => {
            values1.push(checkbox.value);
          });
          this.restaurant.delivery_type = values1;

          this.restaurant.latitude = localStorage.getItem('latitude');
          this.restaurant.longitude = localStorage.getItem('longitude');
          this.restaurant.address = document.getElementById('searchMapInput').value;
          this.restaurant.phone_country_code = this.formattedCountryCode;
          this.restaurant.phone_country_calling_code = this.formattedCountryCallingCode;
          this.restaurant.contact_country_code = this.formattedCountryCode2;
          this.restaurant.contact_country_calling_code = this.formattedCountryCallingCode2;
          if (this.plan_enable) {
            let plan = document.getElementById("selected_plan").value;
            this.restaurant.selected_plan = plan
          } else {
            this.restaurant.selected_plan = ""
          }

          this.$http.post('/signup_form_submit', this.restaurant)
            .then(res => {
              if (res.data.status == true) {
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: res.data.message,
                    icon: 'BellIcon',
                    variant: 'success',
                  },
                })

                this.restaurant = {}

                if (res.data.clientsecret == 0) {
                  this.$swal({
                    title: res.data.message,

                    icon: 'success',
                    customClass: {
                      confirmButton: 'btn btn-primary',
                    },
                    buttonsStyling: false,
                  })
                } else {
                  this.$router.push({ name: "restaurant_checkout", params: { plan: res.data.plan.id, trans: res.data.plan.stripe_price_id, id: res.data.restaurant.id } })
                }
              }
              else {
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

    initMap() {


      var input = document.getElementById('searchMapInput');

      var autocomplete = new google.maps.places.Autocomplete(input);

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
        localStorage.setItem('latitude', place.geometry.location.lat())
        localStorage.setItem('longitude', place.geometry.location.lng())
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
        geocoder.geocode({
          'latLng': latlng
        },
          function (results, status) {

            if (results[0]) {
              var add = results[0].formatted_address;




            } else {
              alert("address not found");
            }

          }

        );



      });
    },

    onChange(event) {

      this.$http.post('/area_list1', this.restaurant)
        .then(res => {
          this.areas = res.data

        })

    },
    onChange1(event) {

      this.disabled = false;
      this.$http.get('/business_types/' + event)
        .then(res => {
          if (res.data.layout_id == 2 || res.data.layout_id == 3) {
            this.naming = false;
            // document.getElementById("cuisines_lists").style.display = "none";
          }
          else {
            this.naming = true;
            // document.getElementById("cuisines_lists").style.display = "block";
          }

        })

    },

  },


}
</script>
<style lang="scss">
@import "~@resources/scss/base/pages/app-ecommerce.scss";
@import '~@resources/scss/vue/pages/page-pricing.scss';
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

  border: 1px solid #ECECEC;
  border-radius: 5px;
  background-color: #ECECEC;
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

  background-image: url(/rest2.jpg);
  background-size: cover;
  background-position: center;
}

.hover {
  cursor: pointer;
}
</style>

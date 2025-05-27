<template>
  <b-row>
    <b-col cols="12">
       <div>
    <form-wizard
      color="#7367F0"
      :title="null"
      :subtitle="null"
      shape="square"
      finish-button-text="Submit"
      back-button-text="Previous"
      class="mb-3"
      @on-complete="formSubmitted"
    >

      <!-- accoint details tab -->
      <tab-content
        title="Driver Details"
        :before-change="validationForm"
      >
      
        <validation-observer
          ref="accountRules"
          tag="form"
        >
         
          <b-row>
            <b-col
              cols="12"
              class="mb-2"
            >
              <h5 class="mb-0">
                Driver Details
              </h5>
              <small class="text-muted">
               Add Driver Details.
              </small>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Restaurant (Optional)"
                label-for="name"
              >
                 <!-- <b-form-select
        v-model="driver.restaurant">
         <option  value="" disabled>- Select Restaurant -</option>
       <option
                v-for="userName in this.rows"
                 :value="userName.id"
                :key="userName.restaurant_name"
            >{{ userName.restaurant_name }}
            </option>
                 </b-form-select> -->

                  <v-select
          id="country"
           v-model="driver.restaurant"
        :options="this.rows"
          label="restaurant_name"
          :reduce="sel => sel.id" 
            placeholder="Select Restaurant"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />

              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Vehicle Name*"
                label-for="email"
              >
                
                  <b-form-select
              v-model="driver.vehicle_name">
                 <option  value="" disabled>- Select Vehicle -</option>
                  <option
                v-for="vehicle1 in this.vehicles"
                 :value="vehicle1.id"
                :key="vehicle1.vehicle_name"
            >{{ vehicle1.vehicle_name }}
            </option>
                 </b-form-select>
              </b-form-group>
            </b-col>
             <b-col md="4">
              <b-form-group
                label="Email *"
                label-for="email"
              >
                <validation-provider
                  #default="{ errors }"
                  name="email"
                  rules="required|email"
                >
                  <b-form-input
                    id="email"
                    v-model="driver.email"
                    type="email"
                    :state="errors.length > 0 ? false:null"
                    placeholder="john.doe@email.com"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Work City"
                label-for="password"
              >
               <b-form-select
              v-model="driver.city">
                 <option  value="" disabled>- Select city -</option>
                  <option
                v-for="city in this.cities"
                 :value="city.id"
                :key="city.city"
            >{{ city.city }}
            </option>
                 </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Driver Name*"
                
              >
                
                  <validation-provider
                  #default="{ errors }"
                  name="drivername"
                  rules="required"
                >
                  <b-form-input
                    id="name"
                    v-model="driver.driver_name"
                    :state="errors.length > 0 ? false:null"
                    placeholder="johndoe"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Phone No *"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="number"
                  rules="required"
                >
                  <b-form-input
                    
                    v-model="driver.phone_no"
                    type="number"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Phone Number"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Work Area (Optional)"
                
              >
                <b-form-select
              v-model="driver.area">
                 <option  value="" disabled>- Select area -</option>
                  <option
                v-for="areas1 in this.areas"
                 :value="areas1.id"
                :key="areas1.city"
            >{{ areas1.area }}
            </option>
                 </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Address Line 1*"
               
              >
                  <validation-provider
                  #default="{ errors }"
                  name="Address"
                  rules="required"
                >
                  <b-form-input
                    id="name"
                    v-model="driver.address_line_1"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Address"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Address Line 2"
               
              >
                 <b-form-input
                    id="name"
                    v-model="driver.address_line_2"
                    
                    placeholder="Enter Address"
                  />
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="City*"
                label-for="c-password"
              >
                <validation-provider
                  #default="{ errors }"
                  name="City"
                  rules="required"
                >
                  <b-form-input
                    v-model="driver.address_city"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter City"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="State/Province*"
                label-for="c-password"
              >
                 <validation-provider
                  #default="{ errors }"
                  name="State"
                  rules="required"
                >
                  <b-form-input
                    v-model="driver.state_province"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter State/Province"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Country*"
                label-for="c-password"
              >
                 <validation-provider
                  #default="{ errors }"
                  name="Country"
                  rules="required"
                >
                  <b-form-input
                    v-model="driver.country"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Country"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Avatar *"
                label-for="c-password"
              >
               
                 <b-form-file
                 v-model="driver.profile_pic"
              class="mt-1"
                plain
         
                />
                  
                
              
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group
                label="Zip Code*"
                label-for="c-password"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Zip Code"
                  rules="required"
                >
                  <b-form-input
                   
                    v-model="driver.zip_code"
                 
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Zip Code"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>



            <b-col md="4">
              <b-form-group
                label="About"
                label-for="c-password"
              >
                 <b-form-textarea
                 v-model="driver.about"
        id="textarea"
        rows="3"
        placeholder="About.. "
      />
              </b-form-group>
            </b-col>

 <b-col md="12">
              <h5 class="mb-0">
                Document Settings
              </h5>
</b-col>



            <b-col md="6">
              <b-form-group
                label="License Front"
               
              >
               
                 <b-form-file
                 v-model="driver.license"
              class="mt-1"
                plain
         
                />
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="GST"
               
              >
                <b-form-file
                 v-model="driver.gst"
              class="mt-1"
                plain
         
                />
              </b-form-group>
            </b-col>


 <b-col md="12">
              <h5 class="mb-0">
                Security Settings
              </h5>
</b-col>
            <b-col md="4">
              <b-form-group
                label="Password"
                label-for="password"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Password"
                  vid="Password"
                  rules="required|password"
                >
                  <b-form-input
                    id="password"
                    v-model="driver.password"
                    type="password"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Password"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
      <b-col md="4">
              <b-form-group
                label="Confirm Password"
                label-for="c-password"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Password Confirm"
                  rules="required|confirmed:Password"
                >
                  <b-form-input
                    id="c-password"
                    v-model="passwordCon"
                    type="password"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Re-type Password"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
               <b-col md="4">
              <b-form-group
                label="Status*"
             
              >
               <b-form-select
        v-model="driver.status"
        :options="options"
        
      />
              </b-form-group>
            </b-col>



          </b-row>
        </validation-observer>
        
      </tab-content>

      <!-- personal details tab -->
      <tab-content
        title="Bank Details"
        :before-change="validationFormBank"
      >
        <validation-observer
          ref="infoRules"
          tag="form"
        >
          <b-row>
            <b-col
              cols="12"
              class="mb-2"
            >
              <h5 class="mb-0">
                Bank Details
              </h5>
              <small class="text-muted">Add Bank Details</small>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Account Name*"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="Account Name"
                  rules="required"
                >
                  <b-form-input
                  
                    v-model="driver_tab.account_name"
                    placeholder="Enter Account Name"
                    :state="errors.length > 0 ? false:null"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Bank Name*"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="Bank Name"
                  rules="required"
                >
                  <b-form-input
                   
                    v-model="driver_tab.bank_name"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Bank Name"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
             <b-col md="6">
              <b-form-group
                label="Account Address*"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="Account Address"
                  rules="required"
                >
                  <b-form-input
                    
                    v-model="driver_tab.account_address"
                    placeholder="Enter Account Address"
                    :state="errors.length > 0 ? false:null"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Branch Name*"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="Branch Name"
                  rules="required"
                >
                  <b-form-input
                   
                    v-model="driver_tab.branch_name"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Branch Name"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
             <b-col md="6">
              <b-form-group
                label="Account Number*"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="Account Number"
                  rules="required"
                >
                  <b-form-input
                    
                    v-model="driver_tab.account_no"
                    placeholder="Enter Account Number"
                    :state="errors.length > 0 ? false:null"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Branch Address *"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="Branch Address"
                  rules="required"
                >
                  <b-form-input
                    
                    v-model="driver_tab.branch_address "
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Branch Address "
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
             <b-col md="6">
              <b-form-group
                label="Swift Code">
                
                  <b-form-input
                    v-model="driver_tab.swift_code"
                    placeholder="Enter Swift Code"
                    
                  />
                 
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Routing Number"
                
              >
                
                  <b-form-input
                    
                    v-model="driver_tab.routing_no"
                   
                    placeholder="Enter Routing Number"
                  />
                 
              </b-form-group>
            </b-col>
            
          </b-row>
        </validation-observer>
      </tab-content>

    </form-wizard>

  </div>
   
    </b-col>
  </b-row>
</template>

<script>


import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'

import 'vue-form-wizard/dist/vue-form-wizard.min.css'
// dropdown
import BCardCode from '@core/components/b-card-code'
//form validation//
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
   BFormSelect, BCardText,  BFormFile,BFormTextarea,
} from 'bootstrap-vue'
import { required, email } from '@validations'
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    FormWizard,
    TabContent,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    vSelect,
    BFormInvalidFeedback,
    // eslint-disable-next-line vue/no-unused-components
    ToastificationContent,
    BFormSelect, BCardText,
   BCardCode,
     BFormFile,
     BFormTextarea,
  },
  
   created() {
    this.$http.get('/admin/res_list')
      .then(res => {
          this.rows = res.data
          })
           this.$http.get('/admin/vehicles_list')
      .then(res => {
          this.vehicles = res.data
          })
           this.$http.get('/admin/city_list')
      .then(res => {
          this.cities = res.data
          })
            this.$http.get('/admin/area_list')
      .then(res => {
          this.areas = res.data
          })
  },
  data() {
    return {
      driver: {},
      driver_tab:{},
       rows: [],
       vehicles:[],
        cities:[],
        areas:[],
       selected_res: "",
       selected_vehicle: "",
       selected_city: "",
        selected_area: null,
      options: [
        { value: null, text: 'Status' ,disable:true},
         { value: 1, text: 'Active' },
          { value: 2, text: 'InActive' },
           { value: 3, text: 'New' },
      
        
      ],
    }
  },
  methods: {
    formSubmitted() {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: 'Form Submitted',
          icon: 'EditIcon',
          variant: 'success',
        },
      })
      
            
           
    },
    validationForm() {
      
      return new Promise((resolve, reject) => {
        this.$refs.accountRules.validate().then(success => {
          if (success) {
               let data = new FormData();
                // data.append('file', this.driver.profile_pic);
                data.append('email', this.driver.email);
               data.append('password', this.driver.password);
                 data.append('profile_pic', this.driver.profile_pic);
                data.append('restaurant', this.driver.restaurant);
                data.append('vehicle_name', this.driver.vehicle_name);
                data.append('city', this.driver.city);
                 data.append('driver_name', this.driver.driver_name);
                 data.append('phone_no', this.driver.phone_no);
                  data.append('area', this.driver.area);
                  data.append('address_line_1', this.driver.address_line_1);
                data.append('address_line_2', this.driver.address_line_2);
                data.append('state_province', this.driver.state_province);
                 data.append('address_city', this.driver.address_city);
                  data.append('country', this.driver.country);
                  data.append('about', this.driver.about);
                data.append('zip_code', this.driver.zip_code);
                data.append('status', this.driver.status);
                  data.append('license', this.driver.license);
                data.append('gst', this.driver.gst);

             this.$http
                .post("/admin/add_driver", data)
                .then(
                    //  (response) => this.$router.push({ name: "brand-list" })
                    // console.log(response.data)
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));

           
           
            resolve(true)
          } else {
             
             
            reject()
          }
        })
      })

     
       
    },
    validationFormBank() {
      return new Promise((resolve, reject) => {
        this.$refs.infoRules.validate().then(success => {
          if (success) {
             this.$http
                .post("/admin/add_driver_tab", this.driver_tab)
                .then(
                     (response) => this.$router.push({ name: "brand-list" })
                    // console.log(response.data)
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));

            resolve(true)
          } else {
            reject()
          }
        })
      })
    },

  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>

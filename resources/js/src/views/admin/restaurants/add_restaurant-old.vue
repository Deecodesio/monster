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

      
       <!-- Restaurant details tab -->
      <tab-content
        title="Restaurant Details"
        :before-change="restaurant"
      >
      
        <validation-observer
         ref="accountRules"
          tag="form"
        >
         
          <b-row>
          
            <!-- <b-form-input
              v-model="restaurants.banner"
            id="banner_image"
            hidden="hidden"
            ref="onFileChange"
            type="file"
            class="d-none"
            @change="onFileChange"
            /> -->

          <input
          
             id="banner_image"
            hidden="hidden"
            ref="onFileChange"
            type="file"
            class="d-none"
            @change="onFileChange"
            :v-model="restaurants.banner"
          />

          <!-- <b-form-input
           v-model="restaurants.image"
          id="logo_image"
            hidden="hidden"
            ref="onFileChange1"
            type="file"
            class="d-none"
            @change="onFileChange1"
          
              /> -->
              
          <input
          id="logo_image"
            hidden="hidden"
            ref="onFileChange1"
            type="file"
            class="d-none"
           @change="onFileChange1"
           :v-model="restaurants.image"
          />
              <b-col md="12">
              <div
    id="user-profile"
  >
 
 <b-card
 id="cover_img"
    class="profile-header mb-2"
     :img-src="url? url : require('@/assets/images/banner/banner-1.jpg')"
    img-top
    alt="cover photo"
    body-class="p-0"
    rounded
     fluid
   >
    <!-- <img :src="url? url : require('@/assets/images/banner/banner-1.jpg')"  v-model="restaurants.banner" hidden/> 
   <img :src="url1? url1:require('@/assets/images/banner/banner-34.jpg')"  v-model="restaurants.image" hidden/>  -->
    <!-- profile picture -->
    <div class="position-relative">
      <div class="profile-img-container d-flex align-items-center">
        <div class="profile-img">
                <b-form-input
                    id="banner_id"
                   hidden="hidden"
                    type="text"
                    value="1"
                  />
                   <b-form-input
                    id="logo_id"
                   hidden="hidden"
                    type="text"
                     value="1"
                    
                  />

                   <input
                    id="res_id"
                    name="res_id"
                   hidden="hidden"
                   type="text"
                  />
          <b-img
          id="profile"
            :src="url1? url1:require('@/assets/images/banner/banner-34.jpg')"
            rounded
            fluid
            alt="profile photo"
             @click="$refs.onFileChange1.click()"
          />
         
        </div>
       
      </div>
    </div>
    <!--/ profile picture -->

  

    <!-- profile navbar -->
    <div class="profile-header-nav">
      <b-navbar
        toggleable="md"
        type="light"
      >
        <!-- toggle button -->
        <b-navbar-toggle
          class="ml-auto"
          target="nav-text-collapse"
        >
          <feather-icon
            icon="AlignJustifyIcon"
            size="21"
          />
        </b-navbar-toggle>
        <!--/ toggle button -->

        <!-- collapse -->
        <b-collapse
          id="nav-text-collapse"
          is-nav
        >
          <b-tabs
            pills
            class="profile-tabs mt-1 mt-md-0"
            nav-class="mb-0"
          >
           

            <!-- edit buttons -->
            <template #tabs-end>
               <b-dropdown
        id="dropdown-dropleft"
        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
        
        text="Edit Cover Photo"
        variant="primary"
        class="ml-auto"
      >
        <b-dropdown-item>
          <a @click="$refs.onFileChange.click()">Update Photo </a>
        </b-dropdown-item>
        <!-- <b-dropdown-item>
          <a  v-b-modal.modal-xl
        variant="outline-primary" >Select Photo </a>
        </b-dropdown-item> -->
        <!-- <b-dropdown-item>
          Something else here
        </b-dropdown-item> -->
      </b-dropdown>
            </template>
            <!-- edit buttons -->
          </b-tabs>

        </b-collapse>
        <!--/ collapse -->
      </b-navbar>
    </div>
    <!--/ profile navbar -->
  </b-card>

  </div>
  </b-col >

   


            <b-col md="6">
              <b-form-group
                label="Restaurant Name"
                label-for="name"
              >
                <validation-provider
                  #default="{ errors }"
                  name="name"
                  rules="required"
                >
                  <b-form-input
                    id="name"
                    v-model="restaurants.restaurant_name"
                    type="email"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Restaurant Name"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

             <b-col md="6">
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
                    v-model="restaurants.email"
                    type="email"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Email"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Packaging charge (%)*"
                
              >
                  <validation-provider
                  #default="{ errors }"
                  name="Packaging charge"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.packaging_charge"
                    :state="errors.length > 0 ? false:null"
                    
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
             <b-col md="6">
              <b-form-group
                label="Select City*"
              >
               <v-select
         v-model="restaurants.city"
         @input="onChange($event)"
        :options="this.cities"
          label="city"
          :reduce="sel => sel.id" 
            placeholder="Select city"
            id="city_list"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />
             
              </b-form-group>
            </b-col>

            <b-col md="6">
              <b-form-group
                label="Password"
                label-for="password"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Password"
                  vid="Password"
                 
                >
                  <b-form-input
                    id="password"
                    v-model="restaurants.password"
                    type="password"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Password"
                    
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col md="6">
              <b-form-group
                label="Select Area *"
                
              >
                 <v-select
         v-model="restaurants.area"
        :options="this.areas"
          label="area"
          :reduce="sel => sel.id" 
            placeholder="Select area"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />
                 
              </b-form-group>
            </b-col>

            <b-col md="6">
              <b-form-group
                label="Mobile number *"
                
              >
                <validation-provider
                  #default="{ errors }"
                  name="number"
                  rules="required"
                >
                  <b-form-input
                    
                    v-model="restaurants.phone"
                    type="number"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Phone Number"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            
            <b-col md="6">
              <b-form-group
                label="Contact Number*"
               
              >
                  <validation-provider
                  #default="{ errors }"
                  name="number"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.contact"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Address"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
           <b-col md="6">
              <b-form-group
                label="Status*"
             
              >

              <v-select
         v-model="restaurants.status"
        :options="options"
          label="text"
          :reduce="sel => sel.value" 
            placeholder="Select Status"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />
               
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Estimated Delivery Time (Mins) *"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Estimated Delivery"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.estimated_delivery_time"
                    :state="errors.length > 0 ? false:null"
                    placeholder="15-20"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Brand"
                
              >
               <v-select
         v-model="restaurants.brands"
        :options="this.brand"
          label="name"
          :reduce="sel => sel.id" 
            placeholder="Select Brands"
            class="brand_name"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

        />
               
                  
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Admin Commision %"
                
              >
                 <validation-provider
                  #default="{ errors }"
                  name="Commision"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.admin_commision"
                    :state="errors.length > 0 ? false:null"
                    placeholder="0"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            


            <b-col md="6">
              <b-form-group
                label="Driver Commission %"
               
              >
                <validation-provider
                  #default="{ errors }"
                  name="DriverCommission"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.driver_commision"
                    :state="errors.length > 0 ? false:null"
                    placeholder="0"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col md="6">
              <b-form-group
                label="Geofence Radius"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Geofence Radius"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.GeofenceRadius"
                    :state="errors.length > 0 ? false:null"
                    placeholder="0"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Delivery Type *"
               
              >

               <b-form-checkbox   
               style="margin-top: 2%;"
            @change="changeMethod" 
             v-model="delivery_type" 
            v-for="option in options_check"
          :key="option.value"
          :value="option.value"
          :id="option.value"
          name="delivery_name"
         >{{ option.text }}</b-form-checkbox>
           
                
              </b-form-group>
            </b-col>
<b-col md="6">
              <b-form-group
                label="Cuisines *"
              > 
              <b-form-checkbox 
              style="margin-top: 2%;"
           v-model="cuisines_name" 
            @change="cuisinesMethod"
             v-for="cuisines1 in cuisines"
          :key="cuisines1.id"
          :value="cuisines1.id"
          :id="cuisines1.name"
          name="cuisines_name"
         >{{ cuisines1.name }}</b-form-checkbox>
             
           
                
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Address *"
              >
                <validation-provider
                  #default="{ errors }"
                  name="Address"
                  rules="required"
                >
                  <b-form-input
                    v-model="restaurants.address"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Enter Address"
                    id="searchMadminnput"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
                <br />
              <input type="hidden" id="latitude" name="latitude" value="12.9813658">
              <input type="hidden" id="longitude" name="longitude" value="80.2199712">
              <div id="gmap"></div>

              </b-form-group>
            </b-col>


           

<b-col md="4">
<div>
      <b-form
        ref="form"
        :style="{height: trHeight}"
        class="repeater-form"
        @submit.prevent="repeateAgain"
      >

        <!-- Row Loop -->
        <b-row
          v-for="(item, index) in items"
          :id="item.id"
          :key="item.id"
          ref="row"
        >

          <!-- Item Name -->
          <b-col md="5">
            <b-form-group
              label="Video Title"
            >
              <b-form-input
                id="item-name"
                type="text"
                v-model="restaurants.video_title"
              />
            </b-form-group>
          </b-col>

          <!-- Cost -->
          <b-col md="5">
            <b-form-group
              label="Video Url"
              
            >
              <b-form-input
                id="cost"
                type="text"
                v-model="restaurants.video_url"
              />
            </b-form-group>
          </b-col>

         

         

          <!-- Remove Button -->
          <b-col
            lg="2"
            md="3"
            class="mb-50"
          >
           
          </b-col>
          <b-col cols="12">
            <hr>
          </b-col>
        </b-row>

      </b-form>
    </div>
    <b-button
      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
      variant="primary"
      @click="repeateAgain"
    >
      <feather-icon
        icon="PlusIcon"
        class="mr-25"
      />    
      <span>Add Video</span>
    </b-button>
     <b-button
              v-ripple.400="'rgba(234, 84, 85, 0.15)'"
              variant="danger"
             
              @click="removeItem(index)"
            >
              <feather-icon
                icon="XIcon"
                class="mr-25"
              />
              <span>Remove Video</span>
            </b-button>

</b-col>



          </b-row>
        </validation-observer>
        
      </tab-content>

       <!-- Restaurant Timing details tab -->
      <tab-content
        title="Restaurant Timing"
        :before-change="Restaurant_Timing"
      >
      
        <validation-observer
          ref="accountRules1"
          tag="form"
        >
           <!-- <input type="hidden" id="res_id" name="res_id" > -->
          <b-row >
           
            <b-col md="2">
          <b-form-group
              label="Day"
              
            >
            </b-form-group>

             
            </b-col>
              <b-col md="4">
                <b-form-group
              label="Resturant Opens*"
              
            >
            </b-form-group>
            </b-col>
              <b-col md="4">
             <b-form-group
              label="Resturant Closes*"
              
            >
            </b-form-group>
            </b-col>
              <b-col md="2">
              <b-form-group
              label="Is holiday?"
            >
             </b-form-group>
            </b-col>
           
          </b-row>

           <b-row style="margin: 2%;" v-for="item in 7" :key="item">
           
            <b-col md="2">
          <b-form-group>
           <label> {{ $t('weekday_'+item) }}</label>
             
            
            </b-form-group>

             
            </b-col>
              <b-col md="4">
               <b-form-timepicker
      :id="'timepicker-placeholder1'+item"
      placeholder="Choose a time"
      local="en"

       v-model="opening_time_[item]"
    />
    <b-form-timepicker
      :id="'timepicker-placeholder2'+item"
      placeholder="Choose a time"
      local="en"
      v-model="closing_time_[item]"
    />
            </b-col>
              <b-col md="4">
             <b-form-timepicker
      :id="'timepicker-placeholder3'+item"
      placeholder="Choose a time"
      local="en"
       v-model="opening_second_time_[item]"
    />
    <b-form-timepicker
      :id="'timepicker-placeholder4'+item"
      placeholder="Choose a time"
      local="en"
      v-model="closing_second_time_[item]"
    />
            </b-col>
           
              <b-col md="2">
                 <b-form-checkbox  
                    :id="item" 
                    :key="item" 
                     :value="item" 
                     name="holiday"
                   v-model="holiday" 
                  >
            
            </b-form-checkbox>
             
                
            
            </b-col>
         
          </b-row>
        </validation-observer>
        
      </tab-content>


      <!-- accoint details tab -->
      <tab-content
        title="Document"
        :before-change="Document"
      >
      
        <validation-observer
          ref="accountRules2"
          tag="form"
        >
         
         <div v-for="item in document_view">
              <b-row  v-if="item.document_for == 2" >
             
             
               <b-col md="6">
              <b-form-group
                :label="item.document_name"
              >
               
                 <b-form-file
                  :id="'document'+item.id"
                />
              </b-form-group>
               <b-form-group>
             <b-img
              :id="'document_img'+item.id"
            rounded
            fluid
            class="banner-img"
            alt="banner photo"
           
          />
      </b-form-group>
            </b-col>

             <b-col md="6" v-if="item.expiry_date_needed == 1">
              <b-form-group
                :label="item.document_name"
              >
               
                 <b-form-datepicker
          :id="'dates'+item.id"
           v-model="doc_date[item.id]"
           :min="new Date()"
         />
           <b-form-input
            :id="'date'+item.id"
                  hidden="hidden"
                   :value=doc_date[item.id] 
                   :key="item.id" 
                  />
              </b-form-group>
            </b-col>
           
             
            
            <!-- <b-col md="6">
              <b-form-group
                label="GST"
               
              >
                <b-form-file
                 v-model="driver.gst"
              class="mt-1"
                plain
         
                />
              </b-form-group>
            </b-col> -->
            
          </b-row>
           </div>
        </validation-observer>
        
      </tab-content>

       <!-- accoint details tab -->
      <tab-content
        title="Bank Details"
        :before-change="bank_details"
      >
      
        <validation-observer
          ref="accountRules3"
          tag="form"
        >
         
          <b-row>
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
                  
                    v-model="bank.account_name"
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
                   
                       v-model="bank.bank_name"
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
                    
                    v-model="bank.account_address"
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
                   
                   v-model="bank.branch_name"
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
                    
                     v-model="bank.account_no"
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
                    
                    v-model="bank.branch_address"
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
                    v-model="bank.swift_code"
                    placeholder="Enter Swift Code"
                    
                  />
                 
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group
                label="Routing Number"
                
              >
                
                  <b-form-input
                    
                    v-model="bank.routing_no"
                   
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
     <b-modal
      id="modal-xl"
      ok-only
      ok-title="Accept"
      size="xl"
      title="Select Photo"
    >
      <div id="account">
       <b-form-input
         
          placeholder="Search.."
          id="myInput" @keyup="myFunction"
        />
    
      <b-row >
     
      <div v-for="imgs in images"  :key="imgs.pathLong">
       <b-img
              :id="'image_clcik'+imgs.pathShort"
            :src="imgs.pathLong"
            rounded
            fluid
            :name="'select_image'+imgs.pathShort"
            :alt="imgs.pathShort"
            @click="select(imgs.pathShort)" 
             loading="lazy" 
             class="lazyload"
              :data-src="imgs.pathLong"
              style="max-width:350px;margin: 1%;max-height: 117px;"
          />
      </div>
     
      
      </b-row>
        
     
      

                    
      </div>
    </b-modal>
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
   BFormSelect, BCardText,  BFormFile,BFormTextarea,BFormDatepicker, 
    BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BNavItem, BButton,BFormCheckbox,
    BForm,BFormTimepicker, BInputGroup,  BInputGroupAppend,   BMedia,
  BAvatar,BDropdown, BDropdownItem, BModal, VBModal,
} from 'bootstrap-vue'
import { required, email } from '@validations'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@core/mixins/ui/transition'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'
import { avatarText } from '@core/utils/filter'
import { ref } from '@vue/composition-api'



export default {
  components: {
 BMedia,
  BAvatar,
  BModal, VBModal,
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
    BFormDatepicker, 
  
    ToastificationContent,
    BFormSelect, BCardText,
   BCardCode,
     BFormFile,
     BFormTextarea,
      BCard, BImg, BNavbar, BNavbarToggle,
       BCollapse, BTabs, BNavItem, BButton,
       BFormCheckbox,
        BForm, 
        BFormTimepicker, BInputGroup,  BInputGroupAppend, 
        BDropdown, BDropdownItem,
  },


  data() {
    return {
      url:null,
       url1:null,
       image:[],
       doc_date:[],
    closing_second_time_:[],
    opening_second_time_:[],
    closing_time_:[],
    opening_time_:[],
      driver: {},
      driver_tab:{},
      driver_doc:{},
      profileData:{},
      restaurants:{},
      documents:{},
      
      restaurants_time:{},
      bank:{},
      cuisines:[],
      avatar:{},
      brand:[],
       rows: [],
       vehicles:[],
        cities:[],
        areas:[],
        images:[],
        holiday:[],
        delivery_type:[],
        cuisines_name:[],
        document_view:[],
       checkedNames: [],
       selected_res: "",
       selected_vehicle: "",
       selected_city: "",
        selected_area: null,
      options: [
       
         { value: 1, text: 'Active' },
          { value: 2, text: 'InActive' },
          
      
        
      ],
       selected_check: [],
      options_check: [
        { text: 'Home Delivery', value: 1 },
        { text: 'Pickup', value: 2 },
        { text: 'Dining', value: 3 },
        { text: 'Schedule order', value: 4 },
      ],
       items: [{
        id: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      nextTodoId: 2,
      
      locale: this.$i18n.locale,
     
   
    }
  },
  
     directives: {
    'b-modal': VBModal,
    Ripple,
  },
   mixins: [heightTransition],
 
  // props: {
  //   headerData: {
  //     type: Object,
  //     default: () => {},
  //   },
  // },
  
  setup(props) {
    
    // const { resolveUserRoleVariant } = useUsersList()

    const roleOptions = [
      { label: 'Admin', value: 'admin' },
      { label: 'Author', value: 'author' },
      { label: 'Editor', value: 'editor' },
      { label: 'Maintainer', value: 'maintainer' },
      { label: 'Subscriber', value: 'subscriber' },
    ]

    const statusOptions = [
      { label: 'Pending', value: 'pending' },
      { label: 'Active', value: 'active' },
      { label: 'Inactive', value: 'inactive' },
    ]

    const permissionsData = [
      {
        module: 'Admin',
        read: true,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'Staff',
        read: false,
        write: true,
        create: false,
        delete: false,
      },
      {
        module: 'Author',
        read: true,
        write: false,
        create: true,
        delete: false,
      },
      {
        module: 'Contributor',
        read: false,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'User',
        read: false,
        write: false,
        create: false,
        delete: true,
      },
    ]
 
    // ? Demo Purpose => Update image on click of update
    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, base64 => {
      // eslint-disable-next-line no-param-reassign
    
       this.url = base64
      //  alert(this.url);
      
    })

    return {
    
      avatarText,
      roleOptions,
      statusOptions,
      permissionsData,

      //  ? Demo - Update Image on click of update button
      refInputEl,
      previewEl,
      inputImageRenderer,
      
    } 
  },
  
  mounted() {
    this.initMap()
    this.initTrHeight()
     this.importAll(require.context('@/assets/images/banner/', true, /\.jpg$/)); 
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
   
   watch: {
    locale(val) {
      this.$i18n.locale = val
    },
    checkedNames: {
    deep: true,
    handler: function(newValue){
      console.log("Selected users changed", newValue)
    }
  },
  },
  created() {
   
   window.addEventListener('resize', this.initTrHeight)
  this.$http.get('/admin/res_list')
    .then(res => {
     
        this.rows = res.data
        // console.log(this.rows[0].id)
        })
        this.$http.get('/admin/cuisines_list')
       .then(res => {
        this.cuisines = res.data
        console.log(this.cuisines)
        })
         this.$http.get('/admin/vehicle_list')
    .then(res => {
     
        this.vehicles = res.data
      //  console.log(this.vehicles)
        })
         this.$http.get('/admin/city_list')
    .then(res => {
     
        this.cities = res.data
      //  console.log(this.vehicles)
        })
    //       this.$http.get('/admin/area_list')
    // .then(res => {
     
    //     this.areas = res.data
    //   //  console.log(this.vehicles)
    //     })
         this.$http.get('/admin/document_view')

         .then(res => {
          this.document_view = res.data
        //   document_view1 =[];
        // res.data.forEach(function (element) {
         
        //  if(element.document_for === '1'){
        //     document_view1 = element
          
        //  }
           
        //  });
      
        // // const check = this.document_view.filter(item => res.data.document_for === '1') 
        //  this.document_view = document_view1
        //       console.log(this.document_view)
             
          
        })
         this.$http.get('/admin/brands_list')
        .then(res => {
        this.brand = res.data
      //  console.log(this.vehicles)
        })
        
     this.$http.get('/admin/document_view')
         .then(res => {
          this.document_view = res.data
      
        })

        if ('loading' in HTMLImageElement.prototype) {
          const images = document.querySelectorAll('img[loading="lazy"]');
          images.forEach(img => {
              img.src = img.dataset.src;
              //alert( img);
          });
      } else {
          // Dynamically import the LazySizes library
          const script = document.createElement('script');
          script.src =
              'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.1.2/lazysizes.min.js';
          document.body.appendChild(script);
      }
      

       if(this.$route.params.id){
        this.$http.get('/admin/edit_restaurant/'+this.$route.params.id)
        .then(res => {
           console.log(res.data.data)
              // this.restaurants=res.data.cuisine_ids;
              this.restaurants=res.data.data;
              this.url.push(this.restaurants.banner);
              this.url1 = this.restaurants.image;
              document.getElementById('cover_img').src = this.restaurants.banner;
              this.restaurants.city = parseInt(res.data.data.city);
              this.restaurants.password =  this.restaurants.org_password;
              
  
  this.$http.post('/admin/area_list1',this.restaurants)
            .then(res => {
           this.areas = res.data
           })
          //  this.restaurants.cuisines.forEach(function(element) {
                  
          //       document.getElementById(element.name).checked = true;
               
          //       });
                 this.restaurants.delivery_type.forEach(function(element) {
                   console.log(element);
                document.getElementById(element).checked = true;
               
                });
                this.restaurants.cuisines.forEach(function(element) {
                 
                document.getElementById(element.name).checked = true;
               
                });
        
              this.restaurants.area = parseInt(res.data.data.area);
              this.restaurants.brands = res.data.data.brands;

              this.bank = this.restaurants.restaurant_bank_details;
              document.getElementById('res_id').value = this.restaurants.id;
               this.opening_time_[1] = this.restaurants.opening_time_1;
                this.closing_time_[1] =this.restaurants.closing_time_1;
                this.opening_second_time_[1] =this.restaurants.opening_second_time_1;
               this.closing_second_time_[1] =this.restaurants.closing_second_time_1;

               this.opening_time_[2] = this.restaurants.opening_time_2;
                this.closing_time_[2] =this.restaurants.closing_time_2;
                this.opening_second_time_[3] =this.restaurants.opening_second_time_2;
               this.closing_second_time_[3] =this.restaurants.closing_second_time_2;

               this.opening_time_[3] = this.restaurants.opening_time_3;
                this.closing_time_[3] =this.restaurants.closing_time_3;
                this.opening_second_time_[3] =this.restaurants.opening_second_time_3;
               this.closing_second_time_[3] =this.restaurants.closing_second_time_3;

               this.opening_time_[4] = this.restaurants.opening_time_4;
                this.closing_time_[4] =this.restaurants.closing_time_4;
                this.opening_second_time_[4] =this.restaurants.opening_second_time_4;
               this.closing_second_time_[4] =this.restaurants.closing_second_time_4;

             this.opening_time_[5] = this.restaurants.opening_time_5;
                this.closing_time_[5] =this.restaurants.closing_time_5;
                this.opening_second_time_[5] =this.restaurants.opening_second_time_5;
               this.closing_second_time_[5] =this.restaurants.closing_second_time_5;



            this.opening_time_[6] = this.restaurants.opening_time_6;
                this.closing_time_[6] =this.restaurants.closing_time_6;
                this.opening_second_time_[6] =this.restaurants.opening_second_time_6;
               this.closing_second_time_[6] =this.restaurants.closing_second_time_6;



                 this.opening_time_[7] = this.restaurants.opening_time_7;
                this.closing_time_[7] =this.restaurants.closing_time_7;
                this.opening_second_time_[7] =this.restaurants.opening_second_time_7;
               this.closing_second_time_[7] =this.restaurants.closing_second_time_7;
             
                
               this.restaurants.holidays.forEach(function(element) {
                  //  console.log(element);
                   document.getElementById(element).checked = true;
                });
              this.document_view = this.restaurants.document;
               console.log(this.document_view);
                 this.document_view.forEach(function(element) {
                var img = document.querySelector('[id="' + 'document_img'+element.id + '"]') ;
                    img.setAttribute("src",element.pivot.document);  
                    this.documents.image[element.id] = element.pivot.document;
                  document.getElementById('dates'+element.id).value = element.pivot.expiry_date;
              });
              
          })
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
     initMap() {
     
      const element = document.getElementById("gmap");
                const mapoptions = {
                    zoom : 13,
                    center : new google.maps.LatLng(12.9813658,80.2199712)
                };
                
                const map = new google.maps.Map(element,mapoptions);  
                var lati = document.getElementById('latitude').value;
                var long = document.getElementById('longitude').value;
                var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
                var geocoder = new google.maps.Geocoder();
                var input = document.getElementById('searchMadminnput');
                // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);
                var infowindow = new google.maps.InfoWindow();
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    draggable: true

                });
                autocomplete.addListener('place_changed', function() {
            //infowindow.close();
            marker.setVisible(true);
            var place = autocomplete.getPlace();

            /* If the place has a geometry, then present it on a map. */
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);

            /* Location details */
        });
        google.maps.event.addListener(marker, 'dragend',
            function(marker) {
                var latLng = marker.latLng;
                var currentLatitude = latLng.lat();
                var currentLongitude = latLng.lng();
                
                geocoder.geocode({
                    'latLng': latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            document.getElementById('searchMadminnput').value = results[0].formatted_address;
                            document.getElementById('latitude').value = currentLatitude;
                            document.getElementById('longitude').value = currentLongitude;
                            infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                            infowindow.open(map, marker);
                        }
                    }
                });
            });
    },
    onChange(event) {
         
           this.$http.post('/admin/area_list1',this.restaurants)
          .then(res => {
        
          this.areas = res.data
          
          })
             console.log(event.target.value)
        },

      importAll(r) {
      r.keys().forEach(key => (this.images.push({ pathLong: r(key), pathShort: key })));
      
    },

       onFileChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
      var d =reader.result;
       this.url = d;
        document.getElementById('cover_img').src = reader.result;
     document.getElementById('Banner1').value = this.url;
      };
     
       document.getElementById('banner_id').value = "1";
    
    },
    
     onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
     reader.onload = () => {
      // alert(reader.result);
      this.url1 = reader.result;
      document.getElementById('profile').src = reader.result;
      document.getElementById('Image1').value = this.url1;
      };
     document.getElementById('logo_id').value = "1";
    },
    select(img){
       
      var imgs = document.getElementById("image_clcik"+img).src;
      this.url = imgs;
      $("#Banner_name1").prop("files", $("image_clcik"+img).prop("files"));
      document.getElementById('banner_id').value = "2";
      document.getElementById("modal-xl").click();
      document.getElementById("modal-xl").close();

      
    },
  //    changeMethod(){
  //      this.restaurants.delivery_type =   this.delivery_type
  // },
  //  cuisinesMethod(){
  //      this.restaurants.cuisines =   this.cuisines_name
     
  // },
  //  dateMethod(){
 
  //      this.restaurants_time.holidays =  this.holiday;
  // },
   fileadd(e){
 
        this.image = e.target.files;
        // alert(this.image);
  },
    

     myFunction() {
      
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("myInput");
            
            filter = input.value.toUpperCase();
            ul = document.getElementById("account");
          
            li = ul.getElementsByTagName("img");

            for (i = 0; i < li.length; i++) {
              
              //  var alt = $('#image_clcik')[0].attr("alt");
                //  alert(alt);
                var alt = ul.getElementsByTagName('img')[0].alt;
                a = li[i].alt;
               
                txtValue = a || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        },
       mounted() {
    this.initTrHeight()
  },
    repeateAgain() {
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },
     
       
   
    restaurant() {
      
      return new Promise((resolve, reject) => {
        this.$refs.accountRules.validate().then(success => {
          if (success) {

            let checkboxes = document.querySelectorAll('input[name="cuisines_name"]:checked');
           
            let values = [];
            checkboxes.forEach((checkbox) => {
                values.push(checkbox.value);
            });
            this.restaurants.cuisines =   values;

            let checkboxes1 = document.querySelectorAll('input[name="delivery_name"]:checked');
            
            let values1 = [];
            checkboxes1.forEach((checkbox) => {
                values1.push(checkbox.value);
            });
            this.restaurants.delivery_type =   values1;
            let data = new FormData();
                var id = document.getElementById('res_id').value;
                data.append('id', id);
                  data.append('banner',document.getElementById('banner_image').files[0]);
                  data.append('image', document.getElementById('logo_image').files[0]);
                data.append('name', this.restaurants.restaurant_name);
                data.append('password', this.restaurants.password);
               data.append('city', this.restaurants.city);
                 data.append('area', this.restaurants.area);
                data.append('status', this.restaurants.status);
                data.append('estimated_delivery_time', this.restaurants.estimated_delivery_time);
                 data.append('address', this.restaurants.address);
                  data.append('packaging_charge', this.restaurants.packaging_charge);
                  data.append('delivery_type', this.restaurants.delivery_type);
                data.append('cuisines', this.restaurants.cuisines);
                data.append('phone', this.restaurants.phone);
                 data.append('email', this.restaurants.email);
                  data.append('GeofenceRadius', this.restaurants.GeofenceRadius);
                  data.append('shop_description', this.restaurants.shop_description);
                data.append('brands', this.restaurants.brands);
                data.append('admin_commision', this.restaurants.admin_commision);
                  data.append('video_title', this.restaurants.video_title);
                data.append('video_url', this.restaurants.video_url);
                data.append('contact', this.restaurants.contact);
                
                
                  console.log(data);
             this.$http
                .post("/admin/add_to_restaurants_tab1", data)
                .then((response) => {
                  // this.id = response.id;
                  document.getElementById('res_id').value = response.data.id;
                  console.log(response.id);
                }
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
    Restaurant_Timing() {
      return new Promise((resolve, reject) => {
        this.$refs.accountRules1.validate().then(success => {
          if (success) {
       let checkboxes2 = document.querySelectorAll('input[name="holiday"]:checked');
            console.log(checkboxes);
            let values2 = [];
            checkboxes2.forEach((checkbox) => {
                values2.push(checkbox.value);
            });
            this.restaurants_time.holidays = values2;
                 var id = document.getElementById('res_id').value;
                let data = new FormData();
                  data.append('id',  id);
                   data.append('opening_time_1',  this.opening_time_[1]);
                  data.append('closing_time_1', this.closing_time_[1]);
                  data.append('opening_second_time_1', this.opening_second_time_[1]);
                  data.append('closing_second_time_1', this.closing_second_time_[1]);

                  data.append('opening_time_2',  this.opening_time_[2]);
                  data.append('closing_time_2', this.closing_time_[2]);
                  data.append('opening_second_time_2', this.opening_second_time_[2]);
                  data.append('closing_second_time_2', this.closing_second_time_[2]);

                  data.append('opening_time_3',  this.opening_time_[3]);
                  data.append('closing_time_3', this.closing_time_[3]);
                  data.append('opening_second_time_3', this.opening_second_time_[3]);
                  data.append('closing_second_time_3', this.closing_second_time_[3]);

                  data.append('opening_time_4',  this.opening_time_[4]);
                  data.append('closing_time_4', this.closing_time_[4]);
                  data.append('opening_second_time_4', this.opening_second_time_[4]);
                  data.append('closing_second_time_4', this.closing_second_time_[4]);

                  data.append('opening_time_5',  this.opening_time_[5]);
                  data.append('closing_time_5', this.closing_time_[5]);
                  data.append('opening_second_time_5', this.opening_second_time_[5]);
                  data.append('closing_second_time_5', this.closing_second_time_[5]);

                  data.append('opening_time_6',  this.opening_time_[6]);
                  data.append('closing_time_6', this.closing_time_[6]);
                  data.append('opening_second_time_6', this.opening_second_time_[6]);
                  data.append('closing_second_time_6', this.closing_second_time_[6]);

                  data.append('opening_time_7',  this.opening_time_[7]);
                  data.append('closing_time_7', this.closing_time_[7]);
                  data.append('opening_second_time_7', this.opening_second_time_[7]);
                  data.append('closing_second_time_7', this.closing_second_time_[7]);



                  data.append('holidays', this.restaurants_time.holidays);

             this.$http
                .post("/admin/add_to_restaurants_tab2", data)
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
     Document() {
      
      return new Promise((resolve, reject) => {
        this.$refs.accountRules2.validate().then(success => {
          if (success) {
            var id = document.getElementById('res_id').value;
            let data = new FormData();
             data.append('id',  id);
            this.document_view.forEach(function(number) {
             if(number.document_for == 2)
             {
               data.append('document['+number.id +'][document]', document.getElementById('document'+number.id).files[0]);
              
                if(number.expiry_date_needed == 1)
                {
                  data.append('document['+number.id +'][date]', document.getElementById('date'+number.id).value);
                
                }
              
            }
             });
             this.$http
                .post("/admin/add_to_restaurants_tab3", data)
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
     bank_details() {
      
      return new Promise((resolve, reject) => {
        this.$refs.accountRules3.validate().then(success => {
          if (success) {
          this.bank.id=  document.getElementById('res_id').value;
            //  let data = new FormData();
            //     data.append('file', this.driver.profile_pic);
            //     data.append('email', this.driver.email);
             this.$http
                .post("/admin/add_to_restaurants_tab4", this.bank)
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

  },

 
   

    
 

  

  
}

</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';
.banner-img {
   width: 9.92rem;
}
.repeater-form {
  overflow: hidden;
  transition: .35s height;
}
.wizard-icon-container{
  background-color: rgb(255 196 70) !important;
}
.stepTitle{
color: rgb(255 212 119) !important;
}
.wizard-btn{
  background-color: rgb(255 210 113) !important;
    border-color: rgb(255 211 118) !important;
    color: white !important;
}
</style>
<style scoped>
#gmap {
  height: 400px;
  width: 100%;
}
</style>

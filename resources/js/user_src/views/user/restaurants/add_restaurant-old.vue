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
          <input
            hidden="hidden"
            ref="onFileChange"
            type="file"
            class="d-none"
            @change="onFileChange"
          >
          <input
            hidden="hidden"
            ref="onFileChange1"
            type="file"
            class="d-none"
            @change="onFileChange1"
          >
              <b-col md="12">
              <div
    id="user-profile"
  >
 
 <b-card
 id="cover_img"
    class="profile-header mb-2"
     :img-src="url? url : require('@@@/assets/images/banner/banner-1.jpg')"
    img-top
    alt="cover photo"
    body-class="p-0"
    rounded
     fluid
   
   >
    <img :src="url? url : require('@@@/assets/images/banner/banner-1.jpg')"  v-model="restaurants.banner" hidden/> 
   <img :src="url1? url1:require('@@@/assets/images/banner/banner-34.jpg')"  v-model="restaurants.image" hidden/> 
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
          <b-img
            :src="url1? url1:require('@@@/assets/images/banner/banner-34.jpg')"
            rounded
            fluid
            alt="profile photo"
            v-model="restaurants.image"
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
        <b-dropdown-item>
          <a  v-b-modal.modal-xl
        variant="outline-primary" >Select Photo </a>
        </b-dropdown-item>
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
                    v-model="restaurants.name"
                    type="email"
                    :state="errors.length > 0 ? false:null"
                    placeholder="john.doe@email.com"
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
                label="Select City *"
                
              >
              <b-form-select
              v-model="restaurants.city" @change="onChange($event)">
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
                
                  <b-form-select
              v-model="restaurants.area"  >
                 <option  value="" disabled>- Select area -</option>
                  <option
                v-for="areas1 in this.areas"
                 :value="areas1.id"
                :key="areas1.area"
            >{{ areas1.area }}
            </option>
                 </b-form-select>
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
                label="Description"
                
              >
                <b-form-textarea
                  v-model="restaurants.shop_description"
      id="textarea-default"
      placeholder="Textarea"
      rows="6"
    />
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
               <b-form-select
        v-model="restaurants.status"
        :options="options"
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
                  <b-form-select
              v-model="restaurants.brands">
                 <option  value="" disabled>- Select brand -</option>
                  <option
                v-for="brand1 in this.brand"
                 :value="brand1.id"
                :key="brand1.name"
            >{{ brand1.name }}
            </option>
                 </b-form-select>
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
                label="Delivery Type *"
               
              >

              <b-form-checkbox-group id="checkbox-group"   v-model="restaurants.delivery_type" name="selectedUsers[]">
           <b-form-checkbox    
            v-for="option in options_check"
          :key="option.value"
         
          :value="option.value"
          name="flavour-3a">{{ option.text }}</b-form-checkbox>
             </b-form-checkbox-group>
                
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
                    placeholder="0"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>


           <b-col md="6">
              <b-form-group
                label="Cuisines *"
              > 
              <b-form-checkbox-group id="checkbox-group"  v-model="checkedNames" name="selectedUsers">
           <b-form-checkbox   v-for="cuisines1 in this.cuisines"
          :key="cuisines1.id"
          :value="cuisines1.name"
          name="flavour-3a">{{ cuisines1.name }}</b-form-checkbox>
             </b-form-checkbox-group>
           
                
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
       v-model="restaurants_time.opening_time_+item"
    />
    <b-form-timepicker
      :id="'timepicker-placeholder2'+item"
      placeholder="Choose a time"
      local="en"
      v-model="restaurants_time.closing_time_+item"
    />
            </b-col>
              <b-col md="4">
             <b-form-timepicker
      :id="'timepicker-placeholder3'+item"
      placeholder="Choose a time"
      local="en"
       v-model="restaurants_time.opening_second_time_+item"
    />
    <b-form-timepicker
      :id="'timepicker-placeholder4'+item"
      placeholder="Choose a time"
      local="en"
      v-model="restaurants_time.closing_second_time_+item"
    />
            </b-col>
           
              <b-col md="2">
              <b-form-checkbox
          :id="'holiday'+item"
          v-model="restaurants_time.holidays"
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
                v-model="documents.document_name"
                />
              </b-form-group>
            </b-col>

             <b-col md="6" v-if="item.expiry_date_needed == 1">
              <b-form-group
                :label="'Expiry Date'"
              >
               
                 <b-form-datepicker
          :id="item.document_name"
            v-model="documents.document_date"
        
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
      title="Extra Large Modal"
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
import BCardCode from '@@@core/components/b-card-code'
//form validation//
import ToastificationContent from '@@@core/components/toastification/ToastificationContent.vue'
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
import { required, email } from '@@@validations'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@@@core/mixins/ui/transition'
import { useInputImageRenderer } from '@@@core/comp-functions/forms/form-utils'
import { avatarText } from '@@@core/utils/filter'
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
        document_view:[],
       checkedNames: [],
       selected_res: "",
       selected_vehicle: "",
       selected_city: "",
        selected_area: null,
      options: [
        { value: null, text: 'Status' ,disabled: true},
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
    this.initTrHeight()
     this.importAll(require.context('@@@/assets/images/banner/', true, /\.jpg$/)); 
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
   created() {
  
     window.addEventListener('resize', this.initTrHeight)
    this.$http.get('/api/res_list')
      .then(res => {
       
          this.rows = res.data
          // console.log(this.rows[0].id)
          })
           this.$http.get('/api/vehicle_list')
      .then(res => {
       
          this.vehicles = res.data
        //  console.log(this.vehicles)
          })
           this.$http.get('/api/city_list')
      .then(res => {
       
          this.cities = res.data
        //  console.log(this.vehicles)
          })
      //       this.$http.get('/api/area_list')
      // .then(res => {
       
      //     this.areas = res.data
      //   //  console.log(this.vehicles)
      //     })
           this.$http.get('/api/document_view')

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
           this.$http.get('/api/brand_list')
          .then(res => {
          this.brand = res.data
        //  console.log(this.vehicles)
          })
          this.$http.get('/api/cuisines_list')
         .then(res => {
          this.cuisines = res.data
        //  console.log(this.vehicles)
          })
       this.$http.get('/api/document_view')
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
    onChange(event) {
         
           this.$http.post('/api/area_list1',this.restaurants)
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
      alert(reader.result);
     this.url = reader.result;

      };
       document.getElementById('banner_id').value = "1";
    
    },
     onFileChange1(e) {
      const file = e.target.files[0];
     
      const reader = new FileReader();
      reader.readAsDataURL(file);
     reader.onload = () => {
     this.url1 = reader.result;

      };
      
     document.getElementById('logo_id').value = "1";
    },
    select(img){
       
    var imgs = document.getElementById("image_clcik"+img).src;
   this.url = imgs;
           document.getElementById('banner_id').value = "2";
            document.getElementById("modal-xl").click();
      
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
      // restaurant() {
      //    console.log(this.restaurants)
             
      //   return new Promise((resolve, reject) => {
      //   this.$refs.accountRules.then(success => {
      //      if (success) {
           
      //       resolve(true)
      //     } else {
             
             
      //       reject()
      //     }
       

      //   })
          
      // })

      // },
       
   
    restaurant() {
      
      return new Promise((resolve, reject) => {
        this.$refs.accountRules.validate().then(success => {
          if (success) {
           
           
               let data = new FormData();
                data.append('name', this.restaurants.name);
                data.append('password', this.restaurants.password);
               data.append('city', this.restaurants.city);
                 data.append('area', this.restaurants.area);
                data.append('status', this.restaurants.status);
                data.append('estimated_delivery_time', this.restaurants.estimated_delivery_time);
                data.append('banner', this.restaurants.banner);
                 data.append('image', this.restaurants.image);
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

             this.$http
                .post("/api/add_to_restaurants_tab1", data)
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
    Restaurant_Timing() {
      return new Promise((resolve, reject) => {
        this.$refs.accountRules1.validate().then(success => {
          if (success) {
             this.$http
                .post("/api/add_to_restaurants_tab2", this.restaurants_time)
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
          
            //  let data = new FormData();
            //     data.append('file', this.driver.profile_pic);
            //     data.append('email', this.driver.email);
             this.$http
                .post("/api/add_to_restaurants_tab3", this.documents)
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
          
            //  let data = new FormData();
            //     data.append('file', this.driver.profile_pic);
            //     data.append('email', this.driver.email);
             this.$http
                .post("/api/add_to_restaurants_tab4", this.bank)
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

.repeater-form {
  overflow: hidden;
  transition: .35s height;
}
</style>

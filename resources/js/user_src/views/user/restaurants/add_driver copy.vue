<template>
    <div >
      <div class="pb-2" style="margin-top:80px;">
          <b-img src="/more1.jpg" fluid alt="Scan" class="imght2"/>
      </div>
  
    <div class="pt-1 mb-2 container" >
      <h4> {{$t("New")}} {{$t("Driver")}} {{$t("Signup")}}</h4>
      <validation-observer ref="simpleRules">
  
  
        <b-form>
          <b-row>
  
            
            <b-col md="6">
  
              <b-form-group>
                <label>{{$t("Driver")}} {{$t("Name")}}</label>
                <validation-provider
                  #default="{ errors }"
                  rules="required"
                  name="Driver Name"
                >
                  <b-form-input
                    v-model="restaurant.name"
                    :state="errors.length > 0 ? false:null"
                    :placeholder="$t('Driver') + ' ' + $t('Name')"

                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
  
            <b-col md="6">
            <b-form-group>
              <label>{{$t("Email Id")}}</label>
              <validation-provider
                #default="{ errors }"
                name="Email address"
                rules="required|email"
              >
                <b-form-input
                  v-model="restaurant.email"
                  :state="errors.length > 0 ? false:null"
                  :placeholder="$t('Email Id') "

                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
            </b-col>


            <b-col md="6">
  
            <b-form-group>
                <label>{{$t("Mobile number")}}</label>
                <validation-provider
                #default="{ errors }"
                rules="required"
                name="Mobile number"
                >
                <b-form-input
                    v-model="restaurant.phone"
                    :state="errors.length > 0 ? false:null"
                    :placeholder="$t('Mobile number') "
                    type="number"
                />
                <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
            </b-form-group>
            </b-col>
  
            <b-col md="6">
            <b-form-group>
                <label>{{$t("Address")}}</label>
                <validation-provider
                #default="{ errors }"
                rules="required"
                name="Address"
                >
                <b-form-input
                    v-model="restaurant.address"
                    :state="errors.length > 0 ? false:null"
                    :placeholder="$t('Address') "
                    id="searchMapInput"
                   
                />
              
                <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
            </b-form-group>
            </b-col>

            <b-col md="6">
  
                <b-form-group>
                    <label>{{$t("Country")}}</label>
                    <validation-provider
                    #default="{ errors }"
                    rules="required"
                    name="Country"
                    >
                    <b-form-input
                        v-model="restaurant.country"
                        :state="errors.length > 0 ? false:null"
                        :placeholder="$t('Country') "

                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>
            </b-col>

            <b-col md="6">
  
            <b-form-group>
                <label>{{$t("City")}}</label>
                <validation-provider
                #default="{ errors }"
                rules="required"
                name="City"
                >
                <b-form-input
                    v-model="restaurant.city"
                    :state="errors.length > 0 ? false:null"
                    :placeholder="$t('City') "
                />
                <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
            </b-form-group>
            </b-col>


            <b-col md="6">
  
                <b-form-group>
                    <label>{{$t("State")}}</label>
                    <validation-provider
                    #default="{ errors }"
                    rules="required"
                    name="State"
                    >
                    <b-form-input
                        v-model="restaurant.state"
                        :state="errors.length > 0 ? false:null"
                        :placeholder="$t('State') "
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group>
            </b-col>

            <b-col md="6">
  
            <b-form-group>
                <label>{{$t("Zipcode")}} </label>
                <validation-provider
                #default="{ errors }"
                rules="required"
                name="Zipcode "
                >
                <b-form-input
                    v-model="restaurant.zippcode "
                    :state="errors.length > 0 ? false:null"
                    :placeholder="$t('Zipcode') "
                />
                <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
            </b-form-group>
            </b-col>



            <b-col md="6">
            <b-form-group>
              <label>{{$t("Password")}} </label>
              <validation-provider
                #default="{ errors }"
                rules="required|password"
                name="password"
                vid="password"
              >
                <b-form-input
                  v-model="restaurant.password"
                  type="password"
                  :state="errors.length > 0 ? false:null"
                  :placeholder="$t('Password') "
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
            </b-col>
  
           
            <b-col md="6">
            <b-form-group>
                <label>{{$t("Repeat")}} {{$t("Password")}} </label>
                <validation-provider
                #default="{ errors }"
                rules="required|confirmed:password"
                name="Confirm Password"
                >
                <b-form-input
                    v-model="restaurant.passwordcon"
                    type="password"
                    :state="errors.length > 0 ? false:null"
                    :placeholder="$t('Repeat') + ' ' + $t('Password')"
                />
                <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
            </b-form-group>
            </b-col>
            <b-col cols="12">
                <div v-for="item in document_view"
                :key="item.id" 
                >
                    <b-row  v-if="item.document_for == 1" >
                    
                        <b-col md="6">
                        <b-form-group
                            :label="item.document_name"
                        >
                        
                            <b-form-file
                                :id="'image'+item.id"
                              
                            />

                            <b-form-input
                                :id="'doc_id'+item.id"
                                hidden="hidden"
                                :value="item.id" 
                                :key="item.id" 
                            />
                        </b-form-group>
                        <b-form-group>
                          
                        </b-form-group>
                        </b-col>

                        <b-col md="6" v-if="item.expiry_date_needed == 1">
                        <b-form-group
                            :label="item.document_name"
                        >
                        
                            <b-form-datepicker
                                :id="'license_expirys'+item.id"
                                locale="en"
                                v-model="license_expiry[item.id]"
                                :min="new Date()"
                            />
                            <b-form-input
                                :id="'license_expiry'+item.id"
                                hidden="hidden"
                                :value=license_expiry[item.id] 
                                :key="item.id" 
                            />
                            <b-form-input
                                :id="'exp_id'+item.id"
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
            <b-col cols="12"  class="pt-1">
            <b-button
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
  
    </div>
  
  </div>
   
  </template>
  
  <script>
  import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
  import { BImg ,BCard,BCardText,BLink,  BRow, BCol,BFormInput, BFormGroup, BForm,BButton,BFormSelect,BFormCheckbox ,BFormDatepicker,BFormFile, } from 'bootstrap-vue'
  import {
    required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
  } from '@validations'
  import { ValidationProvider, ValidationObserver } from 'vee-validate'
  import vSelect from 'vue-select'
  import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { VueRecaptcha } from 'vue-recaptcha'
  
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
  VueRecaptcha ,
      ValidationObserver,BFormInput, BFormGroup, BForm, BButton,BFormSelect, vSelect,BFormCheckbox,BFormDatepicker, BFormFile,
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
      }
    },
    created() {
      
        this.$http.get('/document_view')

        .then(res => {
            
        this.document_view = res.data

        })
  
    },
    methods:{
   
      verifyMethod(){
      this.invalidc = false
    },
      validationForm() {
        this.$refs.simpleRules.validate().then(success => {
          if (success) {
            console.log(this.restaurant.zippcode)
            let data = new FormData();
                data.append('email', this.restaurant.email);
                data.append('password', this.restaurant.password);
                data.append('city', this.restaurant.city);
                data.append('name', this.restaurant.name);
                data.append('phone', this.restaurant.phone);
                data.append('area', this.restaurant.area);
                data.append('state', this.restaurant.state_province);
                data.append('address', this.restaurant.address_city);
                data.append('country', this.restaurant.country);
                data.append('zipcode', this.restaurant.zippcode);
            
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
                }
            
              })
          }
        })
      },
  
    
    },
   
    
  }
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

  
  .hover{
    cursor: pointer;
  }
  </style>
  
<template>
    <div class="container" style="margin: 90px 0px;">
    <validation-observer ref="simpleRules">

    <b-form
      @submit.prevent="signup"
      >
      
        <b-form-group>
          <validation-provider
              #default="{ errors }"
              name="Name"
              rules="required"
              
            >
          <label for="email" > {{$t("Name")}}</label>
          <b-form-input
            id="email"
            type="text"
            placeholder="Name"
            class="form-control-lg"
            v-model="signup_name"
            :state="errors.length > 0 ? false:null"
            autocomplete="off"

          />
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
        <b-form-group>
          <validation-provider
              #default="{ errors }"
              name="Email"
              rules="required|email"
            >
          <label for="email" > {{$t("Email ID")}} </label>
          <b-form-input
            id="email"
            type="email"
            placeholder="Email Address"
            class="form-control-lg"
            v-model="signup_email"
            :state="errors.length > 0 ? false:null"
            autocomplete="off"

          />
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
        <b-form-group>
          <validation-provider
              #default="{ errors }"
              rules="required"
              name="Number"
            >
          <label for="email" >{{$t("Phone Number")}} </label>
          <VuePhoneNumberInput 
            id="phone1"
            no-example 
            @update="updatePhoneNumber"
            v-model="signup_phone"
            :state="errors[0] ? false : (valid ? true : null)"
            :fetch-country=true
          
          >
          </VuePhoneNumberInput>
          <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
        </b-form-group>
        <b-form-group>
          <validation-provider
              #default="{ errors }"
              name="Password"
              vid="Password"
              rules="required|password"
            >
              <div class="d-flex justify-content-between">
                <label for="password">{{$t("Password")}}</label>
              
              </div>
              
                <b-input-group
                  class="input-group-merge"
                >
                  <b-form-input
                    id="password"
                    v-model="signup_password"
                    :type="passwordFieldType"
                    class="form-control-merge"
                    :state="errors.length > 0 ? false:null"
                    name="login-password"
                    placeholder="Password"
                    autocomplete="off"
                  />

                  <b-input-group-append is-text>
                    <feather-icon
                      class="cursor-pointer"
                      :icon="passwordToggleIcon"
                      @click="togglePasswordVisibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
            </b-form-group>


          <b-form-group
            label="Confirm Password"
            label-for="ac-password"
          >
            <validation-provider
              #default="{ errors }"
              name="Confirm Password"
              rules="required|confirmed:Password"
            >

              <b-form-input
                id="ac-password"
                v-model="passValue"
                :state="errors.length > 0 ? false:null"
                type="password"
                placeholder="Confirm Password"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>

            
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            class="mr-1 btn-block btn-lg .col-form-label-lg"
            @click.prevent="signup"
          >
          {{$t("Sign Up")}}
          </b-button>
      
        
      </b-form>
  </validation-observer>
   

    </div>
</template>
      
<script>
import {
    BInputGroup,BDropdown,
  BCardText,
  BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,BButton, BModal, VBModal, BForm, BFormInput, BImg,BFormGroup,BCol,BRow,BInputGroupAppend,
      } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@@@/store'
import { avatarText } from '@@@core/utils/filter'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
      
export default {
    components: {
        BNavItemDropdown,
        BDropdownItem,
        BDropdownDivider,
        BAvatar,
        BButton,
        BModal, 
        VBModal, 
        BForm, 
        BFormInput,
        BFormGroup,
        BImg,
        BCol,
        BRow,
        BInputGroupAppend,
        BInputGroup,
        BDropdown,
        BCardText,
        ValidationProvider,
        ValidationObserver,VuePhoneNumberInput
        },
       
    directives: {
        'b-modal': VBModal,
        Ripple,
    },
    mixins: [togglePasswordVisibility],    
    data() {
        return {
        userData: JSON.parse(localStorage.getItem('webuserData')),
        avatarText,
        userEmail: '',
        password: '',
        username : '',
        loggedin : false,
        forgot_otp1:'',
        forgot_email:'',
        profile_image : '',
        formattedNumber:'',
        passValue:''
        }
        },
    computed: {
        passwordToggleIcon() {
        return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
        },
    },
    created() {
        this.checklogin()
    },
    methods: {
      updatePhoneNumber(data) {
             this.formattedNumber = data.formattedNumber
            },
    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          // eslint-disable-next-line
          alert('form submitted!')
        }
      })
    },
    signupmodal(){
      this.$refs['signupmodal'].show()
    },
    forgotpassoword(){
      this.$refs['forgotpassword'].show()
    },
    checklogin(){
      if(localStorage.getItem("webuserData")){
        var userData = JSON.parse(localStorage.getItem('webuserData'))
        this.loggedin = true
        this.username = userData.name
        this.profile_image = userData.profile_image
        this.$root.$emit('cool_event_name', userData.profile_image)
   
      }
    },
    logout() {
      this.loggedin = false

                // Remove userData from localStorage
                localStorage.removeItem('webuserData')
                this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: "Logged Out",
                      icon: 'CoffeeIcon',
                      variant: 'success',
                    },
                  })
    },
   
    
    signup(){
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          let city = new FormData(); 
      city.append('name', this.signup_name);
      city.append('email', this.signup_email);
      city.append('phone', this.formattedNumber);
      city.append('password', this.signup_password);

      this.$http
                .post("/register", city)
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
                        this.$router.push({ name: 'login'})
                             
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
                      } )                  
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        }
      })
     
    },
    AuthProvider(provider) {
                var bb = this
              this.$auth.authenticate(provider).then(response =>{
           
                this.SocialLogin(provider,response)

                }).catch(err => {
                    console.log({err:err})
                })

            },

    SocialLogin(provider,response){

              this.$http.post('/sociallogin/'+provider,response).then(response => {

                  if (response.data.status == true) {
                              this.loggedin = true

                               const userData  = response.data.data
                               localStorage.setItem('webuserData', JSON.stringify(userData))
                              
                               this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: response.data.message,
                            icon: 'CoffeeIcon',
                            variant: 'success',
                          },
                        })
                        this.checklogin()
                             
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

              }).catch(err => {

                  console.log({err:err})
              })
              .finally(() => (this.$router.go(-1)));


              },
  },
        
      }
</script>
<style>
    .pt-10{
        padding-top: 10rem !important;
      }
</style>
      
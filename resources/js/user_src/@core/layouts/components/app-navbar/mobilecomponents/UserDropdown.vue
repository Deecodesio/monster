<template>
  
  <div class="text-r pt-1">

  <b-button 
        v-if="!loggedin"
        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
       
        variant="outline-primary"
        size="lg"
        @click="$router.push({ name: 'login'})"   
      >
      <feather-icon
        icon="UserIcon"
        class="mr-50"
      />
        Login
  </b-button>
  <b-dropdown
      v-if="loggedin"

      v-ripple.400="'rgba(113, 102, 240, 0.15)'"
     
      variant="flat-success"

    >
    <template #button-content>
       
       <b-avatar
         size="35"
         :src="profile_image"
         variant="light-primary"
         badge
         class="badge-minimal"
        
       >
         <feather-icon
           v-if="!profile_image"
           icon="UserIcon"
           size="22"
         />
       </b-avatar>
     </template>
      <b-dropdown-item
      :to="{ name: 'profile' }"
      >
        Profile
      </b-dropdown-item>
      <b-dropdown-item
      :to="{ name: 'profile' }"
      >
        My Orders
      </b-dropdown-item>
      
      
      <b-dropdown-item
      @click="logout()"
      >
      Logout
      </b-dropdown-item>
    </b-dropdown>
    <!-- <b-nav-item-dropdown
      right
      toggle-class="d-flex align-items-center dropdown-user-link"
      class="dropdown-user"
      v-if="loggedin"
    >
      <template #button-content>
       
        <b-avatar
          size="40"
          :src="userData.profile_image"
          variant="light-primary"
          badge
          class="badge-minimal"
         
        >
          <feather-icon
            v-if="!username"
            icon="UserIcon"
            size="22"
          />
        </b-avatar>
      </template>

   
      <b-dropdown-item
        :to="{ name: 'profile' }"
        >
          Profile
      </b-dropdown-item>
      <b-dropdown-item>
          My Orders
      </b-dropdown-item>
        
        
      <b-dropdown-item
        @click="logout()"
        >
        Logout
      </b-dropdown-item>
    </b-nav-item-dropdown> -->
    <b-modal

      id="modal-login"
      title="Sign Up"
      ok-only
      ok-title="Login"
      cancel-title="Close"
      hide-footer
      hide-header
      size="lg"
      centered
      ref="my-modal"
    >
    <b-row>
      <b-col
            xxl="6"
            lg="6"
            md="6"
            sm="12"
            
            class=''
          >
  <b-img src="/login.svg" fluid alt="Responsive image" />
    </b-col>
    <b-col
            xxl="6"
            lg="6"
            md="6"
            sm="12"
           
           
          >
    <b-form
    @submit.prevent="login"
    >
        <b-form-group>
          <label for="email" class="mt-5">Email ID / Phone Number</label>
          <b-form-input
            id="email"
            type="email"
            placeholder="Email Address"
            class="form-control-lg"
            v-model="userEmail"
          />
        </b-form-group>
        <b-form-group>
              <div class="d-flex justify-content-between">
                <label for="password">Password</label>
                <small @click="forgotpassoword()">Forgot Password?</small>
              </div>
              
                <b-input-group
                  class="input-group-merge"
                >
                  <b-form-input
                    id="password"
                    v-model="password"
                    :type="passwordFieldType"
                    class="form-control-merge"
                 
                    name="login-password"
                    placeholder="Password"
                  />

                  <b-input-group-append is-text>
                    <feather-icon
                      class="cursor-pointer"
                      :icon="passwordToggleIcon"
                      @click="togglePasswordVisibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                
            </b-form-group>
           <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            class="mr-1 btn-block btn-lg .col-form-label-lg"
          >
           Sign In
          </b-button>
          <div class="text-center pt-1 pb-1"><strong>or</strong></div>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="outline-primary"
            class="mr-1 btn-block btn-lg"
            @click="AuthProvider('google')"
          >
           Sign In with Google
          </b-button>
        
      </b-form>
      <b-card-text class="text-center mt-2">
            <span>Don’t have an account? </span>
            <b-link @click="signupmodal()" class="hoverme">
              <span>&nbsp;Create an account</span>
            </b-link>
          </b-card-text>
    </b-col>
  </b-row>

    </b-modal>
    <b-modal
      id="modal-fpass"
      cancel-variant="outline-secondary"
      hide-footer
      centered
      title="Forgot Password"
      ref="forgotpassword"
    >

    
      <b-form
      @submit.prevent="forgot"
      >
        <b-form-group>
          <label for="email">Email:</label>
          <b-form-input
            id="email"
            type="email"
            placeholder="Email Address"
            v-model="forgot_email"
          />
        </b-form-group>
      
        <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg"
            >
            GET OTP
        </b-button>
      </b-form>

    </b-modal>

    <b-modal
      id="modal-vpass"
      cancel-variant="outline-secondary"
      hide-footer
      centered
      title="Forgot Password"
      ref="vpass"
    >

    
      <b-form
      @submit.prevent="forgot_otp"
      >
        <b-form-group>
          <label for="otp">OTP</label>
          <b-form-input
            id="otp"
            type="text"
            placeholder="OTP"
            v-model="forgot_otp1"
          />
        </b-form-group>
      
        <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg"
            >
            Submit
        </b-button>
      </b-form>

    </b-modal>


    <b-modal
      id="modal-npass"
      cancel-variant="outline-secondary"
      hide-footer
      centered
      title="Reset Password"
      ref="npass"
    >

    <validation-observer ref="simpleRules">
      <b-form
      @submit.prevent="reset_pass_password1"
      >
      <b-form-group>
          <validation-provider
              #default="{ errors }"
              name="Password"
              vid="Password"
              rules="required|password"
            >
              <div class="d-flex justify-content-between">
                <label for="password">Password</label>
              
              </div>
              
                <b-input-group
                  class="input-group-merge"
                >
                  <b-form-input
                    id="password"
                    v-model="reset_pass_password"
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

            <validation-provider
              #default="{ errors }"
              name="Password"
              vid="Password"
              rules="required|password"
            >
              <div class="d-flex justify-content-between">
                <label for="password">Confirm Password</label>
              
              </div>
              
                <b-input-group
                  class="input-group-merge"
                >
                  <b-form-input
                    id="confirm_password"
                    v-model="reset_pass_password_cnfrm"
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
      
        <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg"
            >
            Submit
        </b-button>
      </b-form>
    </validation-observer>
    </b-modal>



    <b-modal

      id="modal-signupmodal"
      title="Sign Up"
      ok-only
      ok-title="Sign Up"
      cancel-title="Close"
      hide-footer
      size="lg"
      centered
      ref="signupmodal"
      >
      <b-row>
      <b-col
            xxl="6"
            lg="6"
            md="6"
            sm="12"
            
            class=''
          >
      <b-img src="/login.svg" fluid alt="Responsive image" />
      </b-col>
      <b-col
            xxl="6"
            lg="6"
            md="6"
            sm="12"
          
          
          >
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
          <label for="email" >Name</label>
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
          <label for="email" >Email ID </label>
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
          <label for="email" >Phone Number </label>
          <!-- <b-form-input
            id="email"
            type="number"
            placeholder="Phone Number"
            class="form-control-lg"
            v-model="signup_phone"
            :state="errors.length > 0 ? false:null"
            autocomplete="off"

          /> -->
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
              rules="required|min:8|password"
            >
              <div class="d-flex justify-content-between">
                <label for="password">Password</label>
              
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
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            class="mr-1 btn-block btn-lg .col-form-label-lg"
            @click.prevent="signup"
          >
          Sign Up
          </b-button>
      
        
      </b-form>
    </validation-observer>
      </b-col>
      </b-row>

      </b-modal>
      <b-modal
      id="modal-phone"
      cancel-variant="outline-secondary"
      no-close-on-backdrop
      hide-footer
      centered
      title="Add Phone Number"
      ref="phone"
    >

    
      <b-form
      @submit.prevent="add_phone_func"
      >
        <b-form-group>
          <label for="phone2"> {{$t("Phone Number")}}:</label>
          <!-- <b-form-input
            id="phone"
            type="number"
            placeholder="Phone Number"
            class="form-control-lg"
            v-model="add_phone"
            
            autocomplete="off"

          /> -->
          <VuePhoneNumberInput 
            id="phone2"
            no-example 
            @update="updatePhoneNumber2"
            v-model="signup_phone2"
          
            :fetch-country=true
          
          >
          </VuePhoneNumberInput>
        </b-form-group>
        <input type="hidden" :value="user_id_p">
        <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              type="submit"
              variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg"
            >
            {{$t("Add")}}
            
        </b-button>
      </b-form>

    </b-modal>
  </div>
</template>

<script>
import {
  BInputGroup,BDropdown,
  BCardText,
  BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,BButton, BModal, VBModal, BForm, BFormInput, BImg,BFormGroup,BCol,BRow,BInputGroupAppend, 
} from 'bootstrap-vue'
import { initialAbility } from '@@@/libs/acl/config'
import useJwt from '@@@/auth/jwt/useJwt'
import { avatarText } from '@@@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import {
  required, email, confirmed, password,min
} from '@validations'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
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
      profile_image : '',
      forgot_otp1:'',
      forgot_email:'',
      user_id_p : '',
      add_phone :'',
      signup_phone:'',
      signup_phone2:'',

      country: null,
      formattedNumber:'',
      formattedNumber2:'',

      min
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  created() {
    this.checklogin()
    this.$root.$on('cool_event_name', (interesting_data) => {
     
      this.checklogin()
    })

  
   
  },
  
  methods: {
    updatePhoneNumber2(data) {
             console.log(data)
             this.formattedNumber2 = data.formattedNumber
            },
    updatePhoneNumber(data) {
             console.log(data)
             this.formattedNumber = data.formattedNumber
            },
    add_phone_func(){
      let forg = new FormData(); 
      forg.append('phone',this.formattedNumber2);
      forg.append('user',this.user_id_p);

      this.$http
                .post("/add_phone_number", forg)
                .then(
                    (response => {
                       if (response.data.status == true) {
                             
                        const userData  = response.data.data
                        this.loggedin = true

                            
                          localStorage.setItem('webuserData', JSON.stringify(userData))
                          store.commit('defaults/UPDATE_USER', JSON.parse(localStorage.getItem('webuserData')))
                          //  location.reload()
                          this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: response.data.message,
                            icon: 'CoffeeIcon',
                            variant: 'success',
                          },
                          })
                              this.$refs['phone'].hide()
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
                      } )                  
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
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
      this.$refs['my-modal'].hide()
      this.$refs['forgotpassword'].show()
    },
    checklogin(){
      if(localStorage.getItem("webuserData")){
        var userData = JSON.parse(localStorage.getItem('webuserData'))
        this.loggedin = true
        this.username = userData.name
        this.profile_image = userData.profile_image
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
                  this.$router.push({ name: "home" })

    },
    forgot(){
      let forg = new FormData(); 
      forg.append('email',this.forgot_email);
      this.$http
                .post("/forgot_password", forg)
                .then(
                    (response => {
                      if (response.data.status == true) {
                        localStorage.setItem('forget_id',response.data.id)
                              
                               this.$toast({
                                  component: ToastificationContent,
                                  position: 'bottom-right',
                                  props: {
                                    title: response.data.message,
                                    icon: 'CoffeeIcon',
                                    variant: 'success',
                                  },
                                })
                        this.$refs['forgotpassword'].hide()
                        this.$refs['vpass'].show()

                             
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
    },
    forgot_otp(){
      let forg = new FormData(); 
      forg.append('code',this.forgot_otp1);
      forg.append('id', localStorage.getItem('forget_id'));

      this.$http
                .post("/reset_password", forg)
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
                                this.$refs['vpass'].hide()
                                this.$refs['npass'].show()
                             
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
    },
    reset_pass_password1(){
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          let city = new FormData(); 
      city.append('password', this.reset_pass_password);
      city.append('passwordConfirm', this.reset_pass_password_cnfrm);
      city.append('id', localStorage.getItem('forget_id'));

      this.$http
                .post("/reset_password_submit", city)
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
                        this.$refs['npass'].hide()
                        this.$refs['my-modal'].show()
                             
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
    login() {
      let city = new FormData(); 
      city.append('email', this.userEmail);
      city.append('password', this.password);
      
      this.$http
                .post("/doLogin", city)
                .then(
                    (response => {
                        
                      if (response.data.status == true) {
                          const userData  = response.data.data

                          if(response.data.phone == true){
                            this.user_id_p = response.data.data.id
                            this.$refs['login_modal'].hide()
                            this.$refs['phone'].show()
                          }else{
                            this.loggedin = true

                            
                            localStorage.setItem('webuserData', JSON.stringify(userData))
                            store.commit('defaults/UPDATE_USER', JSON.parse(localStorage.getItem('webuserData')))
                            //  location.reload()
                            this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                              title: response.data.message,
                              icon: 'CoffeeIcon',
                              variant: 'success',
                            },
                            })
                            this.$refs['login_modal'].hide()
                            this.checklogin()
                          }
                              
                             
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
                        
                       console.log(response)
                    
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
                        this.$refs['signupmodal'].hide()
                      
                             
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

                  console.log(response.data)
                  if (response.data.status == true) {
                              this.loggedin = true
                              if(response.data.phone == true){
                            this.user_id_p = response.data.data.id
                            this.$refs['login_modal'].hide()
                            this.$refs['phone'].show()
                          }else{
                               const userData  = response.data.data
                              
                               localStorage.setItem('webuserData', JSON.stringify(userData))
                               store.commit('defaults/UPDATE_USER', JSON.parse(localStorage.getItem('webuserData')))
                              //  location.reload()
                               this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: response.data.message,
                            icon: 'CoffeeIcon',
                            variant: 'success',
                          },
                        })
                        this.$refs['login_modal'].hide()
                        this.checklogin()
                      }
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


              },
  },
}
</script>
<style lang="scss" scoped>
.btn .feather {
    vertical-align: top;
}

.hoverme{
  cursor:pointer;
}
</style>
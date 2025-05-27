<template>
  
    <div >
  
    
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
        :visible="value"
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
            <label for="email" class="mt-5">{{$t("Email Id")}}/{{$t("Phone Number")}}</label>
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
                  <label for="password">{{$t("Password")}}</label>
                  <small @click="forgotpassoword()">{{$t("Forgot Password")}}?</small>
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
            {{$t("Sign In")}}
             
            </b-button>
            <div class="text-center pt-1 pb-1"><strong>or</strong></div>
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
             
              variant="outline-primary"
              class="mr-1 btn-block btn-lg"
              @click="AuthProvider('google')"
            >
            {{$t("Sign In with Google")}}
             
            </b-button>
            <!-- <button @click="AuthProvider('google')">auth Google</button> -->
  
          
        </b-form>
        <b-card-text class="text-center mt-2">
              <span> {{$t("Don’t have an account")}} </span>
              <b-link @click="signupmodal()" class="hoverme">
                <span>&nbsp; {{$t("Create an account")}}</span>
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
            <label for="email"> {{$t("Email Id")}}:</label>
            <b-form-input
              id="email"
              type="email"
              placeholder="Email Id"
              v-model="forgot_email"
            />
          </b-form-group>
        
          <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                type="submit"
                variant="primary"
                class="mr-1 btn-block btn-lg .col-form-label-lg"
              >
              {{$t("GET OTP")}}
              
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
            <label for="otp"> {{$t("OTP")}}</label>
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
              {{$t("Submit")}}
              
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
                  <label for="password"> {{$t("Password")}}</label>
                
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
                  <label for="password"> {{$t("Confirm")}} {{$t("Password")}}</label>
                
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
              {{$t("Submit")}}
              
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
                rules="required|integer"
                name="Number"
              >
            <label for="email" >{{$t("Phone Number")}} </label>
            <b-form-input
              id="email"
              type="number"
              placeholder="Phone Number"
              class="form-control-lg"
              v-model="signup_phone"
              :state="errors.length > 0 ? false:null"
              autocomplete="off"
  
            />
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
        </b-col>
        </b-row>
  
        </b-modal>
    </div>
  </template>
  
  <script>
  import {
    BInputGroup,BDropdown,
    BCardText,
    BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,BButton, BModal, VBModal, BForm, BFormInput, BImg,BFormGroup,BCol,BRow,BInputGroupAppend, BLink
  } from 'bootstrap-vue'
  import { initialAbility } from '@@@/libs/acl/config'
  import useJwt from '@@@/auth/jwt/useJwt'
  import { avatarText } from '@@@core/utils/filter'
  import Ripple from 'vue-ripple-directive'
  import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
  import { togglePasswordVisibility } from '@core/mixins/ui/forms'
  import {
    required, email, confirmed, password,
  } from '@validations'
  import { ValidationProvider, ValidationObserver } from 'vee-validate'
  import store from '@@@/store'
  
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
      ValidationObserver,
      BLink,
  
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
      }
    },
    props: {
                value: {
                    required: true
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
        location.reload()
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
                                this.loggedin = true
  
                                 const userData  = response.data.data
                                 localStorage.setItem('webuserData', JSON.stringify(userData))
                                 
                                 store.commit('defaults/UPDATE_USER', JSON.parse(localStorage.getItem('webuserData')))
                                 location.reload()
                        //          this.$toast({
                        //     component: ToastificationContent,
                        //     position: 'bottom-right',
                        //     props: {
                        //       title: response.data.message,
                        //       icon: 'CoffeeIcon',
                        //       variant: 'success',
                        //     },
                        //   })
                          this.$refs['my-modal'].hide()
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
      signup(){
        this.$refs.simpleRules.validate().then(success => {
          if (success) {
            let city = new FormData(); 
        city.append('name', this.signup_name);
        city.append('email', this.signup_email);
        city.append('phone', this.signup_phone);
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
  
                    if (response.data.status == true) {
                                this.loggedin = true
  
                                 const userData  = response.data.data
                                
                                 localStorage.setItem('webuserData', JSON.stringify(userData))
                               
                                 store.commit('defaults/UPDATE_USER', JSON.parse(localStorage.getItem('webuserData')))
                                 location.reload()
                        //          this.$toast({
                        //     component: ToastificationContent,
                        //     position: 'bottom-right',
                        //     props: {
                        //       title: response.data.message,
                        //       icon: 'CoffeeIcon',
                        //       variant: 'success',
                        //     },
                        //   })
                          this.$refs['my-modal'].hide()
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
  
  
                },
    },
  }
  </script>
  <style lang="scss">
  .btn .feather {
      vertical-align: top;
  }
  .hoverme{
    cursor:pointer;
  }
  </style>
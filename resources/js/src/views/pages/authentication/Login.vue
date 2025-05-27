<template>
  <div class="auth-wrapper auth-v1 px-2">
    <div class="auth-inner py-2">

      <!-- Login v1 -->
      <b-card class="mb-0">
        <b-link class="brand-logo">
          <a href="javascript:void(0);" class="brand-logo">
            <img :src="logo.logo_img" width="150"> 
                                </a>

          <!-- <h2 class="brand-text text-primary ml-1">
            Deliware
          </h2> -->
        </b-link>

        <b-card-title class="mb-1">
          Welcome to the {{logo.name}} Master Ninja portal !
      
        </b-card-title>
        <b-card-text class="mb-2">
          Please sign-in to your account and start the adventure
        </b-card-text>
   <b-alert
            variant="primary"
            show
          >
         
            <div class="alert-body font-small-2" v-if="is_demo">
              <b-row>
              <b-col cols="8">
              <p>
                <h6>Demo Account</h6>
                <small class="mr-50"><span class="font-weight-bold">Email:</span>admin@delicart.app</small><br>
                <small class="mr-50"><span class="font-weight-bold">Password:</span>123456</small>
              </p>
            </b-col>
            <b-col  cols="4" style="text-align: center;
    padding-top: 18px;">
              <feather-icon icon="CopyIcon"  size="25" style="cursor:pointer" @click="copy()"/>
            </b-col>
          </b-row>
            </div>
           
          </b-alert>
        <!-- form -->
        <validation-observer
          ref="loginForm"
         
        >
          <b-form
            class="auth-login-form mt-2"
            @submit.prevent="login"
          >

            <!-- email -->
            <b-form-group
              label-for="email"
              label="Email"
            >
              <validation-provider
                #default="{ errors }"
                name="Email"
                rules="required|email"
              >
                <b-form-input
                  id="email"
                  v-model="userEmail"
                  name="login-email"
                  :state="errors.length > 0 ? false:null"
                  placeholder="john@example.com"
                  autofocus
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- password -->
            <b-form-group>
              <div class="d-flex justify-content-between">
                <label for="password">{{$t('Password')}}</label>
                <!-- <b-link :to="{name:'auth-forgot-password-v1'}">
                  <small>Forgot Password?</small>
                </b-link> -->
              </div>
              <validation-provider
                #default="{ errors }"
                name="Password"
                rules="required"
              >
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid':null"
                >
                  <b-form-input
                    id="password"
                    v-model="password"
                    :type="passwordFieldType"
                    class="form-control-merge"
                    :state="errors.length > 0 ? false:null"
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
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- checkbox -->
            <b-form-group>
              <b-form-checkbox
                id="remember-me"
                v-model="status"
                name="checkbox-1"
              >
                Remember Me
              </b-form-checkbox>
            </b-form-group>
            <!-- <vue-recaptcha 
            :sitekey="sitekey"
            @verify="verifyMethod"
            ></vue-recaptcha> -->

            <!-- submit button -->
            <b-button
              variant="primary"
              type="submit"
              block
             
              id="sub_btn"
             
            >
              Sign in
            </b-button>
          </b-form>
        </validation-observer>

      
      </b-card>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  BButton,
  BForm,
  BFormInput,
  BFormGroup,
  BCard,
  BLink,
  BCardTitle,
  BCardText,
  BInputGroup,
  BInputGroupAppend,
  BFormCheckbox,
  BAlert,
  BCol,
  BRow

} from 'bootstrap-vue'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { VueRecaptcha } from 'vue-recaptcha'

export default {
  components: {
    // BSV
    BButton,
    BForm,
    BFormInput,
    BFormGroup,
    BCard,
    BCardTitle,
    BLink, 
    VuexyLogo,
    BCardText,
    BInputGroup,
    BInputGroupAppend,
    BFormCheckbox,
    ValidationProvider,
    ValidationObserver,
  BAlert,
  BCol,
  BRow,
  VueRecaptcha 


  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      userEmail: '',
      password: '',
      status: '',
      // validation rules
      required,
      email,
      logo:{},
      is_demo : false,
      invalidc:true,
      sitekey:'6LfvY5kkAAAAAExIq6rolvkqFCYDCbOwESs92dbB',
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  created(){
    if(process.env.MIX_IS_DEMO){

        this.is_demo = true
    }

    this.$http.get("/admin/get_logo")
                .then(
                    (response => {
                   
                          this.logo = response.data[0]
                          console.log(this.logo)
                      } )                  
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
    // this.$http
    //             .get("/admin/get_captchakey")
    //             .then(
    //                 (response => {
                   
    //                       this.sitekey = response.data.key
    //                       console.log(this.sitekey)
    //                   } )                  
    //             )
    //             .catch((error) => console.log(error))
    //             .finally(() => (this.loading = false));
    
  },
  methods: {
    copy(){
        // document.getElementById("email").value="admin@deliware.app"
        // document.getElementById("password").value = "123456"
        this.password = "123456"
        this.userEmail = "admin@delicart.app"
        this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Email and Password Copied",
                            icon: 'CopyIcon',
                            variant: 'success',
                          },
                        })
    },
    verifyMethod(){
      this.invalidc = false
    },

    login() {
      this.$refs.loginForm.validate().then(success => {
        if (success) {
          let city = new FormData(); 
            city.append('email', this.userEmail);
            city.append('password', this.password);
            this.$http
                .post("/admin/login", city)
                .then(
                    (response => {
                        // console.log(response);
                        if (response.data.status == true) {
                               const userData  = response.data.data[0]
                               console.log(userData);
                               localStorage.setItem('userData', JSON.stringify(userData))
                               localStorage.setItem('privilege', JSON.stringify(userData.privilege))
                               localStorage.setItem('role', JSON.stringify(userData.role))
                               localStorage.admin_id = userData.id;
                               localStorage.admin_email = userData.email;

                               this.$ability.update(userData.ability)
                            this.$router.push({ name: "dashboard-ecommerce" })
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
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-auth.scss';
</style>

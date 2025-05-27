<template>
  <div>


    <b-modal id="modal-login" title="Sign Up" ok-only ok-title="Login" cancel-title="Close" hide-footer hide-header
      size="sm" centered ref="my-modal" :visible="value">
      <b-row>
        <!-- <b-col
            xxl="6"
            lg="6"
            md="6"
            sm="12"
            
            class=''
          >
  <b-img src="/login.svg" fluid alt="Responsive image" />
    </b-col> -->
        <b-col xxl="12" lg="12" md="12" sm="12">
          <b-form @submit.prevent="login">
            <h4>Create an account or log in</h4>
            <h6>Log in below or create a new {{ app_name }} account.</h6>
            <b-form-group>
              <label for="email" class="mt-2">{{ $t("Email Id") }}/{{ $t("Phone Number") }}</label>
              <b-form-input id="email" type="text" placeholder="Email Address" class="form-control-lg"
                v-model="userEmail" />
            </b-form-group>
            <b-form-group>
              <div class="d-flex justify-content-between">
                <label for="password">{{ $t("Password") }}</label>
                <small @click="forgotpassoword()">{{ $t("Forgot Password") }}?</small>
              </div>

              <b-input-group class="input-group-merge">
                <b-form-input id="password" v-model="password" :type="passwordFieldType" class="form-control-merge"
                  name="login-password" placeholder="Password" />

                <b-input-group-append is-text>
                  <feather-icon class="cursor-pointer" :icon="passwordToggleIcon" @click="togglePasswordVisibility" />
                </b-input-group-append>
              </b-input-group>

            </b-form-group>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg">
              {{ $t("Sign In") }}

            </b-button>
            <div class="text-center pt-1 pb-1"><strong>or</strong></div>
            <!-- <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
           
            variant="outline-primary"
            class="mr-1 btn-block btn-lg"
            @click="AuthProvider('google')"
          >
          {{$t("Sign In with Google")}}
           
          </b-button> -->
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="outline-primary" class="mr-1 btn-block btn-lg"
              @click="AuthProvider('google')">
              <b-img src="/glogin.png" class="mr-50" alt="Responsive image" style="float:left" />
              {{ $t("Continue with Google") }}

            </b-button>
            <!-- <button @click="AuthProvider('google')">auth Google</button> -->


          </b-form>
          <b-card-text class="text-center mt-2">
            <span> {{ $t("Don’t have an account") }} </span>
            <b-link @click="signupmodal()" class="hoverme">
              <span>&nbsp; {{ $t("Create an account") }}</span>
            </b-link>
          </b-card-text>
        </b-col>
      </b-row>

    </b-modal>

    <b-modal id="modal-phone" cancel-variant="outline-secondary" no-close-on-backdrop hide-header hide-footer centered
      title="Add Phone Number" ref="phone">
      <validation-observer ref="simpleRules">


        <b-form @submit.prevent="add_phone_func">
          <b-form-group>
            <label for="email"> {{ $t("Phone Number") }}:</label>
            <!-- <b-form-input
            id="phone"
            type="number"
            placeholder="Phone Number"
            class="form-control-lg"
            v-model="add_phone"
            
            autocomplete="off"

          /> -->
            <validation-provider #default="{ errors }" rules="required" name="Number">
              <VuePhoneNumberInput id="phone12" no-example @update="updatePhoneNumber2" v-model="signup_phone2"
                :fetch-country=true :state="errors[0] ? false : (valid ? true : null)">
              </VuePhoneNumberInput>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
          <input type="hidden" :value="user_id_p">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
            class="mr-1 btn-block btn-lg .col-form-label-lg">
            {{ $t("Add") }}

          </b-button>
        </b-form>
      </validation-observer>
    </b-modal>



    <b-modal id="modal-fpass" cancel-variant="outline-secondary" hide-footer centered title="Forgot Password"
      ref="forgotpassword">


      <b-form @submit.prevent="forgot">
        <b-form-group>
          <label for="email"> {{ $t("Email Id") }}:</label>
          <b-form-input id="email" type="email" placeholder="Email Id" v-model="forgot_email" />
        </b-form-group>

        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
          class="mr-1 btn-block btn-lg .col-form-label-lg">
          {{ $t("GET OTP") }}

        </b-button>
      </b-form>

    </b-modal>

    <b-modal id="modal-vpass" cancel-variant="outline-secondary" hide-footer centered title="Forgot Password" ref="vpass">


      <b-form @submit.prevent="forgot_otp">
        <b-form-group>
          <label for="otp"> {{ $t("OTP") }}</label>
          <b-form-input id="otp" type="text" placeholder="OTP" v-model="forgot_otp1" />
        </b-form-group>

        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
          class="mr-1 btn-block btn-lg .col-form-label-lg">
          {{ $t("Submit") }}

        </b-button>
      </b-form>

    </b-modal>


    <b-modal id="modal-npass" cancel-variant="outline-secondary" hide-footer centered title="Reset Password" ref="npass">

      <validation-observer ref="simpleRules">
        <b-form @submit.prevent="reset_pass_password1">
          <b-form-group>
            <validation-provider #default="{ errors }" name="Password" vid="Password" rules="required|password">
              <div class="d-flex justify-content-between">
                <label for="password"> {{ $t("Password") }}</label>

              </div>

              <b-input-group class="input-group-merge">
                <b-form-input id="password" v-model="reset_pass_password" :type="passwordFieldType"
                  class="form-control-merge" :state="errors.length > 0 ? false : null" name="login-password"
                  placeholder="Password" autocomplete="off" />

                <b-input-group-append is-text>
                  <feather-icon class="cursor-pointer" :icon="passwordToggleIcon" @click="togglePasswordVisibility" />
                </b-input-group-append>
              </b-input-group>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>

            <validation-provider #default="{ errors }" name="Password" vid="Password" rules="required|password">
              <div class="d-flex justify-content-between">
                <label for="password"> {{ $t("Confirm") }} {{ $t("Password") }}</label>

              </div>

              <b-input-group class="input-group-merge">
                <b-form-input id="confirm_password" v-model="reset_pass_password_cnfrm" :type="passwordFieldType"
                  class="form-control-merge" :state="errors.length > 0 ? false : null" name="login-password"
                  placeholder="Password" autocomplete="off" />

                <b-input-group-append is-text>
                  <feather-icon class="cursor-pointer" :icon="passwordToggleIcon" @click="togglePasswordVisibility" />
                </b-input-group-append>
              </b-input-group>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>

          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
            class="mr-1 btn-block btn-lg .col-form-label-lg">
            {{ $t("Submit") }}

          </b-button>
        </b-form>
      </validation-observer>
    </b-modal>



    <b-modal id="modal-signupmodal" title="Sign Up" ok-only ok-title="Sign Up" cancel-title="Close" hide-footer
      hide-header size="sm" centered ref="signupmodal">
      <b-row>
        <!-- <b-col
            xxl="6"
            lg="6"
            md="6"
            sm="12"
            
            class=''
          >
      <b-img src="/login.svg" fluid alt="Responsive image" />
      </b-col> -->
        <b-col xxl="12" lg="12" md="12" sm="12">
          <validation-observer ref="simpleRules">
            <b-form @submit.prevent="signup">
              <h4>Create an account </h4>
              <h6>Create a new {{ app_name }} account.</h6>
              <b-form-group>
                <validation-provider #default="{ errors }" name="Name" rules="required">
                  <label for="email"> {{ $t("Name") }}</label>
                  <b-form-input id="email" type="text" placeholder="Name" class="form-control-lg" v-model="signup_name"
                    :state="errors.length > 0 ? false : null" autocomplete="off" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <b-form-group>
                <validation-provider #default="{ errors }" name="Email" rules="required|email">
                  <label for="email"> {{ $t("Email ID") }} </label>
                  <b-form-input id="email" type="email" placeholder="Email Address" class="form-control-lg"
                    v-model="signup_email" :state="errors.length > 0 ? false : null" autocomplete="off" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <b-form-group>
                <validation-provider #default="{ errors }" rules="required" name="Number">
                  <label for="phone1">{{ $t("Phone Number") }} </label>
                  <VuePhoneNumberInput id="phone1" no-example @update="updatePhoneNumber" v-model="signup_phone"
                    :state="errors[0] ? false : (valid ? true : null)" :fetch-country=true>
                  </VuePhoneNumberInput>
                  <!-- <b-form-input
            id="email"
            type="number"
            placeholder="Phone Number"
            class="form-control-lg"
            v-model="signup_phone"
            :state="errors.length > 0 ? false:null"
            autocomplete="off"

          /> -->
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <b-form-group>
                <validation-provider #default="{ errors }" name="Password" vid="Password" rules="required|min:8|password">
                  <div class="d-flex justify-content-between">
                    <label for="password">{{ $t("Password") }}</label>

                  </div>

                  <b-input-group class="input-group-merge">
                    <b-form-input id="password" v-model="signup_password" :type="passwordFieldType"
                      class="form-control-merge" :state="errors.length > 0 ? false : null" name="login-password"
                      placeholder="Password" autocomplete="off" />

                    <b-input-group-append is-text>
                      <feather-icon class="cursor-pointer" :icon="passwordToggleIcon" @click="togglePasswordVisibility" />
                    </b-input-group-append>
                  </b-input-group>

                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <b-form-group label="Confirm Password" label-for="ac-password">
                <validation-provider #default="{ errors }" name="Confirm Password" rules="required|confirmed:Password">

                  <b-form-input id="ac-password" v-model="passValue" :state="errors.length > 0 ? false : null"
                    type="password" placeholder="Confirm Password" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
                class="mr-1 btn-block btn-lg .col-form-label-lg" @click.prevent="signup">
                {{ $t("Sign Up") }}
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
  BInputGroup, BDropdown,
  BCardText,
  BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar, BButton, BModal, VBModal, BForm, BFormInput, BImg, BFormGroup, BCol, BRow, BInputGroupAppend, BLink
} from 'bootstrap-vue'
import { initialAbility } from '@@@/libs/acl/config'
import useJwt from '@@@/auth/jwt/useJwt'
import { avatarText } from '@@@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import {
  required, email, confirmed, password, min
} from '@validations'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import store from '@@@/store'
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
    ValidationObserver,
    BLink, VuePhoneNumberInput

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
      username: '',
      loggedin: false,
      forgot_otp1: '',
      forgot_email: '',
      profile_image: '',
      app_name: '',
      country: null,
      formattedNumber: '',
      passValue: '',
      formattedNumber2: '',
      signup_phone2: '',
      add_phone: '',
      user_id_p: '',
      phone_id: '',
      min
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
    this.$http.get('/get_site_logo')
      .then(res => {
        this.app_name = res.data.app_name

      })
    this.checklogin()
  },
  methods: {
    updatePhoneNumber2(data) {
      this.formattedNumber2 = data.formattedNumber
    },
    add_phone_func() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          let forg = new FormData();
          forg.append('phone', this.formattedNumber2);
          forg.append('user', this.user_id_p);

          this.$http
            .post("/add_phone_number", forg)
            .then(
              (response => {
                if (response.data.status == true) {

                  const userData = response.data.data
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
              })
            )
            .catch((error) => console.log(error))
            .finally(() => (this.loading = false));
        }
      })
    },
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
    signupmodal() {
      this.$refs['signupmodal'].show()
    },
    forgotpassoword() {
      this.$refs['my-modal'].hide()
      this.$refs['forgotpassword'].show()
    },
    checklogin() {
      if (localStorage.getItem("webuserData")) {
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
    forgot() {
      let forg = new FormData();
      forg.append('email', this.forgot_email);
      this.$http
        .post("/forgot_password", forg)
        .then(
          (response => {
            if (response.data.status == true) {
              localStorage.setItem('forget_id', response.data.id)

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
          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    forgot_otp() {
      let forg = new FormData();
      forg.append('code', this.forgot_otp1);
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
          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    reset_pass_password1() {
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
              })
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

              const userData = response.data.data
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


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    signup() {
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
              })
            )
            .catch((error) => console.log(error))
            .finally(() => (this.loading = false));
        }
      })

    },
    AuthProvider(provider) {
      var bb = this
      this.$auth.authenticate(provider).then(response => {

        this.SocialLogin(provider, response)

      }).catch(err => {
        console.log({ err: err })
      })

    },

    SocialLogin(provider, response) {

      this.$http.post('/sociallogin/' + provider, response).then(response => {

        if (response.data.status == true) {
          this.loggedin = true
          if (response.data.phone == true) {
            this.user_id_p = response.data.data.id
            this.$refs['my-modal'].hide()
            this.$refs['phone'].show()
          } else {
            const userData = response.data.data

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

        console.log({ err: err })
      })


    },
  },
}
</script>
<style lang="scss">
.btn .feather {
  vertical-align: top;
}

.hoverme {
  cursor: pointer;
}
</style>
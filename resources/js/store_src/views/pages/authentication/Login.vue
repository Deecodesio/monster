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
          Welcome to the {{ logo.name }} Merchant Portal !

        </b-card-title>
        <b-card-text class="mb-2">
          Please sign-in to your account and start the adventure
        </b-card-text>
        <b-alert variant="primary" show>
          <div class="alert-body font-small-2" v-if="is_demo">
            <b-row>
              <b-col cols="8">
                <p>
                <h6>Demo Account</h6>
                <small class="mr-50"><span class="font-weight-bold">Email:</span> restaurantadmin@delicart.app
                </small><br>
                <small class="mr-50"><span class="font-weight-bold">Password:</span> 123456</small>
                </p>
              </b-col>
              <b-col cols="4" style="text-align: center;padding-top: 18px;">
                <feather-icon icon="CopyIcon" size="25" style="cursor:pointer" @click="copy()" />
              </b-col>
            </b-row>


          </div>

        </b-alert>
        <!-- form -->
        <validation-observer ref="loginForm">
          <b-form class="auth-login-form mt-2" @submit.prevent="login">

            <!-- email -->
            <b-form-group label-for="email" label="Email">
              <validation-provider #default="{ errors }" name="Email" rules="required|email">
                <b-form-input id="email" v-model="userEmail" name="login-email" :state="errors.length > 0 ? false : null"
                  placeholder="john@example.com" autofocus />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- password -->
            <b-form-group>
              <div class="d-flex justify-content-between">
                <label for="password">Password</label>

              </div>
              <validation-provider #default="{ errors }" name="Password" rules="required">
                <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid' : null">
                  <b-form-input id="password" v-model="password" :type="passwordFieldType" class="form-control-merge"
                    :state="errors.length > 0 ? false : null" name="login-password" placeholder="Password" />

                  <b-input-group-append is-text>
                    <feather-icon class="cursor-pointer" :icon="passwordToggleIcon" @click="togglePasswordVisibility" />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- checkbox -->
            <b-form-group>
              <b-form-checkbox id="remember-me" v-model="status" name="checkbox-1">
                Remember Me
              </b-form-checkbox>
            </b-form-group>
            <!-- <vue-recaptcha 
            :sitekey="sitekey"
            @verify="verifyMethod"
            ></vue-recaptcha> -->
            <!-- submit button -->
            <b-button variant="primary" type="submit" block>
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
      logo: {},
      is_demo: false,
      invalidc: true,
      sitekey: '6LfvY5kkAAAAAExIq6rolvkqFCYDCbOwESs92dbB',
    }
  },
  created() {
    if (process.env.MIX_IS_DEMO) {

      this.is_demo = true
    }
    this.$http.get("/store/get_logo")
      .then(
        (response => {

          this.logo = response.data[0]
          console.log(this.logo)
        })
      )
      .catch((error) => console.log(error))
      .finally(() => (this.loading = false));
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  methods: {
    copy() {

      this.password = "123456"
      this.userEmail = "restaurantadmin@delicart.app"
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
    verifyMethod() {
      this.invalidc = false
    },
    login() {
      this.$refs.loginForm.validate().then(success => {
        if (success) {
          let city = new FormData();
          city.append('email', this.userEmail);
          city.append('password', this.password);

          this.$http
            .post("/store/login", city)
            .then(
              (response => {
                if (response.data.status == true) {
                  const store_userData = response.data.data[0]
                  console.log(store_userData.privilege);
                  localStorage.setItem('store_userData', JSON.stringify(store_userData))
                  localStorage.setItem('privilege', JSON.stringify(store_userData.privilege))
                  localStorage.setItem('role', JSON.stringify(store_userData.role))
                  localStorage.setItem('id', JSON.stringify(store_userData.id))
                  localStorage.id = store_userData.id;


                  this.$ability.update(store_userData.ability)
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


              })
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

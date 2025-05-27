<template>
  <div class="mt-5 ">

    <div class="pt-4">
      <b-img src="/more1.jpg" fluid alt="Scan" />
    </div>

    <div class="container pt-1">
      <b-card>
        <h4 class="pb-2">Contact Us</h4>

        <b-row>
          <b-col cols="12" lg="8" class="pb-2">
            <validation-observer ref="simpleRules">
              <b-form>
                <b-row>
                  <b-col md="6">
                    <b-form-group>
                      <label>Name</label>
                      <validation-provider #default="{ errors }" name="Name" rules="required">
                        <b-form-input v-model="restaurant.name" :state="errors.length > 0 ? false : null"
                          placeholder="Name" />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col md="6">
                    <b-form-group>
                      <label>Email Id</label>
                      <validation-provider #default="{ errors }" name="Email" rules="required|email">
                        <b-form-input v-model="restaurant.email" :state="errors.length > 0 ? false : null" type="email"
                          placeholder="Email" />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col md="6">
                    <b-form-group>
                      <label>Mobile number</label>
                      <validation-provider #default="{ errors }" rules="required" name="Mobile number">

                        <b-form-input v-model="restaurant.phone" :state="errors.length > 0 ? false : null"
                          placeholder="Mobile number" type="number" />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col md="6">
                    <b-form-group>
                      <label>Reason</label>

                      <v-select v-model="restaurant.reason" :options="this.reasons" label="reason"
                        :reduce="sel => sel.id" placeholder="Select Reason" id="city_list"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

                    </b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <label for="textarea-default">Comments</label>
                    <b-form-textarea id="textarea-default" placeholder="Comments" rows="3"
                      v-model="restaurant.comments" />
                  </b-col>
                  <b-col cols="12" class="pt-1">
                    <b-button variant="primary" type="submit" @click.prevent="validationForm">
                      Submit
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>
            </validation-observer>
          </b-col>
          <b-col cols="12" lg="4">
            <!-- <h4>
              We're Here For you!
            </h4>
            <p>
              <b>Order issues:</b>
              For faster resolution of an order issue, please reach out to the merchant directly. You can find the
              merchant's contact number in your order details. If you are not satisfied with the merchant's response,
              please Contact Us
            </p>
            <p>
              <b>Send us your feedback</b>
              We'd love your feedback! Whether it's good, bad or ugly, please let us know....nicely😊
            </p>
            <p>
              <b>Press Inquiries</b>
              Contact Us for press inquiries
            </p> -->
            <div v-html="rows[0].contact">
            </div>

          </b-col>
        </b-row>


      </b-card>
    </div>
  </div>
</template>

<script>
import {
  BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg, BFormTextarea,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import {
  required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import vSelect from 'vue-select'
import store from '@@@/store'

export default {
  components: {
    BButton,
    BForm,
    BImg,
    BFormFile,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    ValidationProvider,
    ValidationObserver, BFormTextarea, vSelect,
  },
  directives: {
    Ripple,
  },

  data() {
    return {
      emailValue: '',
      name: '',
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
      rows: {},
      reasons: [],
      restaurant: {},
    }
  },
  created() {
    store.commit('deliware_cart/UPDATE_FOOTER', false)

    this.$http.get('/getcontactreasons')
      .then(res => {


        this.reasons = res.data

      })


    this.$http.get('/fetch_contact')
      .then(res => {

        this.rows = res.data.data
        store.commit('deliware_cart/UPDATE_FOOTER', true)


      })
  },
  methods: {
    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          this.$http.post('/contacts', this.restaurant)
            .then(res => {
              if (res.data.status == true) {
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
<style>
.pt-10 {
  padding-top: 10rem !important;
}
</style>
<template>
  <b-card>
    <b-tabs vertical content-class="col-12 col-md-9 mt-1 mt-md-0 " pills nav-wrapper-class="col-md-3 col-12"
      nav-class="nav-left">




      <b-tab>


        <template #title>
          <feather-icon icon="MailIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("New Order Mail") }}</span>
        </template>
        <b-card>
          <div>
            <b-form-group label="New Order Mail receivers" label-for="tags-validation">
              <b-form-tags v-model="newordermail" input-id="tags-validation"
                :input-attrs="{ 'aria-describedby': 'tags-validation-help' }" :state="state"
                duplicate-tag-text="Duplicate Emailid" invalid-tag-text="Invalid Email ID" :tag-validator="tagValidator"
                separator=" " placeholder="Enter Email Ids" />

            </b-form-group>
          </div>
          <div>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
              @click="save_email('newordermail', newordermail)">
              {{ $t('save') }}
            </b-button>
          </div>
          <div class="mt-5">
            <new_mail />
          </div>
        </b-card>

      </b-tab>

      <b-tab>


        <template #title>
          <feather-icon icon="MehIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Cancelled Order Mail") }} </span>
        </template>
        <b-card>
          <div>
            <b-form-group label="Cancelled Order Mail receivers" label-for="tags-validation">
              <b-form-tags v-model="cancelledordermail" input-id="tags-validation"
                :input-attrs="{ 'aria-describedby': 'tags-validation-help' }" :state="state"
                duplicate-tag-text="Duplicate Emailid" invalid-tag-text="Invalid Email ID" :tag-validator="tagValidator"
                separator=" " placeholder="Enter Email Ids" />

            </b-form-group>
          </div>
          <div>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
              @click="save_email('cancelledordermail', cancelledordermail)">
              {{ $t('save') }}
            </b-button>
          </div>
          <div class="mt-5">
            <cancelled_mail />
          </div>
        </b-card>

      </b-tab>

      <b-tab>


        <template #title>
          <feather-icon icon="AlertTriangleIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Failed Order Mail") }}</span>
        </template>

        <b-card>
          <div>
            <b-form-group label="Failed Order Mail receivers" label-for="tags-validation">
              <b-form-tags v-model="failedordermail" input-id="tags-validation"
                :input-attrs="{ 'aria-describedby': 'tags-validation-help' }" :state="state"
                duplicate-tag-text="Duplicate Emailid" invalid-tag-text="Invalid Email ID" :tag-validator="tagValidator"
                separator=" " placeholder="Enter Email Ids" />

            </b-form-group>
          </div>
          <div>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
              @click="save_email('failedordermail', failedordermail)">
              {{ $t('save') }}
            </b-button>
          </div>
          <div class="mt-5">
            <failed_mail />
          </div>
        </b-card>
      </b-tab>

      <b-tab>

        <template #title>
          <feather-icon icon="BriefcaseIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Store Signup Mail") }}</span>
        </template>
        <b-card>
          <div>
            <b-form-group label="Store Signup Mail receivers" label-for="tags-validation">
              <b-form-tags v-model="rest_signup" input-id="tags-validation"
                :input-attrs="{ 'aria-describedby': 'tags-validation-help' }" :state="state"
                duplicate-tag-text="Duplicate Emailid" invalid-tag-text="Invalid Email ID" :tag-validator="tagValidator"
                separator=" " placeholder="Enter Email Ids" />

            </b-form-group>
          </div>
          <div>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
              @click="save_email('rest_signup', rest_signup)">
              {{ $t('save') }}
            </b-button>
          </div>
          <div class="mt-5">
            <welcome />
          </div>
        </b-card>


      </b-tab>
      <b-tab>
        <template #title>
          <feather-icon icon="CheckIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Store Activation Mail") }}</span>
        </template>
        <b-card>
          <div class="mt-5">
            <rest_activation />
          </div>
        </b-card>
      </b-tab>
      <b-tab>

        <template #title>
          <feather-icon icon="TruckIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Driver Signup Mail") }}</span>
        </template>
        <b-card>
          <div>
            <b-form-group label="Driver Signup Mail receivers" label-for="tags-validation">
              <b-form-tags v-model="driver_signup" input-id="tags-validation"
                :input-attrs="{ 'aria-describedby': 'tags-validation-help' }" :state="state"
                duplicate-tag-text="Duplicate Emailid" invalid-tag-text="Invalid Email ID" :tag-validator="tagValidator"
                separator=" " placeholder="Enter Email Ids" />

            </b-form-group>
          </div>
          <div>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
              @click="save_email('driver_signup', driver_signup)">
              {{ $t('save') }}
            </b-button>
          </div>
          <div class="mt-5">
            <driver_welcome />
          </div>
        </b-card>


      </b-tab>
      <b-tab>
        <template #title>
          <feather-icon icon="CheckIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Driver Activation Mail") }}</span>
        </template>
        <b-card>
          <div class="mt-5">
            <driver_activation />
          </div>
        </b-card>
      </b-tab>
      <b-tab>

        <template #title>
          <feather-icon icon="MailIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Contact Form") }}</span>
        </template>
        <contact_form />
        <contact_form_template />

      </b-tab>
      <b-tab>

        <template #title>
          <feather-icon icon="BookIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Invoice") }}</span>
        </template>
        <invoice_mail />

      </b-tab>

      <b-tab>

        <template #title>
          <feather-icon icon="BoxIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Enquiry") }}</span>
        </template>
        <enquiry_mail />

      </b-tab>

      <b-tab>

        <template #title>
          <feather-icon icon="CreditCardIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Wallet OTP Mail") }}</span>
        </template>
        <b-card>
          <div>
            <b-form-group label="Driver Signup Mail receivers" label-for="tags-validation">
              <b-form-tags v-model="wallet_otp" input-id="tags-validation"
                :input-attrs="{ 'aria-describedby': 'tags-validation-help' }" :state="state"
                duplicate-tag-text="Duplicate Emailid" invalid-tag-text="Invalid Email ID" :tag-validator="tagValidator"
                separator=" " placeholder="Enter Email Ids" />

            </b-form-group>
          </div>
          <div>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
              @click="save_email('wallet_otp', wallet_otp)">
              {{ $t('save') }}
            </b-button>
          </div>
          <div class="mt-5">
            <wallet_mail />
          </div>
        </b-card>

      </b-tab>
      <b-tab active>


        <template #title>
          <feather-icon icon="SettingsIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Mail Settings") }}</span>
        </template>
        <b-card>
          <b-form @submit.prevent="addBrand">
            <b-row>
              <b-col md="6">
                <b-form-group :label="$t('email')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('email')" v-model="brand.email_user_name" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('password')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('password')" v-model="brand.email_password" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('driver')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('driver')"
                    v-model="brand.mail_driver" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('debug')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('debug')"
                    v-model="brand.mail_debug" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('host')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('host')"
                    v-model="brand.mail_host" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('from')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('from')"
                    v-model="brand.mail_from" />
                </b-form-group>
              </b-col>

              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('from') + ' ' + $t('name')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('from') + ' ' + $t('name')"
                    v-model="brand.mail_from_name" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('port')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('port')"
                    v-model="brand.mail_port" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('username')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('username')"
                    v-model="brand.mail_username" />
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('password')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('password')"
                    v-model="brand.mail_password" />
                </b-form-group>
              </b-col>

              <b-col md="6">
                <b-form-group :label="$t('mail') + ' ' + $t('Encryption')">
                  <b-form-input id="mc-brand_name" :placeholder="$t('mail') + ' ' + $t('Encryption')"
                    v-model="brand.mail_encryption" />
                </b-form-group>
              </b-col>
              <b-col md="6">

              </b-col>
              <!-- submit and reset -->
              <b-col>
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
                  {{ $t('save') }}
                </b-button>

              </b-col>
            </b-row>
          </b-form>
        </b-card>

      </b-tab>
    </b-tabs>


  </b-card>
</template>
  
<script>
import BCardCode from '@core/components/b-card-code'
import {
  BTooltip, BCard, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormSelect, BCardText, BFormFile, BFormTextarea, BFormRadio,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { BTabs, BTab } from 'bootstrap-vue'
import { BFormTags } from 'bootstrap-vue'
import contact_form from './contact_form.vue'
import welcome from './welcome.vue'
import failed_mail from './failed_mail.vue'
import cancelled_mail from './cancelled_mail.vue'
import new_mail from './new_mail.vue'
import invoice_mail from './invoice_mail.vue'

import enquiry_mail from './enquiry_mail.vue'


import driver_welcome from './driver_welcome.vue'

import contact_form_template from './contact_form_template.vue'
import wallet_mail from './wallet_mail.vue'




import driver_activation from './driver_activation.vue'
import rest_activation from './rest_activation.vue'



export default {
  components: {
    BCardCode,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    BCardText,
    BFormFile,
    BFormTextarea,
    BFormRadio,
    BCard,
    BTooltip,
    BTabs,
    BTab,
    BFormTags, contact_form, welcome, failed_mail, cancelled_mail, new_mail, invoice_mail, driver_welcome, contact_form_template, wallet_mail, driver_activation, rest_activation, enquiry_mail


  },
  directives: {
    Ripple,
  },
  data() {
    return {

      selected: 1,
      userData: JSON.parse(localStorage.getItem('userData')),
      brand: {},
      demo: false,
      newordermail: [],
      cancelledordermail: [],
      failedordermail: [],
      rest_signup: [],
      driver_signup: [],
      wallet_otp: [],
      dirty: false,
    }
  },
  computed: {
    state() {

      var st = false
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.newordermail)) {
        st = true
        // return true
      } else {
        st = false
        // return false
      }
      return this.dirty ? st : null
    },
  },
  watch: {
    tags() {

      this.dirty = true
    },
  },

  methods: {
    tagValidator(tag) {

      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(tag)) {
        return true
      } else {
        return false
      }

    },
    save_email(mailtye, mails_list) {
      var mails = []
      for (var i = 0; i < mails_list.length; i++) {
        mails.push(mails_list[i])
      }
      let data = new FormData();
      data.append('type', mailtye);
      data.append('mails', JSON.stringify(mails));
      this.$http
        .post("/admin/update-email", data)
        .then(
          (response => {

            console.log(response)
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t(response.data.message),
                icon: 'CheckIcon',
                variant: 'primary',
              },
            })
          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

    addBrand() {
      if (this.demo == true) {
        this.$toast({
          component: ToastificationContent,
          position: 'bottom-right',
          props: {
            title: this.$t("Settings Cannot be Changed in Demo Mode"),
            icon: 'CheckIcon',
            variant: 'danger',
          },
        })
        return false
      }
      this.brand.type = "email";
      this.$http
        .post("/admin/update-setting", this.brand)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "email_settings" })
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
            } else {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'warning',
                },
              })
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response) {

      const h = this.$createElement
      const vNodesMsg = h('p', { class: ['text-center', 'mb-0'] }, [
        response.data.message
      ])
      this.$bvToast.toast([vNodesMsg], {
        solid: false,
        variant: 'info',
      })
    },
  },
  created() {
    this.$http.get('/admin/check_demo/' + this.userData.email)
      .then(res => {
        if (res.data.demo == true) {
          this.demo = true

        }
      })
    this.$http.get('/admin/settings/site')
      .then(res => {

        this.brand = res.data.message;

        this.newordermail = this.brand.email_user_name
        this.cancelledordermail = this.brand.email_user_name
        this.failedordermail = this.brand.email_user_name
        this.rest_signup = this.brand.email_user_name
        this.driver_signup = this.brand.email_user_name
        this.wallet_otp = this.brand.email_user_name






        if (this.demo) {

          this.brand.mail_encryption = "***************************"
          this.brand.mail_password = "***************************"
          this.brand.mail_username = "***************************"
          this.brand.mail_port = "***************************"
          this.brand.mail_from_name = "***************************"
          this.brand.mail_from = "***************************"
          this.brand.mail_host = "***************************"
          this.brand.mail_debug = "***************************"
          this.brand.mail_driver = "***************************"
          this.brand.email_password = "***************************"
          this.brand.email_user_name = "***************************"




        }
      })
    this.$http.get('/admin/get_mails')
      .then(res => {
        console.log(res)
        if (res.data.neworder != null) {
          this.newordermail = res.data.neworder
        }
        if (res.data.cancelledordermail != null) {
          this.cancelledordermail = res.data.cancelledordermail
        }
        if (res.data.failedordermail != null) {
          this.failedordermail = res.data.failedordermail
        }
        if (res.data.rest_signup != null) {
          this.rest_signup = res.data.rest_signup
        }

        if (res.data.driver_signup != null) {
          this.driver_signup = res.data.driver_signup
        }

        if (res.data.wallet_otp != null) {
          this.wallet_otp = res.data.wallet_otp
        }




      })

  },
}
</script>
  
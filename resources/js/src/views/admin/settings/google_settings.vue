<template>
  <b-card>
    <b-form @submit.prevent="addBrand">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('Google') + ' ' + $t('API') + ' ' + $t('Key')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Google') + ' ' + $t('API') + ' ' + $t('Key')"
              v-model="brand.google_api_key" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('firebase') + ' ' + $t('url')">
            <b-form-input id="mc-brand_name" :placeholder="$t('firebase') + ' ' + $t('url')"
              v-model="brand.firebase_url" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('User') + ' ' + $t('Notification') + ' ' + $t('Key')">
            <b-form-input id="mc-brand_name" :placeholder="$t('User') + ' ' + $t('Notification') + ' ' + $t('Key')"
              v-model="brand.user_notification_key" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('partner') + ' ' + $t('Notification') + ' ' + $t('Key')">
            <b-form-input id="mc-brand_name" :placeholder="$t('partner') + ' ' + $t('Notification') + ' ' + $t('Key')"
              v-model="brand.partner_notification_key" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Captcha') + ' ' + $t('Site') + ' ' + $t('Key')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Captcha') + ' ' + $t('Site') + ' ' + $t('Key')"
              v-model="brand.captcha_site_key" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Captcha') + ' ' + $t('Site') + ' ' + $t('secret')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Captcha') + ' ' + $t('Site') + ' ' + $t('secret')"
              v-model="brand.captcha_site_secret" />
          </b-form-group>
        </b-col>



        <b-col md="6">
          <b-form-group :label="$t('Google Client ID')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Google Client ID')" v-model="brand.google_client_id" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Google Client Secret')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Google Client Secret')"
              v-model="brand.google_client_secret" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Google Redirect URL')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Google Redirect URL')"
              v-model="brand.google_redirect_url" />
          </b-form-group>
        </b-col>

        <!-- submit and reset -->
        <b-col cols="12">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
            {{ $t('save') }}
          </b-button>

        </b-col>
      </b-row>
    </b-form>
  </b-card>
</template>
  
<script>
import BCardCode from '@core/components/b-card-code'
import {
  BTooltip, BCard, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormSelect, BCardText, BFormFile, BFormTextarea, BFormRadio,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


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
  },
  directives: {
    Ripple,
  },
  data() {
    return {

      selected: 1,

      brand: {},

    }
  },
  methods: {
    addBrand() {
      if (process.env.MIX_IS_DEMO) {
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
      this.$http
        .post("/admin/update-setting", this.brand)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "google_settings" })
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

    this.$http.get('/admin/settings/site')
      .then(res => {

        this.brand = res.data.message;
        if (process.env.MIX_IS_DEMO) {

          this.brand.captcha_site_secret = "***************************"
          this.brand.captcha_site_key = "***************************"
          this.brand.partner_notification_key = "***************************"
          this.brand.user_notification_key = "***************************"
          this.brand.firebase_url = "***************************"
          this.brand.google_api_key = "***************************"

        }
      })

  },
}
</script>
  
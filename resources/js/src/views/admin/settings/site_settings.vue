<template>
  <b-card>
    <b-form @submit.prevent="addBrand">
      <b-row>

        <b-col md="6">
          <b-form-group :label="$t('app') + ' ' + $t('name')">
            <b-form-input id="mc-brand_name" :placeholder="$t('app') + ' ' + $t('name')" v-model="brand.app_name" />

          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-form-group :label="$t('Meta') + ' ' + $t('titles')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Meta') + ' ' + $t('titles')" v-model="brand.meta_title" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Meta') + ' ' + $t('description')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Meta') + ' ' + $t('description')"
              v-model="brand.meta_description" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('IOS') + ' ' + $t('app') + ' ' + $t('link')">
            <b-form-input id="mc-brand_name" :placeholder="$t('IOS') + ' ' + $t('app') + ' ' + $t('link')"
              v-model="brand.ios_app_link" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('android') + ' ' + $t('app') + ' ' + $t('link')">
            <b-form-input id="mc-brand_name" :placeholder="$t('android') + ' ' + $t('app') + ' ' + $t('link')"
              v-model="brand.android_app_link" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Site') + ' ' + $t('email')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Site') + ' ' + $t('email')" v-model="brand.site_email" />
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group :label="$t('Site') + ' ' + $t('Fav_Icon')">
            <b-form-file v-model="brand.site_favicon" placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..." @change="onFileChange" />
          </b-form-group>
          <b-form-group>
            <b-img :src="url ? url : brand.site_favicon" rounded fluid id="site_favicon-img" alt="site_favicon photo"
              v-if="brand.site_favicon" />
            <b-img src="/no_image.png" id="site_favicon-img" fluid alt="Scan" v-else />
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group :label="$t('Site') + ' ' + $t('Logo')">
            <b-form-file v-model="brand.site_logo" placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..." @change="onFileChange1" />
          </b-form-group>
          <b-form-group>
            <b-img :src="url1 ? url1 : brand.site_logo" rounded fluid id="site_logo-img" alt="site_logo photo"
              v-if="brand.site_logo" />
            <b-img src="/no_image.png" id="site_logo-img" fluid alt="Scan" v-else />
          </b-form-group>
        </b-col>

        <b-col md="4">
          <b-form-group :label="$t('Qrcode')">
            <b-form-file v-model="brand.qrcode" placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..." @change="onFileChange2" />
          </b-form-group>
          <b-form-group>
            <b-img :src="url2 ? url2 : brand.qrcode" rounded fluid id="qrcode-img" alt="qrcode photo"
              v-if="brand.qrcode" />
            <b-img src="/no_image.png" id="qrcode-img" fluid alt="Scan" v-else />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Site') + ' ' + $t('contact')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Site') + ' ' + $t('contact')"
              v-model="brand.site_contact" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('menu') + ' ' + $t('Colors')">
            <b-form-input id="mc-brand_name" :placeholder="$t('menu') + ' ' + $t('Colors')" v-model="brand.menu_color" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Highlights') + ' ' + $t('Colors')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Highlights') + ' ' + $t('Colors')"
              v-model="brand.highlight_color" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('admin') + ' ' + $t('commission')">
            <b-form-input id="mc-brand_name" :placeholder="$t('admin') + ' ' + $t('commission')"
              v-model="brand.admin_commission" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Store') + ' ' + $t('commission')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Store') + ' ' + $t('commission')"
              v-model="brand.restaurant_commission" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('global') + ' ' + $t('geofence') + ' ' + $t('radius')">
            <b-form-input id="mc-brand_name" :placeholder="$t('global') + ' ' + $t('geofence') + ' ' + $t('radius')"
              v-model="brand.default_radius" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('default') + ' ' + $t('unit')">
            <v-select v-model="brand.default_unit" :options="this.unit" label="text" :reduce="sel => sel.text"
              :placeholder="$t('select') + ' ' + $t('unit')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>



        <b-col md="6">
          <b-form-group :label="$t('order') + ' ' + $t('prefix')">
            <b-form-input id="mc-brand_name" :placeholder="$t('order') + ' ' + $t('prefix')"
              v-model="brand.order_prefix" />
          </b-form-group>
        </b-col>



        <b-col md="6">
          <b-form-group :label="$t('facebook')">
            <b-form-input :placeholder="$t('facebook')" v-model="brand.facebook" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Instagram')">
            <b-form-input :placeholder="$t('Instagram')" v-model="brand.instagram" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Youtube')">
            <b-form-input :placeholder="$t('Youtube')" v-model="brand.youtube" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Twitter')">
            <b-form-input :placeholder="$t('Twitter')" v-model="brand.twitter" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Linked In')">
            <b-form-input :placeholder="$t('Linked In')" v-model="brand.linkedin" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('latitude')">
            <b-form-input :placeholder="$t('latitude')" v-model="brand.Latitude" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('longitude')">
            <b-form-input :placeholder="$t('longitude')" v-model="brand.Longitude" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Primary') + ' ' + $t('Language')">
            <v-select v-model="brand.primary_language" :options="this.primary" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select') + ' ' + $t('Primary')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Secondary') + ' ' + $t('Language')">
            <v-select v-model="brand.secondary_language" :options="this.secondary" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select') + ' ' + $t('Secondary')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>

        <b-col md="12">
          <b-form-group :label="$t('script')">
            <b-form-textarea id="textarea-small" size="sm" :placeholder="$t('script')" v-model="brand.script" />
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('Analytics') + ' ' + $t('script')">
            <b-form-textarea id="textarea-small" size="sm" :placeholder="$t('Analytics') + ' ' + $t('script')"
              v-model="brand.analytics_script" />
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('sharing') + ' ' + $t('script')">
            <b-form-textarea id="textarea-small" size="sm" :placeholder="$t('sharing') + ' ' + $t('script')"
              v-model="brand.sharing_script" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('email') + ' ' + $t('enable')">
            <b-form-radio-group v-model="brand.email_enable" :options="email_radio" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('SMS') + ' ' + $t('enable')">
            <b-form-radio-group v-model="brand.sms_enable" :options="sms_radio" />
          </b-form-group>
        </b-col>



        <b-col md="6">
          <b-form-group :label="$t('cod') + ' ' + $t('enable')">
            <b-form-radio-group v-model="brand.cod_enable" :options="cod_radio" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('online_payment') + ' ' + $t('enable')">
            <b-form-radio-group v-model="brand.onlinepayment_enable" :options="online_radio" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Is Tax') + ' ' + $t('Inclusive')">
            <b-form-radio-group v-model="brand.tax_inclusive" :options="tax_radio" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Tips') + ' ' + $t('enable')">
            <b-form-radio-group v-model="brand.tips_enable" :options="tips_radio" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Language') + ' ' + $t('enable')">
            <b-form-radio-group v-model="brand.lang_enable" :options="lang_radio" />
          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-form-group :label="$t('Tracking Page')">
            <b-form-radio-group v-model="brand.tracking_page" :options="tracking_radio" />
          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-form-group :label="$t('Show Disclaimer on Products')">
            <b-form-radio-group v-model="brand.disclaimer_enable" :options="disclaimer_options" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Disclaimer Text')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Disclaimer Text')" v-model="brand.disclaimer" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Final Payment Text')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Final Payment Text')"
              v-model="brand.pending_payment_text" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Tips')">
            <v-select v-model="selected12" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" multiple label="title"
              :options="options_tips">
              <template #list-header>
                <li @click="open_sizemodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New') }}</span>
                </li>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Tips') + ' ' + $t('text')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Tips') + ' ' + $t('text')" v-model="brand.tips_text" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Store') + ' ' + $t('nearby') + ' ' + $t('radius')">
            <b-form-input id="mc-brand_name" :placeholder="$t('Store') + ' ' + $t('nearby') + ' ' + $t('radius')"
              v-model="brand.restaurant_distance" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Time') + ' ' + $t('Zone')">
            <b-form-select v-model="brand.time_zone" :options="options" />
          </b-form-group>
        </b-col>





        <!-- submit and reset -->
        <b-col md="6">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" @click="clearcache()" variant="primary" class="mr-1">
            {{ $t('Clear Cache') }}
          </b-button>
        </b-col>
        <b-col md="6"></b-col>
        <b-modal id="modal-center" centered :title="$t('Add New')" ok-only :ok-title="$t('Save')" ref="size-modal"
          @ok="tipsm_add">
          <b-row>
            <b-col md="12">
              <b-form-group :label="$t('Tips') + ' ' + $t('Value')">
                <b-form-input id="name" :placeholder="$t('enter') +
                  ' ' +
                  $t('Tips') +
                  ' ' +
                  $t('Value')
                  " v-model="sbrand.name" />
              </b-form-group>
            </b-col>


          </b-row>
        </b-modal>
        <b-col class="mt-2">
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
  BCard, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,
  BFormSelect, BCardText, BFormFile, BFormTextarea, BFormRadio, BImg, BMedia, BTooltip,
  BFormRadioGroup,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'


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
    BImg, BMedia,
    BTooltip,
    BFormRadioGroup, vSelect,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      url1: null,
      url2: null,
      url: null,
      selected: 1,
      options: [
        { value: 'Europe/Sarajevo', text: "(UTC+02:00) Europe/Sarajevo" },
        { value: 'Asia/Calcutta', text: "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi" },
        { value: 'Asia/Dubai', text: "(UTC+04:00) Dubai" },
      ],
      online_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      unit: [
        { text: 'MILES', value: 'MILES' },
        { text: 'KM', value: 'KM' },

      ],
      email_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      cod_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      sms_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      tax_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      tips_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      disclaimer_options: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },
      ],
      tracking_radio: [
        { text: 'Map Based', value: 1 },
        { text: 'Status Based', value: 2 },
      ],
      primary: [
        { text: 'English', value: 'en' },
        { text: 'Dutch', value: 'du' },
        { text: 'French', value: 'fr' },
        { text: 'Portuguese', value: 'pt' },
        { text: 'Arabic', value: 'ar' },
        { text: 'Spanish', value: 'es' },
        { text: 'German', value: 'de' },
        { text: 'Swedish', value: 'sw' },
        { text: 'Vietnamese', value: 'vie' },
        { text: 'Italian', value: 'it' },

      ],
      secondary: [
        { text: 'English', value: 'en' },
        { text: 'Dutch', value: 'du' },
        { text: 'French', value: 'fr' },
        { text: 'Portuguese', value: 'pt' },
        { text: 'Arabic', value: 'ar' },
        { text: 'Spanish', value: 'es' },
        { text: 'German', value: 'de' },
        { text: 'Swedish', value: 'sw' },
        { text: 'Vietnamese', value: 'vie' },
        { text: 'Italian', value: 'it' },


      ],
      lang_radio: [
        { text: 'Yes', value: 1 },
        { text: 'No', value: 0 },

      ],
      brand: {},
      options_tips: [],
      selected12: [],
      sbrand: {},

    }
  },
  methods: {
    clearcache() {
      this.$http
        .post("/admin/clear_cache")
        .then(
          (response => {
            if (response.data.status == true) {
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
      if (this.selected12.length > 3) {
        this.$toast({
          component: ToastificationContent,
          position: 'bottom-right',
          props: {
            title: this.$t("Maximum 3 Tips value can be selected"),
            icon: 'CheckIcon',
            variant: 'danger',
          },
        })
        return false
      }
      if (this.selected12.length < 3) {
        this.$toast({
          component: ToastificationContent,
          position: 'bottom-right',
          props: {
            title: this.$t("Minimum 3 Tips value Should be selected"),
            icon: 'CheckIcon',
            variant: 'danger',
          },
        })
        return false
      }
      if (!this.selected12.length) {
        this.$toast({
          component: ToastificationContent,
          position: 'bottom-right',
          props: {
            title: this.$t("Minimum 3 Tips value should be selected"),
            icon: 'CheckIcon',
            variant: 'danger',
          },
        })
        return false
      } else {
        var tips_ss = []
        for (var i = 0; i < this.selected12.length; i++) {
          tips_ss.push(this.selected12[i].title)
        }
      }
      let data = new FormData();
      data.append('app_name', this.brand.app_name);
      data.append('currency', this.brand.currency);
      data.append('meta_title', this.brand.meta_title);
      data.append('meta_description', this.brand.meta_description);
      data.append('ios_app_link', this.brand.ios_app_link);
      data.append('android_app_link', this.brand.android_app_link);
      data.append('site_email', this.brand.site_email);
      data.append('site_favicon', this.brand.site_favicon);
      data.append('site_logo', this.brand.site_logo);
      data.append('qrcode', this.brand.qrcode);
      data.append('site_contact', this.brand.site_contact);
      data.append('menu_color', this.brand.menu_color);
      data.append('highlight_color', this.brand.highlight_color);
      data.append('admin_commission', this.brand.admin_commission);
      data.append('restaurant_commission', this.brand.restaurant_commission);
      data.append('default_radius', this.brand.default_radius);
      data.append('default_unit', this.brand.default_unit);
      data.append('order_prefix', this.brand.order_prefix);
      data.append('script', this.brand.script);
      data.append('analytics_script', this.brand.analytics_script);
      data.append('sharing_script', this.brand.sharing_script);
      data.append('email_enable', this.brand.email_enable);
      data.append('sms_enable', this.brand.sms_enable);
      data.append('cod_enable', this.brand.cod_enable);
      data.append('onlinepayment_enable', this.brand.onlinepayment_enable);
      data.append('restaurant_distance', this.brand.restaurant_distance);
      data.append('time_zone', this.brand.time_zone);
      data.append('facebook', this.brand.facebook);
      data.append('instagram', this.brand.instagram);

      data.append('youtube', this.brand.youtube);
      data.append('twitter', this.brand.twitter);
      data.append('linkedin', this.brand.linkedin);


      data.append('Longitude', this.brand.Longitude);
      data.append('Latitude', this.brand.Latitude);
      data.append('type', "site");
      data.append('tax_inclusive', this.brand.tax_inclusive);
      data.append('tips_enable', this.brand.tips_enable);
      data.append('tips_text', this.brand.tips_text);
      data.append('tips', JSON.stringify(tips_ss));
      data.append('lang_enable', this.brand.lang_enable);
      data.append('primary_language', this.brand.primary_language);
      data.append('secondary_language', this.brand.secondary_language);
      data.append('disclaimer', this.brand.disclaimer);
      data.append('disclaimer_enable', this.brand.disclaimer_enable);
      data.append('tracking_page', this.brand.tracking_page);
      data.append('pending_payment_text', this.brand.pending_payment_text);




      this.$http
        .post("/admin/update-setting", data)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "site_settings" })
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

    tipsm_add() {
      let brand3 = new FormData();
      brand3.append("name", this.sbrand.name);

      this.$http
        .post("/admin/store_tips", brand3)
        .then((response) => {
          if (response.data.status == true) {
            this.$http.get('/admin/get_tips/')
              .then(res => {
                console.log(res)
                this.options_tips = res.data.tip
              })
            this.$toast({
              component: ToastificationContent,
              position: "bottom-right",
              props: {
                title: response.data.message,
                icon: "CheckIcon",
                variant: "success",
              },
            });
          } else {
            this.$toast({
              component: ToastificationContent,
              position: "bottom-right",
              props: {
                title: response.data.message,
                icon: "CheckIcon",
                variant: "danger",
              },
            });
          }
        })
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));

    },
    open_sizemodal() {
      this.sbrand.name = ""
      this.$refs["size-modal"].show();
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
    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
    },
    onFileChange2(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url2 = reader.result;
      };
    },
    onFileChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url = reader.result;
      };
    },
  },
  created() {

    this.$http.get('/admin/settings/site')
      .then(res => {

        this.brand = res.data.message;
        this.url1 = this.brand.site_logo;
        this.url = this.brand.site_favicon;
        this.url2 = this.brand.qrcode;
        this.brand.Latitude = this.brand.DEFAULT_LATITUDE;
        this.brand.Longitude = this.brand.DEFAULT_LONGITUDE;
      })

    this.$http.get('/admin/get_tips/')
      .then(res => {
        console.log(res)
        this.options_tips = res.data.tip
        this.selected12 = res.data.selected

      })
  },
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#site_logo-img {
  width: 8.92rem;
}

#site_favicon-img {
  width: 8.92rem;
}

#qrcode-img {
  width: 8.92rem;
}
</style>


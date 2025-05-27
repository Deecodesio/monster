<template>
  <b-card>
    <validation-observer ref="simpleRules">

      <b-form>
        <b-row>
          <b-col md="12">
            <b-form-group :label="$t('Plan') + ' ' + $t('name')">
              <validation-provider #default="{ errors }" rules="required" name="Plan Name">
                <b-form-input id="name" :placeholder="$t('Plan') + ' ' + $t('name')" v-model="brand.name"
                  :state="errors.length > 0 ? false : null" />
                <small class="text-danger">{{ errors[0] }}</small>

              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="12">

            <b-form-group :label="$t('Plan') + ' ' + $t('description')">
              <validation-provider #default="{ errors }" rules="required" name="Plan Description">
                <b-form-input id="description" :placeholder="$t('Plan') + ' ' + $t('description')"
                  v-model="brand.description" :state="errors.length > 0 ? false : null" />
                <small class="text-danger">{{ errors[0] }}</small>

              </validation-provider>
            </b-form-group>
            <b-form-group :label="$t('Price')">
              <validation-provider #default="{ errors }" rules="required|integer" name="Price">
                <b-form-input id="Price" :placeholder="$t('Price')" v-model="brand.price"
                  :state="errors.length > 0 ? false : null" />
                <small class="text-danger">{{ errors[0] }}</small>

              </validation-provider>
            </b-form-group>

            <b-form-group :label="$t('Validity') + ' ' + '(' + $t('in days') + ')'">
              <!-- <b-form-checkbox-group

              v-model="brand.lifetime"
              :options="voptions"
              value-field="item"
              text-field="name"
              disabled-field="notEnabled"
              class="demo-inline-spacing"
              @change="validity()"
            /> -->
              <br>

              <b-form-input id="validity" :placeholder="$t('Validity')" v-model="brand.validity" v-if="isHidden" />



            </b-form-group>
            <b-form-group :label="$t('status')">

              <v-select v-model="brand.status" :options="options" label="text" :reduce="sel => sel.value"
                :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
            </b-form-group>
          </b-col>

          <b-col cols="12">
            <b-button variant="primary" type="submit" @click.prevent="addplans">
              Submit
            </b-button>
          </b-col>


        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>
  
<script>
import BCardCode from '@core/components/b-card-code'
import {
  BImg, BMedia, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormSelect, BCardText, BCard, BFormFile, BTooltip, BFormCheckboxGroup
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
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
    BCard,
    vSelect,
    BFormFile,
    BTooltip,
    BImg, BMedia, ValidationProvider,
    ValidationObserver, BFormCheckboxGroup,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      url1: null,
      selected: 1,
      brand: {},
      isHidden: true,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },

      ],
      voptions: [
        { item: '1', name: 'Life Time' },

      ],
      name: '',
      passwordValue: '',
      passwordCon: '',
      emailValue: '',
      number: '',
      numberRange: '',
      numberRegx: '',
      URL: '',
      Alphabetic: '',
      digitValue: '',
      digitValue2: '',
      character: '',
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
      alphaDash,
    }
  },
  methods: {
    validity() {
      if (this.isHidden == true) {
        this.isHidden = false
      } else {
        this.isHidden = true

      }
    },
    addplans() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          this.$http
            .post("/admin/add_to_plans", this.brand)
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
                  this.$router.push({ name: "plans_list" })

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
        }
      })
    }

  },
  created() {

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_plans/' + this.$route.params.id)
        .then(res => {
          console.log(res)

          this.brand = res.data.plan;
          if (this.brand.lifetime == 1) {
            this.isHidden = false
          }
        })
    }

  },
}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';
</style>
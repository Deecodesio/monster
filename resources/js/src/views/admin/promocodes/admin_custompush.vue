<template>
  <b-card-code>
    <b-form @submit.prevent="custom_add">
      <b-row>

        <b-col md="12">
          <b-form-group :label="$t('send') + ' ' + $t('to') + '*'">
            <v-select v-model="custom.send_to" :options="this.rows" label="name" :reduce="sel => sel.id"
              :placeholder="$t('send')+' '+$t('to')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />


          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('titles')">
            <b-form-input id="mc-brand_name" :placeholder="$t('titles')" v-model="custom.title" />
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('messages')">
            <b-form-textarea id="textarea-default" :placeholder="$t('messages')" rows="3" v-model="custom.message" />

          </b-form-group>
        </b-col>


      </b-row>
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
          {{ $t('save') }}
        </b-button>

      </b-col>

    </b-form>


  </b-card-code>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormDatepicker, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormTextarea,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email } from '@validations'
import vSelect from 'vue-select'
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
    BFormRadioGroup,
    BFormDatepicker,
    ValidationProvider,
    ValidationObserver,
    BFormTextarea,
    vSelect,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      custom: {},
      selected_status: null,
      options_status: [
        { value: null, text: '--Status--', disabled: true },
        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },
      ],
      selected_to: null,
      options_to: [
        { value: "ALL", text: this.$t('All Users and Delivery Boys') },
        { value: "USERS", text: this.$t('All Users') },
        { value: "PROVIDERS", text: this.$t('All Delivery Boys') },
      ],
      rows: [],
    }
  },
  methods: {
    custom_add() {

      this.$http
        .post("/admin/admin_custumpush", this.custom)
        .then(
          (response => {

            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
            

            } else {

              this.popToast(response, "AlertTriangleIcon", "danger")
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response, icon, variant) {

      this.$toast({
        component: ToastificationContent,
        position: 'bottom-right',
        props: {
          title: this.$t(response.data.message),
          icon: icon,
          variant: variant,
        },
      })
    },

  },
  created() {
    this.$http.get('/admin/user_list')
      .then(res => {
        this.rows = res.data
      })

  },

}
</script>

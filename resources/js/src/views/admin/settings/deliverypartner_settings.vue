<template>
  <b-card>
    <b-form @submit.prevent="addBrand">
      <b-row>
        <b-col md="12">
          <b-form-group :label="$t('client') + ' ' + $t('id')">
            <b-form-input id="mc-brand_name" :placeholder="$t('client') + ' ' + $t('id')" v-model="brand.dunzo_clientid" />
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('client') + ' ' + $t('secret')">
            <b-form-input id="mc-brand_name" :placeholder="$t('client') + ' ' + $t('secret')"
              v-model="brand.dunzo_clientsecret" />
          </b-form-group>
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
      this.brand.type = "delivery";
      this.$http
        .post("/admin/update-setting", this.brand)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "deliverypartner_settings" })
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
      })


  },
}
</script>
  
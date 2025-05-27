<template>
  <b-card>
    <b-form @submit.prevent="city_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('country')">
            <b-form-input id="mc-first-name" :placeholder="$t('country')" v-model="citys.country" />

          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-form-group :label="$t('country code')">
            <b-form-input id="mc-first-name" :placeholder="$t('country code')" v-model="citys.country_code" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('currency code')">
            <b-form-input id="mc-first-name" :placeholder="$t('currency code')" v-model="citys.currency_code" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('currency symbol')">
            <b-form-input id="mc-first-name" :placeholder="$t('currency symbol')" v-model="citys.currency_symbol" />
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
// import BCardCode from '@core/components/b-card-code'
import {
  BCard, BRow, BCol, BFormGroup, BFormSelect, BFormInput, BFormCheckbox, BForm, BButton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
export default {
  components: {
    // BCardCode,
    BCard,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    vSelect,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      citys: {},
      country: [],
      state: [],
      selected_status: null,
      options_status: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],
    }
  },
  created() {

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_country/' + this.$route.params.id)
        .then(res => {
          this.citys = res.data.message;

        })
    }

  },
  methods: {
    city_add() {
      let citys = new FormData();
      citys.append('id', this.citys.id);
      citys.append('country', this.citys.country);
      citys.append('country_code', this.citys.country_code);
      citys.append('currency_symbol', this.citys.currency_symbol);
      citys.append('currency_code', this.citys.currency_code);
      this.$http
        .post("/admin/save_country", this.citys)
        .then(
          response => {
            if (response.data.status == true) {

              this.$router.push({ name: "country_list" })
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
          }



        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

  },

}
</script>

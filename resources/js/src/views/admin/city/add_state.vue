<template>
  <b-card>
    <b-form @submit.prevent="city_add">
      <b-row>



        <b-col md="6">
          <b-form-group :label="$t('state')">
            <b-form-input id="mc-first-name" :placeholder="$t('state')" v-model="citys.state" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('country')">

            <v-select id="country" v-model="citys.country_id" :options="country" label="country" :reduce="sel => sel.id"
              :placeholder="$t('select country')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              @input="onChange($event)" />


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
  BCard, BRow, BCol, BFormGroup, BFormSelect, BFormInput, BFormCheckbox, BForm, BButton, BSpinner, BTooltip,
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
    BSpinner, BTooltip,
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

    this.$http.get('/admin/country_list')
      .then(res => {

        this.country = res.data

      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_state/' + this.$route.params.id)
        .then(res => {
          this.citys = res.data.message;


        })
    }

  },
  methods: {
    city_add() {
      let citys = new FormData();
      citys.append('id', this.citys.id);
      citys.append('country_id', this.citys.country_id);
      citys.append('state', this.citys.state);

      this.$http
        .post("/admin/save_state", this.citys)
        .then(

          response => {
            if (response.data.status == true) {

              this.$router.push({ name: "state_list" })
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

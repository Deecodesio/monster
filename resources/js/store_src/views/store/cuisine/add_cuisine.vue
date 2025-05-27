<template>
  <b-card>
    <b-form @submit.prevent="addcuisine">
      <b-row>
        <b-col md="12">
          <b-form-group :label="$t('cusisine') + ' ' + $t('name')">
            <v-select v-model="cuisine.name" :options="cuisine_list" label="name" :reduce="sel => sel.id"
              :placeholder="$t('select') + ' ' + $t('cusisine')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

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
  BImg, BMedia,
  BTooltip, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BFormSelect, BCardText, BCard, BFormFile,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { required } from '@@validations'
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
    BImg, BMedia,
    BTooltip,

  },
  directives: {
    Ripple,
  },
  data() {
    return {
      url1: null,
      selected: 1,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],

      cuisine: {},
      cuisine_list: [],

    }
  },
  methods: {
    addcuisine() {
      let cuisine2 = new FormData();

      cuisine2.append('res_id', localStorage.id);
      cuisine2.append('id', this.cuisine.name);
      // cuisine2.append('cuisine_name', this.cuisine.name);
      // cuisine2.append('cuisine_image', this.brand.image);
      this.$http
        .post("/store/add_to_restaurant_cuisines", cuisine2)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "cuisines_list" })
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: response.data.message,
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
            } else {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: response.data.message,
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

  },
  created() {

    this.$http.get('/store/cuisines_list/')
      .then(res => {

        this.cuisine_list = res.data;
      })


    // if (this.$route.params.id) {
    //   this.$http.get('/store/edit_cuisine/' + this.$route.params.id)
    //     .then(res => {
          
    //       this.cuisine.name = res.data.cuisine_id;

    //     })
    // }

  },
}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#cuisine-img {
  width: 15.92rem;
}
</style>

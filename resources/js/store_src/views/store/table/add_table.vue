<template>
  <b-card>
    <b-form @submit.prevent="add_table">
      <b-row>

        <b-col md="12">
          <b-form-group :label="$t('table') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('enter') + ' ' + $t('name')" v-model="table.table_name" />
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
      table: {},

    }
  },

  methods: {
    add_table() {
      this.table.id = localStorage.id;
      this.$http
        .post("/store/add_table", this.table)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "table_list" })
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

}
</script>
 
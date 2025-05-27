<template>
  <b-card>
    <b-form @submit.prevent="addBrand">
      <b-row>
        <b-col md="12" v-if="setting.lang.value == 1">

<b-tabs>
    <b-tab active  @click="onTabChange(1)" >
      <template #title>
          <i class="fa fa-language" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">{{ setting.primary.value }}</span>
      </template>
    </b-tab>

    <!-- Tab: Information -->
    <b-tab @click="onTabChange(2)">
      <template #title>
          <i class="fa fa-language" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">{{ setting.secondary.value }}</span>
      </template>
    </b-tab>

  </b-tabs>
</b-col>
<b-col md="12" id="primary" style="display: block;">
          <b-form-group :label="$t('name')">
            <b-form-input id="mc-name" :placeholder="$t('name')" v-model="brand.name" />
          </b-form-group>
        </b-col>
        <b-col md="12" id="secondary" style="display: none;">
          <b-form-group :label="$t('name')">
            <b-form-input id="mc-name" :placeholder="$t('name')" v-model="brand.secondary_name" />
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,
   BFormSelect, BCardText, BCard, BTooltip,BTabs,BTab
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
    BCard,
    BTooltip,BTabs,BTab
  },
  directives: {
    Ripple,
  },
  data() {
    return {

      selected: 1,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],
      brand: {},
      setting:{},
    }
  },
  methods: {

    onTabChange(id) {
        if(id == 1)
        {
          document.getElementById("primary").style.display = 'block';
          document.getElementById("secondary").style.display = 'none';
        }
        else{
          document.getElementById("primary").style.display = 'none';
          document.getElementById("secondary").style.display = 'block';
        }
   
  },
    addBrand() {

      this.$http
        .post("/admin/store-food-quantity", this.brand)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "food_quantity_list" })
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

  },
  created() {
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })

    if (this.$route.params.id) {
      this.$http.get('/admin/edit-food-quantity/' + this.$route.params.id)
        .then(res => {

          this.brand = res.data.message;


        })
    }

  },
}
</script>

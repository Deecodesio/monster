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
<b-col md="6" v-if="!isHidden">
          <b-form-group :label="$t('brand name')">
            <b-form-input id="name" :placeholder="$t('brand name')" v-model="brand.name" />
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden">
          <b-form-group :label="$t('brand name')">
            <b-form-input id="name" :placeholder="$t('brand name')" v-model="brand.secondary_name" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">

            <v-select id="country" v-model="brand.status" :options="options" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :required="!brand.status" />
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
  BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BTooltip, BButton, 
  BFormSelect, BCardText, BCard,BTabs,BTab
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
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
    BCardText,
    BCard,
    vSelect,
    BTooltip,BTabs,BTab

  },
  directives: {
    Ripple,
  },
  data() {
    return {

      selected: 1,
      setting:{},
      isHidden : false,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],
      brand: {},

    }

  },
  methods: {
    onTabChange(id) {

if (id == 1) {
  this.isHidden = false;
}
else {
  this.isHidden = true;
}
  

},
    addBrand() {
      let brand = new FormData();
      brand.append('id', this.brand.id);
      brand.append('status', this.brand.status);
      brand.append('name', this.brand.name?this.brand.name:'');
      brand.append('secondary_name', this.brand.secondary_name?this.brand.secondary_name:'');

      this.$http
        .post("/admin/store_brand", brand)
        .then(
          (response => {
           
            if (response.data.status == true) {
              this.$router.push({ name: "brands_list" })
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
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_brand/' + this.$route.params.id)
        .then(res => {
          
          this.brand = res.data.message;
        })
    }

  },
}
</script>

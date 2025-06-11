<template>
  <b-card>
    <b-form @submit.prevent="add_to_category">


      <b-row>
        <b-col md="12" v-if="setting.lang.value == 1">
          <b-tabs>
            <b-tab active @click="onTabChange(1)">
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
          <b-form-group :label="$t('Carrier Name')">
            <b-form-input :placeholder="$t('Carrier Name')" v-model="carrier.package_name" required />
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden">
          <b-form-group :label="$t('Carrier Name')">
            <b-form-input :placeholder="$t('Carrier Name')" v-model="carrier.secondary_package_name" />
          </b-form-group>
        </b-col>

        <b-col md="6" v-if="!isHidden">
          <b-form-group :label="$t('Max Weight')">
            <b-form-input :placeholder="$t('Description')" v-model="carrier.description" />
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden">
          <b-form-group :label="$t('Max Weight')">
            <b-form-input :placeholder="$t('Description')" v-model="carrier.second_description" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Minimum Price')">
            <b-form-input :placeholder="$t('Minimum Price')" v-model="carrier.start_range" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('status')">

            <v-select v-model="carrier.status" :options="options" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('Carrier image')">
            <b-form-file v-model="carrier.image" placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..." @change="4" accept=".jpg, .png, .jpeg" />
          </b-form-group>
          <b-form-group>
            <b-img :src="url1 ? url1 : carrier.image" rounded fluid id="categorys-img" alt="carrier photo"
              v-if="carrier.image" />
            <b-img src="/no_image.png" id="categorys-img" fluid alt="Scan" v-else />
          </b-form-group>

        </b-col>
        <b-col md="6">

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
  BImg, BMedia, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm,
  BButton, BFormSelect, BCardText, BFormFile, BCard, BTooltip, BTab, BTabs, BAlert, BLink,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'


export default {
  components: {
    BCardCode,
    BRow,
    BCol,
    vSelect,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    BCardText,
    BFormFile,
    BCard,
    BTooltip,
    BImg, BMedia, BTab, BTabs, BAlert, BLink,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      url1: null,
      selected: 1,
      options_category: [],
      setting: {},
      isHidden: false,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },

      ],
      carrier: {},

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
    add_to_category() {

      let data = new FormData();
      data.append('package_name', this.carrier.package_name);
      data.append('secondary_package_name', this.carrier.secondary_package_name ? this.carrier.secondary_package_name : '');
      data.append('description', this.carrier.description);
      data.append('second_description', this.carrier.second_description);
      data.append('start_range', this.carrier.start_range);
      data.append('image', this.carrier.image);
      data.append('status', this.carrier.status);

      if (this.$route.params.id) {
        data.append('id', this.carrier.id);
      }

      this.$http
        .post("/admin/add_to_carrier", data)
        .then(
          (response => {

            this.$router.push({ name: "carrier_list" })

          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

    4(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
    },
  },
  created() {

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_carrier/' + this.$route.params.id)
        .then(res => {
          this.carrier = res.data;
        })
    }
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
  },
}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#categorys-img {
  width: 10rem;
}
</style>

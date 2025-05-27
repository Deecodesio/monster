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
        <b-col md="6" id="primary" style="display: block;">
          <b-form-group :label="$t('sub category name')">
            <b-form-input id="mc-category_name" :placeholder="$t('sub category name')" v-model="category.category_name"
              required />
          </b-form-group>
        </b-col>
        <b-col md="6" id="secondary" style="display: none;">
          <b-form-group :label="$t('sub category name')">
            <b-form-input id="mc-category_name" :placeholder="$t('sub category name')"
              v-model="category.category_secondaryname" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">

            <v-select v-model="category.status" :options="options" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('category')">

            <v-select v-model="category.business_category_id" :options="options_category" label="category_name"
              :reduce="sel => sel.id" :placeholder="$t('category name')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('sub category image')">
            <b-form-file v-model="category.image" placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..." @change="onFileChange1" accept=".jpg, .png, .jpeg" />
          </b-form-group>
          <b-form-group>
            <b-img :src="url1 ? url1 : category.image" rounded fluid id="categorys-img" alt="category photo"
              v-if="category.image" />
            <b-img src="/no_image.png" id="categorys-img" fluid alt="Scan" v-else />
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
import BCardCode from '@@core/components/b-card-code'
import {
  BImg, BMedia, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,
  BFormSelect, BCardText, BFormFile, BCard, BTooltip, BTab, BTabs, BAlert, BLink,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@@core/components/toastification/ToastificationContent.vue'
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
      options: [
        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },

      ],
      category: {},
      setting: {},

    }
  },
  methods: {
    onTabChange(id) {
      if (id == 1) {
        document.getElementById("primary").style.display = 'block';
        document.getElementById("secondary").style.display = 'none';
      }
      else {
        document.getElementById("primary").style.display = 'none';
        document.getElementById("secondary").style.display = 'block';
      }

    },
    add_to_category() {

      let data = new FormData();
      data.append('business_category_id', this.category.business_category_id);
      data.append('category_name', this.category.category_name ? this.category.category_name : '');
      data.append('category_secondaryname', this.category.category_secondaryname ? this.category.category_secondaryname : '');
      data.append('restaurant_id', localStorage.id);


      data.append('status', this.category.status);
      data.append('category_image', this.category.image);
      if (this.$route.params.id) {
        data.append('id', this.category.id);

      }
      this.$http
        .post("/store/add_to_category", data)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "category_list" })
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

    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
    },
  },
  created() {
    this.$http.get('/store/business_category_lists/' + localStorage.id)
      .then(res => {
        this.options_category = res.data;
        this.Loading = false;

      })
    this.$http.get('/store/get_lang')
      .then(res => {

        this.setting = res.data
      })
    if (this.$route.params.id) {
      this.$http.get('/store/edit_category/' + this.$route.params.id)
        .then(res => {
          this.category = res.data.message;
        })
    }
  },
}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#categorys-img {
  width: 10rem;
}
</style>
  
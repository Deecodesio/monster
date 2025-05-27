<template>
    <b-card>
      <b-form @submit.prevent="add_to_category">
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
     <b-col md="6" id="primary" style="display: block;">
       <b-form-group :label="$t('Package Name')">
              <b-form-input  :placeholder="$t('Package Name')" v-model="package.name" />
            </b-form-group>
        </b-col>
        <b-col md="6" id="secondary" style="display: none;">
          <b-form-group :label="$t('Package Name')">
              <b-form-input  :placeholder="$t('Package Name')" v-model="package.secondary_name" />
            </b-form-group>
        </b-col>
       
          <b-col md="6">
          <b-form-group :label="$t('status')">

            <v-select v-model="package.status" :options="options" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
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
    BImg, BMedia, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm,
     BButton, BFormSelect, BCardText, BFormFile, BCard, BTooltip,BTabs,BTab
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
      BImg, BMedia,BTabs,BTab
    },
    directives: {
      Ripple,
    },
    data() {
      return {
        url1: null,
        selected: 1,
        options_category:[],
        options: [
          { value: 1, text: this.$t('active') },
          { value: 0, text: this.$t('inactive') },
  
        ],
        package: {},
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
      add_to_category() {
  
        let data = new FormData();
         data.append('name', this.package.name);
         data.append('secondary_name', this.package.secondary_name);
         data.append('status', this.package.status);
      
        if (this.$route.params.id) {
          data.append('id', this.package.id);
        }

        this.$http
          .post("/admin/add_to_pacakge", data)
          .then(
            (response => {
  
                this.$router.push({ name: "package_list" })
               
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
      this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
      if (this.$route.params.id) {
        this.$http.get('/admin/edit_pacakge/' + this.$route.params.id)
          .then(res => {
            this.package = res.data;
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

<template>
  <b-card>
    <b-form @submit.prevent="document_add">
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
        <b-col md="6">
          <b-form-group :label="$t('Business Type') ">
            <!-- <b-form-select
        v-model="document.document_for"
        :options="options"
      /> -->
            <v-select v-model="document.layout_id" :options="services" label="layout_name"
              :reduce="sel => sel.id" :placeholder="$t('Business Type') "
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('document') + ' ' + $t('for')">
            <!-- <b-form-select
        v-model="document.document_for"
        :options="options"
      /> -->
            <v-select id="country" v-model="document.document_for" :options="options" label="text"
              :reduce="sel => sel.value" :placeholder="$t('document') + ' ' + $t('for')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>
        <b-col md="6"  v-if="!isHidden">
          <b-form-group :label="$t('document') + ' ' + $t('name')">
            <b-form-input id="mc-last-name" :placeholder="$t('document') + ' ' + $t('name')"
              v-model="document.document_name" />
          </b-form-group>
        </b-col>
        <b-col md="6"  v-if="isHidden">
          <b-form-group :label="$t('document') + ' ' + $t('name')">
            <b-form-input id="mc-last-name" :placeholder="$t('document') + ' ' + $t('name')"
              v-model="document.secondary_document_name" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">
            <!-- <b-form-select
        v-model="document.status"
        :options="options_status"
      /> -->
            <v-select id="country" v-model="document.status" :options="options_status" label="text"
              :reduce="sel => sel.value" :placeholder="$t('select status')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('expiry') + ' ' + $t('date') + ' ' + $t('needed')">
            <b-form-radio-group v-model="document.expiry_date_needed" :options="options_radio" name="radios-stacked" />
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
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormGroup, BFormInput, BFormCheckbox,
   BForm, BButton, BCard,BTab, BTabs,
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
    BFormRadioGroup,
    BCard,
    vSelect,BTab, BTabs,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      document: {},
      selected: null,
      options: [
        { value: "1", text: this.$t('driver') },
        { value: "2", text: this.$t('Store') },
      ],
      selected_status: null,
      options_status: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],
      selected_radio: 'No',
      options_radio: [
        { text: this.$t('yes'), value: 1 },
        { text: this.$t('no'), value: 2 },

      ],
      services:[],
      setting:{},
        isHidden: false,
    }
  },
  created() {
    this.$http.get('/admin/layout')
            .then(res => {
            
             this.services=res.data;
        })
        this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_document/' + this.$route.params.id)
        .then(res => {

          this.document = res.data.message;



        })
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
    document_add() {
      if (this.$route.params.id) {
        this.document.id = this.$route.params.id
      }
      console.log(this.document.id);
      this.$http
        .post("/admin/document_add", this.document)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "document_list" })
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
}
</script>

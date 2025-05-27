<template>
  <b-card>
    <b-form @submit.prevent="add_banner">
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
          <b-form-group :label="$t('role')">
            <v-select v-model="banner.role_id" :options="this.roles" label="role_name" :reduce="sel => sel.id"
              :placeholder="$t('select') + ' ' + $t('role')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" @input="onChange($event)" />


          </b-form-group>
        </b-col>
       
        <b-col md="6" v-if="!isHidden">
          <b-form-group :label="$t('User') + ' ' + $t('name') + '*'">
            <b-form-input id="mc-last-name" :placeholder="$t('User') + ' ' + $t('name')" v-model="banner.name" />
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden">
          <b-form-group :label="$t('User') + ' ' + $t('name') + '*'">
            <b-form-input id="mc-last-name" :placeholder="$t('User') + ' ' + $t('name')" v-model="banner.second_admin_name" />
          </b-form-group>
        </b-col>

       

        <b-col md="6" v-if="!Hidden">
          <b-form-group :label="$t('Store')">
            <v-select v-model="banner.restaurant_id" :options="this.rows" label="restaurant_name"
              :reduce="sel => sel.id" :placeholder="$t('Store') + ' ' + $t('name')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" multiple/>
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('email') + '*'">

            <b-form-input id="email" v-model="banner.email" type="email" placeholder="john.doe@email.com" />


          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('password')">

            <b-form-input id="password" v-model="banner.password"  :placeholder="$t('password')" />


          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select v-model="banner.status" :options="options_status" label="text" :reduce="sel => sel.value"
              :placeholder="$t('status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>

        <b-col md="6" v-if="Hidden">
         
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
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormFile, BTooltip, BFormGroup, BFormInput,
   BFormCheckbox, BForm, BButton, BCard,BTabs, BTab,
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
    BFormFile,
    BCard,
    vSelect,
    BTooltip,BTabs, BTab,
  },
  created() {
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })

    this.$http.get('/admin/res_list')
      .then(res => {

        this.rows = res.data

      })


    this.$http.get('/admin/city_list')
      .then(res => {

        this.cities = res.data

      })


    this.$http.get('/admin/get_roles')
      .then(res => {

        this.roles = res.data

      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_admin/' + this.$route.params.id)
        .then(res => {

          this.$http.get('/admin/get_role_id/'+res.data.message.role_id)
          .then(res => {

            if(res.data[0].type == 1)
            {
             this.Hidden = true
            }
            else{
              this.Hidden = false
            }
          })
          this.banner = res.data.message;


        })
    }

  },
  directives: {
    Ripple,
  },
  data() {
    return {
      banner: {},
      cities: [],
      rows: [],
      roles: [],
      setting:{},
      isHidden: false,
      Hidden:true,
      selected_status: null,
      options_status: [

        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],

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
    onChange(event)
    {
      console.log(event);
      this.$http.get('/admin/get_role_id/'+event)
          .then(res => {
console.log(res.data.type);
            if(res.data[0].type == 1)
            {
             this.Hidden = true
            }
            else{
              this.Hidden = false
            }
       
          // this.state = res.data
        
          })
    },
    add_banner() {
      this.banner.admin_name = this.banner.name
      this.banner.second_admin_name = this.banner.second_admin_name
      this.banner.role_type = this.banner.role_id
      if (this.$route.params.id) {
        this.banner.res_id = this.$route.params.id
      }

      this.$http
        .post("/admin/add_admin", this.banner)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "show_admins" })
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
    popToast(response) {

      const h = this.$createElement
      const vNodesMsg = h('p', { class: ['text-center', 'mb-0'] }, [
        response.data.message
      ])
      this.$bvToast.toast([vNodesMsg], {
        solid: false,
        variant: 'info',
      })
    },
  },
}
</script>

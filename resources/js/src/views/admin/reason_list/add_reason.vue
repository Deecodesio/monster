<template>
  <b-card>
    <b-form @submit.prevent="reason_add">
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
          <b-form-group :label="$t('reason')">

            <b-form-input :placeholder="$t('reason')" v-model="reason.reason" />
          </b-form-group>
        </b-col>
        <b-col md="6" id="secondary" style="display: none;">
          <b-form-group :label="$t('reason')">

            <b-form-input :placeholder="$t('reason')" v-model="reason.secondary_reason" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('cancellation') + ' ' + $t('for')">

            <v-select v-model="reason.cancellation_for" :options="options" label="text" :reduce="sel => sel.value"
              :placeholder="$t('cancellation') + ' ' + $t('for')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">


            <v-select v-model="reason.status" :options="options_status" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>





      </b-row>
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
          {{ $t('save') }}
        </b-button>

      </b-col>

    </b-form>


  </b-card>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormGroup, BFormInput, BFormCheckbox,
   BForm, BButton, BCard,BTabs,BTab
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
    vSelect,BTabs,BTab
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      reason: {},
      setting:{},
      selected: null,
      options: [
        { value: 1, text: this.$t('Store') },
        { value: 2, text: this.$t('User') },
      ],
      selected_status: null,
      options_status: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],

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
    reason_add() {

      this.$http
        .post("/admin/add_reason", this.reason)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "reason_list" })
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
  created() {

    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_reason/' + this.$route.params.id)
        .then(res => {

          this.reason = res.data.message;
          this.reason.cancellation_for = parseInt(res.data.message.cancellation_for);

        })
    }

  },
}
</script>

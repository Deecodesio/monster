<template>
  <b-card>
    <b-form @submit.prevent="city_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('city')">
            <b-form-input id="searchMadminnput" :placeholder="$t('city')" v-model="citys.city" />

          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select v-model="citys.status" :options="options_status" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />



          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('country')">

            <v-select id="country" v-model="citys.country" :options="country" label="country" :reduce="sel => sel.id"
              :placeholder="$t('select country')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              @input="onChange($event)">
              <template #list-header>
                <li @click="open_countrymodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New Country') }}</span>
                </li>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('state')">

            <v-select id="country" v-model="citys.state" :options="this.state" label="state" :reduce="sel => sel.id"
              :placeholder="$t('select state')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" >
              <template #list-header>
                <li @click="open_statemodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New State') }}</span>
                </li>
              </template>
            
            </v-select>

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
    <b-modal id="modal-center" centered :title="$t('Add New State')" ok-only :ok-title="$t('Save')" ref="state-modal"
      @hidden="resetsModal" @show="resetsModal" @ok="state_add">
      <b-row>



        <b-col md="6">
          <b-form-group :label="$t('state')">
            <b-form-input id="mc-first-name" :placeholder="$t('state')" v-model="scitys.state" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('country')">

            <v-select id="country" v-model="scitys.country_id" :options="country" label="country" :reduce="sel => sel.id"
              :placeholder="$t('select country')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              @input="onChange($event)" >
            
              <template #list-header>
                <li @click="open_countrymodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New Country') }}</span>
                </li>
              </template>
            </v-select>


          </b-form-group>
        </b-col>

      </b-row>
    </b-modal>
    <b-modal id="modal-center" centered :title="$t('Add New Country')" ok-only :ok-title="$t('Save')" ref="country-modal"
      @hidden="resetcModal" @show="resetcModal" @ok="country_add">
      <b-row>



        <b-col md="6">
          <b-form-group :label="$t('country')">
            <b-form-input id="mc-first-name" :placeholder="$t('country')" v-model="acitys.country" />

          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('country code')">
            <b-form-input id="mc-first-name" :placeholder="$t('country code')" v-model="acitys.country_code" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('currency code')">
            <b-form-input id="mc-first-name" :placeholder="$t('currency code')" v-model="acitys.currency_code" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('currency symbol')">
            <b-form-input id="mc-first-name" :placeholder="$t('currency symbol')" v-model="acitys.currency_symbol" />
          </b-form-group>
        </b-col>


      </b-row>
    </b-modal>

  </b-card>
</template>

<script>
// import BCardCode from '@core/components/b-card-code'
import {
  BCard, BRow, BCol, BFormGroup, BFormSelect, BFormInput, BFormCheckbox, BForm, BButton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    // BCardCode,
    BCard,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    vSelect,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      acitys: {},
      scitys: {},

      citys: {},
      country: [],
      state: [],
      selected_status: null,
      options_status: [
        { value: "1", text: this.$t('active') },
        { value: "2", text: this.$t('inactive') },
      ],
    }
  },
  created() {

    this.$http.get('/admin/country_list')
      .then(res => {

        this.country = res.data

      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_city/' + this.$route.params.id)
        .then(res => {
          this.citys = res.data.message;
          this.citys.country = res.data.message.country_id;

          this.$http.post('/admin/state_list_by_country', this.citys)
            .then(res => {

              this.state = res.data


            })

          this.citys.state = res.data.message.state_id;
        })
    }

  },
  mounted() {
    this.initMap()
    this.initTrHeight()
    this.importAll(require.context('@/assets/images/banner/', true, /\.jpg$/));
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },

  watch: {
    locale(val) {
      this.$i18n.locale = val
    },
    checkedNames: {
      deep: true,
      handler: function (newValue) {
        console.log("Selected users changed", newValue)
      }
    },
  },
  methods: {
    resetcModal(){
      this.acitys = {}
    },
    resetsModal(){
      this.scitys = {}
    },
    open_countrymodal() {
      this.$refs["country-modal"].show();
    },
    open_statemodal() {
      this.$refs["state-modal"].show();
    },
    state_add() {
      let citys = new FormData();
      var cid =  this.scitys.country_id
      citys.append('country_id', this.scitys.country_id);
      citys.append('state', this.scitys.state);

      this.$http
        .post("/admin/save_state", citys)
        .then(

          response => {
            if (response.data.status == true) {
              console.log(cid)
              let sscitys = new FormData();
              sscitys.append('country', cid);
              this.$http.post('/admin/state_list_by_country', sscitys)
              .then(res => {

                this.state = res.data;
                if (this.state.length > 0) {
                  this.citys.state = this.state[0].id;
                }
                else {
                  this.citys.state = '';
                }

              })
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
          }

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    country_add() {
      let citys = new FormData();
      citys.append('country', this.acitys.country);
      citys.append('country_code', this.acitys.country_code);
      citys.append('currency_symbol', this.acitys.currency_symbol);
      citys.append('currency_code', this.acitys.currency_code);
      this.$http
        .post("/admin/save_country", citys)
        .then(
          response => {
            if (response.data.status == true) {

              this.$http.get('/admin/country_list')
                .then(res => {

                  this.country = res.data
                  this.$refs["country-modal"].hide();
                  this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: this.$t(response.data.message),
                      icon: 'CheckIcon',
                      variant: 'primary',
                    },
                  })
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
          }



        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    city_add() {

      this.$http
        .post("/admin/city_add", this.citys)
        .then(
          response => {
            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
              this.$router.push({ name: "city_list" })

            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }

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


    onChange(event) {

      this.$http.post('/admin/state_list_by_country', this.citys)
        .then(res => {

          this.state = res.data;
          if (this.state.length > 0) {
            this.citys.state = this.state[0].id;
          }
          else {
            this.citys.state = '';
          }

        })

    }
  },

}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';
.add-new-client-header {
    padding: $options-padding-y $options-padding-x;
    color: $success;

    cursor: pointer;
}
</style>

<template>
  <b-card-code>
    <b-form @submit.prevent="promocode_add">
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
        <b-col md="6" v-if="!isHidden_lang">
          <b-form-group :label="$t('titles') + '*'">
            <validation-provider #default="{ errors }" :name="$t('titles')"
              :custom-messages="{ required: $t('The Title field is required') }" rules="required">


              <b-form-input :placeholder="$t('titles')" v-model="promocode.title" />
              <b-form-input hidden="hidden" v-model="promocode.restaurant_id" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden_lang">
          <b-form-group :label="$t('titles') + '*'">
            <b-form-input :placeholder="$t('titles')" v-model="promocode.second_title" />
            <b-form-input hidden="hidden" v-model="promocode.restaurant_id" />

          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-form-group :label="$t('Store')">
            <v-select v-model="promocode.restaurant_id" :options="this.res_list" label="restaurant_name"
              :reduce="sel => sel.id" :placeholder="$t('Store') + ' ' + $t('name')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>


        <b-col md="6" v-if="!isHidden_lang">
          <b-form-group :label="$t('description') + '*'">

            <validation-provider #default="{ errors }" :name="$t('description')"
              :custom-messages="{ required: $t('The Description field is required') }" rules="required">
              <b-form-input id="mc-last-name" :placeholder="$t('description')" v-model="promocode.description" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden_lang">
          <b-form-group :label="$t('description') + '*'">


            <b-form-input id="mc-last-name" :placeholder="$t('description')" v-model="promocode.second_description" />

          </b-form-group>
        </b-col>
        <b-col md="6" v-if="!isHidden_lang">
          <b-form-group :label="$t('promocode') + '*'">
            <validation-provider #default="{ errors }" :name="$t('promocode')"
              :custom-messages="{ required: $t('The Promocode field is required') }" rules="required">
              <b-form-input id="mc-last-name" :placeholder="$t('promocode')" v-model="promocode.code"
                style="text-transform:uppercase" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden_lang">
          <b-form-group :label="$t('promocode') + '*'">

            <b-form-input id="mc-last-name" :placeholder="$t('promocode')" v-model="promocode.second_code"
              style="text-transform:uppercase" />

          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('Offer') + ' ' + $t('type') + '*'">
            <v-select v-model="promocode.offer_type" :options="options_amount" label="text" :reduce="sel => sel.value"
              :placeholder="$t('Offer') + ' ' + $t('type')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              @input="isHiddens" id="offer_type" />


          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('maximum') + ' ' + $t('amount') + '*'">
            <validation-provider #default="{ errors }" :name="$t('maximum') + ' ' + $t('amount')" rules="required">
              <b-form-input type="number" id="Maximum_amount" :placeholder="$t('maximum') + ' ' + $t('amount')"
                v-model="promocode.max_amount" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('minimum') + ' ' + $t('order') + ' ' + $t('value') + '*'">
            <validation-provider #default="{ errors }" :name="$t('minimum') + ' ' + $t('order') + ' ' + $t('value')"
              rules="required">
              <b-form-input type="number" id="Minimum_Order_value"
                :placeholder="$t('minimum') + ' ' + $t('order') + ' ' + $t('value')" v-model="promocode.min_amount" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('discount') + '(%) *'" v-if="isHidden">
            <validation-provider #default="{ errors }" :name="$t('discount')" rules="required">
              <b-form-input placeholder="0-100" v-model="promocode.value" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
          <b-form-group :label="$t('discount') + '(amount) *'" v-else>
            <validation-provider #default="{ errors }" :name="$t('discount')" rules="required">
              <b-form-input placeholder="0-100" v-model="promocode.value" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('usage') + ' ' + $t('limit') + ' ' + $t('per') + ' ' + $t('coupon') + '*'">
            <validation-provider #default="{ errors }"
              :name="$t('usage') + ' ' + $t('limit') + ' ' + $t('per') + ' ' + $t('coupon')" rules="required">
              <b-form-input id="mc-last-name" :placeholder="$t('enter') + ' ' + $t('per') + ' ' + $t('coupon')"
                v-model="promocode.total_use" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('usage') + ' ' + $t('limit') + ' ' + $t('per') + ' ' + $t('User') + '*'">
            <validation-provider #default="{ errors }"
              :name="$t('usage') + ' ' + $t('limit') + ' ' + $t('per') + ' ' + $t('User')" rules="required">
              <b-form-input id="mc-last-name" :placeholder="$t('enter') + ' ' + $t('per') + ' ' + $t('User')"
                v-model="promocode.use_per_customer" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('available') + ' ' + $t('from') + '*'">
            <validation-provider #default="{ errors }" :name="$t('available') + ' ' + $t('from')" rules="required">
              <b-form-datepicker id="example-datepicker" v-model="promocode.available_from" class="mb-1"
                :min="new Date()" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('expiry') + ' ' + $t('date') + ' *'">
            <validation-provider #default="{ errors }" :name="$t('expiry') + ' ' + $t('date')" rules="required">
              <b-form-datepicker id="example-datepicker1" v-model="promocode.valid_till" class="mb-1"
                :min="promocode.available_from" />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>

          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select v-model="promocode.status" :options="options_status" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>


        <b-col md="6" style="display: none;">

          <b-form-checkbox class="custom-control-success" name="check-button" switch v-model="promocode.selected_l"
            id="switch">
            <h5>{{ $t('Code Required') }}</h5>
          </b-form-checkbox>

        </b-col>
        <b-col md="6"></b-col>
        <b-col md="6">
          <h4 class="pt-3">{{ $t('Coupon Availability') }}</h4>
        </b-col>
        <b-col md=12>
          <b-form-group>
            <b-form ref="form" :style="{ height: trHeight }" class="repeater-form" @submit.prevent="repeateAgain">

              <!-- Row Loop -->
              <b-row v-for="(item, index) in items" :id="item.id" :key="item.id" ref="row">

                <!-- Item Name -->
                <b-col md="4">
                  <b-form-group :label="$t('from')" label-for="item-name">
                    <b-form-timepicker v-model="item.from_time" :name="'from' + item.id" />
                    <b-form-input :id="'from' + item.id" hidden="hidden" :value=item.from_time :key="'from' + item.id" />
                  </b-form-group>
                </b-col>

                <!-- Cost -->
                <b-col md="4">
                  <b-form-group :label="$t('to')" label-for="cost">
                    <b-form-timepicker v-model="item.to_time" :name="'to' + item.id" />

                    <b-form-input :id="'to' + item.id" hidden="hidden" :value=item.to_time :key="'to' + item.id" />
                  </b-form-group>

                </b-col>

                <!-- Remove Button -->
                <b-col lg="2" md="3" class="mb-50">
                  <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="outline-danger" class="mt-0 mt-md-2"
                    @click="removeItem(index)">
                    <feather-icon icon="XIcon" class="mr-25" />
                    <span>{{ $t('delete') }}</span>
                  </b-button>
                </b-col>
                <b-col cols="12">
                  <hr>
                </b-col>

              </b-row>

            </b-form>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain">
              <feather-icon icon="PlusIcon" class="mr-25" />
              <span>{{ $t('add') }} {{ $t('new') }} {{ $t('Time Slot') }}</span>
            </b-button>
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('Days') + '*'" />
          <b-row>
            <b-col md="12">
              <b-row>

                <b-col md="12">
                  <b-form-group>
                    <b-form-checkbox id="All_day" @change="showDate" name="flavour-3a">{{ $t('All') }}</b-form-checkbox>
                  </b-form-group>
                </b-col>


                <b-col md="6" id="days">
                  <b-form-group>
                    <b-form-checkbox @change="changeMethod" v-model="Day" v-for="option in options_check"
                      :key="option.value" name="days" :id="'day_' + option.value" :value="option.value"
                      style="margin-bottom: 3%;">{{ option.text
                      }}</b-form-checkbox>

                  </b-form-group>
                </b-col>

              </b-row>


            </b-col>

          </b-row>

        </b-col>



      </b-row>
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
          {{ $t('save') }}
        </b-button>

      </b-col>

    </b-form>


  </b-card-code>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormDatepicker,
  BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BCard, BFormTimepicker, BTabs, BTab,


} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email } from '@validations'
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
    BFormDatepicker,
    ValidationProvider,
    ValidationObserver,
    vSelect, BCard, BFormTimepicker, BTabs, BTab,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      promocode: {},
      rows: [],
      selected_status: null,
      options_status: [
        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },
      ],
      selected_amount: null,
      options_amount: [
        { value: 1, text: this.$t('amount') },
        { value: 0, text: this.$t('percent') },
      ],
      setting: {},
      isHidden_lang: false,
      isHidden: true,
      selected_l: true,
      items: [{
        id: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      nextTodoId: 2,
      datamenu: {},
      Day: [],
      options_check: [
        { text: this.$t('Monday'), value: 1 },
        { text: this.$t('Tuesday'), value: 2 },
        { text: this.$t('Wednesday'), value: 3 },
        { text: this.$t('Thursday'), value: 4 },
        { text: this.$t('Friday'), value: 5 },
        { text: this.$t('Saturday'), value: 6 },
        { text: this.$t('Sunday'), value: 7 },
      ],
      res_list: [],

    }
  },
  methods: {

    changeMethod() {

      this.promocode.sevendays = this.Day

    },
    showDate() {
      var checkBox = document.getElementById("All_day");
      var text = document.getElementById("days");
      if (checkBox.checked == true) {
        text.style.display = "none";
        this.promocode.days = 1;
      } else {
        text.style.display = "block";
        this.promocode.days = "NULL";
      }
      // alert(this.menu.days);
    },
    repeateAgain() {
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,

      })
      // alert( this.nextTodoId)
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      // alert(index);
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },

    onTabChange(id) {

      if (id == 1) {
        this.isHidden_lang = false;
      }
      else {
        this.isHidden_lang = true;
      }


    },
    promocode_add() {

      let promo = new FormData();
      if (this.$route.params.id) {
        promo.append('id', this.$route.params.id);
      }
      promo.append('promo', this.promocode.name ? this.promocode.name : '');
      promo.append('title', this.promocode.title ? this.promocode.title : '');
      promo.append('description', this.promocode.description ? this.promocode.description : '');
      promo.append('code', this.promocode.code);
      promo.append('offer_type', this.promocode.offer_type);
      promo.append('max_amount', this.promocode.max_amount ? this.promocode.max_amount : '');
      promo.append('min_amount', this.promocode.min_amount ? this.promocode.min_amount : '');
      promo.append('value', this.promocode.value);
      promo.append('total_use', this.promocode.total_use ? this.promocode.total_use : '');
      promo.append('use_per_customer', this.promocode.use_per_customer ? this.promocode.use_per_customer : '');
      promo.append('available_from', this.promocode.available_from ? this.promocode.available_from : '');
      promo.append('valid_till', this.promocode.valid_till ? this.promocode.valid_till : '');
      promo.append('status', this.promocode.status);
      promo.append('selected_l', this.promocode.selected_l ? this.promocode.selected_l : '');
      promo.append('days', this.promocode.days ? this.promocode.days : '');
      promo.append('second_title', this.promocode.second_title ? this.promocode.second_title : '');
      promo.append('second_description', this.promocode.second_description ? this.promocode.second_description : '');
      promo.append('second_code', this.promocode.second_code ? this.promocode.second_code : '');
      promo.append('restaurant_id', this.promocode.restaurant_id);

      let checkboxes = document.querySelectorAll('input[name="days"]:checked');

      let values = [];
      checkboxes.forEach((checkbox) => {
        values.push(checkbox.value);
      });

      promo.append('sevendays', JSON.stringify(values));
      this.items.forEach(function (number) {


        promo.append('from[]', document.getElementById('from' + number.id).value);
        promo.append('to[]', document.getElementById('to' + number.id).value);

      });
      this.$http
        .post("/admin/add_to_promocode", promo)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "promocode_list" })
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
          //  console.log(response.data)
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    isHiddens(value) {

      if (value == 1) {
        this.isHidden = false;
      }
      else {
        this.isHidden = true;
      }
    }

  },
  created() {
    this.$http.get('/admin/res_list')
      .then(res => {

        this.res_list = res.data

      })
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_coupon/' + this.$route.params.id)
        .then(res => {
          console.log(res)
          this.promocode = res.data.coupon;
          this.datamenu = res.data.coupon_timing;

          this.items = this.datamenu;
          if (res.data.coupon.code_required == 1) {
            this.promocode.selected_l = true
            document.getElementById("switch").checked = true

          } else {
            this.promocode.selected_l = false
            document.getElementById("switch").checked = false
          }
          if (res.data.coupon.offer_type === 1) {
            this.isHidden = false;
          }
          var checkBox = document.getElementById("All_day");
          var text = document.getElementById("days");
          if (this.datamenu[0].all_days != 1) {

            if (res.data.day.length > 0) {
              console.log(res.data.day)

              res.data.day.forEach(function (element) {

                document.getElementById('day_' + element).checked = true;
              });
            }
            text.style.display = "block";
            this.promocode.days = "NULL";
          }
          else {

            document.getElementById('All_day').checked = true;
            text.style.display = "none";
            this.promocode.days = 1;
          }
          this.datamenu.forEach(function (element, index) {


            document.getElementsByName('from' + element.id).innerHTML = element.from_time;
            document.getElementsByName('to' + element.id).innerHTML = element.to_time;


          });



        })
    }

  },

}
</script>

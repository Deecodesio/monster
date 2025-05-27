<template>
  <b-card>
    <b-form @submit.prevent="vehicle_add">
      <b-row>

        <b-col md="6">
          <b-form-group :label="$t('Carrier') + ' ' + $t('Type')">
            <v-select id="country" v-model="vehicle.carrier_type" :options="options_carrier" label="package_name"
              :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('Carrier')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('vehicle') + ' ' + $t('name')">
            <b-form-input id="mc-first-name" :placeholder="$t('vehicle') + ' ' + $t('name')"
              v-model="vehicle.vehicle_name" />
          </b-form-group>
        </b-col>



        <b-col md="6">
          <b-form-group :label="$t('vehicle') + ' ' + $t('no')">
            <b-form-input id="mc-city" :placeholder="$t('vehicle') + ' ' + $t('no')" v-model="vehicle.vehicle_no" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('status')">

            <!-- <b-form-select
        v-model="vehicle.status"
        :options="options_status"
      /> -->
            <v-select id="country" v-model="vehicle.status" :options="options_status" label="text"
              :reduce="sel => sel.value" :placeholder="$t('select') + ' ' + $t('status')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('insurance') + ' ' + $t('no')">
            <b-form-input id="mc-country" :placeholder="$t('insurance') + ' ' + $t('no')" v-model="vehicle.insurance_no" />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group :label="$t('RC') + ' ' + $t('no')">
            <b-form-input id="mc-first-name" :placeholder="$t('RC') + ' ' + $t('no')" v-model="vehicle.rc_no" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('insurance') + ' ' + $t('expiry') + ' ' + $t('date')">

            <b-form-datepicker id="mc-insurance_expiry_date" v-model="vehicle.insurance_expiry_date" class="mb-1"
              :min="new Date()" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('RC') + ' ' + $t('expiry') + ' ' + $t('date')">
            <b-form-datepicker id="mc-rc_expiry_date" :placeholder="$t('RC') + ' ' + $t('expiry') + ' ' + $t('date')"
              v-model="vehicle.rc_expiry_date" :min="new Date()" />
          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-row>
            <b-col md="3">

              <b-form-group>
                <b-img :src="url3 ? url3 : vehicle.vehicle_image" rounded fluid id="vehicle-img" alt="vehicle photo"
                  v-if="vehicle.vehicle_image" />
                <b-img src="/no_image.png" id="vehicle-img" fluid alt="Scan" v-else />
              </b-form-group>

            </b-col>
            <b-col md="9">
              <b-form-group :label="$t('vehicle') + ' ' + $t('Image')">

                <b-form-file v-model="vehicle.vehicle_image" placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..." @change="onFileChange3" accept=".jpg, .png, .jpeg" />

              </b-form-group>

            </b-col>

          </b-row>

        </b-col>

        <b-col md="6">
          <b-row>
            <b-col md="3">
              <b-form-group>
                <b-img :src="url1 ? url1 : vehicle.insurance_image" rounded fluid id="vehicle-img" alt="insurance photo"
                  v-if="vehicle.insurance_image" />
                <b-img src="/no_image.png" id="vehicle-img" fluid alt="Scan" v-else />
              </b-form-group>

            </b-col>
            <b-col md="9">
              <b-form-group :label="$t('insurance') + ' ' + $t('Image')">
                <!--            
         <b-form-file
        v-model="vehicle.insurance_image"
        class="mt-1"
        plain
          
      /> -->
                <b-form-file v-model="vehicle.insurance_image" placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..." @change="onFileChange1" accept=".jpg, .png, .jpeg" />
              </b-form-group>

            </b-col>

          </b-row>

        </b-col>





        <b-col md="6">
          <b-row>
            <b-col md="3">
              <b-form-group>
                <b-img :src="url2 ? url2 : vehicle.rc_image" rounded fluid id="vehicle-img" alt="rc_image photo"
                  v-if="vehicle.rc_image" />
                <b-img src="/no_image.png" id="vehicle-img" fluid alt="Scan" v-else />
              </b-form-group>

            </b-col>
            <b-col md="9">
              <b-form-group :label="$t('RC') + ' ' + $t('Image')">

                <!-- <b-form-file
        v-model="vehicle.rc_image"
        class="mt-1"
        plain
          
      /> -->
                <b-form-file v-model="vehicle.rc_image" placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..." @change="onFileChange2" accept=".jpg, .png, .jpeg" />
              </b-form-group>

            </b-col>

           
          </b-row>

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
  BImg, BMedia, BCard, BRow, BCol, BFormSelect, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BTooltip, BFormFile, BCardText, BFormDatepicker,
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
    BFormFile,
    BCardText,
    BFormSelect,
    BFormDatepicker,
    BCard,
    vSelect,
    BImg, BMedia,
    BTooltip,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      vehicle: {},
      url1: null,
      url2: null,
      url3: null,
      selected_status: null,
      options_status: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],
      options_carrier:[],
    }
  },
  methods: {

    vehicle_add() {
      let data = new FormData();
      data.append('id', this.vehicle.id);
      data.append('vehicle_name', this.vehicle.vehicle_name?this.vehicle.vehicle_name:'');
      data.append('status', this.vehicle.status?this.vehicle.status:'');
      data.append('vehicle_no', this.vehicle.vehicle_no?this.vehicle.vehicle_no:'');
      data.append('insurance_no', this.vehicle.insurance_no? this.vehicle.insurance_no:'');
      data.append('rc_no', this.vehicle.rc_no?this.vehicle.rc_no:'');
      data.append('insurance_expiry_date', this.vehicle.insurance_expiry_date?this.vehicle.insurance_expiry_date:'');
      data.append('rc_expiry_date', this.vehicle.rc_expiry_date?this.vehicle.rc_expiry_date:'');
      data.append('insurance_image', this.vehicle.insurance_image);
      data.append('rc_image', this.vehicle.rc_image);
      data.append('vehicle_image', this.vehicle.vehicle_image);
      data.append('carrier_type', this.vehicle.carrier_type);



      this.$http
        .post("/admin/vehicle_add", data)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "vehicles_list" })
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
    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
    },
    onFileChange2(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url2 = reader.result;
      };
    },
    onFileChange3(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url3 = reader.result;
      };
    },
  },
  created() {
    this.$http.get('/admin/carrier_list')
              .then(res => { this.options_carrier = res.data })
              
    if (this.$route.params.id) {
      this.$http.get('/admin/vehicle_edit/' + this.$route.params.id)
        .then(res => {
          this.vehicle = res.data;
          console.log(this.vehicle);

        })
    }

  

  },

}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#vehicle-img {
  width: 9rem;
}
</style>

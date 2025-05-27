<template>
  <b-row>
    <b-col cols="12">
      <div>
        <form-wizard color="#7367F0" :title="null" :subtitle="null" shape="square" finish-button-text="Submit"
          back-button-text="Previous" class="mb-3" @on-complete="formSubmitted">

          <!-- Restaurant details tab -->


          <!-- Restaurant Timing details tab -->
          <tab-content title="Store Timing" :before-change="Restaurant_Timing">

            <validation-observer ref="accountRules1" tag="form">

              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group :label="$t('day')" style="font-weight: bold;">
                  </b-form-group>


                </b-col>
                <b-col md="4">
                  <b-form-group :label="$t('Store') + ' ' + $t('opens') + '*'" style="font-weight: bold;">
                  </b-form-group>
                </b-col>
                <b-col md="4">
                  <b-form-group :label="$t('Store') + ' ' + $t('closes') + '*'" style="font-weight: bold;">
                  </b-form-group>
                </b-col>
                <b-col md="2">
                  <b-form-group :label="$t('is') + ' ' + $t('holiday') + '?'" style="font-weight: bold;">
                  </b-form-group>
                </b-col>

              </b-row>

              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_1') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                <b-form-timepicker :id="'timepicker-placeholder1'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_1" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder2'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_1" />
              </b-col>
              <b-col md="4">
               
                  <b-form-timepicker :id="'timepicker-placeholder3'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_1" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder4'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_1" />
              </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday1'" :key="item" value="1" v-model="holiday1"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>


              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_2') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                <b-form-timepicker :id="'timepicker-placeholder5'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_2" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder6'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_2" />
              </b-col>
              <b-col md="4">
               
                  <b-form-timepicker :id="'timepicker-placeholder7'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_2" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder8'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_2" />
              </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday2'" :key="item" value="2" v-model="holiday2"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>

              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_3') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                <b-form-timepicker :id="'timepicker-placeholder9'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_3" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder10'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_3" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder11'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_3" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder12'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_3" />
              </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday3'" :key="item" value="3" v-model="holiday3"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>


              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_4') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                <b-form-timepicker :id="'timepicker-placeholder13'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_4" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder14'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_4" />
              </b-col>
              <b-col md="4">
               
                  <b-form-timepicker :id="'timepicker-placeholder15'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_4" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder16'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_4" />
              </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday4'" :key="item" value="4" v-model="holiday4"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>


              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_5') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                <b-form-timepicker :id="'timepicker-placeholder17'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_5" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder18'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_5" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder19'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_5" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder20'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_5" />
              </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday5'" :key="item" value="5" v-model="holiday5"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>


              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_6') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                  <b-form-timepicker :id="'timepicker-placeholder21'" :placeholder="$t('open') + ' ' + $t('Time')" local="en"
                    v-model="opening_time_6" style="margin-bottom: 3%;" />
                  <b-form-timepicker :id="'timepicker-placeholder22'" :placeholder="$t('close') + ' ' + $t('Time')" local="en"
                    v-model="closing_time_6" />
                </b-col>
                <b-col md="4">
                  <b-form-timepicker :id="'timepicker-placeholder23'"  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                    v-model="opening_second_time_6" style="margin-bottom: 3%;" />
                  <b-form-timepicker :id="'timepicker-placeholder24'" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                    v-model="closing_second_time_6" />
                </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday6'" :key="item" value="6" v-model="holiday6"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>


              <b-row style="margin: 2%;">

                <b-col md="2">
                  <b-form-group>
                    <h5> {{ $t('weekday_7') }}</h5>


                  </b-form-group>


                </b-col>
                <b-col md="4">
                <b-form-timepicker :id="'timepicker-placeholder25'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_7" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder26'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_7" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder27'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_7" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder28'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_7" />
              </b-col>

                <b-col md="2">
                  <b-form-checkbox @change="dateMethod" :id="'holiday7'" :key="item" value="7" v-model="holiday7"
                    name="holidays">

                  </b-form-checkbox>



                </b-col>

              </b-row>
            </validation-observer>

          </tab-content>
        </form-wizard>

      </div>
    </b-col>
  </b-row>


</template>

<script>


import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'

import 'vue-form-wizard/dist/vue-form-wizard.min.css'
// dropdown
import BCardCode from '@@core/components/b-card-code'
//form validation//
import ToastificationContent from '@@core/components/toastification/ToastificationContent.vue'
import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormSelect, BCardText, BFormFile, BFormTextarea, BFormDatepicker,
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BNavItem, BButton, BFormCheckbox,
  BForm, BFormTimepicker, BInputGroup, BInputGroupAppend, BMedia,
  BAvatar, BDropdown, BDropdownItem, BModal, VBModal,
} from 'bootstrap-vue'
import { required, email } from '@@validations'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@@core/mixins/ui/transition'
import { useInputImageRenderer } from '@@core/comp-functions/forms/form-utils'
import { avatarText } from '@@core/utils/filter'
import { ref } from '@vue/composition-api'

export default {
  components: {
    BMedia,
    BAvatar,
    BModal, VBModal,
    ValidationProvider,
    ValidationObserver,
    FormWizard,
    TabContent,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    vSelect,
    BFormInvalidFeedback,
    BFormDatepicker,

    ToastificationContent,
    BFormSelect, BCardText,
    BCardCode,
    BFormFile,
    BFormTextarea,
    BCard, BImg, BNavbar, BNavbarToggle,
    BCollapse, BTabs, BNavItem, BButton,
    BFormCheckbox,
    BForm,
    BFormTimepicker, BInputGroup, BInputGroupAppend,
    BDropdown, BDropdownItem,
  },


  data() {
    return {
      closing_second_time_: [],
      opening_second_time_: [],
      closing_time_: [],
      opening_time_: [],
      closing_second_time_1: [],
      closing_second_time_2: [],
      closing_second_time_3: [],
      closing_second_time_4: [],
      closing_second_time_5: [],
      closing_second_time_6: [],
      closing_second_time_7: [],
      opening_second_time_1: [],
      opening_second_time_2: [],
      opening_second_time_3: [],
      opening_second_time_4: [],
      opening_second_time_5: [],
      opening_second_time_6: [],
      opening_second_time_7: [],
      closing_time_1: [],
      closing_time_2: [],
      closing_time_3: [],
      closing_time_4: [],
      closing_time_5: [],
      closing_time_6: [],
      closing_time_7: [],
      opening_time_1: [],
      opening_time_2: [],
      opening_time_3: [],
      opening_time_4: [],
      opening_time_5: [],
      opening_time_6: [],
      opening_time_7: [],

      restaurants_time: {},
      rows: [],
      holiday: [],
      checkedNames: [],
      selected_res: "",
      selected_vehicle: "",
      selected_city: "",
      selected_area: null,
      user_info: {},
      options: [
        { value: null, text: 'Status', disabled: true },
        { value: 1, text: 'Active' },
        { value: 2, text: 'InActive' },



      ],
      selected_check: [],
      options_check: [
        { text: 'Home Delivery', value: 1 },
        { text: 'Pickup', value: 2 },
        { text: 'Dining', value: 3 },
        { text: 'Schedule order', value: 4 },
      ],
      items: [{
        id: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      nextTodoId: 2,

      locale: this.$i18n.locale,


    }
  },

  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  mixins: [heightTransition],

  // props: {
  //   headerData: {
  //     type: Object,
  //     default: () => {},
  //   },
  // },

  setup(props) {

    // const { resolveUserRoleVariant } = useUsersList()

    const roleOptions = [
      { label: 'Admin', value: 'admin' },
      { label: 'Author', value: 'author' },
      { label: 'Editor', value: 'editor' },
      { label: 'Maintainer', value: 'maintainer' },
      { label: 'Subscriber', value: 'subscriber' },
    ]

    const statusOptions = [
      { label: 'Pending', value: 'pending' },
      { label: 'Active', value: 'active' },
      { label: 'Inactive', value: 'inactive' },
    ]

    const permissionsData = [
      {
        module: 'Admin',
        read: true,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'Staff',
        read: false,
        write: true,
        create: false,
        delete: false,
      },
      {
        module: 'Author',
        read: true,
        write: false,
        create: true,
        delete: false,
      },
      {
        module: 'Contributor',
        read: false,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'User',
        read: false,
        write: false,
        create: false,
        delete: true,
      },
    ]

    // ? Demo Purpose => Update image on click of update
    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, base64 => {
      // eslint-disable-next-line no-param-reassign

      this.url = base64
      //  alert(this.url);

    })

    return {

      avatarText,
      roleOptions,
      statusOptions,
      permissionsData,

      //  ? Demo - Update Image on click of update button
      refInputEl,
      previewEl,
      inputImageRenderer,

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
  created() {

    window.addEventListener('resize', this.initTrHeight)
    this.user_info.id = localStorage.id;
    this.$http.post('/store/edit_time', this.user_info)
      .then(res => {

        this.rows = res.data.data;
        // this.rows.forEach(function(element) {
        //   console.log(element.opening_time_1);

        //    this.opening_time_ = element.opening_time_1;
        //   // document.getElementById('timepicker-placeholder11').setAttribute("value",element.opening_time_1);
        //       });

        //  document.getElementById('timepicker-placeholder1'+1).setAttribute("value",this.rows[0].opening_time_1);
        this.opening_time_1 = this.rows.opening_time_1;
        this.closing_time_1 = this.rows.closing_time_1;
        this.opening_second_time_1 = this.rows.opening_second_time_1;
        this.closing_second_time_1 = this.rows.closing_second_time_1;

        this.opening_time_2 = this.rows.opening_time_2;
        this.closing_time_2 = this.rows.closing_time_2;
        this.opening_second_time_2 = this.rows.opening_second_time_2;
        this.closing_second_time_2 = this.rows.closing_second_time_2;

        this.opening_time_3 = this.rows.opening_time_3;
        this.closing_time_3 = this.rows.closing_time_3;
        this.opening_second_time_3 = this.rows.opening_second_time_3;
        this.closing_second_time_3 = this.rows.closing_second_time_3;

        this.opening_time_4 = this.rows.opening_time_4;
        this.closing_time_4 = this.rows.closing_time_4;
        this.opening_second_time_4 = this.rows.opening_second_time_4;
        this.closing_second_time_4 = this.rows.closing_second_time_4;

        this.opening_time_5 = this.rows.opening_time_5;
        this.closing_time_5 = this.rows.closing_time_5;
        this.opening_second_time_5 = this.rows.opening_second_time_5;
        this.closing_second_time_5 = this.rows.closing_second_time_5;



        this.opening_time_6 = this.rows.opening_time_6;
        this.closing_time_6 = this.rows.closing_time_6;
        this.opening_second_time_6 = this.rows.opening_second_time_6;
        this.closing_second_time_6 = this.rows.closing_second_time_6;



        this.opening_time_7 = this.rows.opening_time_7;
        this.closing_time_7 = this.rows.closing_time_7;
        this.opening_second_time_7 = this.rows.opening_second_time_7;
        this.closing_second_time_7 = this.rows.closing_second_time_7;


        this.rows.holidays.forEach(function (element) {
          document.getElementById('holiday' + element).checked = true;
        });
      })


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
    // formSubmitted() {
    //   this.$toast({
    //     component: ToastificationContent,
    //     props: {
    //       title: 'Form Submitted',
    //       icon: 'EditIcon',
    //       variant: 'success',
    //     },
    //   })



    // },


    //  dateMethod(){

    //      this.restaurants_time.holidays =  this.holiday;
    // },


    mounted() {
      this.initTrHeight()
    },
    repeateAgain() {
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },




    Restaurant_Timing() {
      return new Promise((resolve, reject) => {
        this.$refs.accountRules1.validate().then(success => {
          if (success) {
            let checkboxes1 = document.querySelectorAll('input[name="holidays"]:checked');

            let values1 = [];
            checkboxes1.forEach((checkbox) => {
              values1.push(checkbox.value);
            });
            this.restaurants_time.holidays = values1;
            let data = new FormData();
            data.append('opening_time_1', this.opening_time_1);
            data.append('closing_time_1', this.closing_time_1);
            data.append('opening_second_time_1', this.opening_second_time_1);
            data.append('closing_second_time_1', this.closing_second_time_1);

            data.append('opening_time_2', this.opening_time_2);
            data.append('closing_time_2', this.closing_time_2);
            data.append('opening_second_time_2', this.opening_second_time_2);
            data.append('closing_second_time_2', this.closing_second_time_2);

            data.append('opening_time_3', this.opening_time_3);
            data.append('closing_time_3', this.closing_time_3);
            data.append('opening_second_time_3', this.opening_second_time_3);
            data.append('closing_second_time_3', this.closing_second_time_3);

            data.append('opening_time_4', this.opening_time_4);
            data.append('closing_time_4', this.closing_time_4);
            data.append('opening_second_time_4', this.opening_second_time_4);
            data.append('closing_second_time_4', this.closing_second_time_4);

            data.append('opening_time_5', this.opening_time_5);
            data.append('closing_time_5', this.closing_time_5);
            data.append('opening_second_time_5', this.opening_second_time_5);
            data.append('closing_second_time_5', this.closing_second_time_5);

            data.append('opening_time_6', this.opening_time_6);
            data.append('closing_time_6', this.closing_time_6);
            data.append('opening_second_time_6', this.opening_second_time_6);
            data.append('closing_second_time_6', this.closing_second_time_6);

            data.append('opening_time_7', this.opening_time_7);
            data.append('closing_time_7', this.closing_time_7);
            data.append('opening_second_time_7', this.opening_second_time_7);
            data.append('closing_second_time_7', this.closing_second_time_7);
            data.append('holidays', this.restaurants_time.holidays);
            data.append('id', localStorage.id);
            data.append('time_edit', "time");
            this.$http
              .post("/store/add_to_restaurants_tab2", data)
              .then(
                response => {
                  if (response.data.status == true) {
                    this.$toast({
                      component: ToastificationContent,
                      position: 'bottom-right',
                      props: {
                        title: response.data.message,
                        icon: 'CheckIcon',
                        variant: 'success',
                      },
                    })
                    this.$router.push({ name: "restaurant_timing" })

                  } else {
                    this.$toast({
                      component: ToastificationContent,
                      position: 'bottom-right',
                      props: {
                        title: response.data.message,
                        icon: 'CheckIcon',
                        variant: 'warning',
                      },
                    })

                  }
                }
              )
              .catch((error) => console.log(error))
              .finally(() => (this.loading = false));
            resolve(true)

          } else {
            reject()
          }
        })
      })
    },
    popToast(response, icon, variant) {

      this.$toast({
        component: ToastificationContent,
        position: 'bottom-right',
        props: {
          title: response.data.message,
          icon: icon,
          variant: variant,
        },
      })
    },


  },








}

</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';

.repeater-form {
  overflow: hidden;
  transition: .35s height;
}

.wizard-icon-container {
  background-color: #62C061  !important;
}

.stepTitle {
  color: #62C061  !important;
}

.wizard-btn {
  background-color: #62C061  !important;
  border-color: #62C061  !important;
  color: white !important;
}
</style>
<style scoped>
#gmap {
  height: 400px;
  width: 100%;

}
</style>

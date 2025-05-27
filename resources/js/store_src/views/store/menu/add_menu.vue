<template>
  <b-card>
    <b-form @submit.prevent="addMenu">
      
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
        <b-col md="12" id="primary" style="display: block;">
          <b-form-group :label="$t('menu') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('menu') + ' ' + $t('name')" v-model="menu.name" required/>
          </b-form-group>
        </b-col>
        <b-col md="12" id="secondary" style="display: none;">
          <b-form-group :label="$t('menu') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('menu') + ' ' + $t('name')" v-model="menu.secondary_menu_name" />
          </b-form-group>
        </b-col>






        <b-col md="6">
          <b-form-group>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain">
              <feather-icon icon="PlusIcon" class="mr-25" />
              <span>{{ $t('add') }} {{ $t('new') }}</span>
            </b-button>
          </b-form-group>
        </b-col>



        <b-col md=12>
          <b-form-group>
            <b-form ref="form" :style="{ height: trHeight }" class="repeater-form" @submit.prevent="repeateAgain">

              <!-- Row Loop -->
              <b-row v-for="(item, index) in items" :id="item.id" :key="item.id" ref="row">

                <!-- Item Name -->
                <b-col md="4">
                  <b-form-group :label="$t('from')" label-for="item-name">
                    <b-form-timepicker v-model="from_[item.id]" />
                    <b-form-input :id="'from' + item.id" hidden="hidden" :value=from_[item.id] :key="'from' + item.id" />
                  </b-form-group>
                </b-col>

                <!-- Cost -->
                <b-col md="4">
                  <b-form-group :label="$t('to')" label-for="cost">
                    <b-form-timepicker v-model="to_[item.id]" />

                    <b-form-input :id="'to' + item.id" hidden="hidden" :value=to_[item.id] :key="'to' + item.id" />
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
                      :key="option.value" name="days" :value="option.value" style="margin-bottom: 3%;">{{ option.text
                      }}</b-form-checkbox>

                  </b-form-group>
                </b-col>

              </b-row>


            </b-col>

          </b-row>

        </b-col>





        <!-- submit and reset -->
        <b-col md="12" style="text-align: end;">
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
  BRow, BCol, BFormTimepicker, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,
   BFormSelect, BCardText, BCard, BFormFile,BTab, BTabs,  BAlert, BLink,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import { heightTransition } from '@@core/mixins/ui/transition'
//  import AppCard from '@@core/components/app-card/AppCard.vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BCardCode,
    BRow,
    BCol,
    // AppCard,
    BFormTimepicker,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    BCardText,
    BCard,
    vSelect,
    BFormFile,BTab, BTabs,  BAlert, BLink,

  },
  //   data: () => ({

  //   }),
  directives: {
    Ripple,
  },
  data() {
    return {
      setting:{},
      value: '',
      from_: [],
      to_: [],
      items: [{
        id: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      nextTodoId: 2,

      selected: 1,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],
      menu: {},
      rows: [],
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
    }
  },

  mounted() {
    this.initTrHeight()
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
    this.$http.get('/store/get_lang')
      .then(res => {

        this.setting = res.data
      })

  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
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
    addMenu() {

      let checkboxes = document.querySelectorAll('input[name="days"]:checked');

      let values = [];
      checkboxes.forEach((checkbox) => {
        values.push(checkbox.value);
      });
      this.menu.sevendays = values;
     
      let menu2 = new FormData();
      menu2.append('menu_name', this.menu.name);
      menu2.append('secondary_menu_name', this.menu.secondary_menu_name);
      menu2.append('res_id', localStorage.id);
      menu2.append('sevendays', this.menu.sevendays);
      menu2.append('days', this.menu.days);

      this.items.forEach(function (number) {
        menu2.append('from[]', document.getElementById('from' + number.id).value);
        menu2.append('to[]', document.getElementById('to' + number.id).value);

        //  menu2.append('from[]', this.from_[number.id]);
        // menu2.append('to[]', this.to_[number.id]);
      });



      this.$http
        .post("/store/store_menu", menu2)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "menu_list" })
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: response.data.message,
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
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

    changeMethod() {

      this.menu.sevendays = this.Day

    },

    showDate() {
      var checkBox = document.getElementById("All_day");
      var text = document.getElementById("days");
      if (checkBox.checked == true) {
        text.style.display = "none";
        this.menu.days = 1;
      } else {
        text.style.display = "block";
        this.menu.days = "NULL";
      }
      // alert(this.menu.days);
    }



  },

}
</script>
<style lang="scss" scoped>
.repeater-form {

  transition: .35s height;
}
</style>
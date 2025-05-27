<template>
  <b-card>
    <b-form @submit.prevent="addBrand">
     
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
          <b-form-group :label="$t('addon') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('enter') + ' ' + $t('addon') + ' ' + $t('name')" v-model="brand.name" required/>
          </b-form-group>
        </b-col>
        <b-col md="12" id="secondary" style="display: none;">
          <b-form-group :label="$t('addon') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('enter') + ' ' + $t('addon') + ' ' + $t('name')" v-model="brand.secondary_name" />
          </b-form-group>
        </b-col>

        <b-col md="12">
          <b-form-group :label="$t('price')">
            <b-form-input id="name" :placeholder="$t('enter') + ' ' + $t('price')" v-model="brand.price" />
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
  BRow, BCol, BFormGroup, BTooltip, BFormInput,
   BFormCheckbox, BForm, BButton, BFormSelect, BCardText, BCard, BFormFile,BTab, BTabs,  BAlert, BLink,
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
    BCardText,
    BCard,
    vSelect,
    BFormFile,
    BTooltip,BTab, BTabs,  BAlert, BLink,

  },
  directives: {
    Ripple,
  },
  data() {
    return {
      setting:{},
      selected: 1,
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],
      brand: {},
      rows: [],
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
    addBrand() {
      let brand2 = new FormData();
      brand2.append('id', this.brand.id);
      brand2.append('name',  this.brand.name?this.brand.name:'');
      brand2.append('secondary_name', this.brand.secondary_name ? this.brand.secondary_name:'');
       brand2.append('restaurant', localStorage.id);
      brand2.append('price', this.brand.price);
      this.$http
        .post("/store/store_addons", brand2)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "addons_list" })
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
  },
  created() {
    if (this.$route.params.id) {
      this.$http.get('/store/edit_addons/' + this.$route.params.id)
        .then(res => {

          this.brand = res.data.message;
        })
    }
    this.$http.get('/store/get_lang')
      .then(res => {

        this.setting = res.data
      })

  },
}
</script>

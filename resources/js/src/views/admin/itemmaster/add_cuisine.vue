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
          <b-form-group :label="$t('cusisine') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('cusisine') + ' ' + $t('name')" v-model="brand.name" />
          </b-form-group>
        </b-col>
        <b-col md="12" id="secondary" style="display: none;">
          <b-form-group :label="$t('cusisine') + ' ' + $t('name')">
            <b-form-input id="name" :placeholder="$t('cusisine') + ' ' + $t('name')" v-model="brand.secondary_name" />
          </b-form-group>
        </b-col>
        <b-col md="12">
          <b-form-group :label="$t('cusisine') + ' ' + $t('image')">
            <b-form-file v-model="brand.image" placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..." @change="onFileChange1" />
          </b-form-group>
          <b-form-group>
            <b-img :src="url1 ? url1 : brand.cuisine_image" rounded fluid id="cuisine-img" alt="cuisine photo"
              v-if="brand.cuisine_image" />
            <b-img id="cuisine-img" src="/no_image.png" fluid alt="Scan" v-else />
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
  BImg, BMedia, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,
   BFormSelect, BCardText, BCard, BFormFile, BTooltip,BTabs,BTab
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
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },

      ],
      brand: {},
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
    addBrand() {
      let brand2 = new FormData();
      brand2.append('id', this.brand.id);
      brand2.append('cuisine_name', this.brand.name ? this.brand.name:'');
      brand2.append('secondary_name', this.brand.secondary_name ? this.brand.secondary_name:'');

      brand2.append('cuisine_image', this.brand.image);
      this.$http
        .post("/admin/add_to_cuisines", brand2)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "cuisines_list" })
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
  },
  created() {
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_cuisines/' + this.$route.params.id)
        .then(res => {

          this.brand = res.data.message;
        })
    }

  },
}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#cuisine-img {
  width: 9rem;
}
</style>
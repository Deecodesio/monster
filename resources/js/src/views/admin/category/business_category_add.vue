<template>
  <b-card>
    <b-form @submit.prevent="category_add">



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
        <b-col md="6" id="primary" style="display: block;">
          <b-form-group label="Category Name">

            <b-form-input placeholder="name" v-model="category.category_name" required />
            <b-form-input v-model="category.id" hidden="hidden" />
          </b-form-group>
        </b-col>
        <b-col md="6" id="secondary" style="display: none;">
          <b-form-group label="Category Name">

            <b-form-input placeholder="name" v-model="category.secondary_categoryname" />
            <b-form-input v-model="category.id" hidden="hidden" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group label="Business Type">
            <v-select v-model="category.business_id" :options="options" label="name" :reduce="sel => sel.id"
              placeholder="Business Type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group label="Status">

            <v-select v-model="category.status" :options="options_status" label="text" :reduce="sel => sel.value"
              placeholder="Select Status" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group label="Image">

            <!-- <b-form-file
        v-model="category.image"
        placeholder="Choose a file or drop it here..."
        drop-placeholder="Drop file here..."
         @change="onFileChange1"
         accept=".jpg, .png, .jpeg" />
          -->
          </b-form-group>
          <!-- <b-form-group>
       
      <b-img
             :src="url1? url1:category.image"
            rounded
            fluid
            id="banner-img"
            alt="banner photo"
           
         v-if="category.image" />
          <b-img src="/no_image.png"   id="banner-img" fluid alt="Scan"  v-else/>
      </b-form-group> -->
          <b-form-group>
            <b-img :src="url1 ? url1 : category.image ? category.image : '/no_image.png'" rounded fluid id="banner-img"
              alt="category photo" />

          </b-form-group>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" v-b-modal.modal-xl variant="primary" class="mr-1">
            Choose a file
          </b-button>
        </b-col>





      </b-row>
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
          Save
        </b-button>

      </b-col>

    </b-form>

    <b-modal id="modal-xl" lazy scrollable title="Select Image" size="xl">
      <b-tabs v-model="tabIndex" pills>

        <b-tab>
          <template #title>
            <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">Select File </span>
          </template>
          <div>
            <b-row>
              <b-col cols="2" v-for="(img, index) in imgs" :key="index">
                <b-img :src="img" rounded fluid style="width: 30%;padding: 1%;max-height:100px;cursor:pointer"
                  alt="banner photo" @click="myMethod(img)" id="lazy" />
              </b-col>
            </b-row>
          </div>
        </b-tab>
        <b-tab>
          <template #title>
            <feather-icon icon="FilePlusIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">Crop Image</span>
          </template>
          <div>
            <b-row>
              <b-col md="4"></b-col>
              <b-col md="4">
                <label></label>
                <b-form-file id="new_img" v-model="file" placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..." @change="onFileChange2" accept=".jpg, .png, .jpeg" />
              </b-col>
              <b-col md="4"></b-col>
              <b-col md="12">
                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
                  <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="1 / 1" :restore="false"
                    :crop-box-movable="false" :crop-box-resizable="false" :toggle-drag-mode-on-dblclick="false"
                    :scalable="true" :background="true" :rotatable="true" :src="imgSrc" view-mode="1">
                  </vue-cropper>
                </div>

              </b-col>
            </b-row>
          </div>
        </b-tab>
      </b-tabs>
      <template #modal-footer v-if="tabIndex == 1">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
          @click="cropImage" style=" display: none;" id="crop_btn">Crop Image</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="rotate"
          style=" display: none;" id="rotate_btn">Rotate</b-button>
      </template>
    </b-modal>
  </b-card>
</template>
  
<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormGroup, BFormInput, BFormCheckbox, BForm,
  BButton, BCard, BImg, BMedia, BFormFile, BTab, BTabs, BAlert, BLink,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

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
    vSelect, BImg, BMedia, BFormFile, BTab, BTabs, BAlert, BLink, VueCropper,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      category: {},
      selected: null,
      url1: null,
      options: [],
      setting: {},
      selected_status: null,
      options_status: [
        { value: 1, text: 'Active' },
        { value: 0, text: 'Inactive' },
      ],
      cropImg: '',
      file: '',
      layout_type: 1,
      aspectRatio: "192/193",
      imgs: [],
      imgSrc: require('../../../assets/image.jpg'),
      tabIndex: 0,

    }
  },
  methods: {
    change() {



      this.$refs.cropper.replace(this.imgSrc);
    },
    cropImage() {

      if (this.layout_type == 1) {

        console.log("1")
        this.url1 = this.$refs.cropper.getCroppedCanvas({
          width: 300,
          height: 168
        }).toDataURL();
      } else {
        console.log("2")

        this.url1 = this.$refs.cropper.getCroppedCanvas({
          width: 1200,
          height: 628
        }).toDataURL();
      }

      this.category.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.category.existing = 2;
      this.$bvModal.hide('modal-xl');
    },
    rotate() {

      this.$refs.cropper.rotate(90);
    },
    myMethod(image) {
      this.url1 = image;
      var filename = image.replace(/^.*[\\\/]/, '');
      console.log(filename)
      this.category.image = filename;
      this.category.existing = 1;
      this.tabIndex = 0;

      this.$bvModal.hide('modal-xl');

    },
    onFileChange2(e) {

      const file = e.target.files[0];
      var img_size = e.target.files[0].size;

      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.imgSrc = reader.result;
        this.change();
      };
      document.getElementById('crop_js').style.display = 'block';
      document.getElementById('crop_btn').style.display = 'block';
      document.getElementById('rotate_btn').style.display = 'block';

      // this.$bvModal.hide('modal-xl');
    },
    onTabChange(id) {
      if (id == 1) {
        document.getElementById("primary").style.display = 'block';
        document.getElementById("secondary").style.display = 'none';
      }
      else {
        document.getElementById("primary").style.display = 'none';
        document.getElementById("secondary").style.display = 'block';
      }

    },
    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
    },
    category_add() {

      let data = new FormData();
      data.append('id', this.category.id);
      data.append('category_name', this.category.category_name);
      data.append('secondary_categoryname', this.category.secondary_categoryname ? this.category.secondary_categoryname : '');
      data.append('business_id', this.category.business_id ? this.category.business_id : '');
      data.append('status', this.category.status ? this.category.status : 0);
      data.append("image", this.category.image ? this.category.image : "");
      data.append('existing', this.category.existing);

      this.$http
        .post("/admin/add_business_category", data)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "business_category_list" })
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

  },
  created() {
    this.$http.get('/admin/business_lists')
      .then(res => {
        this.options = res.data;
        this.Loading = false;

      })
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_business_category/' + this.$route.params.id)
        .then(res => {
          console.log(res);
          this.category = res.data;
        })
    }

  },
}
</script>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#banner-img {
  width: 10rem;
}
</style>

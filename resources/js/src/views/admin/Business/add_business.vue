<template>
  <b-card>
    <b-form @submit.prevent="business_add">


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
          <b-form-group label="Business Name">
            <b-form-input placeholder="business name" v-model="business.name" required />
            <b-form-input v-model="business.id" hidden="hidden" />
          </b-form-group>
        </b-col>
        <b-col md="6" id="secondary" style="display: none;">
          <b-form-group label="Business Name">
            <b-form-input placeholder="business name" v-model="business.secondary_name" />
            <b-form-input v-model="business.id" hidden="hidden" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group label="Layout Type">
            <v-select v-model="business.layout_id" :options="options" label="layout_name" :reduce="sel => sel.id"
              placeholder="Layout Type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group label="Status" label-for="mc-city">
            <v-select v-model="business.status" :options="options_status" label="text" :reduce="sel => sel.value"
              placeholder="Select Status" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">

        </b-col>
        <b-col md="6">
          <b-form-group label="Image">

            <!-- <b-form-file
        v-model="business.image"
        placeholder="Choose a file or drop it here..."
        drop-placeholder="Drop file here..."
         @change="onFileChange1"
        /> -->

          </b-form-group>
          <!-- <b-form-group>
       
      <b-img
             :src="url1? url1:business.image"
            rounded
            fluid
            id="banner-img"
            alt="banner photo"
           
         v-if="business.image" />
          <b-img src="/no_image.png"   id="banner-img" fluid alt="Scan"  v-else/>
      </b-form-group> -->
          <b-form-group>
            <b-img :src="url1 ? url1 : business.image ? business.image : '/no_image.png'" rounded fluid id="categorys-img"
              alt="Business photo" />

          </b-form-group>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" v-b-modal.modal-xl variant="primary" class="mr-1">
            Choose a file
          </b-button>
        </b-col>

        <b-col md="6">
          <b-form-group label="Banner Image">

          </b-form-group>
        
          <b-form-group>
            <b-img :src="url2 ? url2 : business.banner_image ? business.banner_image : '/no_image.png'" rounded fluid id="categorys_banner-img"
              alt="Business Banner photo" />

          </b-form-group>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" v-b-modal.modal-xl1 variant="primary" class="mr-1">
            Choose a file
          </b-button>
        </b-col>




      </b-row>
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" style="float: right;">
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

    <b-modal id="modal-xl1" lazy scrollable title="Select Image" size="xl">
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
                  alt="banner photo" @click="myMethod1(img)" id="lazy" />
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
                <b-form-file id="new_img" v-model="file1" placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..." @change="onFileChange3" accept=".jpg, .png, .jpeg" />
              </b-col>
              <b-col md="4"></b-col>
              <b-col md="12">
                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
                  <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="32 / 5" :restore="false"
                    :crop-box-movable="false" :crop-box-resizable="false" :toggle-drag-mode-on-dblclick="false"
                    :scalable="true" :background="true" :rotatable="true" :src="imgSrc1" view-mode="1">
                  </vue-cropper>
                </div>

              </b-col>
            </b-row>
          </div>
        </b-tab>
      </b-tabs>
      <template #modal-footer v-if="tabIndex == 1">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
          @click="cropImage_banner" style=" display: none;" id="crop_btn">Crop Image</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="rotate"
          style=" display: none;" id="rotate_btn">Rotate</b-button>
      </template>
    </b-modal>

  </b-card>
</template>
  
<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BFormGroup,
  BFormInput, BFormCheckbox, BForm, BButton, BCard, BImg, BMedia, BFormFile, BTab, BTabs, BAlert, BLink,
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
      business: {},
      selected: null,
      url1: null,
      url2: null,
      setting: {},
      selected_status: null,
      options: [],
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
      imgSrc1: require('../../../assets/image.jpg'),
      tabIndex: 0,
    }
  },
  methods: {
    change() {



      this.$refs.cropper.replace(this.imgSrc);
    },
    change1() {



this.$refs.cropper.replace(this.imgSrc1);
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

      this.business.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.business.existing = 2;
      this.$bvModal.hide('modal-xl');
    },
    cropImage_banner() {

    if (this.layout_type == 1) {

      console.log("1")
      this.url2 = this.$refs.cropper.getCroppedCanvas({
        width: 300,
        height: 168
      }).toDataURL();
    } else {
      console.log("2")

      this.url2 = this.$refs.cropper.getCroppedCanvas({
        width: 1200,
        height: 628
      }).toDataURL();
    }

    this.business.banner_image = this.$refs.cropper.getCroppedCanvas().toDataURL();
    this.business.existing1 = 2;
    this.$bvModal.hide('modal-xl1');
    },
    rotate() {

      this.$refs.cropper.rotate(90);
    },
    myMethod(image) {
      this.url1 = image;
      var filename = image.replace(/^.*[\\\/]/, '');
      console.log(filename)
      this.business.image = filename;
      this.business.existing = 1;
      this.tabIndex = 0;

      this.$bvModal.hide('modal-xl');

    },
    myMethod1(image) {
      this.url2 = image;
      var filename = image.replace(/^.*[\\\/]/, '');
      console.log(filename)
      this.business.banner_image = filename;
      this.business.existing1 = 1;
      this.tabIndex = 0;

      this.$bvModal.hide('modal-xl1');

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
    onFileChange3(e) {

      const file = e.target.files[0];
      var img_size = e.target.files[0].size;

      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.imgSrc1 = reader.result;
        this.change1();
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
    business_add() {

      let data = new FormData();
      data.append('id', this.business.id);
      data.append('name', this.business.name);
      data.append('secondary_name', this.business.secondary_name ? this.business.secondary_name : '');
      data.append('layout_id', this.business.layout_id ? this.business.layout_id : '');
      data.append('status', this.business.status);
      data.append('image', this.business.image ? this.business.image : '');
      data.append('existing', this.business.existing);
      data.append('existing1', this.business.existing1);
      data.append('banner_image', this.business.banner_image ? this.business.banner_image : '');
      
      this.$http
        .post("/admin/business_add", data)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "business_list" })
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
    if (this.$route.params.id) {
      this.$http.get('/admin/business_edit/' + this.$route.params.id)
        .then(res => {
          console.log(res);
          this.business = res.data;
        })
    }
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })

    this.$http.get('/admin/layout')
      .then(res => {
        console.log(res);
        this.options = res.data;
      })

  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/pages/page-profile.scss';

#banner-img {
  width: 9.92rem;
}
</style>
<style lang="scss" >
@import '~@resources/scss/vue/pages/page-profile.scss';

#categorys_banner-img {
  width: 20rem;
}
#categorys-img {
  width: 10rem;
}
</style>
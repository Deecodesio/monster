<template>
    <b-card>
        <b-form @submit.prevent="add_banner">
            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('Category')">
                        <v-select v-model="banner.category" :options="this.options_category" label="category_name"
                            :reduce="sel => sel.id" :placeholder="$t('Select Category')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                    </b-form-group>
                </b-col>
                <!-- <b-col md="6">
          <b-form-group :label="$t('Product')">
            <v-select v-model="banner.product" :options="this.options_product" label="name" :reduce="sel => sel.id"
              :placeholder="$t('Select Product')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col> -->
                <b-col md="6">
                    <b-form-group :label="$t('city')">
                        <v-select v-model="banner.city_id" :options="this.cities" label="city" :reduce="sel => sel.id"
                            :placeholder="$t('select city')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('status')">
                        <v-select v-model="banner.status" :options="options_status" label="text"
                            :reduce="sel => sel.value" :placeholder="$t('select status')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('banner_image')">
                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" v-b-modal.modal-xl variant="primary"
                            class="mr-1">
                            Choose a file
                        </b-button>
                    </b-form-group>
                    <b-form-group>
                        <b-img :src="url1 ? url1 : banner.image ? banner.image : '/no_image.png'" rounded fluid
                            id="banner-img" alt="banner photo" />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                </b-col>
                <b-col>
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
                        {{ $t('save') }}
                    </b-button>
                </b-col>
            </b-row>
        </b-form>

        <b-modal id="modal-xl" lazy scrollable title="Upload Banner" size="xl">
            <b-tabs v-model="tabIndex" pills>
                <b-tab>
                    <template #title>
                        <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
                        <span class="d-none d-sm-inline">Select File </span>
                    </template>
                    <div>
                        <b-card-text v-for="(img, index) in imgs" :key="index">
                            <b-img :src="img.banner_image" rounded fluid style="width: 20%;padding: 1%"
                                alt="banner photo" @click="myMethod(img.banner_image)" id="lazy" />
                        </b-card-text>
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
                                    drop-placeholder="Drop file here..." @change="onFileChange2"
                                    accept=".jpg, .png, .jpeg" />
                            </b-col>
                            <b-col md="4"></b-col>
                            <b-col md="12">
                                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;"
                                    id="crop_js">
                                    <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="5 / 1"
                                        :restore="false" :crop-box-movable="false" :crop-box-resizable="false"
                                        :toggle-drag-mode-on-dblclick="false" :scalable="true" :background="true"
                                        :rotatable="true" :src="imgSrc" view-mode="1">
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
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
                    @click="rotate" style=" display: none;" id="rotate_btn">Rotate</b-button>
            </template>
        </b-modal>
    </b-card>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
    BImg, BMedia, BRow, BCol, BFormSelect, BFormRadioGroup, BFormFile,
    BFormGroup, BTooltip, BFormInput, BFormCheckbox, BForm, BButton, BCard, BTabs, BTab,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store/index'
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
        BFormFile,
        BCard,
        vSelect,
        BImg, BMedia,
        BTooltip, VueCropper, BTabs, BTab,
    },
    created() {

        this.$http.get('/admin/res_list')
            .then(res => {
                this.rows = res.data
            })
        this.$http.get('/admin/banner_imgs')
            .then(res => {
                this.imgs = res.data
            })
        this.$http.get('/admin/city_list')
            .then(res => {
                this.cities = res.data
            })
        this.$http.get('/admin/business_category_lists')
            .then(res => {
                this.options_category = res.data;
            })
        this.$http.get('/admin/get_products')
            .then(res => {
                this.options_product = res.data.products;
            })
        if (this.$route.params.id) {
            this.$http.get('/admin/edit_banner/' + this.$route.params.id)
                .then(res => {
                    this.banner = res.data.message;
                })
        }
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            banner: {},
            cities: [],
            rows: [],
            imgs: [],
            url1: null,
            selected_status: null,
            options_status: [
                { value: 1, text: this.$t('active') },
                { value: 2, text: this.$t('inactive') },
            ],
            imgSrc: require('../../../assets/image.jpg'),
            cropImg: '',
            tabIndex: 0,
            options_category: [],
            options_product: []

        }
    },
    methods: {

        change() {
            this.$refs.cropper.replace(this.imgSrc);
        },
        cropImage() {
            this.url1 = this.$refs.cropper.getCroppedCanvas({
                width: 1200,
                height: 628
            }).toDataURL();
            this.banner.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
            this.banner.existing = 2;
            this.tabIndex = 0;
            this.$bvModal.hide('modal-xl');
        },
        rotate() {
            this.$refs.cropper.rotate(90);
        },
        add_banner() {
            let data = new FormData();
            // data.append('file', this.driver.profile_pic);
            data.append('banner_image', this.banner.image);
            data.append('status', this.banner.status);
            data.append('city_id', this.banner.city_id);
            data.append('restaurant_id', this.banner.restaurant_id);
            data.append('existing', this.banner.existing);
            data.append('category', this.banner.category);
            data.append('product', this.banner.product);


            if (this.$route.params.id) {
                data.append('id', this.banner.id);
            }
            this.$http
                .post("/admin/add_to_banners", data)
                .then(
                    (response => {
                        if (response.data.status == true) {
                            this.popToast(response, "CheckIcon", "success")
                            this.$router.push({ name: "banner_list" })
                        } else {
                            this.popToast(response, "AlertTriangleIcon", "danger")
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
        onFileChange1(e) {
            const file = e.target.files[0];
            this.banner.image = file;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.url1 = reader.result;
            };
            this.banner.existing = 3;
            this.$bvModal.hide('modal-xl');
        },

        myMethod(image) {
            this.url1 = image;
            var filename = image.replace(/^.*[\\\/]/, '');
            this.banner.image = filename;
            this.banner.existing = 1;
            this.$bvModal.hide('modal-xl');
        },
        onFileChange2(e) {
            const file = e.target.files[0];
            console.log(file);
            var img_size = e.target.files[0].size;
            if (img_size < 20676814) {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    this.imgSrc = reader.result;
                    this.change();
                };

                document.getElementById('crop_js').style.display = 'block';
                document.getElementById('crop_btn').style.display = 'block';
                document.getElementById('rotate_btn').style.display = 'block';
            } else {
                alert("It is too large size Image . allow only minimum 20MB ");
            }
        },
    }
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-profile.scss';

#banner-img {
    width: 9.92rem;
}
</style>

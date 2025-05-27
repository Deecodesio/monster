<template>
    <div>
        <validation-observer ref="simpleRules">
            <b-form>
                <b-card>
                    <b-form-group :label="$t('Page Name')">
                        <validation-provider #default="{ errors }" name="Page Name" rules="required">
                            <b-form-input v-model="page.name" :state="errors.length > 0 ? false : null"
                                placeholder="Page Name" @input="createmeta()" />
                            <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                    </b-form-group>
                    <!-- <b-form-group :label="$t('Page Menu')">
                        <v-select v-model="page.category" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="option" label="name" :reduce="sel => sel.id" @input="get_sub($event)" />
                    </b-form-group> -->
                </b-card>

                <b-card>
                    <b-row>
                        <b-col cols="6">
                            <b-form-group :label="$t('Page Title')">
                                <b-form-input v-model="page.meta_title" placeholder="Page Title" />
                            </b-form-group>

                            <b-form-group :label="$t('Page Description')">
                                <b-form-input v-model="page.meta_desc" placeholder="Page Description" />
                            </b-form-group>

                        </b-col>
                        <b-col cols="6">
                            <b-form-group :label="$t('Slug')">
                                <b-form-input v-model="page.slug" placeholder="Slug" />
                            </b-form-group>

                            <b-form-group :label="$t('Page Image')">
                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" @click="open_uploadbanner(-1)"
                                    variant="primary" class="mr-1">
                                    Choose a file
                                </b-button>
                                <b-form-group>
                                    <b-img :src="page.meta_image ? page.meta_image : '/no_image.png'" rounded fluid
                                        id="banner-img" alt="banner photo" />
                                </b-form-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-card>




                <!-- <b-form-group :label="$t('Page Sub Category')">
                    <validation-provider #default="{ errors }" name="Page Category" rules="required">
                        <v-select v-model="page.sub_category" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="sub_option" label="name" :reduce="sel => sel.id" />
                        <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                </b-form-group> -->
                <b-card>
                    <div>
                        <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                            @submit.prevent="repeateAgain">
                            <b-row v-for="(item, index) in page.segments" :id="item.id" :key="item.id" ref="row">
                                <b-row>
                                    <b-col md="12">
                                        <b-form-group :label="$t('Segment Type')">
                                            <b-row>
                                                <b-col cols="6">
                                                    <div class="imgcontainer">
                                                        <b-img thumbnail src="/rightimg.png" rounded fluid
                                                            style="width: 125%;padding: 1%" alt="banner photo" id="lazy"
                                                            :class="{ 'mt-1 featured': item.type == 1, 'mt-1': item.type != 1 }"
                                                            class="mt-1" :id="'imgs' + index + 'r'" />
                                                        <div class="imgoverlay">
                                                            <div class="imgbutton">
                                                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                                                    variant="primary" class="mr-1"
                                                                    @click="feature_image(1, index)" v-if="item.type != 1">
                                                                    <feather-icon icon="CheckCircleIcon" size="16" />
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-col>
                                                <b-col cols="6">
                                                    <div class="imgcontainer">
                                                        <b-img thumbnail src="/leftimg.png" rounded fluid
                                                            style="width: 125%;padding: 1%" alt="banner photo" id="lazy"
                                                            :class="{ 'mt-1 featured': item.type == 2, 'mt-1': item.type != 2 }"
                                                            class="mt-1" :id="'imgs' + index + 'r'" />
                                                        <div class="imgoverlay">
                                                            <div class="imgbutton">
                                                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                                                    variant="primary" class="mr-1"
                                                                    @click="feature_image(2, index)" v-if="item.type != 2">
                                                                    <feather-icon icon="CheckCircleIcon" size="16" />
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </b-form-group>
                                    </b-col>
                                </b-row>
                                <b-row>
                                    <!-- <b-col md="12">
                                        <b-form-group :label="$t('Image')">
                                            <validation-provider #default="{ errors }" name="Image" rules="required">
                                                <b-form-file :id="item[index]" accept="image/*" v-model="item.image"
                                                    required />

                                                <small class="text-danger">{{ errors[0] }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col> -->
                                    <b-col md="12">
                                        <b-form-group :label="$t('Image')">
                                            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                                @click="open_uploadbanner(index)" variant="primary" class="mr-1">
                                                Choose a file
                                            </b-button>
                                        </b-form-group>
                                        <b-form-group>
                                            <b-img :src="item.image ? item.image : '/no_image.png'" rounded fluid
                                                id="banner-img" alt="banner photo" />
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="12">
                                        <b-form-group :label="$t('Page Content')">
                                            <validation-provider #default="{ errors }" name="Content" rules="required">
                                                <quill-editor v-model="item.content" />
                                                <small class="text-danger">{{ errors[0] }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                </b-row>
                                <b-col lg="2" md="3" class="mb-50">
                                    <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="outline-danger"
                                        class="mt-0 mt-md-2" @click="removeItem(index)">
                                        <feather-icon icon="XIcon" class="mr-25" />
                                        <span>Delete</span>
                                    </b-button>
                                </b-col>
                                <b-col cols="12">
                                    <hr>
                                </b-col>
                            </b-row>
                        </b-form>
                    </div>
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain">
                        <feather-icon icon="PlusIcon" class="mr-25" />
                        <span>{{ $t('Add New Segment') }}</span>
                    </b-button>
                    <!-- </div> -->
                </b-card>
            </b-form>
            <b-col cols="12">
                <b-button variant="primary" type="submit" @click.prevent="validationForm">
                    {{ $t('Submit') }}
                </b-button>
            </b-col>
        </validation-observer>
        <b-modal id="modal-xl" lazy scrollable title="Upload Image" size="xl" ref="uploadbanner">
            <b-tabs v-model="tabIndex" pills>

                <b-tab>
                    <template #title>
                        <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
                        <span class="d-none d-sm-inline">Select File </span>
                    </template>
                    <div>
                        <b-card-text v-for="(img, index) in imgs" :key="index">
                            <b-img :src="img.banner_image" rounded fluid style="width: 20%;padding: 1%" alt="banner photo"
                                @click="myMethod(img.banner_image)" id="lazy" />
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
                                    drop-placeholder="Drop file here..." @change="onFileChange2" />
                            </b-col>
                            <b-col md="4"></b-col>
                            <b-col md="12">
                                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
                                    <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="300 / 157"
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
                    @click="cropImage" style=" display: none;" id="crop_btn">Crop
                    Image</b-button>
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
                    @click="rotate" style=" display: none;" id="rotate_btn">Rotate</b-button>
            </template>
        </b-modal>
    </div>
</template>
<script>
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
    BFormInput, BFormGroup, BForm, BRow, BCol, BButton, BCardText, BImg, BCard, BFormFile, BTabs, BTab
} from 'bootstrap-vue'
import { required, email } from '@validations'
import { quillEditor } from 'vue-quill-editor'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { heightTransition } from '@core/mixins/ui/transition'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'

import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';



export default {
    components: {
        quillEditor,
        ValidationProvider,
        ValidationObserver,
        BCardText,
        BFormInput,
        BFormGroup,
        BForm,
        BRow,
        BCol,
        BButton, BImg, BCard, BFormFile, vSelect, VueCropper, BTabs, BTab
    },
    directives: {
        Ripple,
    },
    mixins: [heightTransition],
    data: () => ({
        content: "",
        name: "",
        layout_type: 1,
        index: 1,
        image: '',
        items: [{
            id: 1,
            selected: 'male',
            selected1: 'designer',
            prevHeight: 0,
        }],
        page: {
            name: '',
            category: '',
            sub_category: '',
            meta_title: '',
            meta_desc: '',
            slug: '',
            meta_image: '',
            existing: 1,
            segments: [{
                id: 0,
                type: 1,
                image: '',
                content: '',
                existing: ''
            }]
        },
        nextTodoId: 2,
        selected: { title: 'Reseller' },
        option: [],
        sub_option: [],

        initTrHeight: '',
        url: null,
        url1: null,
        imgSrc: require('../../../assets/image.jpg'),
        cropImg: '',
        imgs: [],
        file: [],
        tabIndex: 0,
        image_type: 1,
        toupload: -1
    }),
    mounted() {
        this.initTrHeight()
    },
    created() {
        this.$http.get('/admin/get_header_categories')
            .then(res => {
                this.option = res.data.categories
            })
        if (this.$route.params.id) {
            this.$http.get('/admin/fetch_header_page/' + this.$route.params.id)
                .then(res => {
                    console.log(res)
                    this.page.name = res.data.header_pages.page_name
                    this.page.category = res.data.header_pages.header_category

                    this.page.meta_title = res.data.header_pages.meta_title
                    this.page.meta_desc = res.data.header_pages.meta_desc
                    this.page.slug = res.data.header_pages.slug
                    this.page.meta_image = res.data.header_pages.meta_image

                    this.get_sub()
                    this.page.sub_category = res.data.header_pages.header_sub_category
                    if (res.data.page_contents && res.data.page_contents.length) {
                        this.page.segments.splice(0, 1);
                        for (var i = 0; i < res.data.page_contents.length; i++) {
                            var temp = {}
                            temp.type = res.data.page_contents[i].type
                            temp.content = res.data.page_contents[i].content[i].data
                            temp.image = res.data.page_contents[i].image
                            temp.existing = 1
                            this.page.segments.push(temp)
                        }
                    }


                })

        }

    },
    destroyed() {
        window.removeEventListener('resize', this.initTrHeight)
    },
    methods: {
        createmeta() {
            this.page.meta_title = this.page.name

            var str = this.page.name
            str.replace(/\-/g, "")
            this.page.slug = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();


        },
        open_uploadbanner(bt) {
            this.toupload = bt
            this.$refs["uploadbanner"].show();
        },
        change() {
            this.$refs.cropper.replace(this.imgSrc);
        },
        cropImage() {
            if (this.toupload == -1) {
                this.page.meta_image = this.$refs.cropper.getCroppedCanvas().toDataURL();
                this.page.existing = 2;
                this.tabIndex = 0
                this.$bvModal.hide('modal-xl');
            } else {
                this.page.segments[this.toupload].image = this.$refs.cropper.getCroppedCanvas().toDataURL();
                this.page.segments[this.toupload].existing = 2;
                this.tabIndex = 0
                this.$bvModal.hide('modal-xl');
            }

        },
        rotate() {
            this.$refs.cropper.rotate(90);
        },
        myMethod(image) {
            this.url1 = image;
            var filename = image.replace(/^.*[\\\/]/, '');
            this.page.segments = filename;
            this.page.segments = 1;
            this.$bvModal.hide('modal-xl');

        },
        onFileChange2(e) {
            const file = e.target.files[0];
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
        get_sub() {
            this.$http.get('/admin/get_header_sub_categories/' + this.page.category)
                .then(res => {
                    this.sub_option = res.data.sub_categories
                })
        },
        repeateAgain() {
            this.page.segments.push({
                id: this.nextTodoId += this.nextTodoId,
                type: 1
            })

            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight)
            })
        },
        removeItem(index) {
            this.page.segments.splice(index, 1)
            this.trTrimHeight(this.$refs.row[0].offsetHeight)
        },
        initTrHeight() {
            this.trSetHeight(null)
            this.$nextTick(() => {
                this.trSetHeight(this.$refs.form.scrollHeight)
            })
        },
        feature_image(image_id, index) {
            this.page.segments[index].type = image_id
        },
        remove_image(image_id) {
            if (this.layout_type == image_id) {
                this.layout_type = 0
            }
        },
        validationForm() {
            this.$refs.simpleRules.validate().then(success => {
                if (success) {
                    let data = new FormData();
                    if (this.$route.params.id) {
                        this.page.id = this.$route.params.id;
                    }
                    // data.append('name', this.page.name);
                    // data.append('category', this.page.category);
                    // data.append('sub_category', this.sub_category);
                    // data.append('image', this.image);
                    this.$http.post('/admin/add_new_header_page', this.page)
                        .then(
                            response => {
                                this.$toast({
                                    component: ToastificationContent,
                                    position: 'bottom-right',
                                    props: {
                                        title: this.$t(response.data.message),
                                        icon: "CheckIcon",
                                        variant: "success",
                                    },
                                })
                                if (response.data.status == true) {
                                    this.$router.push({ name: "header" })
                                }
                            }
                        )
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                }
            })
        },
    },

}
</script>
<style lang="scss">
@import '~@resources/scss/base/bootstrap-extended/_variables.scss';

.imgoverlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0);
    transition: background 0.5s ease;
    display: flex !important;
}

.imgcontainer:hover .imgoverlay {
    display: block;
    background: rgba(0, 0, 0, .3);
}

.imgbutton {
    width: 500px;
    height: 50px;
    justify-content: center;
    align-self: center;
    opacity: 0;
    transition: opacity 0.35s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.imgbutton a {
    width: 200px;
    padding: 12px 48px;
    text-align: center;
    color: white;
    border: solid 2px white;
    z-index: 1;
}

.imgcontainer:hover .imgbutton {
    opacity: 1;
}

.featured {
    border-style: solid !important;
    border-color: $primary !important;
    border-width: thick !important;
}

#categorys-img {
    width: 10rem;
}

#banner-img {
    width: 9.92rem;
}
</style>

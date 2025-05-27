<template>
    <div>
        <b-card>
            <validation-observer ref="simpleRules">
                <b-form>
                    <b-form-group :label="$t('Section Name')">
                        <validation-provider #default="{ errors }" name="Section Name" rules="required">
                            <b-form-input v-model="name" :state="errors.length > 0 ? false : null"
                                placeholder="Section Name" />
                            <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                    </b-form-group>
                    <b-form-group :label="$t('Layout Type')">
                        <b-row>
                            <b-col cols="6">
                                <div class="imgcontainer">
                                    <b-img thumbnail src="/rightimg.png" rounded fluid style="width: 125%;padding: 1%"
                                        alt="banner photo" id="lazy"
                                        :class="{ 'mt-1 featured': layout_type == 1, 'mt-1': layout_type != 1 }"
                                        class="mt-1" :id="'imgs' + index + 'r'" />
                                    <div class="imgoverlay">
                                        <div class="imgbutton">
                                            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                                class="mr-1" @click="feature_image(1)" v-if="layout_type != 1">
                                                <feather-icon icon="CheckCircleIcon" size="16" />
                                            </b-button>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                            <b-col cols="6">
                                <div class="imgcontainer">
                                    <b-img thumbnail src="/leftimg.png" rounded fluid style="width: 125%;padding: 1%"
                                        alt="banner photo" id="lazy"
                                        :class="{ 'mt-1 featured': layout_type == 2, 'mt-1': layout_type != 2 }"
                                        class="mt-1" :id="'imgs' + index + 'r'" />
                                    <div class="imgoverlay">
                                        <div class="imgbutton">
                                            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                                class="mr-1" @click="feature_image(2)" v-if="layout_type != 2">
                                                <feather-icon icon="CheckCircleIcon" size="16" />
                                            </b-button>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </b-form-group>
                    <b-row>
                        <b-col md="12">
                            <b-form-group :label="$t('Image')">
                                <validation-provider #default="{ errors }" name="Image" rules="required">
                                    <b-form-file id="wildcard" accept="image/*" v-model="image" required />

                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col md="12">
                            <b-form-group :label="$t('Page Content')">
                                <validation-provider #default="{ errors }" name="Content" rules="required">
                                    <quill-editor v-model="content" />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12">
                            <b-button variant="primary" type="submit" @click.prevent="validationForm">
                                {{ $t('Submit') }}
                            </b-button>
                        </b-col>
                    </b-row>
                </b-form>
            </validation-observer>
        </b-card>
    </div>
</template>
<script>
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
    BFormInput, BFormGroup, BForm, BRow, BCol, BButton, BCardText, BImg, BCard, BFormFile
} from 'bootstrap-vue'
import { required, email } from '@validations'
import { quillEditor } from 'vue-quill-editor'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
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
        BButton, BImg, BCard, BFormFile
    },
    data: () => ({
        content: "",
        name: "",
        layout_type: 1,
        index: 1,
        image: ''
    }),
    methods: {
        feature_image(image_id) {
            this.layout_type = image_id
        },
        remove_image(image_id) {
            console.log(image_id)
            if (this.layout_type == image_id) {
                this.layout_type = 0
            }
        },
        validationForm() {
            this.$refs.simpleRules.validate().then(success => {
                if (success) {
                    let data = new FormData();
                    if (this.$route.params.id) {
                        data.append('id', this.$route.params.id);
                    }
                    data.append('content_value', this.content);
                    data.append('content_name', this.name);
                    data.append('layout_type', this.layout_type);
                    data.append('image', this.image);
                    this.$http.post('/admin/add_new_page', data)
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
                                    this.$router.push({ name: "home_settings" })
                                }
                            }
                        )
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                }
            })
        },
    },
    created() {
        if (this.$route.params.id) {
            this.$http.get('/admin/edit_pages/' + this.$route.params.id)
                .then(res => {
                    this.content = res.data.data[0].data
                    this.name = res.data.page_details.name
                    this.layout_type = res.data.page_details.layout_type
                })
        }
    }
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
</style>
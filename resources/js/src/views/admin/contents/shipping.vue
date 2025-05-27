<template>
    <b-card>

        <b-form @submit.prevent="privacy_policy_add">
            <b-row>
                <b-col md="12">
                    <b-form-group>
                        <!-- <quill-editor ref="quillEditor"  contentType="html" :value="this.someText" v-model:content="someText"  theme="snow" ></quill-editor> -->
                        <quill-editor v-model="content" :options="snowOption" />
                        <b-form-input hidden="hidden" v-model:content="someText" />
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col md="12">
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
                        {{ $t('save') }}
                    </b-button>
                </b-col>
            </b-row>
        </b-form>

    </b-card>
</template>
  
<script>
import { BForm, BButton, BFormInput, BCardText, BCard, BFormGroup, } from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import { BRow, BCol } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


export default {
    components: {
        quillEditor,

        BCardText,
        BForm, BButton, BFormInput,
        BCard,
        BRow, BCol, BFormGroup,

    },
    props: ['id'],
    created() {

        this.data = this.id
    },
    data() {
        return {

            data: [],
            someText: '',
            contents: [],
            contents: {},
            snowOption: {
                theme: 'snow',
            },
            content: `<h1 class="ql-align-center">Shipping and delivery Policy`,
        }
    },
    directives: {
        Ripple,
    },
    created() {

        this.$http.get('/admin/shipping_policy')
            .then(res => {

                this.content = res.data

            })

    },

    computed: {
        editor() {
            return this.$refs.quillEditor;
        },
    },
    methods: {
        getSetText() {
            this.someText = "<div><p>this is some text</p> </div>";
            // this.editor.setHTML( this.contents);
        },

        privacy_policy_add() {
            if (process.env.MIX_IS_DEMO) {

                this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                        title: this.$t("Contents Cannot be Changed in Demo Mode"),
                        icon: 'CheckIcon',
                        variant: 'danger',
                    },
                })

                return false


            }
            let data = new FormData();
            data.append('content_value', this.content);
            data.append('content_name', 'shipping');

            this.$http.post('/admin/content_policy_shipping', data)

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
                    }
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },

    },

}

</script>
  
<style lang="scss">
@import '~@resources/scss/vue/libs/quill.scss';
</style>
  
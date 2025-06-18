<template>
    <b-card>
        <!-- media -->
        <b-media no-body>
            <b-media-aside>
                <b-link>
                    <b-img
                        v-if="showImage"
                        ref="previewEl"
                        rounded
                        :src="optionsLocal.profile"
                        height="80"
                        @click="$refs.refInputEl.$el.click()"
                        @error="showImage = false"
                    />
                    <feather-icon v-else icon="UserIcon" size="22" />
                </b-link>
                <!--/ avatar -->
            </b-media-aside>

            <b-media-body class="mt-75 ml-75">
                <b-button
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    variant="primary"
                    size="sm"
                    class="mb-75 mr-75"
                    @click="$refs.refInputEl.$el.click()"
                >
                    {{ $t("change") }}
                </b-button>
                <b-form-file
                    ref="refInputEl"
                    v-model="profileFile"
                    accept=".jpg, .png, .gif"
                    :hidden="true"
                    plain
                    @input="inputImageRenderer"
                    @change="onFileChange1"
                />

                <b-card-text
                    >{{
                        $t("Allowed JPG, GIF or PNG. Max size of ")
                    }}
                    800kB</b-card-text
                >
            </b-media-body>
        </b-media>
        <!--/ media -->

        <!-- form -->
        <b-form class="mt-2" @submit.prevent="profileupdate">
            <b-row>
                <b-col sm="6">
                    <b-form-group
                        :label="$t('Username')"
                        label-for="account-username"
                    >
                        <b-form-input
                            v-model="optionsLocal.name"
                            :placeholder="$t('Username')"
                            name="username"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm="6">
                    <b-form-group
                        :label="$t('Phone Number')"
                        label-for="account-name"
                    >
                        <b-form-input
                            v-model="optionsLocal.phone"
                            name="name"
                            :placeholder="$t('Phone Number')"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm="6">
                    <b-form-group
                        :label="$t('E-mail')"
                        label-for="account-e-mail"
                    >
                        <b-form-input
                            v-model="optionsLocal.email"
                            name="email"
                            :placeholder="$t('E-mail')"
                            disabled
                        />
                    </b-form-group>
                </b-col>

                <b-col cols="12">
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        class="mt-2 mr-1"
                        type="submit"
                    >
                        {{ $t("Save changes") }}
                    </b-button>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {
    BFormFile,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ref } from "@vue/composition-api";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
    },
    directives: {
        Ripple,
    },
    props: {
        generalData: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            showImage: true,
            optionsLocal: JSON.parse(JSON.stringify(this.generalData)),
            profileFile: null,
            userData: JSON.parse(localStorage.getItem("webuserData")),
        };
    },
    watch: {
        "optionsLocal.profile": {
            immediate: true,
            handler() {
                this.showImage = true; // reset if image URL changes
            },
        },
    },
    methods: {
        resetForm() {
            this.optionsLocal = JSON.parse(JSON.stringify(this.generalData));
        },
        onFileChange1(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.optionsLocal.profile = reader.result;
            };
        },
        profileupdate() {
            let city = new FormData();
            city.append("profile_image", this.profileFile);
            city.append("name", this.optionsLocal.name);
            city.append("email", this.optionsLocal.email);
            city.append("phone", this.optionsLocal.phone);
            city.append("id", this.userData.id);

            this.$http
                .post("/update_profile", city)
                .then((response) => {
                    if (response.data.status == true) {
                        this.loggedin = true;

                        const userData = response.data.data;
                        localStorage.setItem(
                            "webuserData",
                            JSON.stringify(userData)
                        );

                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CoffeeIcon",
                                variant: "success",
                            },
                        });
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CoffeeIcon",
                                variant: "failure",
                            },
                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
    },
    setup() {
        const refInputEl = ref(null);
        const previewEl = ref(null);

        const { inputImageRenderer } = useInputImageRenderer(
            refInputEl,
            previewEl
        );

        return {
            refInputEl,
            previewEl,
            inputImageRenderer,
        };
    },
};
</script>

<template>
    <b-card>
        <b-form @submit.prevent="addBulkOrder">
            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('name')">
                        <b-form-input
                            id="name"
                            :placeholder="$t('name')"
                            v-model="consumer.name"
                            :required="!consumer.name"
                        />
                    </b-form-group>
                </b-col>
                 <b-col md="6">
                    <b-form-group :label="$t('phone')">
                        <b-form-input
                            id="phone"
                            type="tel"
                            :placeholder="$t('phone')"
                            v-model="consumer.phone"
                            :required="!consumer.phone"
                        />
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('email')">
                        <b-form-input
                            id="email"
                            type="email"
                            :placeholder="$t('email')"
                            v-model="consumer.email"
                            :required="!consumer.email"
                        />
                    </b-form-group>
                </b-col>
                 <b-col md="6">
                    <b-form-group :label="$t('Wallet Balance')">
                        <b-form-input
                            id="wallet_balance"
                            type="number"
                            :placeholder="$t('Wallet Balance')"
                            v-model="consumer.wallet_amount"
                            :required="!consumer.wallet_amount"
                        />
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <!-- submit and reset -->
                <b-col>
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        type="submit"
                        variant="primary"
                        class="mr-1"
                        :disabled="loading"
                    >
                        <b-spinner small v-if="loading"></b-spinner>
                        {{ loading ? $t("saving") : $t("save") }}
                    </b-button>
                    <b-button
                        variant="outline-secondary"
                        @click="$router.go(-1)"
                    >
                        {{ $t("cancel") }}
                    </b-button>
                </b-col>
            </b-row>
               </b-form>
    </b-card>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import { BSpinner } from "bootstrap-vue";
import {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BTooltip,
    BButton,
    BFormSelect,
    BCardText,
    BCard,
    BTabs,
    BTab,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

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
        BTooltip,
        BTabs,
        BTab,
        BSpinner,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            loading: false,
            consumer: {
                name: "",
                email_id: "",
                phone: "",
                wallet_balance: 0,
            },
        };
    },
    methods: {
        addBulkOrder() {            
            // Set loading state
            this.loading = true;

            let user_info = new FormData();
            user_info.append("id", this.consumer.id || "");
            user_info.append(
                "name",
                this.consumer.name ? this.consumer.name : ""
            );       
            user_info.append(
                "phone",
                this.consumer.phone ? this.consumer.phone : ""
            );
            user_info.append(
                "email",
                this.consumer.email ? this.consumer.email : ""
            );
            user_info.append(
                "wallet_amount",
                this.consumer.wallet_amount ? this.consumer.wallet_amount : ""
            );

            console.log("user_info data to be sent:", user_info);

            // Make the API call
            this.$http
                .post("/admin/add_consumer", user_info)
                .then((response) => {

                    console.log("Response from API:", response);
                    if (response.data.status == true) {
                        this.$router.push({ name: "user_management" });
                        this.popToast(response, "CheckIcon", "success");
                    } else {
                        this.popToast(response, "AlertTriangleIcon", "danger");
                    }
                })
                .catch((error) => {
                    console.error(error);
                    this.popToast(
                        {
                            data: {
                                message: "Failed to save. Please try again.",
                            },
                        },
                        "AlertTriangleIcon",
                        "danger"
                    );
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        
        popToast(response, icon, variant) {
            this.$toast({
                component: ToastificationContent,
                position: "bottom-right",
                props: {
                    title: this.$t(response.data.message),
                    icon: icon,
                    variant: variant,
                },
            });
        },
    },
    created() {
    },
};
</script>

<template>
    <b-card>
        <b-form @submit.prevent="addBulkOrder">
            <b-row>
                <!-- <b-col md="6">
                    <b-form-group :label="$t('name')">
                        <b-form-input
                            id="name"
                            :placeholder="$t('name')"
                            v-model="bulkOrder.name"
                            :required="!bulkOrder.name"
                        />
                    </b-form-group>
                </b-col> -->
                <b-col md="6">
                    <b-form-group :label="$t('state')">
                        <b-form-input
                            id="state"
                            :placeholder="$t('select state')"
                            v-model="bulkOrder.state"
                            :required="!bulkOrder.state"
                        />
                    </b-form-group>
                    <!-- <b-form-group :label="$t('state')">
                        <v-select
                            id="state"
                            v-model="bulkOrder.state"
                            :options="state_list"
                            label="state"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('select state')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!bulkOrder.state"
                            @input="onStateChange"
                        />
                    </b-form-group> -->
                </b-col>
            </b-row>

            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('city')">
                        <b-form-input
                            id="city"
                            :placeholder="$t('select city')"
                            v-model="bulkOrder.district"
                            :required="!bulkOrder.district"
                        />
                    </b-form-group>

                    <!-- <b-form-group :label="$t('city')">
                        <v-select
                            id="city"
                            v-model="bulkOrder.city"
                            :options="city_list"
                            label="city"
                            :reduce="(sel) => sel.value"
                            :placeholder="$t('select city')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!bulkOrder.city"
                        />
                    </b-form-group> -->
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('taluk')">
                        <b-form-input
                            id="taluk"
                            :placeholder="$t('select taluk')"
                            v-model="bulkOrder.taluk"
                            :required="!bulkOrder.taluk"
                        />
                    </b-form-group>
                    <!-- <b-form-group :label="$t('taluk')">
                        <v-select
                            v-model="bulkOrder.taluk"
                            :options="city_list"
                            label="taluk"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('select taluk')"
                            :required="!bulkOrder.taluk"
                        />
                    </b-form-group> -->
                </b-col>
            </b-row>

            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('pincode')">
                        <b-form-input
                            id="pincode"
                            type="text"
                            :placeholder="$t('pincode')"
                            v-model="bulkOrder.pincode"
                            :required="!bulkOrder.pincode"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('address')">
                        <b-form-input
                            id="address"
                            type="text"
                            :placeholder="$t('address')"
                            v-model="bulkOrder.address"
                            :required="!bulkOrder.address"
                        />
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('phone_one')">
                        <b-form-input
                            id="phone1"
                            type="tel"
                            :placeholder="$t('phone_one')"
                            v-model="bulkOrder.phone_1"
                            :required="!bulkOrder.phone_1"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('phone_two')">
                        <b-form-input
                            id="phone2"
                            type="tel"
                            :placeholder="$t('phone_two')"
                            v-model="bulkOrder.phone_2"
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
                            v-model="bulkOrder.email_id"
                            :required="!bulkOrder.email_id"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('product') + ' ' + $t('category')">
                        <v-select
                            v-model="bulkOrder.business_category_id"
                            :options="product_category"
                            label="category_name"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('category')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            @input="onCategoryStateChange"
                        />
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <!-- Left Column -->
                <b-col md="6">
                    <!-- Taluk -->
                    <b-form-group :label="$t('product')">
                        <v-select
                            v-model="bulkOrder.product_id"
                            :options="product_list"
                            label="name"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('product')"
                            :required="!bulkOrder.product_id"
                        />
                    </b-form-group>

                    <!-- Status -->
                    <!-- <b-form-group :label="$t('status')">
                        <v-select
                            v-model="bulkOrder.status"
                            :options="options"
                            label="text"
                            :reduce="(sel) => sel.value"
                            :placeholder="$t('select status')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!bulkOrder.status"
                        />
                    </b-form-group> -->
                </b-col>

                <!-- Right Column -->
                <b-col md="6">
                    <b-form-group :label="$t('messages')">
                        <b-form-textarea
                            id="message"
                            v-model="bulkOrder.message"
                            :placeholder="$t('messages')"
                            rows="4"
                            style="resize: none; height: 105px"
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
            state_list: [],
            city_list: [],
            product_category: [],
            product_list: [],
            options: [
                { value: 1, text: this.$t("active") },
                { value: 2, text: this.$t("inactive") },
            ],
            bulkOrder: {
                name: "",
                state: null,
                city: null,
                taluk: null,
                pincode: "",
                address: "",
                phone1: "",
                phone2: "",
                email: "",
                category_id: null,
                product_id: null,
                status: 1, // Default to active
                message: "",
            },
        };
    },
    // methods: {
    //     addBulkOrder() {
    //         let brand = new FormData();
    //         brand.append("id", this.bulkOrder.id);
    //         brand.append(
    //             "name",
    //             this.bulkOrder.name ? this.bulkOrder.name : ""
    //         );
    //         brand.append(
    //             "state",
    //             this.bulkOrder.state ? this.bulkOrder.state : ""
    //         );
    //         brand.append(
    //             "city",
    //             this.bulkOrder.city ? this.bulkOrder.city : ""
    //         );
    //         brand.append(
    //             "taluk",
    //             this.bulkOrder.taluk ? this.bulkOrder.taluk : ""
    //         );
    //         brand.append(
    //             "pincode",
    //             this.bulkOrder.pincode ? this.bulkOrder.pincode : ""
    //         );
    //         brand.append(
    //             "address",
    //             this.bulkOrder.address ? this.bulkOrder.address : ""
    //         );
    //         brand.append(
    //             "phone1",
    //             this.bulkOrder.phone1 ? this.bulkOrder.phone1 : ""
    //         );
    //         brand.append(
    //             "phone2",
    //             this.bulkOrder.phone2 ? this.bulkOrder.phone2 : ""
    //         );
    //         brand.append(
    //             "email",
    //             this.bulkOrder.email ? this.bulkOrder.email : ""
    //         );
    //         brand.append(
    //             "product_category",
    //             this.bulkOrder.product_category
    //                 ? this.bulkOrder.product_category
    //                 : ""
    //         );
    //         brand.append(
    //             "product",
    //             this.bulkOrder.product_id ? this.bulkOrder.product_id : ""
    //         );
    //         brand.append("status", this.bulkOrder.status);
    //         brand.append(
    //             "message",
    //             this.bulkOrder.message ? this.bulkOrder.message : ""
    //         );

    //         // this.$http
    //         //     .post("/admin/store_brand", brand)
    //         //     .then((response) => {
    //         //         if (response.data.status == true) {
    //         //             this.$router.push({ name: "brands_list" });
    //         //             this.popToast(response, "CheckIcon", "success");
    //         //         } else {
    //         //             this.popToast(response, "AlertTriangleIcon", "danger");
    //         //         }
    //         //     })
    //         //     .catch((error) => console.log(error))
    //         //     .finally(() => (this.loading = false));
    //     },
    //     popToast(response, icon, variant) {
    //         this.$toast({
    //             component: ToastificationContent,
    //             position: "bottom-right",
    //             props: {
    //                 title: this.$t(response.data.message),
    //                 icon: icon,
    //                 variant: variant,
    //             },
    //         });
    //     },

    //     onStateChange(stateId) {
    //         this.$http
    //             .get("/admin/city_list_by_state/" + stateId)
    //             .then((res) => {
    //                 this.city_list = res.data;
    //                 console.log(this.city_list);
    //             });
    //     },

    //     onCategoryStateChange(catId) {
    //         this.$http
    //             .get("/admin/product_list_by_category/" + catId)
    //             .then((res) => {
    //                 this.product_list = res.data;
    //                 console.log(this.product_list);
    //             });
    //     },
    // },
    // created() {
    //     this.$http.get("/admin/state_list").then((res) => {
    //         this.state_list = res.data;
    //         console.log(this.state_list);
    //     });

    //     this.$http.get("/admin/business_category_lists").then((res) => {
    //         this.product_category = res.data;
    //         console.log("product_category assd");
    //         console.log(this.product_category);
    //     });
    // },
    methods: {
        addBulkOrder() {
            if (!this.bulkOrder.business_category_id) {
                this.popToast(
                    {
                        data: {
                            message: "Category is required.",
                        },
                    },
                    "AlertTriangleIcon",
                    "danger"
                );
                return;
            }
            if (!this.bulkOrder.product_id) {
                this.popToast(
                    {
                        data: {
                            message: "Product is required.",
                        },
                    },
                    "AlertTriangleIcon",
                    "danger"
                );
                return;
            }
            // Set loading state
            this.loading = true;

            let brand = new FormData();
            brand.append("id", this.bulkOrder.id || "");
            brand.append(
                "name",
                this.bulkOrder.name ? this.bulkOrder.name : ""
            );
            brand.append(
                "state",
                this.bulkOrder.state ? this.bulkOrder.state : ""
            );
            brand.append(
                "district",
                this.bulkOrder.district ? this.bulkOrder.district : ""
            );
            brand.append(
                "taluk",
                this.bulkOrder.taluk ? this.bulkOrder.taluk : ""
            );
            brand.append(
                "pincode",
                this.bulkOrder.pincode ? this.bulkOrder.pincode : ""
            );
            brand.append(
                "address",
                this.bulkOrder.address ? this.bulkOrder.address : ""
            );
            brand.append(
                "phone_1",
                this.bulkOrder.phone_1 ? this.bulkOrder.phone_1 : ""
            );
            brand.append(
                "phone_2",
                this.bulkOrder.phone_2 ? this.bulkOrder.phone_2 : ""
            );
            brand.append(
                "email_id",
                this.bulkOrder.email_id ? this.bulkOrder.email_id : ""
            );
            brand.append(
                "business_category_id",
                this.bulkOrder.business_category_id
                    ? this.bulkOrder.business_category_id
                    : ""
            );
            brand.append(
                "product_id",
                this.bulkOrder.product_id ? this.bulkOrder.product_id : ""
            );
            brand.append("status", this.bulkOrder.status);
            brand.append(
                "message",
                this.bulkOrder.message ? this.bulkOrder.message : ""
            );

            console.log("Brand data to be sent:", brand);

            // Make the API call
            this.$http
                .post("/api/bulk_enquiry", brand)
                .then((response) => {

                    console.log("Response from API:", response);
                    if (response.data.success == true) {
                        this.$router.push({ name: "bulk_orders" });
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

        onStateChange(stateId) {
            this.$http
                .get("/admin/city_list_by_state/" + stateId)
                .then((res) => {
                    this.city_list = res.data;
                    console.log(this.city_list);
                });
        },

        onCategoryStateChange(catId) {
            this.$http
                .get("/admin/product_list_by_category/" + catId)
                .then((res) => {
                    this.product_list = res.data;
                    console.log(this.product_list);
                });
        },
    },
    created() {
        // this.$http.get("/admin/state_list").then((res) => {
        //     this.state_list = res.data;
        //     console.log(this.state_list);
        // });

        this.$http.get("/admin/business_category_lists").then((res) => {
            this.product_category = res.data;
            console.log("product_category assd");
            console.log(this.product_category); 
        });
        if (this.$route.params.id) {
      this.$http.get('/api/bulk_enquiry/' + this.$route.params.id)
        .then(res => {
          this.bulkOrder = res.data.data;
          this.onCategoryStateChange(this.bulkOrder.business_category_id);
        })
    }
    },
};
</script>

<template>
    <b-card>
        <b-form @submit.prevent="addBulkOrder">
            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('job Name')">
                        <b-form-input class="rose-border" />
                    </b-form-group>

                    <b-form-group :label="$t('Location')">
                        <b-form-input class="rose-border"> </b-form-input>
                    </b-form-group>

                    <b-form-group :label="$t('State/ Union Territory')">
                        <b-form-input class="rose-border"> </b-form-input>
                    </b-form-group>

                    <b-form-group :label="$t('Date of create')">
                        <b-form-input class="rose-border"> </b-form-input>
                    </b-form-group>
                    <b-form-group :label="$t('Created By')">
                        <b-form-input class="rose-border"> </b-form-input>
                    </b-form-group>

                    <b-form-group :label="$t('Status')">
                        <b-form-input class="rose-border"> </b-form-input>
                    </b-form-group>
                </b-col>
                <b-col  md="6">
                   <div class="container-fluid card-editor">job Details</div> 
                </b-col>

            </b-row>


            <b-row>
                <b-col>
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        type="submit"
                        variant="primary"
                        class="mr-1"
                    >
                        {{ $t("save") }}
                    </b-button>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
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
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            state_list: [],
            city_list: [],
            product_category: [],
            product_list: [],
            options: [
                { value: 1, text: this.$t("active") },
                { value: 2, text: this.$t("inactive") },
            ],
            bulkOrder: {},
        };
    },
    methods: {
        addBulkOrder() {
            let brand = new FormData();
            brand.append("id", this.bulkOrder.id);
            brand.append(
                "name",
                this.bulkOrder.name ? this.bulkOrder.name : ""
            );
            brand.append(
                "state",
                this.bulkOrder.state ? this.bulkOrder.state : ""
            );
            brand.append(
                "city",
                this.bulkOrder.city ? this.bulkOrder.city : ""
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
                "phone1",
                this.bulkOrder.phone1 ? this.bulkOrder.phone1 : ""
            );
            brand.append(
                "phone2",
                this.bulkOrder.phone2 ? this.bulkOrder.phone2 : ""
            );
            brand.append(
                "email",
                this.bulkOrder.email ? this.bulkOrder.email : ""
            );
            brand.append(
                "product_category",
                this.bulkOrder.product_category
                    ? this.bulkOrder.product_category
                    : ""
            );
            brand.append(
                "product",
                this.bulkOrder.product_id ? this.bulkOrder.product_id : ""
            );
            brand.append("status", this.bulkOrder.status);
            brand.append(
                "message",
                this.bulkOrder.message ? this.bulkOrder.message : ""
            );

            // this.$http
            //     .post("/admin/store_brand", brand)
            //     .then((response) => {
            //         if (response.data.status == true) {
            //             this.$router.push({ name: "brands_list" });
            //             this.popToast(response, "CheckIcon", "success");
            //         } else {
            //             this.popToast(response, "AlertTriangleIcon", "danger");
            //         }
            //     })
            //     .catch((error) => console.log(error))
            //     .finally(() => (this.loading = false));
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
        this.$http.get("/admin/state_list").then((res) => {
            this.state_list = res.data;
            console.log(this.state_list);
        });

        this.$http.get("/admin/business_category_lists").then((res) => {
            this.product_category = res.data;
            console.log("product_category assd");
            console.log(this.product_category);
        });
    },
};
</script>

<style scoped>
.rose-border {
    border: 1px solid #e91e63;
    box-shadow: none;
}

.card-editor{
margin-left: 115px;
}
</style>

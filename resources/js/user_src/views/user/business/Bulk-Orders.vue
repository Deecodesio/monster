<template>
    <div>
        <div class="container">
            <div class="mt-5 mx-3 py-6">
                <top_banners />
            </div>
            <!-- <h4 class="text-center mb-4" style="font-size: x-large">
                Bulk Orders
            </h4> -->
        </div>

        <div class="w-100" style="background-color: #ff81b633">
            <div class="container">
                <div class="row d-flex">
                    <div class="col-md-6 col-sm-12  d-flex">
                        <div id="apply-form" class="apply-form mt-5" style="
                                background-color: transparent !important;
                                box-shadow: none !important;
                                margin: none !important;
                                max-width: 900px;
                                min-width: 300px;
                            ">
                            <b-form @submit.prevent="submitApplication">
                                <b-card>
                                    <b-row>
                                        <b-col md="6">
                                            <b-form-group label="Full Name" label-for="name" label-cols-sm="12">
                                                <b-form-input id="name" v-model="form.name" :state="nameState"
                                                    required></b-form-input>
                                                <b-form-invalid-feedback v-if="!nameState">
                                                    Name is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Email" label-for="email_id" label-cols-sm="12">
                                                <b-form-input id="email_id" v-model="form.email_id" type="email"
                                                    :state="emailState" required></b-form-input>
                                                <b-form-invalid-feedback v-if="!emailState">
                                                    Please enter a valid email
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Primary Phone Number" label-for="phone_1"
                                                label-cols-sm="12">
                                                <b-form-input id="phone_1" v-model="form.phone_1" :state="phone1State"
                                                    required></b-form-input>
                                                <b-form-invalid-feedback v-if="!phone1State">
                                                    Phone number is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Alternative Phone Number (Optional)"
                                                label-for="phone_2" label-cols-sm="12">
                                                <b-form-input id="phone_2" v-model="form.phone_2"></b-form-input>
                                            </b-form-group>
                                        </b-col>

                                        <!-- <b-col md="12">
                            <b-form-group label="Address" label-for="address">
                                <b-form-textarea id="address" v-model="form.address" rows="1" :state="addressState"
                                    required></b-form-textarea>
                                <b-form-invalid-feedback v-if="!addressState">
                                    Address is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col> -->
                                        <b-col md="12">
                                            <b-form-group label="Address" label-for="address" label-cols-sm="12">
                                                <b-form-textarea id="address" v-model="form.address" rows="2"
                                                    :state="addressState" required
                                                    class="compact-textarea"></b-form-textarea>
                                                <b-form-invalid-feedback v-if="!addressState">
                                                    Address is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="State" label-for="state" label-cols-sm="12">
                                                <b-form-input id="state" v-model="form.state" :state="stateState"
                                                    required></b-form-input>
                                                <b-form-invalid-feedback v-if="!stateState">
                                                    State is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="District" label-for="district" label-cols-sm="12">
                                                <b-form-input id="district" v-model="form.district"
                                                    :state="districtState" required></b-form-input>
                                                <b-form-invalid-feedback v-if="!districtState">
                                                    District is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Taluk" label-for="taluk" label-cols-sm="12">
                                                <b-form-input id="taluk" v-model="form.taluk" :state="talukState"
                                                    required></b-form-input>
                                                <b-form-invalid-feedback v-if="!talukState">
                                                    Taluk is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Pincode" label-for="pincode" label-cols-sm="12">
                                                <b-form-input id="pincode" v-model="form.pincode" :state="pincodeState"
                                                    required></b-form-input>
                                                <b-form-invalid-feedback v-if="!pincodeState">
                                                    Pincode is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Product Category" label-for="business_category_id"
                                                label-cols-sm="12">
                                                <b-form-select id="business_category_id" v-model="form.business_category_id
                                                    " :options="categoryOptions" :state="categoryState" required
                                                    class="form-control" @change="onCategoryChange">
                                                    <template #first>
                                                        <option :value="null" disabled>
                                                            Select a category
                                                        </option>
                                                    </template>
                                                </b-form-select>
                                                <b-form-invalid-feedback v-if="
                                                    !form.business_category_id
                                                ">
                                                    Product Category is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group label="Product" label-for="product_id" label-cols-sm="12">
                                                <b-form-select id="product_id" v-model="form.product_id"
                                                    :options="productOptions" :state="productState" :disabled="!form.business_category_id
                                                        " required class="form-control">
                                                    <template #first>
                                                        <option :value="null" disabled>
                                                            Select a product
                                                        </option>
                                                    </template>
                                                </b-form-select>
                                                <b-form-invalid-feedback v-if="!form.product_id">
                                                    Product is required
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </b-col>

                                        <b-col md="12">
                                            <b-form-group label="Additional Message (Optional)" label-for="message"
                                                label-cols-sm="12">
                                                <b-form-textarea id="message" v-model="form.message" rows="2"
                                                    class="compact-textarea"></b-form-textarea>
                                            </b-form-group>
                                        </b-col>

                                        <b-row cols="12" class="mt-3" style="
                                                margin-left: auto;
                                                margin-right: auto;
                                                font-size: 22px !important;
                                            ">
                                            <b-button type="submit" variant="primary" :disabled="submitting">
                                                <b-spinner small v-if="submitting" class="mr-1"></b-spinner>
                                                {{
                                                    submitting
                                                        ? "Submitting..."
                                                        : "Submit Application"
                                                }}
                                            </b-button>
                                        </b-row>
                                    </b-row>
                                </b-card>
                            </b-form>
                        </div>
                    </div>
                    <div class="franchise-box" style="
    /* background-color: #ffe6f0; */
    padding: 30px;
    border-radius: 10px;
    font-family: Arial, sans-serif;
    color: #000;
    max-width: 500px;
    margin: auto;
    font-size: 15px;
    line-height: 1.8;
    margin-bottom: 14rem;
  ">
                        <h2 style="margin-top: 0; margin-bottom: 10px; color: #000; font-size: 22px;">
                            Form For the Franchise Setup
                        </h2>

                        <h3 style="margin-top: 0; margin-bottom: 20px; color: #ff006b; font-size: 18px; ">
                            Product Details
                        </h3>

                        <ol style="padding-left: 20px; margin: 20px 0 0 0;">
                            <li>30+ years of experience in chicken business</li>
                            <li>Speciality store from Bromaati Agre Ltd.</li>
                            <li>We know our chicken because we grow our chicken.</li>
                            <li>Minimum investment & maximum profit.</li>
                            <li>Best quality feed used</li>
                            <li>Quality control from farm-to-fork</li>
                            <li>Effective supply chain management</li>
                            <li>ISO 9001 certified plant</li>
                            <li>ISO 22000 certified plant</li>
                            <li>Halal certified products</li>
                            <li>30+ years of experience in chicken business</li>
                            <li>Speciality store from Bromaati Agre Ltd.</li>
                            <li>We know our chicken because we grow our chicken.</li>
                            <li>Minimum investment & maximum profit.</li>
                            <li>Best quality feed used</li>
                            <li>Quality control from farm-to-fork</li>
                            <li>Effective supply chain management</li>
                            <li>ISO 9001 certified plant</li>
                            <li>ISO 22000 certified plant</li>
                            <li>Halal certified products</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="container py-4">
            <div class="row gx-4 gy-4">
                <div class="col-md-4 col-sm-12">
                    <div class="style-card p-3 shadow-sm bg-white rounded">
                        <h5 style="color: #ff006b; margin-bottom: 10px; font-size: 18px;">
                            Product Detail
                        </h5>
                        <p>
                            Our food product range includes
                            <strong>Organic Basmati Rice</strong>, known for its
                            aroma and perfect texture for biryani. Cook
                            healthier meals with
                            <strong>Cold-Pressed Coconut Oil</strong>, free from
                            additives and rich in natural goodness. For quick
                            yet nutritious dinners, try
                            <strong>Whole Wheat Pasta</strong>, a fiber-rich
                            alternative. Enjoy creamy, protein-packed
                            <strong>Almond Butter</strong> with no added sugar.
                            Spice up your dishes with our
                            <strong>Spice Mix Combo</strong>, featuring
                            essential Indian spices. For special diets, we offer
                            <strong>Gluten-Free Bread</strong> made with millet
                            and rice flour. And when time is tight, grab our
                            <strong>Instant Soup Packets</strong>—flavorful and
                            ready in minutes.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="style-card p-3 shadow-sm bg-white rounded">
                        <h5 style="color: #ff006b; margin-bottom: 10px;font-size: 18px;">
                            Product Detail
                        </h5>
                        <p>
                            Our food product range includes
                            <strong>Organic Basmati Rice</strong>, known for its
                            aroma and perfect texture for biryani. Cook
                            healthier meals with
                            <strong>Cold-Pressed Coconut Oil</strong>, free from
                            additives and rich in natural goodness. For quick
                            yet nutritious dinners, try
                            <strong>Whole Wheat Pasta</strong>, a fiber-rich
                            alternative. Enjoy creamy, protein-packed
                            <strong>Almond Butter</strong> with no added sugar.
                            Spice up your dishes with our
                            <strong>Spice Mix Combo</strong>, featuring
                            essential Indian spices. For special diets, we offer
                            <strong>Gluten-Free Bread</strong> made with millet
                            and rice flour. And when time is tight, grab our
                            <strong>Instant Soup Packets</strong>—flavorful and
                            ready in minutes.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="style-card p-3 shadow-sm bg-white rounded">
                        <h5 style="color: #ff006b; margin-bottom: 10px;font-size: 18px;">
                            Product Detail
                        </h5>
                        <p>
                            Our food product range includes
                            <strong>Organic Basmati Rice</strong>, known for its
                            aroma and perfect texture for biryani. Cook
                            healthier meals with
                            <strong>Cold-Pressed Coconut Oil</strong>, free from
                            additives and rich in natural goodness. For quick
                            yet nutritious dinners, try
                            <strong>Whole Wheat Pasta</strong>, a fiber-rich
                            alternative. Enjoy creamy, protein-packed
                            <strong>Almond Butter</strong> with no added sugar.
                            Spice up your dishes with our
                            <strong>Spice Mix Combo</strong>, featuring
                            essential Indian spices. For special diets, we offer
                            <strong>Gluten-Free Bread</strong> made with millet
                            and rice flour. And when time is tight, grab our
                            <strong>Instant Soup Packets</strong>—flavorful and
                            ready in minutes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="padding-top: 3rem; padding-right: 0; padding-left: 0">
            <Ourshops />
        </div>
    </div>
</template>

<script>
import Ourshops from "./ourshops.vue";
import Top_banners from "./top_banners.vue";
import axios from "axios";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import {
    BRow,
    BCol,
    BCard,
    BButton,
    BSpinner,
    BForm,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BFormInvalidFeedback,
    BFormSelect,
} from "bootstrap-vue";

export default {
    name: "BulkOrders",
    components: {
        Ourshops,
        Top_banners,
        BRow,
        BCol,
        BCard,
        BButton,
        BSpinner,
        BForm,
        BFormGroup,
        BFormInput,
        BFormTextarea,
        BFormInvalidFeedback,
        BFormSelect,
    },
    data() {
        return {
            form: {
                name: "",
                email_id: "",
                phone_1: "",
                phone_2: "",
                address: "",
                state: "",
                district: "",
                taluk: "",
                pincode: "",
                business_category_id: null,
                product_id: null,
                message: "",
            },
            categoryOptions: [],
            productOptions: [],
            submitting: false,
            loadingProducts: false,
            submitted: false,
        };
    },
    computed: {
        // nameState() {
        //     return this.form.name ? true : false
        // },

        // emailState() {
        //     if (!this.form.email_id) return false
        //     const re = /^\S+@\S+\.\S+$/
        //     return re.test(this.form.email_id)
        // },

        // phone1State() {
        //     return this.form.phone_1 ? true : false
        // },
        // addressState() {
        //     return this.form.address ? true : false
        // },
        // stateState() {
        //     return this.form.state ? true : false
        // },
        // districtState() {
        //     return this.form.district ? true : false
        // },
        // talukState() {
        //     return this.form.taluk ? true : false
        // },
        // pincodeState() {
        //     return this.form.pincode ? true : false
        // },
        // categoryState() {
        //     return this.form.business_category_id ? true : false
        // },
        // productState() {
        //     return this.form.product_id ? true : false
        // }
        nameState() {
            if (!this.submitted) return null;
            return this.form.name ? true : false;
        },
        emailState() {
            if (!this.submitted) return null;
            if (!this.form.email_id) return false;
            const re = /^\S+@\S+\.\S+$/;
            return re.test(this.form.email_id);
        },
        phone1State() {
            if (!this.submitted) return null;
            return this.form.phone_1 ? true : false;
        },
        addressState() {
            if (!this.submitted) return null;
            return this.form.address ? true : false;
        },
        stateState() {
            if (!this.submitted) return null;
            return this.form.state ? true : false;
        },
        districtState() {
            if (!this.submitted) return null;
            return this.form.district ? true : false;
        },
        talukState() {
            if (!this.submitted) return null;
            return this.form.taluk ? true : false;
        },
        pincodeState() {
            if (!this.submitted) return null;
            return this.form.pincode ? true : false;
        },
        categoryState() {
            if (!this.submitted) return null;
            return this.form.business_category_id ? true : false;
        },
        productState() {
            if (!this.submitted) return null;
            return this.form.product_id ? true : false;
        },
    },
    methods: {
        async fetchCategories() {
            try {
                const business_id = localStorage.getItem("single_business_id");
                const response = await axios.get(
                    `/category_lists/${business_id}`
                );
                this.categoryOptions = response.data.data.map((category) => ({
                    value: category.id,
                    text: category.name || category.text || category.title,
                }));
            } catch (error) {
                console.error("Failed to fetch categories:", error);
                this.$toast.error("Failed to load categories", {
                    timeout: 3000,
                    position: "top-right",
                });
            }
        },
        async fetchProducts(categoryId) {
            if (!categoryId) {
                this.productOptions = [];
                this.form.product_id = null;
                return;
            }

            this.loadingProducts = true;
            try {
                const response = await axios.get(
                    `/product_list_by_category/${categoryId}`
                );
                this.productOptions = response.data.data.map((product) => ({
                    value: product.id,
                    text: product.name || product.text || product.title,
                }));
            } catch (error) {
                console.error("Error fetching products:", error);
                this.$toast.error("Failed to load products", {
                    timeout: 3000,
                    position: "top-right",
                });
            } finally {
                this.loadingProducts = false;
            }
        },
        onCategoryChange(categoryId) {
            this.form.product_id = null;
            this.fetchProducts(categoryId);
        },
        async submitApplication() {
            this.submitted = true;
            // Validate all required fields
            if (
                !this.nameState ||
                !this.emailState ||
                !this.phone1State ||
                !this.addressState ||
                !this.stateState ||
                !this.districtState ||
                !this.talukState ||
                !this.pincodeState ||
                !this.categoryState ||
                !this.productState
            ) {
                this.$toast.error("Please fill all required fields", {
                    timeout: 3000,
                    position: "top-right",
                });
                return;
            }

            this.submitting = true;
            try {
                const response = await axios.post(
                    "/api/bulk_enquiry",
                    this.form
                );

                if (response.status === 200 || response.status === 201) {
                    this.$toast.success("Form submitted successfully!", {
                        timeout: 3000,
                        position: "top-right",
                    });
                    this.resetForm();
                } else {
                    this.$toast.error(
                        "Something went wrong. Please try again.",
                        {
                            timeout: 3000,
                            position: "top-right",
                        }
                    );
                }
            } catch (error) {
                console.error("Error submitting form:", error);
                let errorMessage = "Failed to submit form";
                if (error.response) {
                    errorMessage = error.response.data.message || errorMessage;
                }
                this.$toast.error(errorMessage, {
                    timeout: 3000,
                    position: "top-right",
                });
            } finally {
                this.submitting = false;
            }
        },
        resetForm() {
            this.form = {
                name: "",
                email_id: "",
                phone_1: "",
                phone_2: "",
                address: "",
                state: "",
                district: "",
                taluk: "",
                pincode: "",
                business_category_id: null,
                product_id: null,
                message: "",
            };
        },
    },
    mounted() {
        this.fetchCategories();
    },
};
</script>

<style scoped>
.top-banner-wrapper {
    padding: 2rem 1.5rem;
    background-color: #fff;
    max-width: 1280px;
    margin: 0 auto;
}

.apply-form {
    background-color: #ffffff;
    padding: 2.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    max-width: 960px;
    margin: 3rem auto;
    scroll-margin-top: 100px;
    transition: all 0.3s ease-in-out;
}

/* General banner style */
.banner-img {
    width: 100%;
    border-radius: 10px;
    object-fit: cover;
}

/* Headings */
.text-center.mb-4 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #212529;
    margin-top: 2rem;
}

/* Responsive spacing */
@media (max-width: 768px) {
    .apply-form {
        padding: 1.5rem;
        margin: 2rem 1rem;
    }

    .top-banner-wrapper {
        padding: 1.5rem 1rem;
    }

    .text-center.mb-4 {
        font-size: 1.5rem;
    }
}

.b-form-input,
.b-form-textarea,
.b-form-select,
.form-control {
    border: 1.5px solid #ff006b !important;
    border-radius: 6px !important;
    padding: 0.5rem 0.75rem !important;
    box-shadow: none !important;
    background-color: #fff !important;
    font-size: 16px;
    color: rgba(0, 0, 0, 0.78);
    height: auto;
}

.b-form-textarea {
    height: auto !important;
}

.b-form-select:disabled {
    background-color: #ff0000 !important;
}

.b-form-input:focus,
.b-form-textarea:focus,
.b-form-select:focus {
    border-color: #333 !important;
    box-shadow: none !important;
}

.compact-textarea {
    padding: 4px 8px !important;
    font-size: 14px !important;
    resize: none !important;
}

.style-card {
    border: 2px solid lightgrey;
    margin-top: 20px;
}
</style>

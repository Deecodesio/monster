<template>
    <div>
      <div class="mt-5 mx-3 py-6">
        <top_banners /></div>
        <h4 class="text-center mb-4" style="font-size: x-large">
            Bulk Orders
        </h4>
        <div id="apply-form" class="apply-form mt-5">
            <b-form @submit.prevent="submitApplication">
                <b-card>
                    <b-row>
                        <b-col md="6">
                            <b-form-group label="Full Name" label-for="name">
                                <b-form-input id="name" v-model="form.name" :state="nameState" required></b-form-input>
                                <b-form-invalid-feedback v-if="!nameState">
                                    Name is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Email" label-for="email_id">
                                <b-form-input id="email_id" v-model="form.email_id" type="email" :state="emailState"
                                    required></b-form-input>
                                <b-form-invalid-feedback v-if="!emailState">
                                    Please enter a valid email
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Primary Phone Number" label-for="phone_1">
                                <b-form-input id="phone_1" v-model="form.phone_1" :state="phone1State"
                                    required></b-form-input>
                                <b-form-invalid-feedback v-if="!phone1State">
                                    Phone number is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Alternative Phone Number (Optional)" label-for="phone_2">
                                <b-form-input id="phone_2" v-model="form.phone_2"></b-form-input>
                            </b-form-group>
                        </b-col>

                        <b-col md="12">
                            <b-form-group label="Address" label-for="address">
                                <b-form-textarea id="address" v-model="form.address" rows="3" :state="addressState"
                                    required></b-form-textarea>
                                <b-form-invalid-feedback v-if="!addressState">
                                    Address is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="State" label-for="state">
                                <b-form-input id="state" v-model="form.state" :state="stateState"
                                    required></b-form-input>
                                <b-form-invalid-feedback v-if="!stateState">
                                    State is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="District" label-for="district">
                                <b-form-input id="district" v-model="form.district" :state="districtState"
                                    required></b-form-input>
                                <b-form-invalid-feedback v-if="!districtState">
                                    District is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Taluk" label-for="taluk">
                                <b-form-input id="taluk" v-model="form.taluk" :state="talukState"
                                    required></b-form-input>
                                <b-form-invalid-feedback v-if="!talukState">
                                    Taluk is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Pincode" label-for="pincode">
                                <b-form-input id="pincode" v-model="form.pincode" :state="pincodeState"
                                    required></b-form-input>
                                <b-form-invalid-feedback v-if="!pincodeState">
                                    Pincode is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Product Category" label-for="business_category_id">
                                <b-form-select
                                    id="business_category_id"
                                    v-model="form.business_category_id"
                                    :options="categoryOptions"
                                    :state="categoryState"
                                    required
                                    class="form-control"
                                    @change="onCategoryChange"
                                >
                                    <template #first>
                                        <option :value="null" disabled>Select a category</option>
                                    </template>
                                </b-form-select>
                                <b-form-invalid-feedback v-if="!form.business_category_id">
                                    Product Category is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="6">
                            <b-form-group label="Product" label-for="product_id">
                                <b-form-select
                                    id="product_id"
                                    v-model="form.product_id"
                                    :options="productOptions"
                                    :state="productState"
                                    :disabled="!form.business_category_id"
                                    required
                                    class="form-control"
                                >
                                    <template #first>
                                        <option :value="null" disabled>Select a product</option>
                                    </template>
                                </b-form-select>
                                <b-form-invalid-feedback v-if="!form.product_id">
                                    Product is required
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col md="12">
                            <b-form-group label="Additional Message (Optional)" label-for="message">
                                <b-form-textarea id="message" v-model="form.message" rows="3"></b-form-textarea>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" class="mt-3">
                            <b-button type="submit" variant="primary" :disabled="submitting">
                                <b-spinner small v-if="submitting" class="mr-1"></b-spinner>
                                {{ submitting ? 'Submitting...' : 'Submit Application' }}
                            </b-button>
                        </b-col>
                    </b-row>
                </b-card>
            </b-form>
        </div>
        <div class="container-fluid" style="padding-top: 3rem; padding-left: 0">
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
import { BRow, BCol, BCard, BButton, BSpinner, BForm, BFormGroup, BFormInput, BFormTextarea, BFormInvalidFeedback, BFormSelect } from 'bootstrap-vue'

export default {
    name: 'BulkOrders',
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
        BFormSelect
    },
    data() {
        return {
            form: {
                name: '',
                email_id: '',
                phone_1: '',
                phone_2: '',
                address: '',
                state: '',
                district: '',
                taluk: '',
                pincode: '',
                business_category_id: null,
                product_id: null,
                message: ''
            },
            categoryOptions: [],
            productOptions: [],
            submitting: false,
            loadingProducts: false
        }
    },
    computed: {
        nameState() {
            return this.form.name ? true : false
        },
        emailState() {
            if (!this.form.email_id) return false
            const re = /^\S+@\S+\.\S+$/
            return re.test(this.form.email_id)
        },
        phone1State() {
            return this.form.phone_1 ? true : false
        },
        addressState() {
            return this.form.address ? true : false
        },
        stateState() {
            return this.form.state ? true : false
        },
        districtState() {
            return this.form.district ? true : false
        },
        talukState() {
            return this.form.taluk ? true : false
        },
        pincodeState() {
            return this.form.pincode ? true : false
        },
        categoryState() {
            return this.form.business_category_id ? true : false
        },
        productState() {
            return this.form.product_id ? true : false
        }
    },
    methods: {
        async fetchCategories() {
            try {
                const business_id = localStorage.getItem("single_business_id");
                const response = await axios.get(`/category_lists/${business_id}`);
                this.categoryOptions = response.data.data.map(category => ({
                    value: category.id,
                    text: category.name || category.text || category.title
                }));
            } catch (error) {
                console.error("Failed to fetch categories:", error);
                this.$toast.error("Failed to load categories", {
                    timeout: 3000,
                    position: "top-right"
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
                const response = await axios.get(`http://localhost:3000/product_list_by_category/${categoryId}`);
                this.productOptions = response.data.data.map(product => ({
                    value: product.id,
                    text: product.name || product.text || product.title
                }));
            } catch (error) {
                console.error("Error fetching products:", error);
                this.$toast.error("Failed to load products", {
                    timeout: 3000,
                    position: "top-right"
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
            // Validate all required fields
            if (!this.nameState || !this.emailState || !this.phone1State ||
                !this.addressState || !this.stateState || !this.districtState ||
                !this.talukState || !this.pincodeState || !this.categoryState || !this.productState) {
                this.$toast.error("Please fill all required fields", {
                    timeout: 3000,
                    position: "top-right"
                });
                return;
            }

            this.submitting = true;
            try {
                const response = await axios.post("http://127.0.0.1:8000/api/bulk_enquiry", this.form);

                if (response.status === 200 || response.status === 201) {
                    this.$toast.success("Form submitted successfully!", {
                        timeout: 3000,
                        position: "top-right"
                    });
                    this.resetForm();
                } else {
                    this.$toast.error("Something went wrong. Please try again.", {
                        timeout: 3000,
                        position: "top-right"
                    });
                }
            } catch (error) {
                console.error("Error submitting form:", error);
                let errorMessage = "Failed to submit form";
                if (error.response) {
                    errorMessage = error.response.data.message || errorMessage;
                }
                this.$toast.error(errorMessage, {
                    timeout: 3000,
                    position: "top-right"
                });
            } finally {
                this.submitting = false;
            }
        },
        resetForm() {
            this.form = {
                name: '',
                email_id: '',
                phone_1: '',
                phone_2: '',
                address: '',
                state: '',
                district: '',
                taluk: '',
                pincode: '',
                business_category_id: null,
                product_id: null,
                message: ''
            };
        }
    },
    mounted() {
        this.fetchCategories();
    }
}
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
</style>

<template>
  <b-row style="margin-top: 150px">
        <b-col cols="12">
    <div class="franchise-list">
        <!-- Loading state -->
        <div v-if="loading" class="text-center">
            <b-spinner label="Loading..."></b-spinner>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <!-- Franchise content -->
        <div v-else>
            <!-- Banner Section -->
            <div class="banner-section mb-5">
                <b-card no-body class="text-center">
                    <b-card-body class="py-2">
                        <h1 class="display-4 mb-2">
                            Become a Franchise Partner
                        </h1>
                        <p class="lead mb-2">
                            Join our growing network of successful franchise
                            partners across the country
                        </p>
                        <b-button variant="primary" size="lg" href="#apply-form"
                            >Apply Now</b-button
                        >
                    </b-card-body>
                </b-card>
            </div>

            <!-- Benefits Section -->
            <!-- <h3 class="mb-4">WHY PARTNER WITH US</h3>
      <b-row class="mb-5">
        <b-col md="4" class="mb-4">
          <b-card class="h-100 text-center">
            <b-icon icon="graph-up" font-scale="3" class="mb-3"></b-icon>
            <h4>Proven Business Model</h4>
            <p>Join a successful brand with established operations and customer base</p>
          </b-card>
        </b-col>
        <b-col md="4" class="mb-4">
          <b-card class="h-100 text-center">
            <b-icon icon="people" font-scale="3" class="mb-3"></b-icon>
            <h4>Comprehensive Support</h4>
            <p>Get training, marketing, and operational support throughout your journey</p>
          </b-card>
        </b-col>
        <b-col md="4" class="mb-4">
          <b-card class="h-100 text-center">
            <b-icon icon="cash-stack" font-scale="3" class="mb-3"></b-icon>
            <h4>Attractive Returns</h4>
            <p>Benefit from multiple revenue streams and competitive profit margins</p>
          </b-card>
        </b-col>
      </b-row> -->

            <!-- Apply Form Section -->
            <div id="apply-form" class="apply-form mt-5">
                <h3 class="mb-4">APPLY FOR FRANCHISE</h3>
                <b-alert
                    v-model="showSuccessAlert"
                    variant="success"
                    dismissible
                    style="padding: 5px !important"
                >
                    Your franchise application has been submitted successfully!
                    We will contact you soon.
                </b-alert>

                <b-form @submit.prevent="submitApplication">
                    <b-card>
                        <b-row>
                            <b-col md="6">
                                <b-form-group
                                    label="Full Name"
                                    label-for="name"
                                >
                                    <b-form-input
                                        id="name"
                                        v-model="form.name"
                                        :state="nameState"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback v-if="!nameState">
                                        Name is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group
                                    label="Email"
                                    label-for="email_id"
                                >
                                    <b-form-input
                                        id="email_id"
                                        v-model="form.email_id"
                                        type="email"
                                        :state="emailState"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback v-if="!emailState">
                                        Please enter a valid email
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group
                                    label="Primary Phone Number"
                                    label-for="phone_1"
                                >
                                    <b-form-input
                                        id="phone_1"
                                        v-model="form.phone_1"
                                        :state="phone1State"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback
                                        v-if="!phone1State"
                                    >
                                        Phone number is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group
                                    label="Alternative Phone Number (Optional)"
                                    label-for="phone_2"
                                >
                                    <b-form-input
                                        id="phone_2"
                                        v-model="form.phone_2"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>

                            <b-col md="12">
                                <b-form-group
                                    label="Address"
                                    label-for="address"
                                >
                                    <b-form-textarea
                                        id="address"
                                        v-model="form.address"
                                        rows="3"
                                        :state="addressState"
                                        required
                                    ></b-form-textarea>
                                    <b-form-invalid-feedback
                                        v-if="!addressState"
                                    >
                                        Address is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group label="State" label-for="state">
                                    <b-form-input
                                        id="state"
                                        v-model="form.state"
                                        :state="stateState"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback v-if="!stateState">
                                        State is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group
                                    label="District"
                                    label-for="district"
                                >
                                    <b-form-input
                                        id="district"
                                        v-model="form.district"
                                        :state="districtState"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback
                                        v-if="!districtState"
                                    >
                                        District is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group label="Taluk" label-for="taluk">
                                    <b-form-input
                                        id="taluk"
                                        v-model="form.taluk"
                                        :state="talukState"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback v-if="!talukState">
                                        Taluk is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="6">
                                <b-form-group
                                    label="Pincode"
                                    label-for="pincode"
                                >
                                    <b-form-input
                                        id="pincode"
                                        v-model="form.pincode"
                                        :state="pincodeState"
                                        required
                                    ></b-form-input>
                                    <b-form-invalid-feedback
                                        v-if="!pincodeState"
                                    >
                                        Pincode is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="12">
                                <b-form-group
                                    label="Proposed Shop Location Plan"
                                    label-for="shop_location_plan"
                                >
                                    <b-form-textarea
                                        id="shop_location_plan"
                                        v-model="form.shop_location_plan"
                                        rows="3"
                                        :state="shopLocationState"
                                        required
                                    ></b-form-textarea>
                                    <b-form-invalid-feedback
                                        v-if="!shopLocationState"
                                    >
                                        Shop location plan is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col md="12">
                                <b-form-group
                                    label="Additional Message (Optional)"
                                    label-for="message"
                                >
                                    <b-form-textarea
                                        id="message"
                                        v-model="form.message"
                                        rows="3"
                                    ></b-form-textarea>
                                </b-form-group>
                            </b-col>

                            <b-col cols="12" class="mt-3">
                                <b-button
                                    type="submit"
                                    variant="primary"
                                    :disabled="submitting"
                                >
                                    <b-spinner
                                        small
                                        v-if="submitting"
                                        class="mr-1"
                                    ></b-spinner>
                                    {{
                                        submitting
                                            ? "Submitting..."
                                            : "Submit Application"
                                    }}
                                </b-button>
                            </b-col>
                        </b-row>
                    </b-card>
                </b-form>
            </div>
        </div>
    </div>
       <!-- Shop Location Div -->
    <div
        class="container-fluid"
        style="margin-top: 50px; padding-right: 0; padding-left: 0"
    >
        <Ourshops />
    </div>
    </b-col>
        </b-row>
</template>

<script>
import { ref, computed, onMounted } from "@vue/composition-api";
import Ourshops from "../ourshops.vue";
import {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BButton,
    BSpinner,
    BIcon,
    BForm,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BFormInvalidFeedback,
    BAlert,
} from "bootstrap-vue";
import axios from "axios";

export default {
    name: "FranchiseList",
    components: {
        BRow,
        BCol,
        BCard,
        BCardBody,
        BButton,
        BSpinner,
        BIcon,
        BForm,
        BFormGroup,
        BFormInput,
        BFormTextarea,
        BFormInvalidFeedback,
        BAlert,
        Ourshops,
    },
    setup() {
        const loading = ref(false);
        const error = ref(null);
        const submitting = ref(false);
        const showSuccessAlert = ref(false);

        const form = ref({
            name: "",
            email_id: "",
            phone_1: "",
            phone_2: "",
            address: "",
            state: "",
            district: "",
            taluk: "",
            pincode: "",
            shop_location_plan: "",
            message: "",
        });

        const nameState = computed(() => {
            return form.value.name ? true : null;
        });

        const emailState = computed(() => {
            if (!form.value.email_id) return null;
            const re = /^\S+@\S+\.\S+$/;
            return re.test(form.value.email_id);
        });

        const phone1State = computed(() => {
            return form.value.phone_1 ? true : null;
        });

        const addressState = computed(() => {
            return form.value.address ? true : null;
        });

        const stateState = computed(() => {
            return form.value.state ? true : null;
        });

        const districtState = computed(() => {
            return form.value.district ? true : null;
        });

        const talukState = computed(() => {
            return form.value.taluk ? true : null;
        });

        const pincodeState = computed(() => {
            return form.value.pincode ? true : null;
        });

        const shopLocationState = computed(() => {
            return form.value.shop_location_plan ? true : null;
        });

        const submitApplication = async () => {
            // Validate form
            if (
                !nameState.value ||
                !emailState.value ||
                !phone1State.value ||
                !addressState.value ||
                !stateState.value ||
                !districtState.value ||
                !talukState.value ||
                !pincodeState.value ||
                !shopLocationState.value
            ) {
                return;
            }

            try {
                submitting.value = true;

                await axios.post("/api/franchises", form.value);

                // Reset form
                form.value = {
                    name: "",
                    email_id: "",
                    phone_1: "",
                    phone_2: "",
                    address: "",
                    state: "",
                    district: "",
                    taluk: "",
                    pincode: "",
                    shop_location_plan: "",
                    message: "",
                };

                // Show success message
                showSuccessAlert.value = true;

                // Scroll to top of form
                window.scrollTo({
                    top: document.querySelector(".apply-form").offsetTop - 100,
                    behavior: "smooth",
                });

                submitting.value = false;
            } catch (err) {
                error.value = "Error submitting application: " + err.message;
                submitting.value = false;
                console.error("Error submitting franchise application:", err);
            }
        };

        onMounted(() => {
            // Nothing to fetch on mount for this component
        });

        return {
            loading,
            error,
            form,
            nameState,
            emailState,
            phone1State,
            addressState,
            stateState,
            districtState,
            talukState,
            pincodeState,
            shopLocationState,
            submitting,
            submitApplication,
            showSuccessAlert,
        };
    },
};
</script>

<style lang="scss" scoped>
.franchise-list {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;

}

.banner-section {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}

.apply-form {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
    margin-top: 3rem;
    margin-left: 120px;
    margin-right: 120px;
    scroll-margin-top: 100px;
}
.b-form-input,
.b-form-textarea,
.b-form-select,
.form-control {
  border: 1.5px solid #FF006B !important;  
  border-radius: 8px !important;     
  padding: 10px 12px !important;     
  font-size: 16px !important;         
  background-color: #fff !important;  
  box-shadow: none !important;    
  color: rgba(0, 0, 0, 0.780) !important;    
}

/* On focus */
.b-form-input:focus,
.b-form-textarea:focus,
.b-form-select:focus,
.form-control:focus {
    /* Change focus color if needed */
  box-shadow: none !important;
}
.b-form-group .form-control {
  max-width: 300px !important; /* Set your desired width */
  width: 100%; /* Allows responsive shrinking if needed */
}
</style>

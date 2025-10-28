<template>
        <div class="career-list container">
            <div class="mt-5 mx-3 py-6">
                <top_banners />
            </div>
            <!-- Loading -->
            <div v-if="loading" class="text-center">
                <b-spinner label="Loading..."></b-spinner>
            </div>

            <!-- Error -->
            <div v-else-if="error" class="alert alert-danger">
                {{ error }}
            </div>

            <!-- Main Content -->
            <div v-else>
                <div class="text-center my-4">
                    <h5 class="job-title">Career Opportunities</h5>
                </div>

                <!-- Search -->
                <div class="mb-5" style="margin-top: 50px">
                    <div class="d-flex justify-content-center">
                        <div style="max-width: 800px; width: 100%;">
                            <b-card-body class="custom-card" style="padding: 2rem 1rem; box-shadow: none !important;">
                                <b-row class="justify-content-center align-items-center">
                                    <b-col cols="10" md="4" class="d-flex justify-content-center mb-3 mb-md-0">
                                        <b-form-group class="w-100 mb-0">
                                            <b-form-select v-model="selectedCategory" :options="categoryOptions"
                                                class="custom-select-border uniform-control"></b-form-select>
                                        </b-form-group>
                                    </b-col>

                                    <b-col cols="12" md="4" class="d-flex justify-content-center mb-3 mb-md-0">
                                        <b-form-group class="w-100 mb-0">
                                            <b-form-select v-model="selectedLocation" :options="locationOptions"
                                                class="custom-select-border uniform-control"></b-form-select>
                                        </b-form-group>
                                    </b-col>

                                    <b-col cols="12" md="4" class="d-flex justify-content-center">
                                        <b-button class="uniform-control" variant="primary" @click="searchJobs" style="
                                                font-size: 16px !important;
                                                font-weight: bold !important;
                                            ">
                                            SEARCH JOBS
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-card-body>
                        </div>
                    </div>
                </div>


                <!-- Job Details View -->
                <div v-if="selectedJobDetails">
                    <b-card class="mb-4">
                        <b-button variant="light" class="pink-button" @click="backToList">
                            &larr; Back to Job List
                        </b-button>
                        <br />
                        <h3 class="mb-3" style="margin-top: 3rem;">{{ selectedJobDetails.job_name }}</h3>
                        <p><strong>Location: {{ (selectedJobDetails.city && typeof selectedJobDetails.city === 'object' && selectedJobDetails.city.city) ? selectedJobDetails.city.city : (selectedJobDetails.location_name || 'N/A') }} </strong></p>
                        <p><strong>Posted On: {{ formatDate(selectedJobDetails.created_at) }} </strong></p>
                        <div v-if="selectedJobDetails.job_details">
                            <strong>Description:</strong>
                            <div v-html="selectedJobDetails.job_details"></div>
                        </div>
                    </b-card>

                    <div class="mt-5" style="max-width: 850px;min-width:300px;">
                        <p class="mb-4" style="color: black; font-size: 28px; font-weight: 600;">Apply Online</p>

                        <b-form @submit.prevent="submitApplication">
                            <b-form-group>
                                <b-row class="align-items-center mb-3">
                                    <b-col md="3">
                                        <label for="name" class="form-label-custom">
                                            Name <span class="text-danger">*</span>
                                        </label>
                                    </b-col>

                                    <b-col md="6">
                                        <b-form-input id="name" v-model="form.name" :state="nameState" required
                                            class="custom-input" />
                                        <b-form-invalid-feedback v-if="!nameState">Name is
                                            required</b-form-invalid-feedback>
                                    </b-col>
                                </b-row>

                                <b-row class="align-items-center mb-3">
                                    <b-col md="3">
                                        <label for="email" class="form-label-custom">Email ID<span
                                                class="text-danger">*</span></label>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-input id="email" v-model="form.email" type="email" :state="emailState"
                                            required class="custom-input" />
                                        <b-form-invalid-feedback v-if="!emailState">Please enter a valid
                                            email</b-form-invalid-feedback>
                                    </b-col>
                                </b-row>

                                <b-row class="align-items-center mb-3">
                                    <b-col md="3">
                                        <label for="contact_number" class="form-label-custom">Contact Number <span
                                                class="text-danger">*</span></label>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-input id="contact_number" v-model="form.contact_number"
                                            :state="contactState" required class="custom-input" />
                                        <b-form-invalid-feedback v-if="!contactState">Contact number is
                                            required</b-form-invalid-feedback>
                                    </b-col>
                                </b-row>

                                <!-- <b-row class="align-items-center mb-3">
                                    <b-col md="3">
                                        <label for="resume" class="form-label-custom">Attach Resume <span
                                                class="text-danger">*</span></label>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-file id="resume" v-model="form.resume" :state="resumeState" accept=".pdf"
                                            placeholder="Choose a file or drop it here..."
                                            drop-placeholder="Drop file here..." required />
                                        <b-form-invalid-feedback v-if="!resumeState">Please upload a PDF file (max
                                            5MB)</b-form-invalid-feedback>
                                    </b-col>
                                </b-row> -->
                                <b-row class="align-items-center mb-3">
                                    <b-col md="3">
                                        <label for="resume" class="form-label-custom"
                                            style="padding-bottom: 65px !important;">
                                            Attach Resume <span class="text-danger">*</span>
                                        </label>
                                    </b-col>
                                    <b-col md="6">

                                        <input id="resume" ref="fileInput" type="file" accept=".pdf"
                                            @change="handleFileChange" style="display: none" />

                                        <div class="custom-file-wrapper" @click="triggerFileInput">
                                            <input type="text" :value="fileName" placeholder="No file chosen"
                                                readonly />
                                            <button type="button">Browse</button>

                                        </div>
                                        <div class="text-right mt-3">
                                            <b-button type="submit" class="btn-black" :disabled="submitting">
                                                <b-spinner small v-if="submitting" class="mr-1"></b-spinner>
                                                Submit
                                            </b-button>
                                        </div>



                                        <!-- <div v-if="!resumeState" class="invalid-feedback d-block">
                                            Please upload a PDF file (max 5MB)
                                        </div> -->
                                    </b-col>
                                </b-row>

                                <b-row class="justify-content-end">
                                    <b-col md="6" class="text-right">

                                    </b-col>
                                </b-row>

                            </b-form-group>
                        </b-form>
                    </div>
                </div>

                <!-- Job Table or No Result -->
                <b-row v-else-if="showJobs && (selectedLocation || selectedCategory)">
                    <b-col v-if="jobs.length === 0" cols="12" class="text-center">
                        <h5 class="pink-text mb-0">No jobs available in this location.</h5>
                    </b-col>
                    <b-col v-else cols="12">
                        <b-table :items="jobs" :fields="fields" bordered striped responsive thead-class="custom-table-header">
                            <template #cell(job_name)="data">
                                <span @click="goToCareerDetails(data.item)" style="cursor: pointer;  ">
                                    <strong>{{ data.item.job_name }}</strong>
                                </span>
                            </template>
                            <template #cell(location_name)="data">
                                {{ (data.item.city && typeof data.item.city === 'object' && data.item.city.city) ? data.item.city.city : (data.item.location_name || 'N/A') }}
                            </template>
                            <template #cell(created_at)="data">
                                <strong>{{ formatDate(data.item.created_at) }}</strong>
                            </template>
                        </b-table>
                    </b-col>
                </b-row>

                <!-- Pagination -->
                <div v-if="showJobs && selectedLocation && selectedCategory && jobs.length && !selectedJobDetails"
                    class="d-flex justify-content-center mt-4">
                    <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage"
                        @change="handlePageChange" class="custom-pagination" />
                </div>
            </div>
        </div>
    </template>

<script>
import { ref, onMounted, computed } from "@vue/composition-api";
import Top_banners from "../top_banners.vue";
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BButton,
    BSpinner,
    BPagination,
    BFormSelect,
    BFormGroup,
    BForm,
    BFormInput,
    BFormFile,
    BFormInvalidFeedback,
    BTable,
    BIcon,
} from "bootstrap-vue";
import axios from "axios";

export default {
    name: "CareerList",
    components: {
        Top_banners,
        BRow,
        BCol,
        BCard,
        BCardBody,
        BButton,
        BSpinner,
        BPagination,
        BFormSelect,
        BFormGroup,
        BForm,
        BFormInput,
        BFormFile,
        BFormInvalidFeedback,
        BTable,
        BIcon,
    },
    methods: {
        async goToCareerDetails(job) {
            // Fetch job details from API and show in-page
            this.loading = true;
            try {
                const response = await axios.get(`/api/career-jobs/${job.id}`);
                this.selectedJobDetails = response.data.data || response.data;
            } catch (err) {
                this.error = "Error loading job details: " + err.message;
            } finally {
                this.loading = false;
            }
        },
        backToList() {
            this.selectedJobDetails = null;
        }
    },

    setup(_, { root }) {
        const jobs = ref([]);
        const loading = ref(false);
        const error = ref(null);
        const currentPage = ref(1);
        const perPage = ref(10);
        const totalRows = ref(0);
        const selectedLocation = ref("");
        const selectedCategory = ref("");
        const locationOptions = ref([{ value: "", text: "Select location", disabled: true }]);
        const categoryOptions = ref([{ value: "", text: "Select category", disabled: true }]);
        const showJobs = ref(false);
        const selectedJobDetails = ref(null);
        const fileName = ref("");
        const submitting = ref(false);

        const form = ref({
            name: "",
            email: "",
            contact_number: "",
            resume: null,
            career_job_id: selectedJobDetails.value?.id || '',
        });

        const nameState = computed(() => (form.value.name ? true : null));
        const emailState = computed(() => {
            if (!form.value.email) return null;
            const re = /^\S+@\S+\.\S+$/;
            return re.test(form.value.email);
        });
        const contactState = computed(() =>
            form.value.contact_number ? true : null
        );

        const fields = [
            { key: "job_name", label: "Job Title" },
            { key: "location_name", label: "Location" },
            { key: "created_at", label: "Posted On" },
        ];

        const fetchJobs = async (page = 1) => {
            try {
                loading.value = true;
                const response = await axios.get(`/api/career-jobs?page=${page}`);
                jobs.value = response.data.data || [];
                totalRows.value = response.data.total;
                currentPage.value = response.data.current_page;
                perPage.value = response.data.per_page;
                loading.value = false;
            } catch (err) {
                error.value = "Error loading jobs: " + err.message;
                loading.value = false;
            }
        };

        const fetchLocations = async () => {
            try {
                const response = await axios.get("/restaurant_cities");
                locationOptions.value = [
                    { value: "", text: "Select location", disabled: false },
                    ...response.data.map((location) => ({
                        value: location.id,
                        text: location.city,
                    })),
                ];
            } catch (err) {
                console.error("Error fetching locations:", err);
            }
        };

        const fetchCategories = async () => {
            try {
                const response = await axios.get("/api/career-job-categories");
                categoryOptions.value = [
                    { value: "", text: "Select category", disabled: false },
                    ...response.data.data.map((category) => ({
                        value: category.id,
                        text: category.name,
                    })),
                ];
            } catch (err) {
                console.error("Error fetching categories:", err);
            }
        };

        const searchJobs = async (page = 1) => {
            showJobs.value = false;
            selectedJobDetails.value = null;
            if (!selectedLocation.value && !selectedCategory.value) {
                jobs.value = [];
                totalRows.value = 0;
                return;
            }
            try {
                loading.value = true;
                let url = `/api/career-jobs?page=${page}`;
                if (selectedLocation.value || selectedCategory.value) {
                    url += `&location_id=${selectedLocation.value}&category_id=${selectedCategory.value}`;
                }
                const response = await axios.get(url);
                jobs.value = response.data.data || [];
                totalRows.value = response.data.total || 0;
                currentPage.value = response.data.current_page || 1;
                perPage.value = response.data.per_page || 10;
                loading.value = false;
                showJobs.value = true;
            } catch (err) {
                error.value = "Error searching jobs: " + err.message;
                loading.value = false;
                showJobs.value = true;
            }
        };

        const handlePageChange = (page) => {
            searchJobs(page);
        };

        const handleFileChange = (event) => {
            const file = event.target.files[0];
            if (file) {
                form.value.resume = file;
                fileName.value = file.name;
            } else {
                form.value.resume = null;
                fileName.value = "";
            }
        };

        const formatDate = (date) => {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        };

        const triggerFileInput = () => {
            document.getElementById("resume").click();
        };

        onMounted(async () => {
            await fetchLocations();
            await fetchCategories();
            loading.value = false;
        });

        const submitApplication = async () => {
            if (
                !nameState.value ||
                !emailState.value ||
                !contactState.value ||
                !resumeState.value
            ) {
                return;
            }
            try {
                submitting.value = true;
                const formData = new FormData();
                formData.append("name", form.value.name);
                formData.append("email", form.value.email);
                formData.append("contact_number", form.value.contact_number);
                formData.append("attach_resume", form.value.resume);
                formData.append("career_job_id", selectedJobDetails.value?.id || '');

                await axios.post("/api/job-applications", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                form.value = {
                    name: "",
                    email: "",
                    contact_number: "",
                    resume: null,
                    career_job_id: selectedJobDetails.value?.id || '',
                };
                fileName.value = "";
                showSuccessAlert.value = true;
                this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                        title: 'Job application submitted successfully',
                        icon: 'CheckCircleIcon',
                        variant: 'success',
                    },
                })
                window.scrollTo({
                    top: document.querySelector(".apply-form").offsetTop - 100,
                    behavior: "smooth",
                });
                submitting.value = false;
            } catch (err) {
                // error.value = "Error submitting application: " + err.message;
                submitting.value = false;
            }
        };

        const resumeState = computed(() => {
            if (!form.value.resume) return null;
            if (form.value.resume.type !== "application/pdf") return false;
            if (form.value.resume.size > 5 * 1024 * 1024) return false;
            return true;
        });

        // Expose selectedJobDetails for template
        return {
            jobs,
            loading,
            error,
            currentPage,
            totalRows,
            perPage,
            selectedLocation,
            locationOptions,
            selectedCategory,
            categoryOptions,
            searchJobs,
            handlePageChange,
            formatDate,
            fields,
            showJobs,
            selectedJobDetails,
            formatDate,
            form,
            nameState,
            emailState,
            contactState,
            resumeState,
            handleFileChange,
            fileName,
            triggerFileInput,
            submitting,
            submitApplication
        };
    },
};
</script>

<style lang="scss">
.custom-table-header,
.custom-table-header th {
    background-color: #ff006b !important;
    color: #fff !important;
    font-weight: 600 !important;
    font-size: 1.15rem !important;
}

.custom-select-border {
    border: 1.5px solid #FF006B !important;
    box-shadow: none;
    border-radius: 8px;
}

.custom-card {
    border: 1.5px solid #afafaf70 !important;
    border-radius: 24px;
    height: max-content
}

.custom-select-border option[disabled] {
    color: #999;
    font-style: italic;
}

.btn-black {
    background-color: #000 !important;
    border-color: #000 !important;
    color: #fff !important;
}


.custom-file-wrapper {
    display: flex;
    border: 1px solid #FF006B !important;
    border-radius: 1px;
    overflow: hidden;
    width: 100%;
    cursor: pointer;
}

.custom-file-wrapper input[type="text"] {
    flex: 1;
    padding: 8px;
    border: none;
    outline: none;
    cursor: pointer;
}

.custom-file-wrapper button {
    background-color: black;
    color: white;
    border: none;
    padding: 8px 16px;
    font-weight: bold;
    cursor: pointer;
}

.custom-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #FF006B !important;
    border-radius: 0px !important;
    box-shadow: none;
}

.form-label-custom {
    font-size: 14px;
    font-weight: 600;
    color: #5f6368;
}

.pink-button {
    background-color: #ff006b !important;
    color: white !important;
    border: none;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 20px;

    &:hover {
        background-color: #e64382 !important;
    }
}


.pink-border {
    border: 2px solid #ff4f93 !important;
    border-radius: 24px;
    background-color: #fff0f5;
}

.pink-text {
    color: #ff006b !important;
}

.career-list {
    padding: 2rem;
}

.job-card {
    transition: transform 0.3s ease;

    &:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
}

.custom-pagination>>>.page-item .page-link {
    border: none;
    color: #d9534f;
    background: none;
    font-weight: 600;
    font-size: 20px;
    border-radius: 6px;
    padding: 8px 20px;
}

.custom-pagination>>>.page-item.active .page-link {
    background: #d9534f;
    color: #fff;
}

.custom-pagination>>>.page-item .page-link .arrow {
    font-size: 25px !important;
}
.custom-select-border {

    height: 40px !important;
}

.uniform-control {
    height: 45px;
    /* same height for selects and button */
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-body {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);


}
.job-title {
  font-size: 28px;
  font-weight: 700;
  color: #333; /* Dark gray for modern look */
  text-transform: uppercase;
  letter-spacing: 1px;
  position: relative;
  display: inline-block;    
  padding-bottom: 8px;
}

.job-title::after {
  content: "";
  display: block;
  width: 60%;
  height: 3px;
  margin: 8px auto 0;
  border-radius: 2px;
}

</style>

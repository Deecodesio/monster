<template>
    <div class="career-detail container" style="margin-top: 150px;   ">
        <!-- Loading state -->
        <div v-if="loading" class="text-center">
            <b-spinner label="Loading..."></b-spinner>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <!-- Career content -->
        <div v-else>
            <!-- Top summary card (like list view) -->
            <div class="main_wrap">
                <b-card-body class="custom-card h-100 job-card mb-4">
                    <!-- <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mt-2 mb-2 mb-0">{{ job.job_name }}</h4>
                        <small class="text-muted" style="margin-left:  0px !important;">
                            {{ formatDate(job.created_at) }}
                        </small>
                             <small class="text-muted">
                            {{ formatDate(job.created_at) }}
                        </small>
                    </div> -->
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Left side -->
                        <div class="job-title">
                            <h4 class="mb-0" style="color:#FF006B; font-size: 18px;font-weight: 600;">{{ job.job_name }}
                            </h4>
                        </div>

                        <!-- Right side -->
                        <div class="d-flex align-items-center gap-3 " style="margin-right: 20px;">
                            <!-- <small class="text-muted"  style="margin-right: 250px;">{{ formatDate(job.created_at) }}</small> -->
                            <small class="text-muted d-flex align-items-center" style="margin-right: 250px;">
                                <i class="fa fa-map-marker-alt mr-1"></i>
                                {{ formatDate(job.created_at) }}
                            </small>

                            <small class="text-muted">{{ formatDate(job.created_at) }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <b-icon icon="geo-alt-fill" class="mr-2"></b-icon>
                        <span>{{ job.location_name }}</span>
                    </div>
                   <p class="clamp-text">
              {{ getExcerpt(job.job_details) }}
            </p>
                    <!-- <b-button style="width: 22rem" variant="primary" :to="{
                        name: 'career-detail',
                        params: { id: job.id },
                    }" class="mt-auto">
                        View Details
                    </b-button> -->
                </b-card-body>
                <div class="mb-5">
                    <h2 style="color: black;font-size: 35px;font-weight: 700;">{{ job.job_name }}</h2>
                    <div class="text-muted">
                        <!-- {{ job.location_name }}, {{ job.state_name }} -->
                    </div>
                    <div v-html="job.job_details"></div>
                    <!-- <div class="text-muted mt-2">
                        Posted: {{ formatDate(job.created_at) }}
                    </div> -->
                </div>
                <!-- <div class="mt-5">
                    <p class="mb-4" style="color: black;font-size:28px;font-weight: 600;">Apply Online</p>
                    <b-alert v-model="showSuccessAlert" variant="success" dismissible>
                        Your application has been submitted successfully!
                    </b-alert>

                    <b-form @submit.prevent="submitApplication">
                        <b-row>
                            <b-col cols="12">
                                <b-form-group label="Full Name" label-for="name">
                                    <b-form-input id="name" v-model="form.name" :state="nameState" required
                                        class="custom-input"></b-form-input>
                                    <b-form-invalid-feedback v-if="!nameState">
                                        Name is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col cols="12">
                                <b-form-group label="Email" label-for="email">
                                    <b-form-input id="email" v-model="form.email" type="email" :state="emailState"
                                        required class="custom-input"></b-form-input>
                                    <b-form-invalid-feedback v-if="!emailState">
                                        Please enter a valid email
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col cols="12">
                                <b-form-group label="Contact Number" label-for="contact_number">
                                    <b-form-input id="contact_number" v-model="form.contact_number"
                                        :state="contactState" required class="custom-input"></b-form-input>
                                    <b-form-invalid-feedback v-if="!contactState">
                                        Contact number is required
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col cols="12">
                                <b-form-group label="Resume (PDF only, max 5MB)" label-for="resume">
                                    <b-form-file id="resume" v-model="form.resume" :state="resumeState" accept=".pdf"
                                        placeholder="Choose a file or drop it here..."
                                        drop-placeholder="Drop file here..." required
                                        class="custom-input"></b-form-file>
                                    <b-form-invalid-feedback v-if="!resumeState">
                                        Please upload a PDF file (max 5MB)
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col cols="12" class="mt-3 text-right">
                                <b-button type="submit" class="btn-black" :disabled="submitting">
                                    <b-spinner small v-if="submitting" class="mr-1"></b-spinner>
                                    Submit
                                </b-button>
                            </b-col>
                        </b-row>
                    </b-form>
                </div> -->
                <div class="mt-5">
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
                                        <input type="text" :value="fileName" placeholder="No file chosen" readonly />
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


            <!-- Full job content, no card -->


            <!-- Full-width Apply Online Form -->


            <!-- Back to careers button -->
            <!-- <div class="text-start mt-4">
                <b-button variant="outline-primary" :to="{ name: 'careers' }">
                    <b-icon icon="arrow-left" class="mr-1"></b-icon>
                    Back to Careers
                </b-button>
            </div> -->
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "@vue/composition-api";
import {
    BCard,
    BCardBody,
    BButton,
    BSpinner,
    BIcon,
    BForm,
    BFormGroup,
    BFormInput,
    BFormFile,
    BFormInvalidFeedback,
    BRow,
    BCol,
    BAlert,
} from "bootstrap-vue";
import axios from "axios";
export default {
    props: ["id"],
    components: {
        BCard,
        BCardBody,
        BButton,
        BSpinner,
        BIcon,
        BForm,
        BFormGroup,
        BFormInput,
        BFormFile,
        BFormInvalidFeedback,
        BRow,
        BCol,
        BAlert,
    },


    setup(props) {
        const job = ref({});
        const loading = ref(true);
        const error = ref(null);
        const submitting = ref(false);
        const showSuccessAlert = ref(false);
        const fileName = ref("");



        const form = ref({
            name: "",
            email: "",
            contact_number: "",
            resume: null,
            career_job_id: props.id,
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
        const resumeState = computed(() => {
            if (!form.value.resume) return null;
            if (form.value.resume.type !== "application/pdf") return false;
            if (form.value.resume.size > 5 * 1024 * 1024) return false;
            return true;
        });

        const fetchJob = async () => {
            try {
                loading.value = true;
                const response = await axios.get(
                    `/api/career-jobs/${props.id}`
                );
                job.value = response.data.data || response.data; // support both {data: {...}} and {...}
                loading.value = false;
            } catch (err) {
                error.value = "Error loading job: " + err.message;
                loading.value = false;
            }
        };
        const getExcerpt = (html) => {
            // Create a temporary element to strip HTML tags
            const tempElement = document.createElement("div");
            tempElement.innerHTML = html;
            const text = tempElement.textContent || tempElement.innerText || "";

            // Return first 150 characters as excerpt
            return text.length > 150 ? text.substring(0, 150) + "..." : text;
        };
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
                formData.append("career_job_id", props.id);

                await axios.post("/api/job-applications", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                form.value = {
                    name: "",
                    email: "",
                    contact_number: "",
                    resume: null,
                    career_job_id: props.id,
                };
                showSuccessAlert.value = true;
                window.scrollTo({
                    top: document.querySelector(".apply-form").offsetTop - 100,
                    behavior: "smooth",
                });
                submitting.value = false;
            } catch (err) {
                error.value = "Error submitting application: " + err.message;
                submitting.value = false;
            }
        };

        const formatDate = (date) => {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        };

        onMounted(fetchJob);
        const triggerFileInput = () => {
            document.getElementById("resume").click();
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


        return {
            job,
            loading,
            error,
            formatDate,
            form,
            nameState,
            emailState,
            contactState,
            resumeState,
            submitting,
            submitApplication,
            showSuccessAlert,
            handleFileChange,
            fileName,
            triggerFileInput,
            getExcerpt,
        };
    },
};
</script>

<style lang="scss" scoped>
.btn-black {
    background-color: #000 !important;
    border-color: #000 !important;
    color: #fff !important;
}

.career-detail {
    padding: 2rem;
   
}

.job-title {
    font-size: 2.5rem;
    font-weight: 700;
}

.job-meta {
    color: #6c757d;
}

.job-content {
    line-height: 1.8;
    font-size: 1.1rem;

    ::v-deep img {
        max-width: 100%;
        height: auto;
        margin: 1.5rem 0;
    }

    ::v-deep h2,
    ::v-deep h3,
    ::v-deep h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    ::v-deep p {
        margin-bottom: 1.5rem;
    }

    ::v-deep ul,
    ::v-deep ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    ::v-deep li {
        margin-bottom: 0.5rem;
    }
}

.apply-form {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
}

.custom-select-border {
    border: 3px solid #26323870 !important;
    box-shadow: none;
    border-radius: 8px;
}

.custom-card {
    border: 1.5px solid #afafaf70 !important;
    border-radius: 24px;
    height: 10rem;
}

.featured-job {
    .featured-img {
        height: 100%;
        background-color: #f8f9fa;
    }
}

.job-card {
    transition: transform 0.3s ease;

    &:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
}

/* Clamp to 2 lines in summary card */
.clamp-text-2 {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
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

.invalid-feedback {
    font-size: 0.875rem;
    color: #dc3545;
}
</style>

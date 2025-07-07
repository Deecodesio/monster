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
            <!-- Search -->
            <div class="mb-5 " style="margin-top: 50px">
                <b-card-body class="custom-card ">
                    <b-row>
                        <b-col cols="12" md="5" style="margin-top: 22px;">
                            <b-form-group>
                                <b-form-select v-model="selectedLocation" :options="locationOptions"
                                    class="mb-3 custom-select-border">
                                </b-form-select>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="2" style="margin-top: 22px;">
                            <div class="text-center">
                                <b-button style="width: 100%" variant="primary" @click="searchJobs">SEARCH
                                    JOBS</b-button>
                            </div>
                        </b-col>
                    </b-row>
                </b-card-body>
            </div>

            <!-- Job Details View -->
            <div v-if="selectedJobDetails">
                <b-card class="mb-4">
                    <b-button variant="light" class="pink-button" @click="backToList">
                        &larr; Back to Job List
                    </b-button>
                    <br />
                    <h3 class="mb-3" style="margin-top: 3rem;">{{ selectedJobDetails.job_name }}</h3>
                    <p><strong>Location: {{ selectedJobDetails.location_name }} </strong></p>
                    <p><strong>Posted On: {{ formatDate(selectedJobDetails.created_at) }} </strong></p>
                    <div v-if="selectedJobDetails.job_details">
                        <strong>Description:</strong>
                        <div v-html="selectedJobDetails.job_details"></div>
                    </div>
                </b-card>
            </div>

            <!-- Job Table or No Result -->
            <b-row v-else-if="showJobs && selectedLocation">
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
                            {{ data.item.location_name }}
                        </template>
                        <template #cell(created_at)="data">
                            <strong>{{ formatDate(data.item.created_at) }}</strong>
                        </template>
                    </b-table>
                </b-col>
            </b-row>

            <!-- Pagination -->
            <div v-if="showJobs && selectedLocation && jobs.length && !selectedJobDetails"
                class="d-flex justify-content-center mt-4">
                <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage"
                    @change="handlePageChange" class="custom-pagination" />
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "@vue/composition-api";
import Top_banners from "../top_banners.vue";
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
        const locationOptions = ref([{ value: "", text: "Select location", disabled: true }]);
        const showJobs = ref(false);
        const selectedJobDetails = ref(null);

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

        const searchJobs = async (page = 1) => {
            showJobs.value = false;
            selectedJobDetails.value = null;
            if (!selectedLocation.value) {
                jobs.value = [];
                totalRows.value = 0;
                return;
            }
            try {
                loading.value = true;
                let url = `/api/career-jobs?page=${page}`;
                if (selectedLocation.value) {
                    url += `&location_id=${selectedLocation.value}`;
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

        const formatDate = (date) => {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        };

        onMounted(async () => {
            await fetchLocations();
            loading.value = false;
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
            searchJobs,
            handlePageChange,
            formatDate,
            fields,
            showJobs,
            selectedJobDetails,
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
    height: 10rem;
}

.custom-select-border option[disabled] {
    color: #999;
    font-style: italic;
}
.pink-button {
  background-color: #ff006b!important;
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
</style>

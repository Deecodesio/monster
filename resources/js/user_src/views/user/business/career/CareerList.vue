<template>
    <div class="career-list">
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
            <!-- Search Section -->
            <div class="mb-5" style="margin-top: 150px">
                <!-- <b-card no-body> -->
                <b-card-body class="custom-card">
                    <h3 class="mb-2">SEARCH FOR JOBS</h3>
                    <b-row>
                        <b-col md="5">
                            <b-form-group>
                                <b-form-input
                                    v-model="searchQuery"
                                    placeholder="Search  job"
                                    class="mb-3"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col md="5">
                            <b-form-group>
                                <b-form-select
                                    v-model="selectedLocation"
                                    :options="locationOptions"
                                    placeholder="Select location"
                                    class="mb-3 custom-select-border"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="2">
                            <div class="text-center">
                                <b-button
                                    style="width: 22rem"
                                    variant="primary"
                                    @click="searchJobs"
                                    >SEARCH JOBS</b-button
                                >
                            </div>
                        </b-col>
                    </b-row>
                </b-card-body>
                <!-- </b-card> -->
            </div>

            <!-- Featured Job (Latest) -->
            <!-- <div v-if="featuredJob" class="featured-job mb-5">
        <b-row>
          <b-col cols="12">
            <b-card no-body class="overflow-hidden">
              <b-row no-gutters>
                <b-col md="6">
                  <div class="featured-img d-flex align-items-center justify-content-center bg-light p-5">
                    <h2 class="text-primary">FEATURED POSITION</h2>
                  </div>
                </b-col>
                <b-col md="6">
                  <b-card-body>
                    <small class="text-muted">{{ formatDate(featuredJob.created_at) }}</small>
                    <h2 class="mt-2 mb-3">{{ featuredJob.job_name }}</h2>
                    <p class="job-excerpt">{{ getExcerpt(featuredJob.job_details) }}</p>
                    <div class="d-flex align-items-center mb-3">
                      <b-icon icon="geo-alt-fill" class="mr-2"></b-icon>
                      <span>{{ featuredJob.location_name }}</span>
                    </div>
                    <b-button
                      variant="primary"
                      :to="{ name: 'career-detail', params: { id: featuredJob.id }}"
                    >
                      View Details
                    </b-button>
                  </b-card-body>
                </b-col>
              </b-row>
            </b-card>
          </b-col>
        </b-row>
      </div> -->

            <!-- Job Grid -->
            <b-row>
                <b-col
                    v-for="job in regularJobs"
                    :key="job.id"
                    cols="12"
                    md="12"
                    class="mb-4"
                >
                    <!-- <b-card no-body class=""> -->
                    <b-card-body class="custom-card h-100 job-card">
                        <div
                            class="d-flex justify-content-between align-items-center"
                        >
                            <h4 class="mt-2 mb-2 mb-0">{{ job.job_name }}</h4>
                            <small class="text-muted">{{
                                formatDate(job.created_at)
                            }}</small>
                        </div>
                        <div class="d-flex align-items-center ">
                            <b-icon icon="geo-alt-fill" class="mr-2"></b-icon>
                            <span>{{ job.location_name }}</span>
                        </div>
                        <p class="clamp-text"  >
                            {{ getExcerpt(job.job_details) }}
                        </p>
                        <b-button style =" width: 22rem;"
                            variant="primary"
                            :to="{
                                name: 'career-detail',
                                params: { id: job.id },
                            }"
                            class="mt-auto"
                        >
                            View Details
                        </b-button>
                    </b-card-body>
                    <!-- </b-card> -->
                </b-col>
            </b-row>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    @change="handlePageChange"
                ></b-pagination>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "@vue/composition-api";
import {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BImg,
    BButton,
    BSpinner,
    BPagination,
    BFormInput,
    BFormSelect,
    BFormGroup,
    BIcon,
} from "bootstrap-vue";
import axios from "axios";

export default {
    name: "CareerList",
    components: {
        BRow,
        BCol,
        BCard,
        BCardBody,
        BImg,
        BButton,
        BSpinner,
        BPagination,
        BFormInput,
        BFormSelect,
        BFormGroup,
        BIcon,
    },
    setup() {
        const jobs = ref([]);
        const loading = ref(true);
        const error = ref(null);
        const currentPage = ref(1);
        const perPage = ref(10);
        const totalRows = ref(0);
        const searchQuery = ref("");
        const selectedLocation = ref(null);
        const locationOptions = ref([{ value: null, text: "Select location" }]);

        // Remove the featuredJob computed property

        // Update regularJobs to show all jobs instead of skipping the first one
        const regularJobs = computed(() => jobs.value);

        const fetchJobs = async (page = 1) => {
            try {
                loading.value = true;
                const response = await axios.get(
                    `/api/career-jobs?page=${page}`
                );

                console.log("response");
                console.log(response.data.data);
                // Handle pagination data
                if (response.data.data) {
                    console.log("response1");
                    jobs.value = response.data.data.data;
                    totalRows.value = response.data.data.total;
                    currentPage.value = response.data.data.current_page;
                    perPage.value = response.data.data.per_page;
                } else {
                    console.log("response2");

                    jobs.value = response.data.data.data;
                }

                loading.value = false;
            } catch (err) {
                error.value = "Error loading jobs: " + err.message;
                loading.value = false;
                console.error("Error fetching jobs:", err);
            }
        };

        const fetchLocations = async () => {
            try {
                // const response = await axios.get('/locations')
                // const locations = response.data.map(location => ({
                //   value: location.id,
                //   text: location.name
                // }))
                const response = await axios.get("/restaurant_cities");
                const locations = response.data.map((location) => ({
                    value: location.id,
                    text: location.city,
                }));
                locationOptions.value = [
                    { value: null, text: "Select location" },
                    ...locations,
                ];
            } catch (err) {
                console.error("Error fetching locations:", err);
            }
        };

        const searchJobs = async () => {
            try {
                loading.value = true;
                let url = "/career-jobs?";

                if (searchQuery.value) {
                    url += `search=${searchQuery.value}&`;
                }

                if (selectedLocation.value) {
                    url += `location_id=${selectedLocation.value}&`;
                }

                const response = await axios.get(url);

                if (response.data.data) {
                    jobs.value = response.data.data;
                    totalRows.value = response.data.total;
                    currentPage.value = response.data.current_page;
                    perPage.value = response.data.per_page;
                } else {
                    jobs.value = response.data;
                }

                loading.value = false;
            } catch (err) {
                error.value = "Error searching jobs: " + err.message;
                loading.value = false;
                console.error("Error searching jobs:", err);
            }
        };

        const handlePageChange = (page) => {
            fetchJobs(page);
        };

        const formatDate = (date) => {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        };

        const getExcerpt = (html) => {
            // Create a temporary element to strip HTML tags
            const tempElement = document.createElement("div");
            tempElement.innerHTML = html;
            const text = tempElement.textContent || tempElement.innerText || "";

            // Return first 150 characters as excerpt
            return text.length > 150 ? text.substring(0, 150) + "..." : text;
        };

        onMounted(() => {
            fetchJobs();
            fetchLocations();
        });

        return {
            jobs,
            loading,
            error,
            formatDate,
            getExcerpt,
            // Remove featuredJob from return statement
            regularJobs,
            currentPage,
            totalRows,
            perPage,
            handlePageChange,
            searchQuery,
            selectedLocation,
            locationOptions,
            searchJobs,
        };
    },
};
</script>

<style lang="scss" scoped>
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
.career-list {
    padding: 2rem;
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

    .job-excerpt {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        line-clamp: 5;
        -webkit-box-orient: vertical;
       
    }
}


.search-section {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}
.clamp-text {
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Change to 2 if you want 2 lines */
  line-clamp: 3; /* Standard property for compatibility */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

</style>

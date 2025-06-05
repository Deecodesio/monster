<template>
    <div>
        <div class="mt-5 mx-3 py-5">
            <!-- Breadcrumb -->
            <h6 class="mb-3 mt-2" style="font-size: 1.2rem">
                <span class="text-muted">Home ></span>
                <span class="fw-bold">Blog</span>
            </h6>
            <!-- Main Blog Heading -->
            <h3 class="text-bold mb-4">Blog</h3>

            <!-- Loading State -->
            <div v-if="loading" class="text-center">
                <b-spinner label="Loading..."></b-spinner>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="alert alert-danger">
                {{ error }}
            </div>

            <!-- Blog Content -->
            <div v-else>
                <!-- Featured Blog -->
                <div v-if="featuredBlog" class="featured-blog mb-5">
                    <b-row>
                        <b-col cols="12">
                            <b-card no-body class="overflow-hidden">
                                <b-row no-gutters>
                                    <b-col md="6">
                                        <b-img
                                            :src="featuredBlog.featured_image"
                                            fluid
                                            class="featured-img"
                                            alt="Featured blog image"
                                        />
                                    </b-col>
                                    <b-col md="6">
                                        <b-card-body>
                                            <small class="text-muted">
                                                {{
                                                    formatDate(
                                                        featuredBlog.published_at
                                                    )
                                                }}
                                            </small>
                                            <h2 class="mt-2 mb-3">
                                                {{ featuredBlog.title }}
                                            </h2>
                                            <p class="blog-excerpt">
                                                {{ featuredBlog.excerpt }}
                                            </p>
                                            <b-button
                                                variant="primary"
                                                :to="{
                                                    name: 'blog-detail',
                                                    params: {
                                                        slug: featuredBlog.slug,
                                                    },
                                                }"
                                            >
                                                Read more
                                            </b-button>
                                        </b-card-body>
                                    </b-col>
                                </b-row>
                            </b-card>
                        </b-col>
                    </b-row>
                </div>

                <!-- Blog Grid -->
                <h3 class="mb-4">Latest Articles</h3>
                <b-row>
                    <b-col
                        v-for="blog in regularBlogs"
                        :key="blog.id"
                        cols="12"
                        md="6"
                        class="mb-4"
                    >
                        <b-card no-body class="h-100 blog-card">
                            <div class="blog-img-container">
                                <b-img
                                    :src="blog.featured_image"
                                    fluid
                                    class="card-img-top blog-img"
                                    alt="Blog image"
                                />
                            </div>
                            <b-card-body>
                                <small class="text-muted">
                                    {{ formatDate(blog.published_at) }}
                                </small>
                                <h4 class="mt-2 mb-2">{{ blog.title }}</h4>
                                <p class="blog-excerpt">{{ blog.excerpt }}</p>
                                <b-button
                                    variant="primary"
                                    :to="{
                                        name: 'blog-detail',
                                        params: { slug: blog.slug },
                                    }"
                                    class="mt-auto"
                                >
                                    Read more
                                </b-button>
                            </b-card-body>
                        </b-card>
                    </b-col>
                </b-row>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="totalRows"
                        :per-page="perPage"
                        @change="handlePageChange"
                    />
                </div>
            </div>
        </div>
        <div
            class="container-fluid"
            style="margin-top: 50px; padding-right: 0; padding-left: 0"
        >
            <Ourshops />
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "@vue/composition-api";
import Ourshops from '../ourshops.vue';
import {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BImg,
    BButton,
    BSpinner,
    BPagination,
} from "bootstrap-vue";
import axios from "axios";


export default {
    name: "BlogList",
    components: {
        BRow,
        BCol,
        BCard,
        BCardBody,
        BImg,
        BButton,
        BSpinner,
        Ourshops,
        BPagination,
    },
    setup() {
        const blogs = ref([]);
        const loading = ref(true);
        const error = ref(null);
        const currentPage = ref(1);
        const perPage = ref(10);
        const totalRows = ref(0);

        // Computed property for featured blog (latest one)
        const featuredBlog = computed(() =>
            blogs.value.length > 0 ? blogs.value[0] : null
        );

        // Computed property for regular blogs (all except the featured one)
        const regularBlogs = computed(() =>
            blogs.value.length > 1 ? blogs.value.slice(1) : []
        );

        const fetchBlogs = async (page = 1) => {
            try {
                loading.value = true;
                const response = await axios.get(`/api/blogs?page=${page}`);
                console.log("API Response:", response); // Add this line

                // Handle pagination data
                if (response.data.data) {
                    blogs.value = response.data.data;
                    totalRows.value = response.data.total;
                    currentPage.value = response.data.current_page;
                    perPage.value = response.data.per_page;
                } else {
                    blogs.value = response.data;
                }

                loading.value = false;
            } catch (err) {
                error.value = "Error loading blogs: " + err.message;
                loading.value = false;
                console.error("Error fetching blogs:", err);
            }
        };

        const handlePageChange = (page) => {
            fetchBlogs(page);
        };

        const formatDate = (date) => {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        };

        onMounted(() => {
            fetchBlogs();
        });

        return {
            blogs,
            loading,
            error,
            formatDate,
            featuredBlog,
            regularBlogs,
            currentPage,
            totalRows,
            perPage,
            handlePageChange,
        };
    },
};
</script>

<style lang="scss" scoped>
.featured-blog {
    .featured-img {
        height: 100%;
        object-fit: cover;
    }

    .card-body {
        padding: 2rem;
    }
}

.blog-card {
    transition: transform 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;

    &:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .blog-img-container {
        height: 200px;
        overflow: hidden;
    }

    .blog-img {
        height: 100%;
        object-fit: cover;
        width: 100%;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .blog-excerpt {
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
}
</style>

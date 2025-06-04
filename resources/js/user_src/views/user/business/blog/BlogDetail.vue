<template>
    <div>
        <div class="mx-3 py-5" style="margin-top: 25rem">
            <!-- Loading state -->
            <div v-if="loading" class="text-center">
                <b-spinner label="Loading..."></b-spinner>
            </div>

            <!-- Error state -->
            <div v-else-if="error" class="alert alert-danger">
                {{ error }}
            </div>

            <!-- Blog content -->
            <div v-else>
                <b-card no-body>
                    <div class="featured-image-container mt-3">
                        <b-img
                            :src="blog.featured_image"
                            fluid
                            class="featured-image"
                            alt="Blog featured image"
                        />
                    </div>
                    <b-card-body>
                        <h1 class="blog-title mb-3">{{ blog.title }}</h1>
                        <div class="blog-meta mb-4">
                            <small class="text-muted">
                                <b-icon icon="calendar" class="mr-1"></b-icon>
                                Published on {{ formatDate(blog.published_at) }}
                            </small>
                            <small v-if="blog.category" class="text-muted ml-3">
                                <b-icon icon="folder" class="mr-1"></b-icon>
                                {{ blog.category.name }}
                            </small>
                        </div>
                        <div class="blog-content" v-html="blog.content" />
                    </b-card-body>
                </b-card>

                <!-- Back to blogs button -->
                <div class="text-center mt-4">
                    <b-button variant="outline-primary" :to="{ name: 'blogs' }">
                        <b-icon icon="arrow-left" class="mr-1"></b-icon>
                        Back to Blogs
                    </b-button>
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
import { ref, onMounted } from "@vue/composition-api";
import Ourshops from '../OurShops.vue';

import {
    BCard,
    BCardBody,
    BImg,
    BButton,
    BSpinner,
    BIcon,
} from "bootstrap-vue";
import axios from "axios";

export default {
    components: {
        BCard,
        BCardBody,
        BImg,
        BButton,
        BSpinner,
        BIcon,
        Ourshops,
    },
    props: {
        slug: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const blog = ref({});
        const loading = ref(true);
        const error = ref(null);

        const fetchBlog = async () => {
            try {
                loading.value = true;
                const response = await axios.get(`/api/blogs/${props.slug}`);
                blog.value = response.data;
                loading.value = false;
            } catch (err) {
                error.value = "Error loading blog: " + err.message;
                loading.value = false;
                console.error("Error fetching blog:", err);
            }
        };

        const formatDate = (date) => {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(date).toLocaleDateString(undefined, options);
        };

        onMounted(fetchBlog);

        return {
            blog,
            loading,
            error,
            formatDate,
        };
    },
};
</script>

<style lang="scss" scoped>
.blog-detail {
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.featured-image-container {
    height: 400px;
    overflow: hidden;
}

.featured-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-title {
    font-size: 2.5rem;
    font-weight: 700;
}

.blog-meta {
    color: #6c757d;
}

.blog-content {
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

    ::v-deep blockquote {
        border-left: 4px solid #dee2e6;
        padding-left: 1rem;
        font-style: italic;
        margin: 1.5rem 0;
    }
}
</style>

<template>
       <div style="margin-top: 150px">
         <h6 class="mb-3 mt-2 mx-3" style="font-size: 1.2rem; margin-left: 44px; ">
                <span class="text-muted">Home ></span>
                <span class="fw-bold">Blog</span>
            </h6>
  <!-- <div class="blog-list-root"> -->

    <!-- Main Heading -->
    <h1 class="main-heading" style="font-weight: bolder;">Blog</h1>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-5">
      <b-spinner label="Loading..."></b-spinner>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <!-- Blog Content -->
    <div v-else>
      <!-- Featured Blog -->
      <div v-if="featuredBlog" class="featured-blog-card">
        <div class="featured-blog-row">
          <div class="featured-blog-content">
            <small class="text-muted">{{ formatDate(featuredBlog.published_at) }}</small>
            <h3 class="featured-title">{{ featuredBlog.title }}</h3>
            <p class="featured-excerpt">{{ featuredBlog.excerpt }}</p>
            <b-button
              variant="danger"
              class="read-more-btn"
              :to="{ name: 'blog-detail', params: { slug: featuredBlog.slug } }"
            >
              Read more →
            </b-button>
          </div>
          <div class="featured-blog-image">
            <b-img
              :src="'/blogs/'+featuredBlog.featured_image"
              fluid
              alt="Featured blog image"
              class="featured-img"
            />
          </div>
        </div>
      </div>
      </div>
      <!-- Blog Grid -->
      <div class="row blog-grid">
        <div
          v-for="blog in regularBlogs"
          :key="blog.id"
          class="col-md-6 mb-4"
        >
          <router-link
            :to="{ name: 'blog-detail', params: { slug: blog.slug } }"
            class="card h-100 blog-card-link"
          >
            <div class="card-body d-flex flex-column">
              <b-img
                :src="blog.featured_image"
                fluid
                alt="Blog image"
                class="blog-img mb-3"
              />
              <small class="text-muted">{{ formatDate(blog.published_at) }}</small>
              <h4 class="blog-title mt-2 mb-2">{{ blog.title }}</h4>
              <p class="blog-excerpt">{{ blog.excerpt }}</p>
            </div>
          </router-link>
        </div>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          @change="handlePageChange"
          class="custom-pagination"
        />
      </div>
      <div
            class="container-fluid"
            style="margin-top: 50px; padding-right: 0; padding-left: 0"
        >
            <Ourshops />
        </div>
    <!-- </div> -->
  </div>
</template>

<script>
import { ref, computed, onMounted } from "@vue/composition-api";
import { BImg, BButton, BSpinner, BPagination } from "bootstrap-vue";
import axios from "axios";
import Ourshops from '../ourshops.vue';

export default {
  name: "BlogList",
  components: { BImg, BButton, BSpinner, BPagination,Ourshops },
  setup() {
    const blogs = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const currentPage = ref(1);
    const perPage = ref(6);
    const totalRows = ref(0);

    const featuredBlog = computed(() =>
      blogs.value.length > 0 ? blogs.value[0] : null
     
    );
    const regularBlogs = computed(() =>
      blogs.value.length > 1 ? blogs.value.slice(1) : []
    );

    const fetchBlogs = async (page = 1) => {
      try {
        loading.value = true;
        error.value = null;
        const res = await axios.get(`/api/blogs?page=${page}&per_page=${perPage.value}`);
        console.log("askdjasdkjas",res.data.data.data);
        blogs.value = res.data.data.data || res.data;
        totalRows.value = res.data.total || 0;
        currentPage.value = res.data.current_page || 1;
        perPage.value = res.data.per_page || 6;
        loading.value = false;
      } catch (err) {
        error.value = "Error loading blogs: " + err.message;
        loading.value = false;
      }
       console.log("asdkjhgasdjashd",blogs.value[0]);
    };

    const handlePageChange = (page) => {
      fetchBlogs(page);
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString(undefined, {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    };

    onMounted(() => {
      fetchBlogs();
    });

    return {
      blogs,
      loading,
      error,
      featuredBlog,
      regularBlogs,
      currentPage,
      totalRows,
      perPage,
      handlePageChange,
      formatDate,
    };
  },
};
</script>

<style scoped>
.blog-list-root {
  background: #f9f9fb;
  min-height: 100vh;
  padding: 0;
}

.main-heading {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 30px;
  margin-left: 44px;
  margin-top: 38px;
}

.featured-blog-card {
  width: 96%;
  margin: 0 auto 40px auto;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
  padding: 0;
}

.featured-blog-row {
  display: flex;
  flex-direction: row;
  border-radius: 14px;
  overflow: hidden;
  background-color: #fff;
  align-items: stretch;
  padding: 0;
}

.featured-blog-content {
  flex: 1 1 0;
  max-width: 50%;
  padding: 38px 34px 38px 34px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.featured-blog-image {
  flex: 1 1 0;
  max-width: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  /* background: #f7f7fa; */
  padding: 34px 34px 34px 0;
}
.featured-img {
  width: 100%;
  height: 37rem;
  object-fit: cover;
  border-radius: 12px;
  background: #f0f0f0;
  box-shadow: 0 1px 10px rgba(0,0,0,0.04);
}
.featured-title {
  font-size: 1.22rem;
  font-weight: 600;
  margin-bottom: 14px;
}
.featured-excerpt {
  font-size: 1rem;
  color: #555;
  line-height: 1.6;
  margin-bottom: 18px;
}
.read-more-btn {
  font-size: 1.5rem;
  padding: 8px 28px;
  border-radius: 799px;
  font-weight: 600;
  margin-top: 2px;
  background: #d9534f !important;
  border: none;
  width:15rem;
   margin-top: auto;
}

.blog-grid {
  width: 96%;
  margin: 0 auto 0 auto;
}

.blog-card-link {
  display: block;
  background: #fff;
  border-radius: 12px;
  text-decoration: none;
  color: inherit;
  box-shadow: 0 1px 10px rgba(0, 0, 0, 0.06);
  min-height: 320px;
  transition: transform 0.2s, box-shadow 0.2s;
}
.blog-card-link:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 24px rgba(217, 83, 79, 0.08);
}
.card-body {
  padding: 18px 20px 22px 20px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
    overflow: hidden;
}
.blog-img {
  width: 100%;
  height: 37rem;
  object-fit: cover;
  border-radius: 8px;
    aspect-ratio: 4/3;
}
.blog-title {
  font-size: 1.1rem;
  font-weight: bold;
}
.blog-excerpt {
  font-size: 0.98rem;
  color: #666;
  line-height: 1.6;
}

/* Pagination (UI) */
.custom-pagination >>> .page-item .page-link {
  border: none;
  color: #d9534f;
  background: none;
  font-weight: 500;
  font-size: 1.09rem;
  border-radius: 4px;
  margin: 0 2px;
  padding: 2px 10px;
}
.custom-pagination >>> .page-item.active .page-link {
  background: #d9534f;
  color: #fff;
}

/* Responsive */
@media (max-width: 900px) {
  .main-heading {
    margin-left: 10px;
    margin-top: 18px;
  }

  .featured-blog-card,
  .blog-grid {
    width: 100%;
    margin: 0;
  }
  .featured-blog-row {
    flex-direction: column;
  }
  .featured-blog-content,
  .featured-blog-image {
    max-width: 100%;
    padding: 20px 14px !important;
  }
  .featured-img {
    height: 160px;
    border-radius: 8px;
      aspect-ratio: 4/3;
  }
  .blog-img {
    height: 100px;
  }
}
</style>

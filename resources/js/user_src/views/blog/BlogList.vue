<template>
  <div class="blog-list">
    <!-- Add loading state -->
    <div v-if="loading" class="text-center">
      <b-spinner label="Loading..."></b-spinner>
    </div>

    <!-- Add error state -->
    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <!-- Blog content -->
    <b-row v-else>
      <b-col
        v-for="blog in blogs"
        :key="blog.id"
        cols="12"
        md="6"
        lg="4"
      >
        <b-card
          no-body
          class="mb-4"
        >
          <b-img
            :src="blog.featured_image"
            fluid
            class="card-img-top"
          />
          <b-card-body>
            <small class="text-muted">{{ formatDate(blog.published_at) }}</small>
            <h4 class="mt-2">{{ blog.title }}</h4>
            <p>{{ blog.excerpt }}</p>
            <b-button
              variant="primary"
              :to="{ name: 'blog-detail', params: { slug: blog.slug }}"
            >
              Read more
            </b-button>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { ref, onMounted } from '@vue/composition-api'
import {
  BRow,
  BCol,
  BCard,
  BCardBody,
  BImg,
  BButton,
  BSpinner,
} from 'bootstrap-vue'
import axios from 'axios'

export default {
  name: 'BlogList',
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BImg,
    BButton,
    BSpinner,
  },
  setup() {
    const blogs = ref([])
    const loading = ref(true)
    const error = ref(null)

    const fetchBlogs = async () => {
      try {
        loading.value = true
        const response = await axios.get('/api/blogs')
        blogs.value = response.data.data || response.data
        loading.value = false
      } catch (err) {
        error.value = 'Error loading blogs: ' + err.message
        loading.value = false
        console.error('Error fetching blogs:', err)
      }
    }

    const formatDate = date => new Date(date).toLocaleDateString()

    onMounted(() => {
      fetchBlogs()
    })

    return {
      blogs,
      loading,
      error,
      formatDate,
    }
  },
}
</script>

<style lang="scss" scoped>
.blog-list {
  padding: 2rem;
}
</style>
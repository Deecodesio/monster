<template>
  <div class="blog-detail">
    <b-card no-body>
      <b-img
        :src="blog.featured_image"
        fluid
        class="card-img-top"
      />
      <b-card-body>
        <h1 class="mb-2">{{ blog.title }}</h1>
        <div class="blog-meta mb-3">
          <small class="text-muted">Published on {{ formatDate(blog.published_at) }}</small>
        </div>
        <div
          class="blog-content"
          v-html="blog.content"
        />
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import { ref, onMounted } from '@vue/composition-api'
import { BCard, BCardBody, BImg } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BImg,
  },
  props: {
    slug: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const blog = ref({})

    const fetchBlog = async () => {
      try {
        const response = await fetch(`/api/blogs/${props.slug}`)
        blog.value = await response.json()
      } catch (error) {
        console.error('Error fetching blog:', error)
      }
    }

    const formatDate = date => new Date(date).toLocaleDateString()

    onMounted(fetchBlog)

    return {
      blog,
      formatDate,
    }
  },
}
</script>
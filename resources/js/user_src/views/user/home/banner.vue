<template>
  <div>
    <!-- faq search section -->
    <section id="faq-search-filter ">
      <b-card no-body class="faq-search ">




        <b-img-lazy src="/banner.jpg" fluid alt="Scan" class="rounded3" width="1500" height="347" />

      </b-card>
    </section>
    <!--/ faq search section -->

  </div>
</template>

<script>
import {
  BCard,
  BCardBody,
  BForm,
  BInputGroup,
  BFormInput,
  BCardText,
  BInputGroupPrepend,
  BTabs,
  BTab,
  BImg,
  BRow,
  BCol,
  BAvatar,
  BNav,
  BNavItem, BImgLazy
} from 'bootstrap-vue'

export default {
  components: {
    BForm,
    BCard,
    BRow,
    BCol,
    BAvatar,
    BCardBody,
    BInputGroup,
    BFormInput,
    BCardText,
    BInputGroupPrepend,
    BTabs,
    BTab,
    BImg,
    BNav,
    BNavItem, BImgLazy
  },
  data() {
    return {
      faqSearchQuery: '',
      faqData: {},
    }
  },
  watch: {
    faqSearchQuery: {
      immediate: true,
      handler() {
        this.fetchData()
      },
    },
  },
  methods: {
    fetchData() {
      this.$http.get('/faq/data', { params: { q: this.faqSearchQuery } }).then(res => {
        this.faqData = res.data
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-faq.scss';
</style>
<style scoped>
.nav-link {
  color: black;
}

.ht {
  min-height: 600px;
}
</style>

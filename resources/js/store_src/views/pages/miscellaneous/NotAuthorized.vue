<template>
  <div class="misc-wrapper">
    <!-- <b-link class="brand-logo">
      <vuexy-logo />
      <h2 class="brand-text text-primary ml-1">
        Deliware
      </h2>
    </b-link> -->
        <!-- <b-link class="brand-logo">
          <a href="javascript:void(0);" class="brand-logo">
                                    <img src="https://www.deliware.app/restaurant_uploads/8pVbiYXcIgf26mugAqAzIKWG40a9vW7K.svg" width="150"> 
                                   
                                </a>

        </b-link> -->
    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          You are not authorized! 🔐
        </h2>
        <p class="mb-2">
          You don’t have permission to access this page. Go Home!!
        </p>
        <b-button
          variant="primary"
          class="mb-1 btn-sm-block"
           @click="$router.push({ name: 'dashboard-ecommerce'})"
        >Back to Home</b-button>
        <b-img
          fluid
          :src="imgUrl"
          alt="Not authorized page"
        />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { BLink, BImg, BButton } from 'bootstrap-vue'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import store from '@/store/index'
import { getHomeRouteForLoggedInUser } from '@/auth/utils'

export default {
  components: {
    BLink,
    BImg,
    BButton,
    VuexyLogo,
  },
  data() {
    return {
      downImg: "https://www.deliware.app/restaurant_uploads/8pVbiYXcIgf26mugAqAzIKWG40a9vW7K.svg",
    }
  },
  computed: {
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = "https://www.deliware.app/restaurant_uploads/8pVbiYXcIgf26mugAqAzIKWG40a9vW7K.svg"
        return this.downImg
      }
      return this.downImg
    },
  },
  methods: {
    loginRoute() {
      const user = JSON.parse(localStorage.getItem('userData'))
      return getHomeRouteForLoggedInUser(user ? user.role : null)
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-misc.scss';
</style>

<template>
  <div>
    
    <!-- Styled Section Title -->
    <div class="section-title-container">
      <h2 class="section-title">{{ title1 }}</h2>
      <hr class="section-underline" />
    </div>
          

    <!-- Main Content -->
    <b-row style="margin-top: 110px">
      <b-col cols="12">
        <!-- Loading Spinner -->
        <div
          v-if="is_loading"
          class="mt-1 text-center d-flex flex-column align-items-center"
        >
          <lottie-player
            src="/animations/loading.json"
            background="#FFFDFD"
            speed="1"
            style="width: 300px; height: 500px"
            loop
            autoplay
          ></lottie-player>
        </div>

        <!-- Loaded Components -->
        <div class="container" v-if="!is_loading">
          <component
            v-for="component in componentOrder"
            :is="component.name"
            :key="component.name"
            :prop="component.prop"
            :business_id="business_id"
            :isopen="isopen"
            :title="component.title"
            :prop2="component.id"
          ></component>
        </div>
         <Ourshops />
      </b-col>
    </b-row>
  </div>
  
</template>

<script>
import { BRow, BCol, BCard } from "bootstrap-vue";
import top_banners from "./top_banners.vue";
import categories from "./categories.vue";
import featured from "./featured.vue";
import download from "@@@/views/user/home/download.vue";
import page1 from "@@@/views/user/static_pages/page1.vue";
import page2 from "@@@/views/user/static_pages/page2.vue";
import store from "@@@/store";
import banner from "@@@/views/user/home/banner.vue";
import Ourshops from "./ourshops.vue";





export default {
    components: {
        BRow,
        BCol,
        BCard,

        top_banners,
        categories,
        featured,
        Ourshops,
        download,
        page1,
        page2,
        banner,
    },

    data() {
        return {
            featured: [],
            recent: [],
            pages: [],
            lat: localStorage.getItem("latitude"),
            lng: localStorage.getItem("longitude"),
            business_id: localStorage.getItem("single_business_id"),
            isopen: 0,
            title1: "Popular Products",
            title2: "Best Seller",
            componentOrder: [],
            is_loading: false,
        };
    },
    created() {
        this.get_products();
    },
    computed: {
        orderedComponents() {
            return this.componentOrder.map((key) => {
                let name = key.name;
                return this.$options.components[name];
            });
        },
    },
    methods: {
        get_pages() {
            this.is_loading = true;
            this.$http.get("/get_pages").then((res) => {
                this.pages = res.data.pages;
                for (var i = 0; i < this.pages.length; i++) {
                    if (this.pages[i].is_editable == 1) {
                        this.pages[i].name = "page1";
                        // this.componentOrder.push(this.pages[i])
                    } else {
                        if (this.pages[i].name == "recent") {
                            this.pages[i].name = "featured";
                            this.pages[i].prop = this.recent;
                            this.pages[i].title = this.title2;
                        } else if (this.pages[i].name == "featured") {
                            this.pages[i].prop = this.featured;
                            this.pages[i].title = this.title1;
                        } else {
                            this.pages[i].prop = [];
                            this.pages[i].title = "";
                        }
                        this.componentOrder.push(this.pages[i]);
                    }
                }
                this.is_loading = false;
            });
        },
        get_products() {
            store.commit("deliware_cart/UPDATE_FOOTER", false);

            console.log(this.lat);
            this.$http
                .get(
                    "/single_restaurant_products/" +
                        this.lat +
                        "/" +
                        this.lng +
                        "/" +
                        this.business_id
                )
                .then((res) => {
                    this.featured = res.data.featured;
                    this.recent = res.data.recent;
                    this.isopen = res.data.restaurant_details.is_open;
                    this.get_pages();
                    store.commit("deliware_cart/UPDATE_FOOTER", true);
                });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/swiper.scss";

.section-title-container {
  text-align: center;
  margin-bottom: 24px;
}

.section-title {
  color: #ff006b;
  font-weight: bold;
  font-size: 32px;
  padding-left: 355px;
  text-align: center;
}

.section-underline {
  width: 64px;
  height: 0;
  border: none;
  border-top: 4px solid #ffce00;
  margin: 8px auto 0 auto;
}
</style>


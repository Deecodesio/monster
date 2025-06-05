<template>
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
            <div class="mx-5 py-5" v-if="!is_loading">
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
            <sectionB />
            <!-- Shop Location Div -->
            <div
                class="container-fluid"
                style="margin-top: 50px; padding-right: 0; padding-left: 0"
            >
                <Ourshops />
            </div>
        </b-col>
    </b-row>
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
import sectionB from "./sectionB.vue";

export default {
    components: {
        BRow,
        BCol,
        BCard,
        top_banners,
        categories,
        featured,
        Ourshops,
        sectionB,
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
            title1: "Top Selling Products",
            title2: "Best Seller",
            componentOrder: [],
            is_loading: false,
            is_loading: false,
            settings: [],
        };
    },
    created() {
        this.get_products();
        this.get_settings();
    },
    methods: {
        get_pages() {
            this.is_loading = true;
            this.$http.get("/get_pages").then((res) => {
                this.pages = res.data.pages;
                for (var i = 0; i < this.pages.length; i++) {
                    if (this.pages[i].is_editable == 1) {
                        this.pages[i].name = "page1";
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
        get_settings() {
            this.$http.get("/get_settings").then((res) => {
                this.settings = res.data.setting;
                console.log("settings", this.settings);
                // Find the tax_inclusive setting
                const taxSetting = this.settings.find(
                    (s) => s.key_word === "tax_inclusive"
                );

                if (taxSetting) {
                    const taxValue = parseInt(taxSetting.value, 10);
                    localStorage.setItem("IS_TAX_INCLUSIVE", taxValue);
                }
            });
        },
        get_products() {
            store.commit("deliware_cart/UPDATE_FOOTER", false);
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

<style lang="scss" scoped>
@import "~@resources/scss/vue/libs/swiper.scss";
</style>

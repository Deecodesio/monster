<template>
    <div class="pt-1" v-if="!isempty">
        <div class="subcat_main_div">
            <div class="subcat_slider">
                <div class="mt-5 mb-3 text-center">
                    <h2>{{ $t("Product Categories") }}</h2>
                    <hr class="mx-auto">
                </div>
                <!-- <div class="mt-5 mb-3 text-center">
                    <h2>{{ title3 }}</h2>
                    <hr class="mx-auto">
                </div> -->

                <div class="swiper-container-wrapper">
                    <!-- Navigation Buttons (Now Outside Swiper) -->
                    <div id="swiper-button-prev" class="swiper-button-prev"></div>
                    <swiper class="swiper-multiple rounded swiper-shadow text-center" :options="swiperOptions"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                        <swiper-slide v-for="(data, index) in rows2" :key="index">
                            <div class="cat-card-main" @click="gotor(data.id, data.text)">
                                <b-img class="fl" :src="data.img" :alt="data.text" />
                                <div class="swiper-text pt-md-1 pt-sm-50">
                                    <div class="main_Cat_name">
                                        <b>{{ data.text }}</b>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                    <div id="swiper-button-next" class="swiper-button-next"></div>
                </div>

                <!-- Swiper Wrapper -->
                <!-- <div class="swiper-container-wrapper">
                    <div id="swiper-button-prev" class="swiper-button-prev"></div>
                    <swiper class="swiper-navigations rounded swiper-shadow text-center" :options="swiperOptions"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                        <swiper-slide v-for="(data, index) in sub_categories" :key="index">
                            <div class="cat-card-main subcat_card" @click="gotor(data.id, data.name)"
                                :class="{ 'subcat_card_active': selected_subcat == data.id }">
                                <b-img class="fl subcat_img" :src="data.image" :alt="data.name" />
                                <div class="swiper-text pt-md-1 pt-sm-50">
                                    <h6><b>{{ data.name }}</b></h6>
                                </div>
                            </div>
                        </swiper-slide>
                    </swiper>
                    <div id="swiper-button-next" class="swiper-button-next"></div>
                </div> -->
            </div>
        </div>
        <div v-if="cat_products.length">
            <div class="container">
                <div class="mt-2 mb-2">
                    <b-breadcrumb separator="|">
                        <b-breadcrumb-item to="/">
                            <span class="bc_active bc_home">HOME</span>
                        </b-breadcrumb-item>
                        <b-breadcrumb-item active>
                            <span class="bc_active"> {{ title }}</span>
                        </b-breadcrumb-item>
                    </b-breadcrumb>
                </div>
                <featured :prop="cat_products" :business_id="business_id" :isopen="isopen" title=""
                    v-if="cat_products.length" />
            </div>
        </div>
        <div v-if="!cat_products.length && is_clicked" class="text-center"
            style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
            <lottie-player src="/animations/no_prod.json" background="#FFFDFD" speed="1"
                style="width: 300px; height: 200px" loop autoplay></lottie-player>
            <h4>{{ $t("No Products Found") }}</h4>
        </div>



    </div>
</template>


<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import {
    BImg, BCard, BRow,
    BCol, BCardText, BBreadcrumb, BBreadcrumbItem
} from 'bootstrap-vue'
import 'swiper/css/swiper.css'
import featured from './featured.vue'
import loadcart from './cartfunctions.js'
import store from '@@@/store'

export default {
    components: {
        Swiper,
        SwiperSlide,
        BImg,
        BCardText,
        BCard,
        BRow,
        BCol,
        featured, BBreadcrumb, BBreadcrumbItem
    },
    data() {
        return {
            rows: {},
            rows2: {},
            rows_more: {},
            rows_near: {},
            rows_near1: {},
            category: {},
            isempty_cat: false,
            isempty_near: false,
            isempty_more: false,
            isempty3: true,
            isempty: false,
            isLoading: true,
            more_name: '',
            near_name: '',
            iscat: false,
            isShow: false,
            lat: localStorage.getItem('latitude'),
            lng: localStorage.getItem('longitude'),
            business_id: localStorage.getItem('single_business_id'),
            isopen: 1,
            cat_products: [],

            swiperOptions: {
                breakpoints: {
                    320: { slidesPerView: 1, spaceBetween: 20 },
                    420: { slidesPerView: 1, spaceBetween: 10 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1200: { slidesPerView: 3, spaceBetween: 250 },
                    1440: { slidesPerView: 4, spaceBetween: 170 },
                },
                navigation: {
                    nextEl: '#swiper-button-next', // Use ID
                    prevEl: '#swiper-button-prev', // Use ID
                },
            },

            sub_categories: [],
            title3: '',
            title: '',
            is_clicked: false,
            selected_subcat: null
        }
    },
    watch: {
        $route(to, from) {
            this.get_created()


        }
    },
    methods: {
        ...loadcart,

        gotor(id, name) {
            this.selected_subcat = id
            this.is_clicked = true
            this.$http.get('/sub_category_products/' + this.lat + '/' + this.lng + '/' + id + '/' + this.business_id)
                .then(res => {
                    this.cat_products = res.data.cat_products
                    this.title = name
                    this.loadcart()

                })
        },
        toTitleCase(str) {
            return str.replace(
                /\w\S*/g,
                function (txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                }
            );
        },
        get_created() {
            store.commit('deliware_cart/UPDATE_FOOTER', false)

            var business_id = localStorage.getItem("single_business_id");
            this.$http.get("/category_lists/" + business_id).then((res) => {
                console.log("category_lists");
                console.log(res);
                this.isLoading = false;
                this.rows2 = res.data.data;

                if (this.rows2.length > 0) {
                    this.isempty_cat = true;
                }

                this.rows2.forEach((item) => {
                    console.log("Image URL:", "/" + item.img);
                });

                store.commit("deliware_cart/UPDATE_FOOTER", true);
            });

            console.log("Route Case");
            console.log(this.$route);
            if (this.$route.name == "sub_cat_list") {
                const productSlug = this.$route.params.slug
                const productId = productSlug.substring(productSlug.lastIndexOf('-') + 1)
                console.log(productId);
                this.$http.get('/sub_category_products/' + this.lat + '/' + this.lng + '/' + productId + '/' + this.business_id)
                    .then(res => {
                        this.cat_products = res.data.cat_products
                        this.title = res.data.title
                        this.loadcart()
                        store.commit('deliware_cart/UPDATE_FOOTER', true)

                    })

                // const productSlug2 = this.$route.params.slug
                // const t = productSlug2.substr(0, productSlug2.lastIndexOf("-"));

                // const productId2 = t.substring(t.lastIndexOf('-') + 1)
                // this.$http.get('/api/get_home_categories')
                //     .then(res => {
                //         if (res.data.categories.length) {
                //             for (var i = 0; i < res.data.categories.length; i++) {
                //                 if (res.data.categories[i].id == productId2) {
                //                     console.log(res.data.categories[i].sub_categories);
                //                     this.sub_categories = res.data.categories[i].sub_categories
                //                     this.title3 = res.data.categories[i].name
                //                 }
                //             }
                //         }
                //         store.commit('deliware_cart/UPDATE_FOOTER', true)

                //     })
            } else {
                console.log("Exceuted");
                console.log(this.$route.params);
                const productSlug = this.$route.params.slug
                const productId = productSlug.substring(productSlug.lastIndexOf('-') + 1)
                this.$http.get('/sub_category_products/' + this.lat + '/' + this.lng + '/' + productId + '/' + this.business_id)
                    .then(res => {
                        console.log("Welocme");
                        console.log(res);
                        this.cat_products = res.data.cat_products
                        this.title = res.data.title                         
                        this.loadcart()

                         store.commit('deliware_cart/UPDATE_FOOTER', true)


                    })
                // this.$http.get('/api/get_home_categories')
                //     .then(res => {
                //         if (res.data.categories.length) {
                //             for (var i = 0; i < res.data.categories.length; i++) {
                //                 if (res.data.categories[i].id == productId) {
                //                     console.log(res.data.categories[i].sub_categories);
                //                     this.sub_categories = res.data.categories[i].sub_categories
                //                     if (this.sub_categories.length) {
                //                         this.gotor(this.sub_categories[0].id, this.sub_categories[0].name)
                //                     }
                //                     this.title3 = res.data.categories[i].name
                //                 }
                //             }
                //         }
                //         store.commit('deliware_cart/UPDATE_FOOTER', true)

                //     })
            }
        }
    },
    created() {
        this.get_created()



    },


}
</script>
<style scoped>
.cat-card {
    background: #ffffff90;
    border-style: solid;
    border-color: #E01764;
    color: #000000;
    border-radius: 20px;

    cursor: pointer;

}

.cat-card[data-v-7701e4fa] {
    color: #000000;
    width: 100px !important;
    height: 103px !important;
}

.h7 {
    font-size: 14px;
    font-weight: bold;
}

.fl {
    max-width: 90%;
}

.card-title {
    color: #E01764;
}

.card-body {
    padding: 10px !important;
}

/* Wrapper to ensure buttons are positioned relative to swiper */
.swiper-container-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.swiper-button-prev,
.swiper-button-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    z-index: 10;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0px solid #E01764;
}

/* Positioning the Buttons */
.swiper-button-prev {
    left: -60px;
    /* Adjust to move outside */
}

.swiper-button-next {
    right: -60px;
    /* Adjust to move outside */
}

/* Make sure parent divs do not clip buttons */
.subcat_main_div,
.subcat_slider {
    overflow: visible !important;
}

.swiper_subcat {
    display: flex;
    justify-content: space-between;
}

.subcat_main_div {
    background: #FF81B61A;
    height: 50vh;
    justify-content: center;
    display: flex;
    align-items: center;
}

.subcat_slider {
    width: 50%;
}

.subcat_card {
    padding: 10px;
}

.subcat_card_active {
    background-color: #fff;
}

.subcat_img {
    border-radius: 10px;
    width: 150px;
}
</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

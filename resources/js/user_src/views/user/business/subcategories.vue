<template>

    <div class="pt-1" v-if="!isempty">
        <div class="subcat_main_div">
            <div class="subcat_slider">
                <div class="mt-5 mb-3 text-center">
                    <!-- <div v-if="title == 'Top Selling Products'">
                    <h2 style="text-align: center">{{ title }}</h2>
                    <div class="hrLine"></div>
                </div> -->



                    <div class="text-center" style="margin-top: 80px; margin-bottom: 0px;">
                        <h2>{{ $t(" Product Categories") }}</h2>
                        <hr class="mx-auto">
                    </div>
                    <!-- Navigation Buttons (Now Outside Swiper) -->
                     <div class="swiper-category-wrapper">
            <div id="swiper-button-prev" class="swiper-button-prev"></div>

            <swiper ref="mySwiper" class="swiper-multiple rounded text-center" :options="swiperOptions"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                <swiper-slide v-for="(data, index) in rows2" :key="index">
                    <div class="cat-img-text" @click="gotor(data.id, data.text)">
                        <b-img class="cat-img" :src="data.img" :alt="data.text" />
                        <div class="main_Cat_name">
                            <b>{{ data.text }}</b>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>

            <div id="swiper-button-next" class="swiper-button-next"></div>
        </div>
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
                <div class="mt-3 mb-1">
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
                    0: { slidesPerView: 2, spaceBetween: 4 },
                    480: { slidesPerView: 3, spaceBetween: 6 },
                    768: { slidesPerView: 4, spaceBetween: 8 },
                    992: { slidesPerView: 5, spaceBetween: 10 },
                    1200: { slidesPerView: 5, spaceBetween: 12 },
                },
                navigation: {
                    nextEl: "#swiper-button-next",
                    prevEl: "#swiper-button-prev",
                },
                loop: false,
                watchOverflow: true,
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
            this.cat_products = [];
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
                this.cat_products = [];
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
                const productSlug = this.$route.params.slug
                const productId = productSlug.substring(productSlug.lastIndexOf('-') + 1)
                this.cat_products = [];
                this.$http.get('/sub_category_products/' + this.lat + '/' + this.lng + '/' + productId + '/' + this.business_id)
                    .then(res => {
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
.subcat_slider {
    width: 100%;
}

.hrLine {
    max-width: 75px;
    margin: 5px auto 15px;
    border: 3px solid #ffce00;
    border-radius: 5px;
    background-color: #ffce00;
}

.swiper-category-wrapper {
    position: relative;
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 0 40px;
    box-sizing: border-box;
}

.swiper-multiple {
    width: 100% !important;
}

.swiper-button-next,
.swiper-button-prev {
    width: 32px;
    height: 32px;
    /* background-color: white; */
    border-radius: 50%;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    top: 50%;

    position: absolute;
}

.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 18px;
    font-weight: bold;
    color: #e6007e;
}

.swiper-button-prev {
    left: 5px;
    padding-top: 13px;
    border: none !important;
}
.swiper-button-prev{
    opacity: 1 !important;
}
.swiper-button-next{
    opacity: 1 !important;
}
.swiper-button-next {
    right: 5px;
    padding-top: 13px;
}

.cat-img {
  width: 100%;
  aspect-ratio: 4 / 2; /* Wider rectangle */
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}



.cat-img-text {
  width: 100%;
  max-width: 280px; /* Increased from 220px */
  margin: auto;
  text-align: center;
}

.cat-img-text:hover .cat-img {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.main_Cat_name {
    margin-top: 7px;
    font-weight: 600;
    font-size: 16px;
    color: #222;
    text-align: center;
    line-height: 1.2;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 0;
    margin: 0;
}

/* Tablet */
@media (max-width: 1024px) {
    .cat-img-text {
        max-width: 160px;
    }

    .main_Cat_name {
        font-size: 14px;
    }

    .swiper-button-next,
    .swiper-button-prev {
        display: none !important;
    }
}

/* Mobile */
@media (max-width: 767px) {
    .cat-img-text {
        max-width: 120px;
    }

    .main_Cat_name {
        font-size: 13px;
    }
}

</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

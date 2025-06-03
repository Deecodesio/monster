<template>

    <div class="pt-1" v-if="!isempty">
       <div class="subcat_main_div">
            <div class="subcat_slider">
              <div class="mt-5 mb-3 text-center">
 <!-- <div v-if="title == 'Top Selling Products'">
                    <h2 style="text-align: center">{{ title }}</h2>
                    <div class="hrLine"></div>
                </div> -->



                <div class="mt-5 mb-3 text-center">
                    <h2>{{ $t(" Product Categories")}}</h2>
                    <hr class="mx-auto">
                </div>

                <div class="container swiper-container-wrapper swiper-wrapper swiper-button-disabled swiper-container-rtl subcat_slider">
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
                    320: { slidesPerView: 2, spaceBetween: 10 },
    768: { slidesPerView: 3, spaceBetween: 20 },
    1024: { slidesPerView: 4, spaceBetween: 30 },
    1440: { slidesPerView: 2, spaceBetween: 70 },
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
            this.cat_products=[];
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
                this.cat_products=[];
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
                this.cat_products=[];
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
.subcat_slider{
    width:100%;
}
.hrLine {
    max-width: 75px;
    margin: 10px auto;
    border: 2px solid #ffce00;
    border-radius: 5px;
    background-color: #ffce00;
}




.pt-5{
  padding-top: 0rem !important;
}
.cat-card-main {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0 auto;
  padding: 10px;
  margin-left: 40px;

}

.fl {
 width: 250px; height: 150px;
  object-fit: cover;
  border-radius: 12px;
  margin-left: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: transform 0.3s ease, box-shadow 0.3s ease;

}

.fl:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}



.main_Cat_name {
  margin-top: 8px;
  font-weight: 600;
  font-size: 18px;
  color: black;
  text-align: center;
}
.swiper-slide {
  width: 200px !important;
  display: flex;
  justify-content: center;
}
.swiper-button-next,
.swiper-button-prev {

  width: 30px;
  height: 30px;

  border-radius: 50%;

  z-index: 10;
}
.swiper-button-next{
    margin-right: 25px;
}
.swiper-button-prev{
    margin-left: 25px;
}
.swiper-button-next::after,
.swiper-button-prev::after {
  font-size: 18px;
  font-weight: bold;
}
.swiper-category-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  position: relative;

}


.swiper-category-wrapper .swiper-button-prev,
.swiper-category-wrapper .swiper-button-next {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  z-index: 10;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;

}

.swiper-category-wrapper .swiper-button-prev::after,
.swiper-category-wrapper .swiper-button-next::after {
  font-size: 18px;

  /* color: #e91e63; */
}


.swiper-button-prev.swiper-button-disabled,
.swiper-button-next.swiper-button-disabled {
  opacity: 1 !important;
  pointer-events: auto !important;
}

.swiper-button-prev,
.swiper-container-rtl .swiper-button-next{
    border: none !important;}



</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

<template>
    <div v-if="!isempty">
        <b-row>
            <b-col md="12">
                <h2 style="text-align: center;margin-top: 15px; margin-bottom: 10px;">
                    {{ $t("Product Categories") }}
                </h2>
                <div class="hrLine"></div>
            </b-col>
        </b-row>
        <!-- Swiper Wrapper -->
        <div class="swiper-category-wrapper mb-0">
            <!-- <div id="swiper-button-next" class="swiper-button-next"></div> -->
            <div id="swiper-button-next" class="swiper-button-next" :class="{ 'force-disabled': rows2.length <= 6 }">
            </div>
            <swiper ref="mySwiper" class="swiper-multiple rounded text-center" :options="swiperOptions"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                <swiper-slide v-for="(data, index) in rows2" :key="index">
                    <div class="cat-card-main" @click="gotor(data.slug)">
                        <b-img class="fl" :src="data.img" :alt="data.text" />
                        <div class="swiper-text pt-md-1 pt-sm-50">
                            <div class="main_Cat_name">
                                <b>{{ data.text }}</b>
                            </div>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>
            <!-- <div id="swiper-button-prev" class="swiper-button-prev"></div>-->
            <div id="swiper-button-prev" class="swiper-button-prev" :class="{ 'force-disabled': rows2.length <= 5 }">
            </div>

        </div>


    </div>
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import { BImg, BCard, BRow, BCol, BCardText } from "bootstrap-vue";
import "swiper/css/swiper.css";
import store from "@@@/store";

export default {
    components: {
        Swiper,
        SwiperSlide,
        BImg,
        BCardText,
        BCard,
        BRow,
        BCol,
    },
    data() {
        return {
            rows: {},
            rows2: [],
            rows_more: {},
            business_id: 1,
            rows_near: {},
            rows_near1: {},
            category: {},
            isempty_cat: false,
            isempty_near: false,
            isempty_more: false,
            isempty3: true,
            isempty: false,
            isLoading: true,
            more_name: "",
            near_name: "",
            iscat: false,
            isShow: false,

            swiperOptions: {
                breakpoints: {
                    320: { slidesPerView: 2, spaceBetween: 10 },
                    768: { slidesPerView: 3, spaceBetween: 20 },
                    1024: { slidesPerView: 4, spaceBetween: 30 },
                    1440: { slidesPerView: 4, spaceBetween: 70 },
                },
                slidesPerView: 4, // Desktop default
                spaceBetween: 20,
                centeredSlides: false,
                freeMode: true,
                loop: false,
                navigation: {
                    nextEl: "#swiper-button-prev",
                    prevEl: "#swiper-button-next",
                },
                watchOverflow: false,
            },
        };
    },

    methods: {
        gotor(slug) {
            console.log("slug*****");
            console.log(slug);
            this.$router.push({ name: "cat_list", params: { slug: slug } });
        },
        toTitleCase(str) {
            return str.replace(/\w\S*/g, function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        },
    },

    created() {
        store.commit("deliware_cart/UPDATE_FOOTER", false);

        const business_id = localStorage.getItem("single_business_id");

        this.$http.get("/category_lists/" + business_id).then((res) => {
            console.log("category_lists");
            console.log(res);
            this.isLoading = false;
            this.rows2 = res.data.data;

            if (this.rows2.length > 0) {
                this.isempty_cat = true;
            }

            // Log image URLs
            this.rows2.forEach((item) => {
                console.log("Image URL:", "/" + item.img);
            });

            // Force Swiper to reinitialize in case slide count changed
            if (this.rows2.length <= 5) {
                this.swiperInstance.navigation.prevEl.classList.add('force-disabled');
                this.swiperInstance.navigation.nextEl.classList.add('force-disabled');
            } else if (this.rows2.length <= 6) {
                this.swiperInstance.navigation.nextEl.classList.add('force-disabled');
            }

            store.commit("deliware_cart/UPDATE_FOOTER", true);
        });
    },
}

</script>
<style scoped>
.swiper-category-wrapper {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.cat-card-main {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 auto;
    padding: 10px;
}

.fl {
    width: 250px;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.fl:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.swiper-wrapper {
    display: flex;
    gap: 50px;
}

.swiper-slide {
    width: 200px !important;
    display: flex;
    justify-content: center;
}

.main_Cat_name {
    margin-top: 8px;
    font-weight: 600;
    font-size: 18px;
    color: black;
    text-align: center;
}

/* Swiper Buttons */
.swiper-button-prev,
.swiper-button-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 30px;
    color: white;
    z-index: 10;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
}

.swiper-button-prev {
    left: 10px;
}

.swiper-button-next {
    right: 10px;
}

.swiper-button-prev::after,
.swiper-button-next::after {
    content: '';
    display: inline-block;
    font-size: 30px;
    color: white;
    font-family: swiper-icons;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.7);
}


/* .swiper-button-next,
.swiper-container-rtl .swiper-button-prev {
    right: 10px;
    left: auto;
    background-color: #f4fffd90;
    border-radius: 10px;
    top: 45%;
}

.swiper-button-prev,
.swiper-container-rtl .swiper-button-next {
    left: 10px;
    right: auto;
    background-color: #f4fffd90;
    border-radius: 10px;
 border: 2px solid #f4fffd;
    top: 45%;
} */

.hrLine {
    max-width: 75px;
    margin-top: 5px;
    margin-bottom: 15px;
    margin-left: auto;
    margin-right: auto;
    border: 3px solid #ffce00;
    border-radius: 5px;
    background-color: #ffce00;
}
</style>

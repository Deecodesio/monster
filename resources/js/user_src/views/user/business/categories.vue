<template>
    <div v-if="!isempty">
        <b-row>
            <b-col md="12">
                <h2 style="text-align: center; margin-top: 15px; margin-bottom: 10px;">
                    {{ $t("Product Categories") }}
                </h2>
                <div class="hrLine"></div>
            </b-col>
        </b-row>

        <div class="swiper-category-wrapper">
            <div id="swiper-button-prev" class="swiper-button-prev"></div>

            <swiper ref="mySwiper" class="swiper-multiple rounded text-center" :options="swiperOptions"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                <swiper-slide v-for="(data, index) in rows2" :key="index">
                    <div class="cat-img-text g-3" @click="gotor(data.slug)">
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
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import { BImg, BRow, BCol } from "bootstrap-vue";
import "swiper/css/swiper.css";
import store from "@@@/store";

export default {
    components: {
        Swiper,
        SwiperSlide,
        BImg,
        BRow,
        BCol,
    },
    data() {
        return {
            rows2: [],
            isempty: false,
            swiperOptions: {
                breakpoints: {
                    0: { slidesPerView: 2, spaceBetween: 6 },
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
            }


        };
    },
    methods: {
        gotor(slug) {
            this.$router.push({ name: "cat_list", params: { slug } });
        },
    },
    created() {
        store.commit("deliware_cart/UPDATE_FOOTER", false);
        const business_id = localStorage.getItem("single_business_id");
        this.$http.get("/category_lists/" + business_id).then((res) => {
            this.rows2 = res.data.data;
            this.isempty = this.rows2.length === 0;
            store.commit("deliware_cart/UPDATE_FOOTER", true);
        });
    },
};
</script>

<style scoped>
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
    background-color: white;
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
  max-width: 265px; /* Increased from 220px */
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
    .main_Cat_name {
        font-size: 13px;
    }
}

</style>

<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

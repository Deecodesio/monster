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
        <!-- <div class="swiper-category-wrapper mb-0">

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


        </div> -->

               <div class="container swiper-container-wrapper swiper-wrapper swiper-button-disabled swiper-container-rtl subcat_slider">
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
    320: { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 10 },
    768: { slidesPerView: 3, slidesPerGroup: 1, spaceBetween: 20 },
    1024: { slidesPerView: 4, slidesPerGroup: 2, spaceBetween: 30 },
    1440: { slidesPerView: 4, slidesPerGroup: 2, spaceBetween: 70 },
  },
  slidesPerView: 4,
  slidesPerGroup: 2, // 👈 Move 2 slides per click
  spaceBetween: 20,
  loop: false,
  navigation: {
    nextEl: "#swiper-button-next",
    prevEl: "#swiper-button-prev",
  },
  watchOverflow: true,
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
.hrLine {
  max-width: 75px;
  margin: 5px auto 15px;
  border: 3px solid #ffce00;
  border-radius: 5px;
  background-color: #ffce00;
}

/* Category Card */
.cat-card-main {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0 auto;
  padding: 6px;
  cursor: pointer;
}

/* Image Styling */
.fl {
  width: 220px;
  height: 130px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: transform 0.3s, box-shadow 0.3s;
  margin-left: 6rem;
}
.fl:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Category Name Text */
.main_Cat_name {
  margin-top: 8px;
  font-weight: 600;
  font-size: 18px;
  color: black;
  text-align: center;
}

/* Swiper Slide */
.swiper-slide {
  width: 200px !important;
  display: flex;
  justify-content: center;
}

/* Navigation Buttons */
.swiper-button-next,
.swiper-button-prev {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  z-index: 10;
}
.swiper-button-next {
  margin-right: 25px;
}
.swiper-button-prev {
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
}

.swiper-button-prev.swiper-button-disabled,
.swiper-button-next.swiper-button-disabled {
  opacity: 1 !important;
  pointer-events: auto !important;
}
.swiper-button-prev,
.swiper-container-rtl .swiper-button-next {
  border: none !important;
}

/* --------- Responsive Breakpoints ---------- */

/* Tablets (768px - 1024px) */
@media (max-width: 1024px) {
  .fl {
    width: 160px;
    height: 100px;
    margin-left: 3rem;
  }
  .main_Cat_name {
    font-size: 16px;
  }
  .swiper-slide {
    width: 160px !important;
  }
}

/* Small Tablets / Large Phones (600px - 767px) */
@media (max-width: 767px) {
  .fl {
    width: 120px;
    height: 72px;
    margin-left: 2rem;
  }
  .cat-card-main {
    padding: 4px;
  }
  .main_Cat_name {
    font-size: 14px;
  }
  .swiper-slide {
    width: 120px !important;
  }
  .swiper-button-next,
  .swiper-button-prev {
    display: flex !important;
    width: 28px;
    height: 28px;
  }
}

/* Mobile Phones (Below 600px) */
@media (max-width: 599px) {
  .fl {
    width: 90px;
    height: 54px;
    margin-left: 1rem;
  }
  .main_Cat_name {
    font-size: 12px;
  }
  .swiper-slide {
    width: 90px !important;
  }
}
</style>

<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

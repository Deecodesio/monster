<template>
    <div class="pt-5" v-if="!isempty">
  

        <b-row>
            <b-col md="9">
      
                    <h2
                        style="
                             
                            text-align: center;
                            color: #ff006b;
                          

                            padding-left: 355px;
                            /* padding-bottom: 20px; */
                            font-weight: bold;
                            font-size: 32px;
                            ;
                            /* margin-bottom: 0; */
                        "
                    >
                      {{ $t("Product Categories") }}
                
        <hr style="
  width: 64px;
  height: 0;
  border: none;
  border-top: 4px solid #FFCE00;
  margin: 16px auto 0 auto;
    margin: 8px auto 0 auto;
">
    </h2>
</hr>
             

           
            </b-col>
        </b-row>
        <!-- Swiper Wrapper -->
      <div class="swiper-category-wrapper swiper-wrapper swiper-button-disabled swiper-container-rtl" >
  <div id="swiper-button-prev" class="swiper-button-prev"></div>

  <swiper
    class="swiper-multiple rounded swiper-shadow text-center"
    :options="swiperOptions"
    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
  >
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
            rows2: {},
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
    1440: { slidesPerView: 2, spaceBetween: 60 },
  },
  slidesPerView: 'auto',
//   spacearound: 20,
  spaceBetween: 10,
  centeredSlides: false,
  freeMode: true,
  navigation: {
    nextEl: "#swiper-button-next",
    prevEl: "#swiper-button-prev",
  },
  loop: false,
}


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
                return (
                    txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                );
            });
        },
    },
    created() {
        store.commit("deliware_cart/UPDATE_FOOTER", false);

        var lat = localStorage.getItem("latitude");
        var lng = localStorage.getItem("longitude");
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
    },
};
</script>
<style scoped>
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
    margin-right: 2px;
}
.swiper-button-prev{
    margin-left: 2px;
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
    border: none !important;    
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
</style>
<style lang="scss">
@import "~@resources/scss/vue/libs/swiper.scss";


</style>

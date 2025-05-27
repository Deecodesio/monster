<template>
  <div class="pt-1" v-if="!isempty">
    <b-row>
      <b-col cols="9" v-if="business_id === 2">
        <h2>{{ $t("Nearby") }} {{ $t("Store") }}</h2>
        <hr>
      </b-col>
      <b-col cols="9" v-else>
        <h2>{{ $t("Nearby") }} {{ $t("Restaurants") }}</h2>
        <hr>
      </b-col>
      <b-col md="3">
        <div style="position: relative;float: right;margin-left: 8%;" slot="button-next" class="swiper-button-next"></div>
        <div style="position: relative;float: right;" slot="button-prev" class="swiper-button-prev"></div>

      </b-col>
    </b-row>

    <swiper class="swiper-multiple rounded3" :options="swiperOptions" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
      <swiper-slide v-for="(data, index) in rows" :key="index">
        <b-card :class=data.class
          @click="$router.push({ name: 'view_restaurant', params: { id: data.id, name: data.str } })">

          <b-img class="rounded2 imght2" :src="data.img" fluid />
          <div class="swiper-text pt-md-1 pt-sm-50  pt-4">
            <h3>{{ data.text }}</h3>
          </div>
          <b-col>
            <b-row>

              <div v-for="(data2, index2) in data.cuisines" :key="index2">
                {{ data2.name }},
              </div>
            </b-row>
          </b-col>
          <b-col>
            <b-row>
              <b-card-text cols="4" class="p-1-1">
                <b-img src="/time.svg" fluid alt="Scan" />

                <b>{{ data.travel_time }} Min</b>
              </b-card-text>
              <b-card-text cols="4" class="p-1-1">
                <b-img src="/location.svg" fluid alt="Scan" />

                <b> {{ data.distance }} Kms</b>
              </b-card-text>
              <b-card-text cols="4" class="p-1-1">
                <b-img src="/rating.svg" fluid alt="Scan" />

                <b>{{ data.rating }}</b>
              </b-card-text>
            </b-row>
          </b-col>

        </b-card>
      </swiper-slide>
      <!-- <div
      slot="button-next"
      class="swiper-button-next"
    ></div>
    <div
      slot="button-prev"
      class="swiper-button-prev"
    ></div> -->

    </swiper>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import { BImg, BCard, BCardText, BLink, BRow, BCol, } from 'bootstrap-vue'
import 'swiper/css/swiper.css'

export default {
  components: {
    Swiper,
    SwiperSlide,
    BImg,
    BCard,
    BCardText,
    BLink,
    BRow,
    BCol,
  },
  data() {
    return {
      rows: {},
      business_id: [],
      isempty: false,

      swiperOptions: {
        breakpoints: {
          320: {
            slidesPerView: 1.2,
            spaceBetween: 10
          },
          760: {
            slidesPerView: 2.2,
            spaceBetween: 20
          },


          1200: {
            slidesPerView: 4.2,
            spaceBetween: 20
          },

        },

        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        scrollbar: { draggable: true }
      },
    }
  },
  created() {
    var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');
    this.isempty = true
    var business_id = localStorage.getItem('single_business_id');

    this.$http.get('/todays_special_business/' + lat + '/' + lng + '/' + business_id)
      .then(res => {
        this.rows = res.data.data;
        this.business_id = res.data.business_id;
        for (let index = 0; index < this.rows.length; index++) {
          var str = this.rows[index].text
          str.replace(/\-/g, "")
          this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
          if (this.rows[index].is_open == 1) {
            this.rows[index].class = "rounded3"
          } else {
            this.rows[index].class = "rounded3 closed"
          }

        }
        if (this.rows.length > 0) {
          this.isempty = false
        }
      })


  },


}
</script>
<style lang="scss">
@import "~@resources/scss/base/pages/app-ecommerce.scss";
</style>

<style scoped>
.swiper-button-next,
.swiper-container-rtl .swiper-button-prev {
  right: 10px;
  left: auto;
  /* top: -30px; 
    margin-top: -68px;*/
  /* z-index: 999999; */
  background-color: #f4fffd90;
  border-radius: 10px;
  border: 2px solid #62C061;
  top: 45%;
}

.swiper-button-prev,
.swiper-container-rtl .swiper-button-next {
  left: 10px;
  right: auto;
  background-color: #f4fffd90;
  border-radius: 10px;
  border: 2px solid #62C061;
  top: 45%;
}

.rounded3 {
  cursor: pointer;
}


.closed {
  filter: grayscale(1);
}
</style>

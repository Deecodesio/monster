<template>
  <b v-if="!isempty">
    <swiper class="swiper-multiple" :options="swiperOptions" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
      <swiper-slide v-for="(data, index) in rows" :key="index">
        <b-img class="rounded3" :src="data.img" fluid :alt="data.str"
          @click="$router.push({ name: 'view_restaurant', params: { id: data.restaurant_id, name: data.str } })"
          width="425" height="200" />
      </swiper-slide>


    </swiper>


  </b>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import { BImg, BCard } from 'bootstrap-vue'
import 'swiper/css/swiper.css'

export default {
  components: {
    Swiper,
    SwiperSlide,
    BImg,
    BCard,
  },
  data() {
    return {
      rows: {},

      isempty: false,

      swiperOptions: {
        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 10
          },
          760: {
            slidesPerView: 2,
            spaceBetween: 30
          },


          1200: {
            slidesPerView: 3,
            spaceBetween: 30
          },

        },
        autoplay: {
          delay: 2500,
          disableOnInteraction: true,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
      },
    }
  },
  created() {
    var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');
    this.isempty = true
    this.$http.get('/banner_list/' + lat + '/' + lng)
      .then(res => {
        this.rows = res.data.data
        for (let index = 0; index < this.rows.length; index++) {
          var str = this.rows[index].name
          str.replace(/\-/g, "")
          this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();

        }

        if (this.rows.length > 0) {
          this.isempty = false
        }
      })

  },


}
</script>

<style scoped>
.rounded {
  border-radius: 2px !important;
}

.rounded3 {
  cursor: pointer;
}

.pt-6 {
  padding-top: 6rem !important;
}
</style>

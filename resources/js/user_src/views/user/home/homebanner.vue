<template>
  <swiper class="swiper-multiple rounded3" :options="swiperOptions" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
    <swiper-slide v-for="(data, index) in rows" :key="index">
      <b-img class="rounded3" :src="data.img" alt="teerya" fluid />
    </swiper-slide>

    <div slot="pagination" class="swiper-pagination" />
  </swiper>
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



      swiperOptions: {
        slidesPerView: 3,
        spaceBetween: 30,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
      },
    }
  },
  created() {
    this.$http.get('/banner_list')
      .then(res => {
        this.rows = res.data.data
      })

  },


}
</script>

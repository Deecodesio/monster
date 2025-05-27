<template>
    <div>
        <!-- <swiper ref="swiperTop" class="swiper-gallery gallery-top" :options="swiperOptions"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
            <swiper-slide v-for="(data, index) in prop" :key="index">
                <b-img thumbnail :src="data" fluid />
            </swiper-slide>
            <div slot="button-next" class="swiper-button-next swiper-button-white"></div>
            <div slot="button-prev" class="swiper-button-prev swiper-button-white"></div>
        </swiper>
        <swiper ref="swiperThumbs" class="swiper gallery-thumbs" :options="swiperOptionThumbs">
            <swiper-slide v-for="(data, index) in prop" :key="index">
                <b-img thumbnail :src="data" fluid />
            </swiper-slide>
        </swiper> -->

        <lingallery :iid.sync="currentId" :width="600" :height="400" :items=prop :responsive="true"
            :disableImageClick="true" :allowMediaOverlap="false" />
    </div>
</template>
  
<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import { BImg } from 'bootstrap-vue'
import 'swiper/css/swiper.css'
import lingallery from 'lingallery';
export default {
    components: {
        Swiper,
        SwiperSlide,
        BImg, lingallery
    },
    data() {
        return {

            currentId: null
        }
    },
    props: {
        prop: {
            default: () => [],
        },

    },
    mounted() {

    },
}
</script>

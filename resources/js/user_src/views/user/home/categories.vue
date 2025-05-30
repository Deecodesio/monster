<template>
  <div class="pt-1" v-if="!isempty">
    <b-row>
      <b-col md="9">
        <h2> 123{{ $t("Product Categories") }}</h2>
        <hr>
      </b-col>
      <!-- <b-col md="3">
        <div style="position: relative;float: right;margin-left: 8%;" slot="button-next" class="swiper-button-next"
          id="swiper-button-next-cat"></div>
        <div style="position: relative;float: right;" slot="button-prev" class="swiper-button-prev"
          id="swiper-button-prev-cat"></div>

      </b-col> -->
    </b-row>


    <swiper class="swiper-multiple rounded swiper-shadow text-center" :options="swiperOptions"
      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
      <swiper-slide v-for="(data, index) in rows2" :key="index">
        <b-card class="cat-card">
          <b-img class="fl" :src="data.img" @click="gotor(data.id, data.str, data.text)" :alt="data.text" width="129"
            height="129" />
          <div class="swiper-text pt-md-1 pt-sm-50">
            <h6><b>{{ data.text }}</b></h6>
          </div>
        </b-card>
      </swiper-slide>


    </swiper>

  </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import {
  BImg, BCard, BRow,
  BCol,
} from 'bootstrap-vue'
import 'swiper/css/swiper.css'

export default {
  components: {
    Swiper,
    SwiperSlide,
    BImg,
    BCard,
    BRow,
    BCol,
  },
  data() {
    return {
      rows: {},
      rows2: {},


      isempty: false,

      swiperOptions: {
        breakpoints: {
          320: {
            slidesPerView: 1.5,
            spaceBetween: 20
          },
          420: {
            slidesPerView: 1.5,
            spaceBetween: 10
          },


          768: {
            slidesPerView: 3.5,
            spaceBetween: 30
          },
          1200: {
            slidesPerView: 6.5,
            spaceBetween: 30
          },

        },

        navigation: {
          nextEl: '#swiper-button-next-cat',
          prevEl: '#swiper-button-prev-cat',
        },
      },
    }
  },
  methods: {
    gotor(id, str, title) {
      localStorage.bussiness_id = id;
      if (this.$route.name == "home") {
        this.$http.get('/get_business/' + id)
          .then(res => {

            this.business_id = res.data
            if (this.business_id.layout_id === 3) {
              this.$router.push({ name: 'pickupdrop', params: { id: id, name: str } });
            }
            else {

              this.$router.push({ name: 'cat_list', params: { id: id, name: str } });
            }

          })
      } else {
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');

        this.$http.get('/get_category_wise_food_list/' + lat + '/' + lng + '/' + id)
          .then(res => {

            this.rows = res.data.data
            for (let index = 0; index < this.rows.length; index++) {
              var str = this.rows[index].text
              str.replace(/\-/g, "")
              this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
              if (this.rows[index].is_open == 1) {
                this.rows[index].class = "rounded2 imght2"
              } else {
                this.rows[index].class = "rounded2 imght2 closed"
              }

            }
            this.count = this.rows.length
            if (this.rows.length > 0) {
              this.isempty = false
            }
            this.$emit('clicked-show-detail', this.rows, title);
          })

      }

    },
    toTitleCase(str) {
      return str.replace(
        /\w\S*/g,
        function (txt) {
          return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
      );
    }
  },
  created() {
    var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');
    this.isempty = true
    this.$http.get('/category_list')
      .then(res => {
        this.rows2 = res.data.data
        for (let index = 0; index < this.rows2.length; index++) {
          var str = this.rows2[index].text
          str.replace(/\-/g, "")
          this.rows2[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();

        }
        if (this.rows2.length > 0) {
          this.isempty = false
        }
      })

  },


}
</script>
<style scoped>
.cat-card {
  background: #f4fffd90;
  border-style: solid;
  border-color: #E01764;
  color: #000000;
  border-radius: 20px;

  cursor: pointer;

}

.cat-card[data-v-7701e4fa] {
  color: #000000;
  width: 100px !important;
  height: 103px !important;
}

.h7 {
  font-size: 14px;
  font-weight: bold;
}

.fl {
  max-width: 90%;
}

.card-title {
  color: #E01764;
}

.card-body {
  padding: 10px !important;
}

.swiper-button-next,
.swiper-container-rtl .swiper-button-prev {
  right: 10px;
  left: auto;
  /* top: -30px;
   margin-top: -68px;*/
  /* z-index: 999999; */
  background-color: #f4fffd90;
  border-radius: 10px;
  border: 2px solid #E01764;
  top: 45%;
}

.swiper-button-prev,
.swiper-container-rtl .swiper-button-next {
  left: 10px;
  right: auto;
  background-color: #f4fffd90;
  border-radius: 10px;
  border: 2px solid #E01764;
  top: 45%;
}
</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

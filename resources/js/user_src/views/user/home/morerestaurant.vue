<template>
  <div class="pt-1" v-if="!isempty">
    <b-row>

      <b-col cols="6">
        <h2>{{ $t("More") }} {{ $t("Restaurant") }}</h2>
        <hr>
      </b-col>
      <b-col cols="6" class="text-r">
        <b-link @click="$router.push({ name: 'more_restaurant', query: { name: 'morerest' } })">{{ $t("See") }}
          {{ $t("More") }}</b-link>

      </b-col>
    </b-row>

    <b-row>

      <b-col xxl="3" lg="3" md="6" cols="12" v-for="(data, index) in rows" :key="index" class="col-xxl-3 hover"
        @click="$router.push({ name: 'view_restaurant', params: { id: data.id, name: data.str } })">
        <div :class=data.class class="rounded2 card">
          <b-img :class=data.class class="rounded2-1 imght2 round" :src="data.img" :alt="data.text" fluid width="425"
            height="200" />
          <div class=" swiper-text pt-md-1 pt-sm-50 pt-4" style="padding:0px 20px;">
            <h3>{{ data.text }}</h3>
          </div>
          <b-col style="min-height: 20px; margin:0px 20px;">
            <b-row>

              <div v-for="(data2, index2) in data.cuisines" :key="index2">
                {{ data2.name }},
              </div>
            </b-row>
          </b-col>
          <b-col>
            <b-row style="padding:0px 20px 20px 20px;">
              <b-card-text cols="4" class="p-1-1">
                <b-img src="/time.svg" fluid alt="Scan" width="12" height="9" />

                <b>{{ data.travel_time }} Min</b>
              </b-card-text>
              <b-card-text cols="4" class="p-1-1">
                <b-img src="/location.svg" fluid alt="Scan" width="12" height="9" />

                <b> {{ data.distance }} Kms</b>
              </b-card-text>
              <b-card-text cols="4" class="p-1-1">
                <b-img src="/rating.svg" fluid alt="Scan" width="12" height="9" />

                <b>{{ data.rating }}</b>
              </b-card-text>
            </b-row>
          </b-col>
        </div>
      </b-col>



    </b-row>



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


      isempty: false,
      swiperOptions: {
        slidesPerView: 5,
        spaceBetween: 30,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      },
    }
  },
  created() {
    var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');
    this.isempty = true
    this.$http.get('/get_more_restaurant_new/' + lat + '/' + lng)
      .then(res => {
        this.rows = res.data.data
        if (this.rows.length) {
          for (let index = 0; index < this.rows.length; index++) {

            var str = this.rows[index].text + " " + this.rows[index].area + " " + this.rows[index].city
            str.replace(/\-/g, "")
            this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();

            if (this.rows[index].is_open == 1) {
              this.rows[index].class = "rounded3 imght2"
            } else {
              this.rows[index].class = "rounded3 imght2 closed"
            }

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
  
<style lang="scss" scoped>
.item-view-radio-group ::v-deep {
  .btn {
    display: flex;
    align-items: center;
  }
}

.cat-card {

  border-radius: 20px;


}

.img-fluid {
  border-radius: 10px;
}

.hover {
  cursor: pointer;
}

.round {
  border-top-left-radius: 20px !important;
  border-top-right-radius: 20px !important;
  border-bottom-left-radius: 0px !important;
  border-bottom-right-radius: 0px !important;

}

.card:hover,
.card:focus,
.card:active {

  -webkit-transform: scale(1.1);
  transform: scale(1.1);

}
</style>
  
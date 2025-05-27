<template>   
  <div class="pt-1" v-if="!isempty">
    <h2>{{$t("Popular")}} {{$t("Brands")}}</h2>
    <hr>
    <swiper
      class="swiper-multiple brand-pad"
      :options="swiperOptions"
      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
    >
      <swiper-slide
        v-for="(data,index) in rows"
        :key="index"
      >
      <b >
        <b-img
        class=" rounded3 shadow-sm "
          :src="data.img"
          fluid
          @click="navigate(data.id,data.str,data.count,data.restaurant,data.restaurant_name)"

        />
        <!-- <div class="swiper-text pt-md-1 pt-sm-50">
        {{ data.text }}
        </div> -->
      </b>
      </swiper-slide>
      <div
      slot="button-next"
      class="swiper-button-next"
    />
    <div
      slot="button-prev"
      class="swiper-button-prev"
    />    
       
    </swiper>
  </div>

    
 
</template>

<script>
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import { BImg ,BCard} from 'bootstrap-vue'
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
      isempty:false,


      swiperOptions: {
        breakpoints: {       
      320: {       
         slidesPerView: 2.2,
         spaceBetween: 10     
      },          
          760: {       
            slidesPerView: 4.2,       
            spaceBetween: 30     
          },   
      
          771: {       
            slidesPerView: 4.2,       
            spaceBetween: 30     
          } ,
          1200:{
            slidesPerView: 8.5,       
            spaceBetween: 30     
          },
          
      } ,
        
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
   
    this.$http.get('/get_near_brands/'+lat+'/'+lng)
      .then(res => {
          this.rows = res.data.data
          for (let index = 0; index < this.rows.length ; index++) {
            var str = this.rows[index].text
            str.replace(/\-/g,"")
            this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
            
          }
          if(this.rows.length > 0){
              this.isempty = false
          }
          })

  },
  methods:{
    navigate(id,str,count,rest,rest_name){
        if(count == 1){

          this.$router.push({ name: 'view_restaurant', params: { id: rest ,name:rest_name}})

        }else{
         this.$router.push({ name: 'brand_rest', params: { id: id ,name:str}})

        }
    }
  }
 
  
}
</script>
<style scoped>

.swiper-button-next, .swiper-container-rtl .swiper-button-prev {
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
.swiper-button-prev,.swiper-container-rtl .swiper-button-next {
    left: 10px;
    right: auto;
     background-color: #f4fffd90;
    border-radius: 10px;
    border: 2px solid #62C061;
    top: 45%;
}



.rounded3{
  cursor: pointer;
}
</style>
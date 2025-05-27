<template>
    <div  style="margin-top:80px;">

      <div class="pt-3">
        <b-img src="/more.png" fluid alt="Scan" class="imght2"/>

      </div>
    

    <div class="pt-1 container" >
      <categories @clicked-show-detail="check"/>


      <h2>{{title}} ({{ count }})</h2>
      <hr>
      <div v-if="!rows.length" class="text-center  mb-2"  style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
            <lottie-player
                src="/animations/no-rest.json"
                background="#FFFDFD"
                speed="1"
                style="width: 300px; height: 200px"
                loop
                autoplay
            ></lottie-player>
            <h4>{{$t("No Store Found")}}</h4>

      </div>
      <div v-if="is_loading" class="text-center"  style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
        <b-img src="/loader.svg" fluid alt="Scan"  />

      </div>
      <div v-if="rows.length">
        
          <b-row>
          
          <b-col
              xxl="3"
              lg="3"
              md="6"
              cols="12"
              v-for="(data,index) in rows"
              :key="index"
              class="col-xxl-3 hover"
              @click="$router.push({ name: 'view_restaurant', params: { id: data.id ,name:data.str}})"
            >
        <b-card 
        
        class="cat-card">
          <b-img
          :class=data.class
            :src="data.img"
            fluid
          />
          <div class="swiper-text pt-md-1 pt-sm-50 pt-4">
         <h3> {{ data.text }}</h3>
          </div>
          <b-col>
          <b-row>
  
          <div 
          v-for="(data2,index2) in data.cuisines"
              :key="index2"
          >
          {{ data2.name }},
          </div>
        </b-row>
      </b-col>
         <b-col>
          <b-row>
          <b-card-text  cols="4" class="p-1-1">
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
    </b-col>
  
    
  
      </b-row>
  
    </div>
  
    </div>
  
</div>
   
  </template>
  
  <script>
  import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
  import { BImg ,BCard,BCardText,BLink,  BRow, BCol,} from 'bootstrap-vue'
  import categories from '@@@/views/user/business/categories.vue'
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
      categories,
    },
    data() {
      return {
        rows: {},
        rows2: {},
        is_loading:false,
        title :'',
        count:'',
        isempty:false,
        swiperOptions: {
        breakpoints: {       
      320: {       
         slidesPerView: 3,
         spaceBetween: 20     
      },
      420: {       
         slidesPerView: 4,
         spaceBetween: 10     
      },            
         
      
          768: {       
            slidesPerView: 7,       
            spaceBetween: 30     
          } ,
          1200:{
            slidesPerView: 9.5,       
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
      
        this.title = this.toTitleCase(this.$route.params.name)
        this.$http.get('/get_category_wise_food_list/'+lat+'/'+lng+'/'+this.$route.params.id)
        .then(res => {
            this.rows = res.data.data
            for (let index = 0; index < this.rows.length ; index++) {
            var str = this.rows[index].text
            str.replace(/\-/g,"")
            this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
            if(this.rows[index].is_open == 1){
              this.rows[index].class = "rounded2 imght2"
            }else{
              this.rows[index].class = "rounded2 imght2 closed"
            }
            
          }
            this.count = this.rows.length
            if(this.rows.length > 0){
                this.isempty = false
            }
            })
      
            
     
      
  
    },
    methods:{
      check: function (value,title) {
       
        this.title = this.toTitleCase(title)
        this.rows = value
        this.count = this.rows.length
        if(this.rows.length > 0){
                this.isempty = false
            }
        },
         toTitleCase(str) {
            return str.replace(
                /\w\S*/g,
                function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                }
            );
            }
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
  
  
  .hover{
    cursor: pointer;
  }
  </style>
  
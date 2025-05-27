<template>
    <div class="pt-3">
    <!-- <div>
        <b-img src="/more.png" fluid alt="Scan"  class="imght2" />

    </div> -->
    <banner />

    <div class="pt-1 container" >
      <h2 v-if="isempty">{{$t(title)}}</h2>
      <h2 v-if="!isempty">{{$t(title)}} ({{ count }})</h2>
      <hr>
      <div v-if="isempty" class="text-center" style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">

              <lottie-player src="/animations/offer.json"  background="#FFFDFD"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>
              <h4 class="text-center mb-2">{{$t("No Offers Found")}}</h4>
           
           

      </div>
      <div v-if="!isempty">
        
          <b-row>
          
          <b-col
              xxl="3"
              lg="3"
              md="6"
              cols="12"
              v-for="(data,index) in rows"
              :key="index"
              class="col-xxl-3 hover"
             
            >
        <b-card 
        
        class="cat-card">
          
            <div class="swiper-text pt-md-1 pt-sm-50 pt-4">

                <span style="padding:10px; border: 2px dashed #FFBD00; border-radius: 10px; background-color: #FFBD0030;">{{ data.code }} </span>
                <h4 class="pt-2">{{ data.restaurant_name }}</h4>
                <p>{{ data.description }}</p>

            </div>
          
         
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
  import 'swiper/css/swiper.css'
  import banner from '@@@/views/user/users/banner.vue'
  
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
      banner,
     
    },
    data() {
      return {
        rows: {},
        title :'Offers',
        count:'',
        isempty:false,
        userData: JSON.parse(localStorage.getItem('webuserData')),
      
      }
    },
    created() {
      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');
      this.isempty = true
      
        
        this.$http.get('/offers/'+this.userData.id)
        .then(res => {
            this.rows = res.data.data
            this.count = this.rows.length
            if(this.rows.length > 0){
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
  .img-fluid{
    border-radius: 10px;
  }
  
  .hover{
    cursor: pointer;
  }
  </style>
  
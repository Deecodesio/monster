<template>

  <div class="container">
    <!-- faq search section -->
    <section id="faq-search-filter"   
             v-for="(data,index) in rows"
            :key="index">
      <b-card
        no-body
      
        :style="{backgroundImage:`url(${data.banner})`,height:'300px'}"
      >
        <b-card-body>
          <b-row>
            <b-col cols="6">
              <h1 class="text-yellow" >{{data.name}}</h1>
              <b-img src="/location.svg" fluid alt="Scan" /> {{data.address}}
            </b-col> 
            <b-col cols="6">
              <h1 class="text-yellow" >
                <b-img src="/time.svg" fluid alt="Scan" />{{data.travel_time}} Mins
              </h1>
              <h1 class="text-yellow" >
                <b-img src="/rating.svg" fluid alt="Scan" />{{data.rating}}
              </h1>
            </b-col>
          </b-row>

        <span v-if="data.is_open === 1">
                <b-badge :variant="statusVariant(data.is_open)">
           Open
          </b-badge>
          </span>
          <span v-else>
                 <b-badge :variant="statusVariant(data.is_open)">
            Closed
          </b-badge>
          </span>
        </b-card-body>
      </b-card>
    </section>
    <!--/ faq search section -->
    <div class="sidebar-detached sidebar-left s-border"  
          >
    <div class="sidebar">
      <div
        class="sidebar-shop"
       
      >
       
          <div class="multi-range-price text-r side">
           
            <div  v-for="(data2,index) in rows_detail"
            :key="index" 
            @click="scrollMeTo(data2.menu_name)"
            >
            
              {{data2.menu_name}}
            
            </div>
         
            
          </div>
        

      

      </div>
    </div>

    
  </div>
  <div class="ecommerce-searchbar mt-1">
      <b-row>
        <b-col cols="12"
        v-for="(data2,index2) in rows_detail"
            :key="index2">
         <h3>{{data2.menu_name}}</h3>
        <div :ref=data2.menu_name>

          <b-row >
              <b-col
              xxl="6"
              lg="6"
              md="6"
              cols="12"
              class="col-xxl-6"
              v-for="(data3,index3) in data2.items"
              :key="index3"
            >
                <b-card  class="rounded3">
                  <div class="media-list" >
                    <b-media vertical-align="top">
                      <template #aside v-if="data3.image">
                        <b-img class="bor"
                          :src="data3.image"
                          blank-color="#ccc"
                          width="100"
                        
                        />
                      </template>
                      <h4 class="media-heading">
                        {{data3.name}}
                      </h4>
                      <b-card-text class="mb-0">
                        {{data3.description}}
                      </b-card-text>
                      <h4 class="media-heading text-primary pt-4">
                        {{data3.price}}
                      </h4>
                    </b-media>
                  </div>
                </b-card>
            </b-col>
          </b-row>
          </div>

        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import {
  BCard,
  BCardBody,
  BForm,
  BInputGroup,
  BFormInput,
  BCardText,
  BInputGroupPrepend,
  BTabs,
  BTab,
  BImg,
  BRow,
  BCol,
  BAvatar,
  BNav, 
  BBadge,
  BNavItem ,
  BMedia, 
} from 'bootstrap-vue'
import VueSlider from 'vue-slider-component'
import { useWindowScroll } from '@vueuse/core'

export default {
  components: {
    BForm,
    BCard,
    BRow,
    BCol,
    BAvatar,
    BCardBody,
    BInputGroup,
    BFormInput,
    BCardText,
    BInputGroupPrepend,
    BTabs,
    BTab,
    BImg,
    BNav, 
  BNavItem ,
  BBadge,
  VueSlider,
   BMedia, 
  },
  data() {
    return {
    
      rows: {},
      rows_detail: {},


    }
  },
  setup() {
    const { y } = useWindowScroll()

    const scrollToTop = (name) => {
    
      // var element = document.getElementById(name);
      var element = this.$refs[refName];
      alert(element.offset().top);
      const rootEle = document.documentElement
      rootEle.scrollTo({
        top: element.offsetTop,
        behavior: 'smooth',
      })
    }

    return { y, scrollToTop }
  },
  methods: {
  scrollMeTo(refName) {
    var element = this.$refs[refName];
   console.log(element);
    var top = element.offsetTop;

    window.scrollTo(0, top);
  }
},
  props: {
    filters: {
      type: Object,
      required: true,
    },
    filterOptions: {
      type: Object,
      required: true,
    },
    mqShallShowLeftSidebar: {
      type: Boolean,
      required: true,
    },
  },
 
  created() {
    this.$http.get('/get_food_list_by_menu/'+this.$route.params.id)
      .then(res => {
         
          this.rows_detail = res.data.items

          console.log(this.rows_detail);
          })
          this.$http.get('/single_restaurant/'+this.$route.params.id)
      .then(res => {
          this.rows = res.data.restaurants
    

          console.log(this.rows);
          })

  },
  computed: {
    
    statusVariant() {
      const statusColor = {
       
        1       : 'light-primary',
       
        0       : 'light-info',
       
       
        
      }

      return status => statusColor[status]
    },
    
    direction() {
      if (store.state.appConfig.isRTL) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.dir = true
        return this.dir
      }
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.dir = false
      return this.dir 
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-faq.scss';
</style>
<style scoped>
.nav-link{
  color:black;
}
.text-yellow{
  color:yellow;
}
.text-white{
  color:white;
}
</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-slider.scss';
</style>

<style lang="scss" scoped>
.categories-radio-group,
.brands-radio-group,
.price-range-defined-radio-group {
    ::v-deep > .custom-control {
    margin-bottom: 0.75rem;
  }
}
.overlay {
    background-color: #000;
    /* position: absolute; */
    opacity: 0.5;
}

.sidebar-shop{
  cursor: pointer;
}
.bor{border-radius:10px;}
.side{padding: 30px 30px 0px 0px;
    line-height: 3;}
.s-border{

    border-right: 2px solid #e6e6e6;
    min-height: 400px;
}
</style>
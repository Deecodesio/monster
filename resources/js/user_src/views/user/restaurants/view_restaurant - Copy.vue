<template>
  <div>
   <div v-if="isLoading" class="ecommerce-searchbar mt-1 ">
    <b-row>
       
       <b-col cols="5">
      </b-col>
      <b-col cols="2">
        <b-img src="/loader.svg" fluid alt="Scan"  />
      </b-col>
      <b-col cols="5">
      </b-col>
         
    </b-row>
  </div>
<div v-if="!isLoading">
    <!-- faq search section -->
    <section id="faq-search-filter"   
             v-for="(data,index) in rows"
            :key="index" style="blank-color:#000;" 
            >
      <b-card class="bg-color pad0 nobor "
      style="padding-top:60px;"
      >
      <div class="overlay banimg"
        :style="{backgroundImage:`url(${data.banner})`,height:'400px'}"></div>
      
        <b-card-body class="">
          <div class="container restaurant-detailed-header">
          <b-row class="marg">
            
            <b-col 
            lg="8"
            md="12"
            sm="12">
              <b-row class>
                <b-col                 lg="2"        md="3"            sm="4"                cols="4" class="text-r">
                  <b-img :src=data.image fluid alt="Scan" class="imght1" /> 
                </b-col>
                <b-col lg="8"        md="12" cols="12">
                  <h2 class="text-yellow" style="float: left; margin-right:10px;">{{data.name}}</h2>
                  <div style="float: left; padding-top:5px;">
                  <span v-if="data.is_open === 1">
                    <b-badge class="badge-glow" :variant="statusVariant(data.is_open)">
                      {{$t("Open")}}
                    </b-badge>
                  </span>
                  <span v-else>

                        <b-badge class="badge-glow" :variant="statusVariant(data.is_open)">
                          {{$t("Closed")}}
                        </b-badge>
                  </span>
                  </div>
                  <div style="float: left;min-width: 80%">
                    <p class="textp2"><feather-icon           icon="MapPinIcon"          size="16"      class="ico-color2"  /> {{data.address}}
                    </p>
                  </div>                  
                </b-col>
              </b-row>
            </b-col> 
            <b-col style="padding-left: 0px; padding-right: 0px;"

            lg="4"
            md="12"
            sm="12"

             class="text-r text-rm pt-6">
              <b-row>
                
                <b-col cols="12" >
                  
                <b-button class="badge-glow text-yellow mborderw">
                  <i class="fa-regular fa-clock"></i> {{data.travel_time}} {{$t("Mins")}}
                </b-button>

                <b-button class="badge-glow text-yellow mborderw">
                  <i class="fa-solid fa-star"></i>  {{data.rating}}
                </b-button>

                <b-button class="badge-glow text-yellow mborderw" @click="markasfav">
                  <div v-if="!isfav">
                    <i class="fa-regular fa-heart"></i>
                  </div>
                  <div v-if="isfav">
                    <i class="fa-solid fa-heart"></i>
                  </div>
                </b-button>

                <b-button 
                v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  v-b-modal.modal-info
                class="badge-glow text-yellow mborderw">
                  <i class="fa-sharp fa-solid fa-circle-info"></i>
                </b-button>
                    
               

              <b-modal
                id="modal-info"
                ok-only
                ok-variant="success"
                ok-title="Accept"
                modal-class="modal-success"
                centered
                hide-footer
                :title="$t('Restaurant_Info')"
                >
              
                <div class="gmap_canvas"><iframe width="100%" height="170" id="gmap_canvas" :src=address></iframe></div>
                <p class=" pt-4"><strong class="mb-1 f-size-2"> {{rows[0].name}} </strong><span v-if="rows[0].is_open === 1">
                  <b-badge class="badge-glow" :variant="statusVariant(rows[0].is_open)">
                    {{$t("Open")}}
                  </b-badge>
                  </span><span v-else>

                        <b-badge class="badge-glow" :variant="statusVariant(rows[0].is_open)">
                          {{$t("Closed")}}
                  </b-badge>
                  </span>
                </p>

                <p><i class="fa-solid fa-location-dot"></i> {{rows[0].address}}</p>
                <p><i class="fa-solid fa-phone"></i> {{rows[0].phone}}</p>
                <p><i class="fa-solid fa-envelope"></i> {{rows[0].email}}</p>

                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i><strong> {{$t("Monday")}} : </strong>{{rows[0].opening_time_1 | tConvert}} - {{rows[0].closing_time_1 | tConvert}} , {{rows[0].opening_second_time_1 | tConvert}} - {{rows[0].closing_second_time_1 | tConvert}} </p>
                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{$t("Tuesday")}} : </strong>{{rows[0].opening_time_2 | tConvert}} - {{rows[0].closing_time_2 | tConvert}} , {{rows[0].opening_second_time_2 | tConvert}} - {{rows[0].closing_second_time_2 | tConvert}} </p>
                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i>  <strong>{{$t("Wednesday")}} : </strong>{{rows[0].opening_time_3 | tConvert}} - {{rows[0].closing_time_3 | tConvert}} , {{rows[0].opening_second_time_3 | tConvert}} - {{rows[0].closing_second_time_3 | tConvert}}</p>
                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{$t("Thursday")}} : </strong>{{rows[0].opening_time_4 | tConvert}} - {{rows[0].closing_time_4 | tConvert}} , {{rows[0].opening_second_time_4 | tConvert}} - {{rows[0].closing_second_time_4 | tConvert}}</p>
                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{$t("Friday")}} :  </strong>{{rows[0].opening_time_5 | tConvert}} - {{rows[0].closing_time_5 | tConvert}} , {{rows[0].opening_second_time_5 | tConvert}} - {{rows[0].closing_second_time_5 | tConvert}}</p>
                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{$t("Saturday")}} : </strong>{{rows[0].opening_time_6 | tConvert}} - {{rows[0].closing_time_6 | tConvert}} , {{rows[0].opening_second_time_6 | tConvert}} - {{rows[0].closing_second_time_6 | tConvert}}</p>
                <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{$t("Sunday")}} :  </strong>{{rows[0].opening_time_7 | tConvert}} - {{rows[0].closing_time_7 | tConvert}} , {{rows[0].opening_second_time_7 | tConvert}} - {{rows[0].closing_second_time_7 | tConvert}}</p>
              </b-modal>
                </b-col>
              </b-row>
            </b-col>
          </b-row>

        
          
        </div>
        </b-card-body>    
      </b-card>
    </section>
    <div class="container">
      <div 
        :class="{'menu dsp4' : $store.state['deliware_cart'].cartItemsCount , 'menu2 dsp4' : !$store.state['deliware_cart'].cartItemsCount}">
            <b-button
              v-ripple.400="'rgba(40, 199, 111, 0.15)'"
              v-b-modal.modal-success
              
              class="btn-scroll-to-top "
            >
            <i class="fa-solid fa-utensils ico-color"></i>
            </b-button>
      </div>

      <b-modal       id="modal-success"      title="Menu"                        ok-title="Accept"            centered      hide-footer      scrollable>


      <div  style="line-height:3;">
        <div @click="scrollMeTo(0,0)" id="0">All</div>
          
            <div  v-for="(data2,index) in rows_detail2"            :key="index"             @click="scrollMeTo(data2.menu_id,rows[0].id)"            :id="data2.menu_name">            
             {{data2.menu_name}} 
            
            </div>
      </div>
    </b-modal>
    <!--/ faq search section -->

    <b-row>

      <b-col    lg="3"            md="12"            cols="12">

          <div class="text-r side" v-if="promocodes.length">
            <div  v-for="(data7,index) in promocodes"
                :key="index" 
                >
                <div style="border: 1px dashed #FFBD00; background-color: #FFBD0010; border-radius: 10px;">
                <b-row
                v-clipboard:copy="data7.code"
                v-clipboard:success="onCopy"
                >
                  <b-col cols="4">
                    <b-img src="/offer.png" style="width:35px"></b-img>
                  </b-col>
                  <b-col cols="8">
                    <p style="text-align:left;padding-top:10px;">
                      {{data7.description}} | {{$t("USE Coupon")}} : {{data7.code}}
                    </p>
                  </b-col>
                </b-row>
                </div>

            </div>
          </div>

          <div class="text-r side sticky-top3 dsp">
            <div @click="scrollMeTo(0,0)" id="0">{{$t("All")}}</div>
            <div  v-for="(data2,index) in rows_detail2"
            :key="index" 
            @click="scrollMeTo(data2.menu_id,rows[0].id)"
            :id="data2.menu_name"            >
            
             {{data2.menu_name}} 
            
            </div>
          </div>
     
  
      </b-col>
      <b-col     lg="9"
            cols="12"> 
  <div v-if="isLoading" class="ecommerce-searchbar mt-1 ">
    <b-row>
       
       <b-col cols="5">
      </b-col>
      <b-col cols="2">
        <b-img src="/loader.svg" fluid alt="Scan"  />
      </b-col>
      <b-col cols="5">
      </b-col>
         
    </b-row>
  </div>
  <div class="ecommerce-searchbar mt-1" v-if="!isLoading">
    <div v-if="top_selling.length" class="mb-2" style="background-color: #F0F0F0; padding: 20px; border-radius: 20px">
      <h3 class="" style="padding-bottom: 20px;">{{$t("Top Selling")}}</h3>
      <swiper
      class="swiper-multiple"
      :options="swiperOptions"
      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"    >

      <swiper-slide       v-for="(data9,index9) in top_selling"        :key="index9" >
      <b-card class="rounded3">

        <b-img
        class="rounded2 imght3"           :src="data9.image"          fluid          @click="open_image(data9.image,data9.name)"        />
        <div class="swiper-text pt-md-1 pt-sm-50  pt-4">
        <h3 style="white-space: nowrap;     overflow: hidden !important;    text-overflow: ellipsis; "><b>{{ data9.name }}</b></h3>
        </div>
        <!--<b-col>
         <b-row>
          <b-card-text class="mb-0 textp">
            {{data9.description}}
          </b-card-text>
        </b-row>
        </b-col> -->
      <b-col>
          <b-row class="pt-3">
            <b-col 
              cols="4" class="pad0">
                      <h3 class="media-heading text-primary pt-0"><b>
                        {{$store.state['defaults'].currency}} {{data9.price}}</b>
                      </h3>
            </b-col>
            <b-col               v-if="isopen"              cols="6" class="text-r ">
            <b-button
                          v-ripple.400="'rgba(113, 102, 240, 0.15)'"                           variant="outline-primary"                          class="btn-icon rounded-circle add_to_cart"
                          :id="'food_add_'+data9.food_id+'r'"                           :data-price = data9.price                          @click="addcart(data9.price,data9.item_tax,data9.name,data9.food_id,data9.is_veg,data9.add_ons,data9.food_quantity,data9.image)"  style="border: 2px solid #00A082 !important;">
                          <feather-icon icon="PlusIcon" class="ico-color" size="15"/>
            </b-button>
            <!-- <span class="count-number float-right" :id="'food_control_'+data9.food_id+'r'" style="display:none">
                <div class="input-group text-r" style="">
                    <span class="input-group-btn">
                        <button :id="'food_minus_'+data9.food_id" class="btn btn-outline-secondary quantity-left-minus  remove_from_cart btn-sm left dec" @click="removefromcart(data9.price,data9.item_tax,data9.name,data9.food_id,data9.is_veg,data9.add_ons,data9.food_quantity,data9.image)"> <i class="fa-solid fa-minus"></i></button>
                    </span>
                    <input :id="'food_qty_'+data9.food_id+'r'"  class="form-control input-number count-number-input" type="text" value="1" readonly="" style="max-width: 40px;">
                    <span class="input-group-btn">
                        <button :id="'food_plus_'+data9.food_id" class="btn btn-outline-secondary quantity-right-plus add_to_cart btn-sm right inc" @click="addcart(data9.price,data9.item_tax,data9.name,data9.food_id,data9.is_veg,data9.add_ons,data9.food_quantity,data9.image)"> <i class="fa-solid fa-plus"></i> </button>
                    </span>
                </div>
            </span> -->


            <div class="num-block skin-2" :id="'food_control_'+data9.food_id+'r'" style="display:none">
                            <div class="num-in">
                                <span class="minus dis" :id="'food_minus_'+data9.food_id"  @click="removefromcart(data9.price,data9.item_tax,data9.name,data9.food_id,data9.is_veg,data9.add_ons,data9.food_quantity,data9.image)"></span>
                                    <input type="text" class="in-num" value="1" readonly=""  :id="'food_qty_'+data9.food_id+'r'" >
                                <span class="plus" :id="'food_plus_'+data9.food_id" @click="addcart(data9.price,data9.item_tax,data9.name,data9.food_id,data9.is_veg,data9.add_ons,data9.food_quantity,data9.image)"></span>
                            </div>
                          </div>
            </b-col>
          </b-row>
        
        
      </b-col> 
     
      </b-card>
      </swiper-slide>
      <div
      slot="button-next"
      class="swiper-button-next"
    ></div>
    <div
      slot="button-prev"
      class="swiper-button-prev"
    ></div>
      
    </swiper>
  
  </div>
    
      <b-row>
        <b-col cols="12"
        v-for="(data2,index2) in rows_detail"
            :key="index2" id="products">
         <h3 >{{data2.menu_name}}</h3>
        <div :id=data2.menu_name>

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
                <b-card  class="rounded3 pad0">
                  <div class="media-list" >
                    <b-media vertical-align="top">
                      <template #aside v-if="data3.image">
                        <b-img class="bor"
                          :src="data3.image"
                          blank-color="#ccc"
                          width="100"
                          @click="open_image(data3.image,data3.name)"
                        />
                      </template>
                      <h4 class="media-heading">
                        <div v-if="data3.is_veg">
                           {{data3.name}} <b-img src="/veg.svg" fluid alt="Scan" style="width: 12px;" />
                        </div>
                        <div v-else>
                           {{data3.name}} <b-img src="/non_veg.svg" fluid alt="Scan" style="width: 12px;" />
                        </div>
                        
                      </h4>
                      <b-card-text class="mb-0 textp">
                        {{data3.description}}
                      </b-card-text>
                      <b-row class="pt-3">
              <b-col 
              
              cols="4" lg="8">
                      <h4 class="media-heading text-primary">
                        {{$store.state['defaults'].currency}} {{data3.price}}
                      </h4>
                      </b-col>
                      <b-col
              v-if="isopen"
              cols="8" lg="4" class="text-r">
             
                      <b-button
                        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                        variant="outline-primary"
                        class="btn-icon rounded-circle add_to_cart"
                        :id="'food_add_'+data3.food_id+'r'"
                        :data-price = data3.price
                        @click="addcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"
                        style="border: 2px solid #00A082 !important;"
                      >
                        <feather-icon icon="PlusIcon" class="ico-color" size="15"/>
                      </b-button>
                    
                      <!-- <span class="count-number float-right" :id="'food_control_'+data3.food_id+'r'" style="display:none">
                        <div class="input-group" style="width:105px;">
                            <span class="input-group-btn">
                              <button :id="'food_minus_'+data3.food_id" class="btn btn-outline-secondary quantity-left-minus  remove_from_cart btn-sm left dec" @click="removefromcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"> <i class="fa-solid fa-minus"></i></button>
                            </span>
                            <input :id="'food_qty_'+data3.food_id+'r'"  class="form-control input-number count-number-input" type="text" value="1" readonly="">
                              <span class="input-group-btn">
                                <button :id="'food_plus_'+data3.food_id" class="btn btn-outline-secondary quantity-right-plus add_to_cart btn-sm right inc" @click="addcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"> <i class="fa-solid fa-plus"></i> </button>
                              </span>
                        </div>
                      </span> -->

                     
                        <div class="num-block skin-2" :id="'food_control_'+data3.food_id+'r'" style="display:none">
                            <div class="num-in">
                                <span class="minus dis" :id="'food_minus_'+data3.food_id" @click="removefromcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"></span>
                                    <input type="text" class="in-num" value="1" readonly="" :id="'food_qty_'+data3.food_id+'r'">
                                <span class="plus" :id="'food_plus_'+data3.food_id" @click="addcart(data3.price,data3.item_tax,data3.name,data3.food_id,data3.is_veg,data3.add_ons,data3.food_quantity,data3.image)"></span>
                            </div>
                          </div>
              </b-col>

            </b-row>
                    </b-media>
                  </div>
                </b-card>
            </b-col>
          </b-row>
          </div>

        </b-col>
      </b-row>
    </div>
    </b-col>
</b-row>


    <b-modal
      id="modal-center"
      centered
      :title="modal_title"
      hide-footer
      ok-only
      ok-title="Accept"
      ref="image-modal"
    >
      <b-card-text>
        <img class="modal-content" :src="modal_image" id="img01">
      </b-card-text>
    </b-modal>


      <b-modal
                id="modal-addons"
                ok-only
                ok-variant="success"
                ok-title="Accept"
                modal-class="modal-success"
                centered
                hide-footer
                :title="$t('Customize')"
                ref="addon-modal"
              >
              
              <div id="addonsModalBody" class="text-center">
                <table v-if="add_ons.length" width="80%">
                  <tr><td style="font-size:16px;"><b>{{$t("Addons")}}</b></td></tr>
                  <tr v-for="(data4,index4) in add_ons"
                    :key="index4">
                   
                    <td width="20%"  ALIGN="center">
                      <input type="checkbox" :name="'addonsOfItem_'+itemid"  :id="'addon_'+itemid+'_'+data4.id" :data-name="data4.name" :data-price="data4.price" :value="data4.id" >  
                    </td>
                   
                    <td >
                      <label :for="'addon_'+itemid+'_'+data4.id" style="font-size:16px;"> {{data4.name}} </label>
                      
                    </td>
                    <td>
                      <label :for="'addon_'+itemid+'_'+data4.id" style="font-size:16px;"> {{$store.state['defaults'].currency}} {{data4.price}} </label>
                    </td>
                   
                  </tr>
                </table>
                <table v-if="food_quantity.length" width="80%" style="margin-top:30px;">
                  <tr><td style="font-size:16px;"><b>{{$t("Sizes")}}</b><br></td></tr>
                  <tr  v-for="(data5,index5) in food_quantity"
                    :key="index5">

                    <td width="20%" v-if="data5.is_default">
                      <input type="radio" :name="'sizesOfItem_'+itemid"  :id="'size_'+itemid+'_'+data5.id" :data-name="data5.name" :data-price="data5.price"  checked >  
                    </td>
                    <td width="20%" ALIGN="center" v-else>
                      <input type="radio" :name="'sizesOfItem_'+itemid"  :id="'size_'+itemid+'_'+data5.id" :data-name="data5.name" :data-price="data5.price" >  
                    </td>
                    <td >
                      <label :for="'size_'+itemid+'_'+data5.id" style="font-size:16px;"> {{data5.name}} </label>
                      
                    </td>
                    <td>
                      <label :for="'size_'+itemid+'_'+data5.id" style="font-size:16px;">{{$store.state['defaults'].currency}} {{data5.price}} </label>
                    </td>
                  </tr>
                </table>
                <b-button class="mt-2" variant="primary" id="addCustomizedProduct" @click="addCustomizedProduct(selected)" style="width:100%;">{{$t("Add")}}</b-button>
              </div>


      </b-modal>
    </div>
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
  BButton,
  BModal, VBModal,BFormSpinbutton,
} from 'bootstrap-vue'
import VueSlider from 'vue-slider-component'
import { useWindowScroll } from '@vueuse/core'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import store from '@@@/store'
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import 'swiper/css/swiper.css'

export default {
  components: {
    BButton,
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
    BModal, 
    VBModal,
    BFormSpinbutton,

    Swiper,
    SwiperSlide,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem('webuserData')),
      rows: {},
      rows_detail: {},
      rows_detail2:{},
      isLoading: true,
      isfav:false,
      address:'',
      cart : JSON.parse(localStorage.getItem("cart")),
      add_ons:{},
      food_quantity:{},
      itemid:'',
      selected:{},
      quantity:[],
      promocodes :{},
      top_selling:{},
      isopen : true,
      modal_image:'',
      modal_title:'',
      swiperOptions: {
        breakpoints: {       
      320: {       
         slidesPerView: 1.2,
         spaceBetween: 30     
      },
      420: {       
         slidesPerView: 2.2,
         spaceBetween: 30     
      },            
         
      
          768: {       
            slidesPerView: 3.2,       
            spaceBetween: 30     
          } ,
          1200:{
            slidesPerView: 4.2,       
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
  filters: {
    // tConvert :function (time) {
      
    //   time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    //   if (time.length > 1) { 
    //     time = time.slice (1);  
    //     time[5] = +time[0] < 12 ? 'AM' : 'PM'; 
    //     time[0] = +time[0] % 12 || 12; 
    //   }
    //   return time.join (''); 
    // },
    tConvert (date) {
      
      var strTime = date.toLocaleString('en-US', {hour: '2-digit', minute:'2-digit'})
           .replace(/(:\d{2}| [AP]M)$/, "");
      return strTime;
    }
       
      },
  setup() {
    
  },
  methods: {
    open_image(image,title){
      this.$refs['image-modal'].show()
      this.modal_image = image
      this.modal_title = title

    },
    onCopy() {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: this.$t('Coupon Code copied'),
          icon: 'BellIcon',
        },
      })
    },
    handleScroll(){
      var findMe = document.querySelectorAll('#products h3 ');
      var li = document.querySelectorAll('.side div');

      findMe.forEach(element => {
      if (this.checkisInViewport(element)) {

        li.forEach(li => {
          li.classList.remove("active");
        })
        li.forEach(li => {
        
          if (li.innerHTML.trim() === element.innerHTML.trim()) {
            li.classList.add("active");
          }
        })
       
        
      }
    });
     
    },
    scrollMeTo(refName,id) {
      this.isLoading=true
      if(refName == 0){
        this.$http.get('/get_food_list_by_menu/'+this.$route.params.id)
        .then(res => {
         
          this.rows_detail = res.data.items
          this.rows_detail2 = res.data.items
          this.isLoading=false
        
          var li = document.querySelectorAll('.side div');
            li.forEach(li => {
            li.classList.remove("active");
            })
            this.isLoading=false
            var box = document.querySelector('[id="' + refName + '"]') ;
            box.classList.add("active");
            const rootEle = document.documentElement
            rootEle.scrollTo({
              top: 0,
              behavior: 'smooth',
            })
          })
      }else{
        this.$http.get('/get_food_list_by_menu_id/'+refName+'/'+this.$route.params.id)
        .then(res => {
          
            this.rows_detail = res.data.items
           
            var li = document.querySelectorAll('.side div');
            li.forEach(li => {
            li.classList.remove("active");
            })
            this.isLoading=false
            var box = document.querySelector('[id="' + this.rows_detail[0].menu_name + '"]') ;
            box.classList.add("active");
            const rootEle = document.documentElement
            rootEle.scrollTo({
              top: 0,
              behavior: 'smooth',
            })
            })
      }
      
    
    },
   isInViewport(el) {
    const rect = el.getBoundingClientRect();
   
    return rect.top;
    
},
checkisInViewport(el) {
  var distance = el.getBoundingClientRect();
          return (
            distance.top >= 0 &&
            distance.left >= 0 &&
            distance.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            distance.right <= (window.innerWidth || document.documentElement.clientWidth)
          );
},
markasfav(){
  if(localStorage.getItem("webuserData")){
    let loggeduser = JSON.parse(localStorage.getItem("webuserData"));
    this.$http.get('/mark_as_favourite/'+this.$route.params.id+'/'+loggeduser.id)
      .then(res => {
                if(res.data.status == true){
                  this.isfav = true
                  this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: res.data.message,
                            icon: 'HeartIcon',
                            variant: 'success',
                          },
                        })
                }else{
                  this.isfav = false
                  this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: res.data.message,
                            icon: 'HeartIcon',
                            variant: 'danger',
                          },
                        })
                }
       
              
          })
  }else{
    this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Please Login to Mark Restaurant as Favorite",
                            icon: 'HeartIcon',
                            variant: 'danger',
                          },
                        })
  }
},

addcart: function (price , item_tax,name,food_id,is_veg,add_ons,food_quantity,food_image) {
  localStorage.setItem("cart", localStorage.getItem("cart") || "[]");

   this.itemid = food_id
   let  id = food_id,
        restaurant = this.rows[0].id,
        isveg = is_veg,
        restaurant_name = this.rows[0].name,
        restaurant_address = this.rows[0].address,
        restaurant_image = this.rows[0].image,
        taxperc = parseFloat(item_tax),
        cart = JSON.parse(localStorage.getItem("cart"));
        price = parseFloat(price);

        this.selected.id = id
        this.selected.restaurant = restaurant
        this.selected.isveg = isveg
        this.selected.restaurant_name = restaurant_name
        this.selected.restaurant_address = restaurant_address
        this.selected.restaurant_image = restaurant_image
        this.selected.taxperc = taxperc
        this.selected.price = price
        this.selected.add_ons = add_ons
        this.selected.food_quantity = food_quantity
        this.selected.food_image = food_image
        this.selected.name = name



  if (add_ons.length > 0 || food_quantity.length > 0) {
    this.add_ons = add_ons
    this.food_quantity = food_quantity
    this.$refs['addon-modal'].show()
    
  }else{
    if (cart.length && cart[0].restaurant != restaurant) {
      let ok = confirm(this.$t('The items previously will be deleted'));
                if (ok) {
                  cart = [];
                  store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', store.state['deliware_cart'].cartItemsCount - 1)
                }
                else return;
    }
    let foodIndex = cart.findIndex(function (food, index) {
                return food.id == id;
            });
            if (foodIndex !== -1) {
                cart[foodIndex].quantity++;
                cart[foodIndex].addons += ' ';
                cart[foodIndex].sizes += ' ';
            } else {
                cart.push({
                    id: id,
                    name: name,
                    isveg: isveg,
                    price: parseFloat(parseFloat(price).toFixed(2)),
                    quantity: 1,
                    restaurant: restaurant,
                    addons: '',
                    sizes: '',
                    addonsName: '',
                    addonsPrice: 0,
                    sizeName: '',
                    restaurant_name: restaurant_name,
                    restaurant_address: restaurant_address,
                    restaurant_image: restaurant_image,
                    sizePrice: 0,
                    taxperc: taxperc,
                    food_image:food_image,
                })
            }
            localStorage.setItem("cart", JSON.stringify(cart));
            let cartn = JSON.parse(localStorage.getItem("cart"));
            store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
            store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
            this.loadcart()
            
  }
},
number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
},
price_format_final(price) {
  
  return this.number_format(price, 2, '.', '');

},
loadcoupon(){
  let avail_res_coupon = [];
  for (let i = 0; i < this.promocodes.length; i++) {
            
            avail_res_coupon.push({
                couponcode: this.promocodes[i].code,
                couponvalue: this.promocodes[i].value,
                coupontype: this.promocodes[i].type,
                couponmaxamount: this.promocodes[i].max_amount,
                couponminamount: this.promocodes[i].min_amount,
                couponcount: this.promocodes.length,
                coupontype: this.promocodes[i].offer_type,

            })
        }

        localStorage.setItem("avail_res_coupon", JSON.stringify(avail_res_coupon));
},
loadcart(){
 

  localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
  var DELIVERY_CHARGE = JSON.parse(this.rows[0].delivery_charge_limits);
  localStorage.setItem('DC', JSON.stringify(DELIVERY_CHARGE));

  var offer_discount = parseFloat(this.rows[0].offer_discount);
  var DELIVERY_CHARGE_TYPE = this.rows[0].delivery_charge_type;
  localStorage.setItem('DELIVERY_CHARGE_TYPE', DELIVERY_CHARGE_TYPE);

  var packaging_charge = parseFloat(this.rows[0].packaging_charge);
  localStorage.setItem('RES_PACK_CHARGE', packaging_charge);

  var DELIVERY_CHARGE_BASEDON = this.rows[0].DeliveryChargeBasedOn;
  localStorage.setItem('DC_BON', JSON.stringify(DELIVERY_CHARGE_BASEDON));

  localStorage.setItem('IS_TAX_INCLUSIVE', this.rows[0].tax);


  localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
      let cart = JSON.parse(localStorage.getItem("cart"));
      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');
      if(localStorage.getItem('webuserData')){
        this.$http.get('/get_checkout_details/'+cart[0].restaurant+'/'+lat+'/'+lng+'/'+this.userData.id)
        .then(res => {
          console.log(res)

          // if(res.data.status == true){
          localStorage.setItem('DISTANCE', res.data.distance);
          // }else{
          //   this.$toast({
          //                 component: ToastificationContent,
          //                 position: 'bottom-right',
          //                 props: {
          //                   title: res.data.message,
          //                   icon: 'UserIcon',
          //                   variant: 'danger',
          //                 },
          //               })
          // }
          
        })
      
      }
  var tips = 0;

  let total_price = 0;
  let total_item = 0;
  let tot_tax = 0;
  let is_tax = localStorage.getItem('IS_TAX_INCLUSIVE');
  let tot_amt = 0;
  let item_amount_total = 0;
  let restaurant_packaging_charge = 0;
  if (packaging_charge == 0) {
        packaging_charge = JSON.parse(localStorage.getItem('RES_PACK_CHARGE'));

  }
  if(cart.length === 0){

  }else{
    cart.forEach(function (item,i) {
            if(document.querySelectorAll('[id="food_control_' + item.id + 'r"]')){
              
              var elms = document.querySelectorAll('[id="food_control_' + item.id + 'r"]');

              for(var i = 0; i < elms.length; i++) {
                elms[i].style.display='block';
              }
              var elms2 = document.querySelectorAll('[id="food_add_' + item.id+'r"]');
              for(var i = 0; i < elms2.length; i++) {
                elms2[i].style.display='none';
              }
                
              var elms3 = document.querySelectorAll('[id="food_qty_' + item.id+'r"]');
              for(var i = 0; i < elms3.length; i++) {
                elms3[i].value=item.quantity;
              }
              // document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
              // document.getElementById('food_add_' + item.id+'r').style.display = 'none';

              // document.getElementById('food_qty_' + item.id+'r').value = item.quantity;
            }
            
            total_price += parseFloat((item.quantity * (item.price + item.addonsPrice)).toFixed(2));
            total_item ++;
            let thisAmount = parseFloat(((item.price + item.addonsPrice) * item.quantity).toFixed(2));
            tot_tax += parseFloat((thisAmount / 100) * item.taxperc);
            localStorage.setItem('tot_tax', tot_tax);
            if (is_tax == 0) {
                tot_amt = total_price + tot_tax;
            } else {
                tot_amt = total_price; 
            }
          
            item_amount_total += thisAmount;

        })
        restaurant_packaging_charge = parseFloat((item_amount_total / 100) * packaging_charge);
        var dc = 1;
        if (dc == 1) {
          var  delivery_charge_calc = this.calc_delivery_charge(tot_amt);

        } else {
          var  delivery_charge_calc = 0;
          var tips = 0;
            
        }
        var offer_discount = 0;
        total_price = tot_amt === 0 ? tot_amt : (tot_amt  + restaurant_packaging_charge + delivery_charge_calc).toFixed(2);
        localStorage.setItem('tot_amt', total_price);
        // var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':0,'bill':total_price};
        var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':offer_discount,'bill':total_price,'tips':tips,'dc':dc,'tax':tot_tax};
        localStorage.setItem('bill', bill);
        store.commit('deliware_cart/UPDATE_CART_TOTAL', bill)
        this.loadcoupon()
  }


},


calc_delivery_charge(total_price) {

  

var DC = JSON.parse(localStorage.getItem('DC'));
if (DC) {
  var delivery_charge = typeof DELIVERY_CHARGE !== "undefined" ? DELIVERY_CHARGE : DC;
} else {
  var delivery_charge = [];
}

var DELIVERY_CHARGE_TYPE = JSON.parse(localStorage.getItem('DELIVERY_CHARGE_TYPE'));
var delivery_charge_type = parseFloat(typeof DELIVERY_CHARGE_TYPE !== "undefined" ? DELIVERY_CHARGE_TYPE : 0);

var delivery_charge_calc = 0; // Assigned 0 because if the list is empty loop will not execute.
var deliver_charge_mode = JSON.parse(localStorage.getItem('DC_BON'));
var deliver_distance = localStorage.getItem('DISTANCE');
var total = 0;
var tot = 0;
var delivery_charge_calc1 = 0;
var delivery_charge_calc2 = 0;
var delivery_charge_calc3 = 0;
var dc_rate = 0;
var dc_total = 0;
for (let each_delivery_charge of delivery_charge) {
  // Total price should be greater than minimum AND (less than maximum or maximum can be null(infinity))

  //price
  /*if (deliver_charge_mode == 1 &&
    total_price >= each_delivery_charge.Minimum &&
    (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum) && delivery_charge_type == 1 removed this dctype
  ) */
  if (deliver_charge_mode == 1) {
    if (
      total_price >= each_delivery_charge.Minimum &&
      (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum)
    ) {
      delivery_charge_calc = parseFloat(each_delivery_charge.Value);
      delivery_charge_type = each_delivery_charge.Type;
    }
  }
  //km
  if (deliver_charge_mode == 2) {
    //loop deliver charge type

    var dc = deliver_distance;
    //var dc = deliver_distance - each_delivery_charge.Maximum;
    if (
      dc >= each_delivery_charge.Minimum &&
      (each_delivery_charge.Maximum == null || dc <= each_delivery_charge.Maximum) && each_delivery_charge.Type == 1
    ) {
      var delivery_charge_calc1 = parseFloat(each_delivery_charge.Value);
      delivery_charge_calc3 += delivery_charge_calc1;
      delivery_charge_type = each_delivery_charge.Type;
    }
    for (var i = 1; i <= deliver_distance; i++) {
      if (i <= each_delivery_charge.Maximum && i >= each_delivery_charge.Minimum && each_delivery_charge.Type == 3) {
        var total = each_delivery_charge.Value * 1;
        tot += total;
        var delivery_charge_calc2 = parseFloat(tot);
      } else {
        if (i == each_delivery_charge.Maximum) {
          var dc_rate = each_delivery_charge.Value;
        }
      }
      var dckm_rate = parseFloat(dc_rate) + parseFloat(delivery_charge_calc2);
    }
    // calculat tot have diff type 
  if(dckm_rate){
    var delivery_charge_calc = parseFloat(delivery_charge_calc3) + parseFloat(dckm_rate);
}else{
var delivery_charge_calc = parseFloat(delivery_charge_calc3);
}

    dc_total += delivery_charge_calc;
  }

}

//If calculation type is percentage, then calculate the percentage

if (deliver_charge_mode == 1 && delivery_charge_type == 2) {
  delivery_charge_calc = parseFloat((total_price / 100) * delivery_charge_calc);
}

delivery_charge_calc = parseFloat(parseFloat(delivery_charge_calc).toFixed(2));
return (delivery_charge_calc);
},

addCustomizedProduct(selected){
  localStorage.setItem("cart", localStorage.getItem("cart") || "[]");

  let  id = selected.id,
        restaurant = selected.restaurant,
        isveg = selected.isveg,
        restaurant_name = selected.restaurant_name,
        restaurant_address = selected.restaurant_address,
        restaurant_image = selected.restaurant_image,
        taxperc = selected.taxperc,
        name=selected.name,
        food_image=selected.food_image,
        cart = JSON.parse(localStorage.getItem("cart"));
        let price = parseFloat(selected.price);
       
        let selectedSize = undefined;
        let selectedSizeName = undefined;
        let selectedSizePrice  = '';
        if(document.querySelector('input[name="sizesOfItem_' + id + '"]:checked')){
           selectedSize = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').value;
            selectedSizeName = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').getAttribute('data-name');
             selectedSizePrice  = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').getAttribute('data-price');
        }
        
        selectedSize = selectedSize == undefined ? '' : selectedSize;
        
        selectedSizePrice = isNaN(selectedSizePrice) ? 0 : selectedSizePrice;
                if (selectedSizePrice > 0) {
                    price = selectedSizePrice;
                }

                let selectedAddons = [];
                let selectedAddonsName = [];
                let selectedAddonsPrice = 0;

                document.querySelectorAll('input[name="addonsOfItem_' + id + '"]:checked').forEach(function (index, value) {
                 
                    selectedAddons.push(index.value);  
                    selectedAddonsName.push(index.getAttribute('data-name'));
                    selectedAddonsPrice += parseFloat(index.getAttribute('data-price'));
                });
                let selectedAddonsString = selectedAddons.join(',');
                selectedAddonsName = selectedAddonsName.join(', ');
               
                selectedAddonsPrice = parseFloat(selectedAddonsPrice);
        if (cart.length && cart[0].restaurant != restaurant) {
            let ok = confirm('The items previously will be deleted');
                      if (ok) {
                        cart = [];
                        store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', store.state['deliware_cart'].cartItemsCount - 1)
                      }
                      else return;
          }
          if (cart.length != 0) {

                    if (typeof selectedSizeName == "undefined" && selectedAddonsName != "") {
                        let food_isPresent = cart.some(function (el) { return el.id === id });
                        let food_addon_isPresent = cart.some(function (el) { return el.addonsName === selectedAddonsName });

                        if (food_isPresent === true && food_addon_isPresent === true) {
                            cart = localStorage.getItem("cart");
                            cart = JSON.parse(cart);
                            for (var i = 0; i < cart.length; i++) {
                                if (cart[i].addonsName === selectedAddonsName && cart[i].id === id) {
                                    cart[i].quantity = cart[i].quantity + 1;
                                    break;
                                }
                            }
                        } else {
                            cart.push({
                                id: id,
                                name: name,
                                isveg: isveg,
                                price: parseFloat(parseFloat(price).toFixed(2)),
                                quantity: 1,
                                restaurant: restaurant,
                                addons: selectedAddonsString,
                                sizes: selectedSize,
                                addonsName: selectedAddonsName,
                                addonsPrice: selectedAddonsPrice,
                                sizeName: selectedSizeName,
                                restaurant_name: restaurant_name,
                                restaurant_address: restaurant_address,
                                restaurant_image: restaurant_image,
                                sizePrice: selectedSizePrice,
                                taxperc: taxperc,
                                food_image:food_image,
                            })
                        }
                    } else {

                        if (selectedSizeName != "" && selectedAddonsName != "") {
                            let food_isPresent = cart.some(function (el) { return el.id === id });

                            let food_addon_isPresent = cart.some(function (el) { return el.addonsName === selectedAddonsName });
                            let food_size_isPresent = cart.some(function (el) { return el.sizeName === selectedSizeName });
                            if (food_isPresent === true && food_addon_isPresent === true && food_size_isPresent == true) {
                                cart = localStorage.getItem("cart");
                                cart = JSON.parse(cart);
                                for (var i = 0; i < cart.length; i++) {
                                    let temp_loop_count = i + 1;
                                    if (cart[i].addonsName === selectedAddonsName && cart[i].sizeName === selectedSizeName && cart[i].id === id) {
                                        cart[i].quantity = cart[i].quantity + 1;
                                        break;
                                    }
                                    if (temp_loop_count == cart.length) {
                                        cart.push({
                                            id: id,
                                            name: name,
                                            isveg: isveg,
                                            price: parseFloat(parseFloat(price).toFixed(2)),
                                            quantity: 1,
                                            restaurant: restaurant,
                                            addons: selectedAddonsString,
                                            sizes: selectedSize,
                                            addonsName: selectedAddonsName,
                                            addonsPrice: selectedAddonsPrice,
                                            sizeName: selectedSizeName,
                                            restaurant_name: restaurant_name,
                                            restaurant_address: restaurant_address,
                                            restaurant_image: restaurant_image,
                                            sizePrice: selectedSizePrice,
                                            taxperc: taxperc,
                                            food_image:food_image,
                                        });
                                        break;
                                    }
                                }
                            } else {
                              
                             
                                cart.push({
                                    id: id,
                                    name: name,
                                    isveg: isveg,
                                    price: parseFloat(parseFloat(price).toFixed(2)),
                                    quantity: 1,
                                    restaurant: restaurant,
                                    addons: selectedAddonsString,
                                    sizes: selectedSize,
                                    addonsName: selectedAddonsName,
                                    addonsPrice: selectedAddonsPrice,
                                    sizeName: selectedSizeName,
                                    restaurant_name: restaurant_name,
                                    restaurant_address: restaurant_address,
                                    restaurant_image: restaurant_image,
                                    sizePrice: selectedSizePrice,
                                    taxperc: taxperc,
                                    food_image:food_image,
                                })
                                
                            }
                        }
                        if (selectedSizeName != "" && selectedAddonsName === "") {
                            let food_isPresent = cart.some(function (el) { return el.id === id });
                            let food_size_isPresent = cart.some(function (el) { return el.sizeName === selectedSizeName });
                            if (food_isPresent === true && food_size_isPresent == true) {
                                cart = localStorage.getItem("cart");
                                cart = JSON.parse(cart);
                                for (var i = 0; i < cart.length; i++) {
                                    let temp_loop_count = i + 1;
                                    if (cart[i].addonsName === selectedAddonsName && cart[i].sizeName === selectedSizeName && cart[i].id === id) {
                                        cart[i].quantity = cart[i].quantity + 1;
                                        break;
                                    }
                                    if (temp_loop_count == cart.length) {
                                        cart.push({
                                            id: id,
                                            name: name,
                                            isveg: isveg,
                                            price: parseFloat(parseFloat(price).toFixed(2)),
                                            quantity: 1,
                                            restaurant: restaurant,
                                            addons: selectedAddonsString,
                                            sizes: selectedSize,
                                            addonsName: selectedAddonsName,
                                            addonsPrice: selectedAddonsPrice,
                                            sizeName: selectedSizeName,
                                            restaurant_name: restaurant_name,
                                            restaurant_address: restaurant_address,
                                            restaurant_image: restaurant_image,
                                            sizePrice: selectedSizePrice,
                                            taxperc: taxperc,
                                            food_image:food_image,
                                        });
                                        break;
                                    }
                                }
                            } else {
                                cart.push({
                                    id: id,
                                    name: name,
                                    isveg: isveg,
                                    price: parseFloat(parseFloat(price).toFixed(2)),
                                    quantity: 1,
                                    restaurant: restaurant,
                                    addons: selectedAddonsString,
                                    sizes: selectedSize,
                                    addonsName: selectedAddonsName,
                                    addonsPrice: selectedAddonsPrice,
                                    sizeName: selectedSizeName,
                                    restaurant_name: restaurant_name,
                                    restaurant_address: restaurant_address,
                                    restaurant_image: restaurant_image,
                                    sizePrice: selectedSizePrice,
                                    taxperc: taxperc,
                                    food_image:food_image,
                                })
                            }
                        }
                    }
                } else {
                 

                    cart.push({
                        id: id,
                        name: name,
                        isveg: isveg,
                        price: parseFloat(parseFloat(price).toFixed(2)),
                        quantity: 1,
                        restaurant: restaurant,
                        addons: selectedAddonsString,
                        sizes: selectedSize,
                        addonsName: selectedAddonsName,
                        addonsPrice: selectedAddonsPrice,
                        sizeName: selectedSizeName,
                        restaurant_name: restaurant_name,
                        restaurant_address: restaurant_address,
                        restaurant_image: restaurant_image,
                        sizePrice: selectedSizePrice,
                        taxperc: taxperc,
                        food_image:food_image,
                    })
                }
                localStorage.setItem("cart", JSON.stringify(cart));
                let cartn = JSON.parse(localStorage.getItem("cart"));

                store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
                store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
                this.$refs['addon-modal'].hide()
                this.loadcart()
},

removefromcart: function (price , item_tax,name,food_id,is_veg,add_ons,food_quantity,food_image) {
  localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
  this.itemid = food_id
   let  id = food_id,
       
        cart = JSON.parse(localStorage.getItem("cart"));
        price = parseFloat(price);

        let foodIndex = cart.findIndex(function (food, index) {
            return food.id == id;
        });

        if (foodIndex !== -1) {
                if (cart[foodIndex].quantity > 0)
                    cart[foodIndex].quantity--;
                if (cart[foodIndex].quantity <= 0) {
                    cart.splice(foodIndex, 1);
                    var elms = document.querySelectorAll('[id="food_control_' + id + 'r"]');

                    for(var i = 0; i < elms.length; i++) {
                      elms[i].style.display='none';
                    }
                    var elms2 = document.querySelectorAll('[id="food_add_' + id + 'r"]');

                    for(var i = 0; i < elms2.length; i++) {
                      elms2[i].style.display='inline-block';
                    }

                    // document.getElementById('food_control_' + id +'r').style.display = 'none';
                    // document.getElementById('food_add_' + id +'r').style.display = 'inline-block';
                }
            }

            localStorage.setItem("cart", JSON.stringify(cart));

            let cartn = JSON.parse(localStorage.getItem("cart"));
            
                store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
                store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
                
                this.loadcart()
},
  

},

 
  created() {
    document.addEventListener('scroll', this.handleScroll);
  
    this.$http.get('/get_food_list_by_menu/'+this.$route.params.id)
          .then(res => {
           
            
              this.rows_detail = res.data.items

              
              this.rows_detail2 = res.data.items
              this.isLoading=false
            
              })
          if(localStorage.getItem("webuserData")){
            let loggeduser = localStorage.getItem("webuserData");

            var user_id = loggeduser.id
          }else{
            var user_id = 0
          }
         
          this.$http.get('/single_restaurant/'+this.$route.params.id+'/'+user_id)
          .then(res => {
              this.rows = res.data.restaurants
              if(this.rows[0].is_open != 1){
                  this.isopen =  false
              }
              this.promocodes = res.data.promocodes
              this.address = 'https://maps.google.com/maps?t=&z=9&ie=UTF8&iwloc=&output=embed&q='+this.rows[0].address
        
              if(this.rows[0].is_favourite == 1){
                this.isfav = true
              }
              this.loadcart()
              })

              this.$http.get('/top_selling/'+this.$route.params.id)
                .then(res => {
                 
                  this.top_selling = res.data.items
                 
                    })

         
  },
  
  computed: {
    
    statusVariant() {
      const statusColor = {
       
        1       : 'success',
       
        0       : 'danger',
       
       
        
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
    tConvert (time) {
      
      time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

      if (time.length > 1) { 
        time = time.slice (1);  
        time[5] = +time[0] < 12 ? 'AM' : 'PM'; 
        time[0] = +time[0] % 12 || 12; 
      }
      return time.join (''); 
    }
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
  color:#FFBD00;
}
.text-white{
  color:white;
}
</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-slider.scss';
</style>


<style lang="scss" scoped>
.swiper-slide {
    
    width: 20%;
    height: 100%;

}


.rounded3{
  cursor: pointer;
}
.categories-radio-group,
.brands-radio-group,
.price-range-defined-radio-group {
    ::v-deep > .custom-control {
    margin-bottom: 0.75rem;
  }
}
.count-number-input {
    width: 40px;
    text-align: center;
    margin: 0 -4px;
    background: #F4FFFD;
    border: 1px solid #82868b !important;
    color: #282828;
    height: 24px;
    border-radius: 0px;
    vertical-align: bottom;
}
.b-sidebar {
   
   min-width: 420px !important;
  
}
[dir] .btn-secondary {
    border-color: #fff !important;
    background-color: transparent !important;
}
.sidebar-shop{
  cursor: pointer;
}
.bor{border-radius:10px;}
.side{
  padding: 0px 30px 0px 0px;
    line-height: 3;
    cursor: pointer;
  }
.s-border{

    border-right: 2px solid #e6e6e6;
    min-height: 400px;
}

.active{
  background-color: #F4FFFD;
    border-right: 4px solid rgb(0 160 130 / 70%);
    padding: 0px 30px 0px 0px;
    font-weight: bold;
}
.restaurant-detailed-header {
    bottom: 0;
    left: 0;
    padding: 65px 0 17px;
    position: absolute;
    right: 0;
    margin-bottom: 40px;

}
.bg-color{background-color:#000 !important;}
.overlay {
    background-color: #000;
    /* position: absolute; */
    opacity: 0.5;
}
.mborderw {
    font-family: sans-serif;
    color: #fff;
    padding: 10px;
    border-radius: 30px !important;
    border: 2px solid #fff;
    margin: 0px 5px;
    text-align: center;
    
}
.mborderw2 {
  width: 40px;
    font-family: sans-serif;
    color: #fff;
    padding: 10px;
    border-radius: 30px !important;
    border: 2px solid #fff;
    margin: 0px 5px;
    text-align: center;
    
}
.navbar-sticky .app-content {
    padding: 0px;
}

.pad0 .card-body{
  padding: 0rem !important;
}
.sticky-top3 {
    position: -webkit-sticky;
    position: sticky;
    z-index: 0;
    top: 100px;
    overflow-y: scroll;
    max-height: 600px;
    margin-bottom: 50px;

}
.textp {
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 2; /* number of lines to show */
           line-clamp: 2; 
   -webkit-box-orient: vertical;
   min-height: 36px

   
}
.textp2 {color: #e6e6e6;   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 2; /* number of lines to show */
           line-clamp: 2; 
   -webkit-box-orient: vertical;}

.btn-icon{
    padding: 5px;
   }
.btn-scroll-to-top {
  background-color:#FFBD00 !important;
  border-radius:100px;
  border: 1px solid #FFBD00 !important;
  color:#000;
  display: inline-block;
  padding: 20px;

}
.swiper-button-prev, .swiper-button-next {
  color: #00A082;
}

</style >
<style scoped>
.swiper-button-next, .swiper-container-rtl .swiper-button-prev {
    right: 10px;
    left: auto;
    /* top: -30px; 
    margin-top: -68px;*/
    /* z-index: 999999; */
    background-color: #f4fffd90;
    border-radius: 10px;
    border: 2px solid #00a082;
    top: 45%;
}
.swiper-button-prev,.swiper-container-rtl .swiper-button-next {
    left: 10px;
    right: auto;
     background-color: #f4fffd90;
    border-radius: 10px;
    border: 2px solid #00a082;
    top: 45%;
}
.btn-sm {
    padding: 6px 8px 5px 10px;
    border-radius: 0.358rem;
}
.form-control {
    padding: 6px 8px 5px 10px !important;
    
}
.btn-outline-secondary {
    border: 1px solid #009688 !important;
    background-color: transparent;
}
.count-number-input {
        border: 1px solid #009688 !important;
    
}
.skin-2 .num-in {
  background: #FFFFFF;
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.15);
  border-radius: 25px;
  height: 28px;
  width: 80px;
  float: left;
}

.skin-2 .num-in span {
  width: 40%;
  display: block;
  height: 28px;
  float: left;
  position: relative;
}

.skin-2 .num-in span:before, .skin-2 .num-in span:after {
  content: '';
  position: absolute;
  background-color: #00A082;
  height: 2px;
  width: 10px;
  top: 50%;
  left: 50%;
  margin-top: -1px;
  margin-left: -5px;
}

.skin-2 .num-in span.plus:after {
  transform: rotate(90deg);
}

.skin-2 .num-in input {
    float: left;
    width: 20%;
    height: 28px;
    border: none;
    text-align: center;
}
</style>

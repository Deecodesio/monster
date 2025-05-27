<template>
  <div>
    <div v-if="isLoading" class=" mt-1 text-center"
      style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">

      <lottie-player src="/animations/loading.json" background="#FFFDFD" speed="1" style="width: 300px; height: 500px"
        loop autoplay></lottie-player>
    </div>
    <div v-if="!isLoading">
      <section id="faq-search-filter" v-for="(data, index) in rows" :key="index" style="blank-color:#000;">
        <b-card class="bg-color pad0 nobor " style="padding-top:60px;">
          <div class="overlay banimg" :style="{ backgroundImage: `url(${data.banner})`, height: '400px' }"></div>

          <b-card-body class="">
            <div class="container restaurant-detailed-header">
              <b-row class="marg">

                <b-col lg="8" md="12" sm="12">
                  <b-row class>
                    <b-col lg="2" md="3" sm="4" cols="4" class="text-r">
                      <b-img-lazy :src=data.image fluid alt="Scan" class="imght1" />
                    </b-col>
                    <b-col lg="8" md="12" cols="12">


                      <h2 class="text-yellow" style="float: left; margin-right:10px;">{{ data.name }}</h2>

                      <div style="padding-top:5px;">
                        <span v-if="data.is_open === 1">
                          <b-badge class="badge-glow" :variant="statusVariant(data.is_open)">
                            {{ $t("Open") }}
                          </b-badge>
                        </span>
                        <span v-else>

                          <b-badge class="badge-glow" :variant="statusVariant(data.is_open)">
                            {{ $t("Closed") }}
                          </b-badge>
                        </span>
                      </div>

                      <b-row style="margin-top: 11px;">
                        <b-col>
                          <h5 class="textp2"><feather-icon icon="MapPinIcon" size="16" class="ico-color2" />
                            {{ data.address }}
                          </h5>
                        </b-col>
                      </b-row>
                    </b-col>
                  </b-row>
                </b-col>
                <b-col style="padding-left: 0px; padding-right: 0px;" lg="4" md="12" sm="12" class="text-r text-rm pt-6">
                  <b-row>

                    <b-col cols="12">

                      <b-button class="badge-glow text-yellow mborderw">
                        <i class="fa-regular fa-clock"></i> {{ data.travel_time }} {{ $t("Mins") }}
                      </b-button>

                      <b-button class="badge-glow text-yellow mborderw">
                        <i class="fa-solid fa-star"></i> {{ data.rating }}
                      </b-button>

                      <b-button class="badge-glow text-yellow mborderw" @click="markasfav">
                        <div v-if="!isfav">
                          <i class="fa-regular fa-heart"></i>
                        </div>
                        <div v-if="isfav">
                          <i class="fa-solid fa-heart"></i>
                        </div>
                      </b-button>


                      <b-button v-ripple.400="'rgb(151, 199, 245)'" @click="show_infomdl"
                        class="badge-glow text-yellow mborderw">
                        <i class="fa-sharp fa-solid fa-circle-info"></i>
                      </b-button>





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
          :class="{ 'menu dsp4': $store.state['deliware_cart'].cartItemsCount, 'menu2 dsp4': !$store.state['deliware_cart'].cartItemsCount }">
          <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" v-b-modal.modal-success class="btn-scroll-to-top ">
            <i class="fa-solid fa-bars ico-color" v-if="business_id === 2"></i>
            <i class="fa-solid fa-utensils ico-color" v-else></i>
          </b-button>
        </div>

        <b-modal id="modal-success" title="Menu" ok-title="Accept" centered hide-footer scrollable>


          <div style="line-height:3;">
            <div @click="scrollMeTo(0, 0)" id="0">All</div>

            <div v-for="(data2, index) in rows_detail2" :key="index" @click="scrollMeTo(data2.menu_id, rows[0].id)"
              :id="data2.menu_name">
              {{ data2.menu_name }}

            </div>
          </div>
        </b-modal>

        <b-row>

          <b-col lg="3" md="12" cols="12">

            <div class="text-r side" v-if="promocodes.length">

              <swiper ref="mySwiper" :options="swiperOptions2" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                <swiper-slide v-for="(data7, index) in promocodes" :key="index">
                  <div style="border: 1px dashed #FFBD00; background-color: #FFBD0010; border-radius: 10px;">
                    <b-row v-clipboard:copy="data7.code" v-clipboard:success="onCopy">
                      <b-col cols="4">
                        <b-img-lazy src="/offer.png" style="width:35px"></b-img-lazy>
                      </b-col>
                      <b-col cols="8">
                        <p style="text-align:left;padding-top:10px;">
                          {{ data7.description }} | {{ $t("USE Coupon") }} : {{ data7.code }}
                        </p>
                      </b-col>
                    </b-row>
                  </div>
                </swiper-slide>
              </swiper>
            </div>

            <div class="text-r side sticky-top3 dsp" v-if="business_id === 2">
              <div @click="scrollMeTo(0, 0)" id="0">{{ $t("All") }}</div>
              <div v-for="(data2, index) in rows_detail2" :key="index" @click="scrollMeTo(data2.menu_id, rows[0].id)"
                :id="data2.menu_name">

                {{ data2.menu_name }}


                <b-img class="bor" :src="data2.cat_image" width="25" />



              </div>
            </div>
            <div class="text-r side sticky-top3 dsp" v-else>
              <div @click="scrollMeTo(0, 0)" id="0">{{ $t("All") }}</div>
              <div v-for="(data2, index) in rows_detail2" :key="index" @click="scrollMeTo(data2.menu_id, rows[0].id)"
                :id="data2.menu_name">

                {{ data2.menu_name }}


              </div>
            </div>


          </b-col>
          <b-col lg="9" cols="12">
            <div v-if="isLoading" class=" mt-1 text-center"
              style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">

              <lottie-player src="/animations/loading.json" background="#FFFDFD" speed="1"
                style="width: 300px; height: 500px" loop autoplay></lottie-player>
            </div>
            <div class="ecommerce-searchbar mt-1" v-if="!isLoading">
              <div v-if="top_selling.length" class="mb-2"
                style="background-color: #F0F0F0; padding: 20px; border-radius: 20px">
                <h3 class="" style="padding-bottom: 20px;">{{ ttitle }}</h3>
                <swiper class="swiper-multiple" :options="swiperOptions"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">

                  <swiper-slide v-for="(data9, index9) in top_selling" :key="index9">

                    <b-card
                      :class="{ 'rounded3': data9.menu_available === 1, 'rounded3 closed': data9.menu_available !== 1 }"
                      class="rounded3">

                      <b-img class="rounded2 imght3" :src="data9.image" fluid @click="open_image(data9, data9)" />

                      <b-col>
                        <b-row class="pt-3">
                          <b-col cols="6" class="pad0">
                            <h4 class="media-heading text-primary pt-0" @click="open_image(data9, data9)"><b>
                                {{ $store.state['defaults'].currency }} {{ data9.price | priceformat }}</b>
                            </h4>
                          </b-col>
                          <b-col v-if="isopen" cols="6" class="text-r  pad0" style="padding-left: 0px;">
                            <b-button v-if="data9.menu_available === 1" v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                              variant="outline-primary" class="btn-icon rounded-circle add_to_cart"
                              :id="'food_add_' + data9.food_id + 'r'" :data-price=data9.price
                              @click="open_plus(data9, data9)" style="border: 2px solid #117439 !important;">
                              <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                            </b-button>


                            <div class="num-block skin-2" :id="'food_control_' + data9.food_id + 'r'" style="display:none"
                              v-if="data9.menu_available === 1">
                              <div class="num-in">
                                <span class="minus dis" :id="'food_minus_' + data9.food_id"
                                  @click="removefromcart(data9.price, data9.item_tax, data9.name, data9.food_id, data9.is_veg, data9.add_ons, data9.food_quantity, data9.image)"></span>
                                <input type="text" class="in-num" value="1" readonly=""
                                  :id="'food_qty_' + data9.food_id + 'r'">
                                <span class="plus" :id="'food_plus_' + data9.food_id"
                                  @click="open_plus(data9, data9)"></span>
                              </div>
                            </div>
                          </b-col>
                        </b-row>


                      </b-col>
                      <div class="swiper-text pt-md-1 pt-sm-50  pt-1" style="min-height: 55px;">
                        <h4 style=""><b>{{ data9.name }}</b></h4>
                      </div>
                    </b-card>
                  </swiper-slide>
                  <div slot="button-next" class="swiper-button-next"></div>
                  <div slot="button-prev" class="swiper-button-prev"></div>

                </swiper>

              </div>

              <b-row>
                <b-col cols="12" v-for="(data2, index2) in rows_detail" :key="index2" id="products">
                  <h3 style="padding-bottom:10px;">{{ data2.menu_name }}</h3>
                  <div :id=data2.menu_name>

                    <b-row v-if="business_id === 2">
                      <b-col xxl="4" lg="4" md="4" cols="12" class="col-xxl-4" v-for="(data3, index3) in data2.items"
                        :key="index3">
                        <b-card :class="{
                          'rounded3 '
                            : data2.menu_available === 1, 'rounded3  closed'
                            : data2.menu_available !== 1
                        }">
                          <b-media>
                            <template>
                              <div style="text-align: center;">
                                <b-img class="bor" :src="data3.image" blank-color="#ccc" width="100"
                                  style=" min-width: 240px;" @click="open_image(data3, data2)" />

                              </div>
                            </template>



                            <h4 class="media-heading pt-4" @click="open_image(data3, data2)">
                              <div v-if="data3.is_veg">
                                {{ data3.name }} <b-img-lazy src="/prescription.png" fluid alt="Scan" style="width: 12px;"
                                  v-if="pharmacy_id == 1" />
                              </div>
                              <div style="min-height: 40px;" v-else>
                                {{ data3.name }}
                              </div>


                            </h4>

                            <b-row class="pt-3">
                              <b-col cols="6" lg="8">
                                <h4 class="media-heading text-primary" @click="open_image(data3, data2)">
                                  {{ $store.state['defaults'].currency }} {{ data3.price | priceformat }}
                                </h4>
                              </b-col>
                              <b-col v-if="isopen" cols="6" lg="4" class="text-r" style="padding-left: 0px;">

                                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                                  class="btn-icon rounded-circle add_to_cart" :id="'food_add_' + data3.food_id + 'r'"
                                  :data-price=data3.price @click="open_plus(data3, data2)"
                                  style="border: 2px solid #117439 !important;" v-if="data2.menu_available === 1">
                                  <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                                </b-button>



                                <div class="num-block skin-2" :id="'food_control_' + data3.food_id + 'r'"
                                  style="display:none" v-if="data2.menu_available === 1">
                                  <div class="num-in">
                                    <span class="minus dis" :id="'food_minus_' + data3.food_id"
                                      @click="removefromcart(data3.price, data3.item_tax, data3.name, data3.food_id, data3.is_veg, data3.add_ons, data3.food_quantity, data3.image)"></span>
                                    <input type="text" class="in-num" value="1" readonly=""
                                      :id="'food_qty_' + data3.food_id + 'r'">
                                    <span class="plus" :id="'food_plus_' + data3.food_id"
                                      @click="open_plus(data3, data2)"></span>
                                  </div>
                                </div>
                              </b-col>

                            </b-row>
                          </b-media>
                        </b-card>

                      </b-col>
                    </b-row>


                    <b-row v-else>
                      <b-col xxl="6" lg="6" md="6" cols="12" class="col-xxl-6" v-for="(data3, index3) in data2.items"
                        :key="index3">


                        <b-card :class="{
                          'rounded3 pad0'
                            : data2.menu_available === 1, 'rounded3 pad0 closed'
                            : data2.menu_available !== 1
                        }">
                          <div class="media-list">
                            <b-media vertical-align="top">
                              <template #aside v-if="data3.image">
                                <b-img class="bor" :src="data3.image" blank-color="#ccc" width="100"
                                  @click="open_image(data3, data2)" />
                              </template>
                              <h4 class="media-heading" @click="open_image(data3, data2)">
                                <div v-if="data3.is_veg">
                                  {{ data3.name }} <b-img-lazy src="/veg.svg" fluid alt="Scan" style="width: 12px;" />
                                </div>
                                <div v-else>
                                  {{ data3.name }} <b-img-lazy src="/non_veg.svg" fluid alt="Scan" style="width: 12px;" />
                                </div>

                              </h4>
                              <b-card-text class="mb-0 textp" @click="open_image(data3, data2)">
                                {{ data3.description }}
                              </b-card-text>
                              <b-row class="pt-3">
                                <b-col cols="6" lg="8">
                                  <h4 class="media-heading text-primary" @click="open_image(data3, data2)">
                                    {{ $store.state['defaults'].currency }} {{ data3.price | priceformat }}
                                  </h4>
                                </b-col>
                                <b-col v-if="isopen" cols="6" lg="4" class="text-r" style="padding-left: 0px;">

                                  <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                                    class="btn-icon rounded-circle add_to_cart" :id="'food_add_' + data3.food_id + 'r'"
                                    :data-price=data3.price @click="open_plus(data3, data2)"
                                    style="border: 2px solid #117439 !important;" v-if="data2.menu_available === 1">
                                    <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                                  </b-button>



                                  <div class="num-block skin-2" :id="'food_control_' + data3.food_id + 'r'"
                                    style="display:none" v-if="data2.menu_available === 1">
                                    <div class="num-in">
                                      <span class="minus dis" :id="'food_minus_' + data3.food_id"
                                        @click="removefromcart(data3.price, data3.item_tax, data3.name, data3.food_id, data3.is_veg, data3.add_ons, data3.food_quantity, data3.image)"></span>
                                      <input type="text" class="in-num" value="1" readonly=""
                                        :id="'food_qty_' + data3.food_id + 'r'">
                                      <span class="plus" :id="'food_plus_' + data3.food_id"
                                        @click="open_plus(data3, data2)"></span>
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


        <b-modal id="image-modal" scrollable hide-header ok-only ref="image-modal" body-class="body_class">

          <b-button variant="outline-primary" class="btn-icon rounded-circle " @click="close_img"
            style="background-color: #00000080 !important;border: 1px solid rgba(32, 33, 37, 0.08) !important;position: absolute !important;right: 20px;top:20px">
            <feather-icon icon="XIcon" style="color:#fff" />
          </b-button>

          <b-card-text>


            <img class="modal_img" :src="modal_image" id="img01">
            <div class="container">
              <div class="sticky-div mt-1">
                <h4>
                  <div v-if="content.isveg">
                    {{ modal_title }} <b-img-lazy src="/veg.svg" fluid alt="Scan" style="width: 12px;"
                      v-if="pharmacy_id == 0" />
                  </div>
                  <div v-else>
                    {{ modal_title }} <b-img-lazy src="/non_veg.svg" fluid alt="Scan" style="width: 12px;"
                      v-if="pharmacy_id == 0" />
                  </div>
                </h4>
                <h6>{{ modal_desc }}</h6>
              </div>

              <div id="addonsModalBody" class="text-center"
                v-if="food_quantity.length || add_ons.length || modal_groups.length">

                <div v-if="!food_quantity.length" class="cart-bord">
                  <div v-if="add_ons.length">
                    <div v-if="add_ons.length" style="text-align: left;">
                      <h4>{{ $t("Addons") }}</h4>
                      <table v-if="add_ons.length" width="100%">

                        <tr v-for="(data4, index4) in add_ons" :key="index4">
                          <td ALIGN="left" width="25%">

                            <b-form-checkbox :name="'addonsOfItem_' + itemid" :id="'addon_' + itemid + '_' + data4.id"
                              :data-name="data4.name" :data-price="data4.price" :value="data4.id">
                            </b-form-checkbox>
                          </td>
                          <td ALIGN="left" width="55%">
                            <label :for="'addon_' + itemid + '_' + data4.id" style="font-size:16px;"> {{ data4.name }}
                            </label>

                          </td>
                          <td ALIGN="left" width="20%">
                            <label :for="'addon_' + itemid + '_' + data4.id" style="font-size:16px;"
                              v-if="data4.price > 0">
                              {{ $store.state['defaults'].currency }}{{ data4.price | priceformat }} </label>
                          </td>

                        </tr>
                      </table>
                    </div>
                  </div>
                  <div v-if="modal_groups.length">
                    <div v-for="ga1 in modal_groups" :key="ga1.id">

                      <!-- Checkbox -->
                      <div v-if="ga1.group_addons.length && ga1.allow_multiple !== 1" style="text-align: left;">
                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} should be selected</small></h5>

                        <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max != 1" width="100%">

                          <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                            <td ALIGN="left" width="25%">
                              <b-form-checkbox :name="'faddon' + ga1.id" :id="'size_' + ga1.id + '_' + ga2.id"
                                :data-name="ga2.name" :data-price="ga2.price" @change="validate_sizeaddons(ga1, ga2)"
                                :value="ga2.id">
                              </b-form-checkbox>
                            </td>
                            <td ALIGN="left" width="55%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"> {{ ga2.name }}
                              </label>
                            </td>
                            <td ALIGN="left" width="20%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                v-if="ga2.price > 0">{{
                                  $store.state['defaults'].currency
                                }}{{
  ga2.price |
  priceformat
}} </label>
                            </td>

                          </tr>

                        </table>

                        <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max == 1" width="100%">

                          <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                            <td ALIGN="left" width="25%">
                              <b-form-radio :name="'faddon' + ga1.id" :id="'size_' + ga1.id + '_' + ga2.id"
                                :data-name="ga2.name" :data-price="ga2.price" :value="ga2.id">
                              </b-form-radio>
                            </td>
                            <td ALIGN="left" width="55%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"> {{ ga2.name }}
                              </label>
                            </td>
                            <td ALIGN="left" width="20%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                v-if="ga2.price > 0">{{
                                  $store.state['defaults'].currency
                                }}{{
  ga2.price |
  priceformat
}} </label>
                            </td>

                          </tr>

                        </table>
                      </div>
                      <!-- Allow Multiple -->
                      <div v-else style="text-align: left;">
                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} should be selected</small></h5>

                        <table v-if="ga1.group_addons.length && ga1.allow_multiple == 1" width="100%">

                          <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                            <td ALIGN="left" width="25%">
                              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                                class="btn-icon rounded-circle add_to_cart" :id="'size_addon_' + ga1.id + '_' + ga2.id"
                                style="border: 2px solid #00A082 !important;" @click="add_sizeaddon(ga1, ga2)"
                                :data-name="ga2.name" :data-price="ga2.price" :value="ga2.id">
                                <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                              </b-button>
                              <input type="hidden" :id="'hidden_' + ga1.id" value="0" />
                              <div class="num-block skin-2" :id="'size_control_' + ga1.id + '_' + ga2.id"
                                style="display:none">
                                <div class="num-in">
                                  <span class="minus dis" :id="'size_minus' + ga1.id + '_' + ga2.id"
                                    @click="decrease_size(ga1, ga2)"></span>
                                  <input type="text" class="in-num" value="0" readonly="" style="font-weight:800;"
                                    :id="'size_qty_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                    :data-price="ga2.price" :data-value="ga2.id">
                                  <span class="plus" :id="'size_plus' + ga1.id + '_' + ga2.id"
                                    @click="increase_size(ga1, ga2)"></span>
                                </div>
                              </div>
                            </td>
                            <td ALIGN="left" width="55%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"> {{ ga2.name }}
                              </label>
                            </td>
                            <td ALIGN="left" width="20%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                v-if="ga2.price > 0">{{
                                  $store.state['defaults'].currency
                                }}{{
  ga2.price |
  priceformat
}} </label>
                            </td>

                          </tr>

                        </table>
                      </div>
                    </div>
                  </div>

                </div>


                <div v-if="food_quantity.length" style="text-align: left;">
                  <h4>{{ $t("Sizes") }}</h4>

                </div>
                <table v-if="food_quantity.length" width="100%">
                  <!-- <tr><td style="font-size:16px;"><b>{{$t("Sizes")}}</b><br></td></tr> -->
                  <tr v-for="(data5, index5) in food_quantity" :key="index5">
                    <td v-if="data5.is_default" ALIGN="left" width="25%">


                      <b-form-radio :name="'sizesOfItem_' + itemid" :id="'size_' + itemid + '_' + data5.id"
                        :data-name="data5.name" :data-price="data5.price" v-model="s_fq" :value="data5.id"
                        @change="check_size(data5, add_ons, modal_groups)">

                      </b-form-radio>

                    </td>
                    <td ALIGN="left" v-else width="25%">


                      <b-form-radio :name="'sizesOfItem_' + itemid" :id="'size_' + itemid + '_' + data5.id"
                        :data-name="data5.name" :data-price="data5.price" v-model="s_fq" :value="data5.id"
                        @click="check_size(data5, add_ons, modal_groups)">

                      </b-form-radio>
                    </td>

                    <td ALIGN="left" width="55%">
                      <label :for="'size_' + itemid + '_' + data5.id" style="font-size:16px;"
                        @click="check_size(data5, add_ons, modal_groups)"> {{ data5.name }} </label>

                    </td>
                    <td ALIGN="left" width="20%">
                      <label :for="'size_' + itemid + '_' + data5.id" style="font-size:16px;"
                        @click="check_size(data5, add_ons, modal_groups)" v-if="data5.price > 0">{{
                          $store.state['defaults'].currency }}{{ data5.price | priceformat }} </label>
                    </td>

                  </tr>
                </table>




                <div v-if="food_quantity.length">

                  <!-- size Addons -->

                  <div v-for="(data6, index6) in food_quantity" :key="index6">
                    <div v-if="data6.groups && data6.groups.length">
                      <div v-for="(data7, index7) in data6.groups" :key="index7">
                        <div v-if="data6.id == s_fq">
                          <div v-if="data7.group_addons.length && data7.allow_multiple != 1"
                            style="text-align: left;margin-top:30px;">
                            <h4>{{ data7.name }}<sup v-if="data7.mandatory === 1" style="color:red"> *</sup></h4>
                            <h5 v-if="data7.mandatory === 1"><small>Minimum {{ data7.min }} Should be selected</small>
                            </h5>

                            <table v-if="data7.group_addons.length && data7.allow_multiple != 1 && data7.max != 1"
                              width="100%">

                              <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                <td ALIGN="left" width="25%">
                                  <b-form-checkbox :name="'faddon' + data7.id" :id="'size_' + data7.id + '_' + data8.id"
                                    :data-name="data8.name" :data-price="data8.price"
                                    @change="validate_sizeaddons(data7, data8)" :value="data8.id">
                                  </b-form-checkbox>
                                </td>
                                <td ALIGN="left" width="55%">
                                  <label :for="'size_' + data7.id + '_' + data8.id" style="font-size:16px;"> {{
                                    data8.name
                                  }}
                                  </label>
                                </td>
                                <td ALIGN="left" width="20%">
                                  <label :for="'size_' + data7.id + '_' + data8.id" style="font-size:16px;"
                                    v-if="data8.price > 0">{{
                                      $store.state['defaults'].currency
                                    }}{{
  data8.price |
  priceformat
}} </label>
                                </td>

                              </tr>
                            </table>

                            <table v-if="data7.group_addons.length && data7.allow_multiple != 1 && data7.max == 1"
                              width="100%">

                              <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                <td ALIGN="left" width="25%">
                                  <b-form-radio :name="'faddon' + data7.id" :id="'size_' + data7.id + '_' + data8.id"
                                    :data-name="data8.name" :data-price="data8.price" :value="data8.id">

                                  </b-form-radio>

                                </td>
                                <td ALIGN="left" width="55%">
                                  <label :for="'size_' + data7.id + '_' + data8.id" style="font-size:16px;"> {{
                                    data8.name
                                  }}
                                  </label>
                                </td>
                                <td ALIGN="left" width="20%">
                                  <label :for="'size_' + data7.id + '_' + data8.id" style="font-size:16px;"
                                    v-if="data8.price > 0">{{
                                      $store.state['defaults'].currency
                                    }}{{
  data8.price |
  priceformat
}} </label>
                                </td>

                              </tr>
                            </table>
                          </div>
                          <div v-else style="text-align: left;margin-top:30px;">
                            <h4>{{ data7.name }}<sup v-if="data7.mandatory === 1" style="color:red"> *</sup></h4>
                            <h5 v-if="data7.mandatory === 1"><small>Minimum {{ data7.min }} Should be selected</small>
                            </h5>

                            <table v-if="data7.group_addons.length && data7.allow_multiple == 1" width="100%">

                              <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                <td ALIGN="left" width="25%">
                                  <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                                    class="btn-icon rounded-circle add_to_cart"
                                    :id="'size_addon_' + data7.id + '_' + data8.id"
                                    style="border: 2px solid #00A082 !important;" @click="add_sizeaddon(data7, data8)"
                                    :data-name="data8.name" :data-price="data8.price" :value="data8.id">
                                    <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                                  </b-button>
                                  <input type="hidden" :id="'hidden_' + data7.id" value="0" />
                                  <div class="num-block skin-2" :id="'size_control_' + data7.id + '_' + data8.id"
                                    style="display:none">
                                    <div class="num-in">
                                      <span class="minus dis" :id="'size_minus' + data7.id + '_' + data8.id"
                                        @click="decrease_size(data7, data8)"></span>
                                      <input type="text" class="in-num" value="0" readonly="" style="font-weight:800;"
                                        :id="'size_qty_' + data7.id + '_' + data8.id" :data-name="data8.name"
                                        :data-price="data8.price" :data-value="data8.id">
                                      <span class="plus" :id="'size_plus' + data7.id + '_' + data8.id"
                                        @click="increase_size(data7, data8)"></span>
                                    </div>
                                  </div>
                                </td>
                                <td ALIGN="left" width="55%">
                                  <label :for="'size_' + data7.id + '_' + data8.id" style="font-size:16px;"> {{
                                    data8.name
                                  }}
                                  </label>
                                </td>
                                <td ALIGN="left" width="20%">
                                  <label :for="'size_' + data7.id + '_' + data8.id" style="font-size:16px;"
                                    v-if="data8.price > 0">{{
                                      $store.state['defaults'].currency
                                    }}{{
  data8.price |
  priceformat
}} </label>
                                </td>

                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Simple Addons -->
                    <div v-if="data6.add_ons && data6.add_ons.length">
                      <div v-if="data6.id == s_fq" style="text-align: left;">
                        <h4>{{ $t("Addons") }}</h4>

                      </div>
                      <table v-if="data6.id == s_fq" width="100%">

                        <tr v-for="(d6, i6) in data6.add_ons" :key="i6">


                          <td ALIGN="left" width="25%">

                            <b-form-checkbox :name="'saddonsOfItem_' + data6.id" :id="'addon_' + itemid + '_' + d6.id"
                              :data-name="d6.name" :data-price="d6.price" :value="d6.id">
                            </b-form-checkbox>
                          </td>

                          <td ALIGN="left" width="55%">
                            <label :for="'addon_' + itemid + '_' + d6.id" style="font-size:16px;"> {{ d6.name }}
                            </label>

                          </td>
                          <td ALIGN="left" width="20%">
                            <label :for="'addon_' + itemid + '_' + d6.id" style="font-size:16px;" v-if="d6.price > 0">
                              {{ $store.state['defaults'].currency }}{{ d6.price | priceformat }} </label>
                          </td>

                        </tr>
                      </table>
                    </div>
                  </div>
                  <!-- General Addons -->

                  <!-- Group Addons -->
                  <div v-if="modal_groups.length">
                    <div v-for="ga1 in modal_groups" :key="ga1.id">

                      <!-- Checkbox -->
                      <div v-if="ga1.group_addons.length && ga1.allow_multiple !== 1"
                        style="text-align: left;margin-top:30px;">
                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} Should be selected</small></h5>

                        <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max != 1" width="100%">

                          <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                            <td ALIGN="left" width="25%">
                              <b-form-checkbox :name="'faddon' + ga1.id" :id="'size_' + ga1.id + '_' + ga2.id"
                                :data-name="ga2.name" :data-price="ga2.price" @change="validate_sizeaddons(ga1, ga2)"
                                :value="ga2.id">
                              </b-form-checkbox>
                            </td>
                            <td ALIGN="left" width="55%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"> {{ ga2.name }}
                              </label>
                            </td>
                            <td ALIGN="left" width="20%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                v-if="ga2.price > 0">{{
                                  $store.state['defaults'].currency
                                }}{{
  ga2.price |
  priceformat
}} </label>
                            </td>

                          </tr>

                        </table>

                        <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max == 1" width="100%">

                          <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                            <td ALIGN="left" width="25%">


                              <b-form-radio :name="'faddon' + ga1.id" :id="'size_' + ga1.id + '_' + ga2.id"
                                :data-name="ga2.name" :data-price="ga2.price" :value="ga2.id">

                              </b-form-radio>
                            </td>
                            <td ALIGN="left" width="55%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"> {{ ga2.name }}
                              </label>
                            </td>
                            <td ALIGN="left" width="20%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                v-if="ga2.price > 0">{{
                                  $store.state['defaults'].currency
                                }}{{
  ga2.price |
  priceformat
}} </label>
                            </td>

                          </tr>

                        </table>
                      </div>
                      <!-- Allow Multiple -->
                      <div v-else style="text-align: left;margin-top:30px;">
                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} Should be selected</small></h5>

                        <table v-if="ga1.group_addons.length && ga1.allow_multiple == 1" width="100%">


                          <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                            <td ALIGN="left" width="25%">
                              <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                                class="btn-icon rounded-circle add_to_cart" :id="'size_addon_' + ga1.id + '_' + ga2.id"
                                style="border: 2px solid #00A082 !important;" @click="add_sizeaddon(ga1, ga2)"
                                :data-name="ga2.name" :data-price="ga2.price" :value="ga2.id">
                                <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                              </b-button>
                              <input type="hidden" :id="'hidden_' + ga1.id" value="0" />
                              <div class="num-block skin-2" :id="'size_control_' + ga1.id + '_' + ga2.id"
                                style="display:none">
                                <div class="num-in">
                                  <span class="minus dis" :id="'size_minus' + ga1.id + '_' + ga2.id"
                                    @click="decrease_size(ga1, ga2)"></span>
                                  <input type="text" class="in-num" value="0" readonly="" style="font-weight:800;"
                                    :id="'size_qty_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                    :data-price="ga2.price" :data-value="ga2.id">
                                  <span class="plus" :id="'size_plus' + ga1.id + '_' + ga2.id"
                                    @click="increase_size(ga1, ga2)"></span>
                                </div>
                              </div>
                            </td>
                            <td ALIGN="left" width="55%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"> {{ ga2.name }}
                              </label>
                            </td>
                            <td ALIGN="left" width="20%">
                              <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                v-if="ga2.price > 0">{{
                                  $store.state['defaults'].currency
                                }}{{
  ga2.price |
  priceformat
}} </label>
                            </td>

                          </tr>

                        </table>
                      </div>
                    </div>
                  </div>

                  <!-- Simple Addons -->
                  <div v-if="add_ons.length">
                    <div v-if="add_ons.length" style="text-align: left;margin-top:30px;">
                      <h4>{{ $t("Addons") }}</h4>

                    </div>
                    <table v-if="add_ons.length" width="100%">
                      <!-- <tr><td style="font-size:16px;float: left;"><b>{{$t("Addons")}}</b></td></tr> -->
                      <tr v-for="(data4, index4) in add_ons" :key="index4">

                        <td ALIGN="left" width="25%">

                          <b-form-checkbox :name="'addonsOfItem_' + itemid" :id="'addon_' + itemid + '_' + data4.id"
                            :data-name="data4.name" :data-price="data4.price" :value="data4.id">
                          </b-form-checkbox>
                        </td>
                        <td ALIGN="left" width="55%">
                          <label :for="'addon_' + itemid + '_' + data4.id" style="font-size:16px;"> {{ data4.name }}
                          </label>

                        </td>
                        <td ALIGN="left" width="20%">
                          <label :for="'addon_' + itemid + '_' + data4.id" style="font-size:16px;" v-if="data4.price > 0">
                            {{ $store.state['defaults'].currency }}{{ data4.price | priceformat }} </label>
                        </td>



                      </tr>
                    </table>
                  </div>
                </div>



              </div>
            </div>

          </b-card-text>
          <div slot="modal-footer" class="w-100 modal_footer">
            <!-- <b-button class="mt-2" variant="primary" id="addCustomizedProduct"   @click="change_tab(1)" style="width:100%;" v-if="tabIndex == 0 && food_quantity.length && !dont_show">{{$t("Next")}}</b-button> -->
            <b-alert v-model="showDismissibleAlert" v-height-fade.appear variant="danger" dismissible class="mb-0">
              <div class="alert-body">
                {{ alert_text }}
              </div>
            </b-alert>
            <b-button class="" variant="primary" id="addCustomizedProduct" @click="check_cart(selected)"
              style="width:100%;font-size:16px;font-weight:800" v-if="this.rows[0].is_open === 1">{{ $t("Add to order")
              }}</b-button>
          </div>
        </b-modal>
        <b-modal id="modal-center" centered :title="modal_title" hide-header hide-footer ok-only ok-title="Accept"
          ref="image-show">
          <b-card-text>
            <img class="modal-content" :src="modal_image" id="img01">
            <b-row class="mt-1">
              <b-col cols="6">
                <h4>
                  <div v-if="content.is_veg">
                    {{ modal_title }} <b-img-lazy src="/veg.svg" fluid alt="Scan" style="width: 12px;"
                      v-if="pharmacy_id == 0" />
                  </div>
                  <div v-else>
                    {{ modal_title }} <b-img-lazy src="/non_veg.svg" fluid alt="Scan" style="width: 12px;"
                      v-if="pharmacy_id == 0" />
                  </div>
                </h4>
                <h6>{{ modal_desc }}</h6>
              </b-col>
              <b-col cols="3">
              </b-col>
              <b-col cols="3">


                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary"
                  :id="'food_add_' + content.food_id + 'r'" :data-price=content.price @click="open_plus(content)">
                  <feather-icon icon="PlusIcon" class="mr-50" />
                  <span class="align-middle">Add</span>
                </b-button>



                <div class="num-block skin-2" :id="'food_control_' + content.food_id + 'r'" style="display:none">
                  <div class="num-in">
                    <span class="minus dis" :id="'food_minus_' + content.food_id"
                      @click="removefromcart(content.price, content.item_tax, content.name, content.food_id, content.is_veg, content.add_ons, content.food_quantity, content.image)"></span>
                    <input type="text" class="in-num" value="1" readonly="" :id="'food_qty_' + content.food_id + 'r'">
                    <span class="plus" :id="'food_plus_' + content.food_id" @click="open_plus(content)"></span>
                  </div>
                </div>


              </b-col>
            </b-row>


          </b-card-text>
        </b-modal>

        <b-modal id="modal-addons" ok-only ok-variant="success" ok-title="Accept" modal-class="modal-success" centered
          hide-footer :title="$t('Customize')" ref="addon-modal">

          <div id="addonsModalBody" class="text-center">
            <table v-if="add_ons.length" width="80%">
              <tr>
                <td style="font-size:16px;"><b>{{ $t("Addons") }}</b></td>
              </tr>
              <tr v-for="(data4, index4) in add_ons" :key="index4">

                <td width="20%" ALIGN="center">
                  <input type="checkbox" :name="'addonsOfItem_' + itemid" :id="'addon_' + itemid + '_' + data4.id"
                    :data-name="data4.name" :data-price="data4.price" :value="data4.id">
                </td>

                <td>
                  <label :for="'addon_' + itemid + '_' + data4.id" style="font-size:16px;"> {{ data4.name }} </label>

                </td>
                <td>
                  <label :for="'addon_' + itemid + '_' + data4.id" style="font-size:16px;">
                    {{ $store.state['defaults'].currency }} {{ data4.price | priceformat }} </label>
                </td>

              </tr>
            </table>
            <table v-if="food_quantity.length" width="80%" style="margin-top:30px;">
              <tr>
                <td style="font-size:16px;"><b>{{ $t("Sizes") }}</b><br></td>
              </tr>
              <tr v-for="(data5, index5) in food_quantity" :key="index5">

                <td width="20%" v-if="data5.is_default">
                  <input type="radio" :name="'sizesOfItem_' + itemid" :id="'size_' + itemid + '_' + data5.id"
                    :data-name="data5.name" :data-price="data5.price" checked>
                </td>
                <td width="20%" ALIGN="center" v-else>
                  <input type="radio" :name="'sizesOfItem_' + itemid" :id="'size_' + itemid + '_' + data5.id"
                    :data-name="data5.name" :data-price="data5.price">
                </td>
                <td>
                  <label :for="'size_' + itemid + '_' + data5.id" style="font-size:16px;"> {{ data5.name }} </label>

                </td>
                <td>
                  <label :for="'size_' + itemid + '_' + data5.id" style="font-size:16px;">{{
                    $store.state['defaults'].currency }}
                    {{ data5.price | priceformat }} </label>
                </td>
              </tr>
            </table>
            <b-button class="mt-2" variant="primary" id="addCustomizedProduct" @click="addCustomizedProduct(selected)"
              style="width:100%;">{{ $t("Add") }}</b-button>
          </div>


        </b-modal>
        <b-modal id="modal-info" centered hide-header hide-footer ref="info-mdl">

          <div id="gmap"></div>
          <p class=" pt-4"><strong class="mb-1 f-size-2"> {{ rows[0].name }} </strong><span v-if="rows[0].is_open === 1">
              <b-badge class="badge-glow" :variant="statusVariant(rows[0].is_open)">
                {{ $t("Open") }}
              </b-badge>
            </span><span v-else>

              <b-badge class="badge-glow" :variant="statusVariant(rows[0].is_open)">
                {{ $t("Closed") }}
              </b-badge>
            </span>
          </p>

          <p><i class="fa-solid fa-location-dot"></i> {{ rows[0].address }}</p>
          <p><i class="fa-solid fa-phone"></i> {{ rows[0].phone }}</p>
          <p><i class="fa-solid fa-envelope"></i> {{ rows[0].email }}</p>

          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i><strong> {{ $t("Monday") }} :
            </strong>{{ rows[0].opening_time_1 | tConvert }} - {{ rows[0].closing_time_1 | tConvert }} ,
            {{ rows[0].opening_second_time_1 | tConvert }} - {{ rows[0].closing_second_time_1 | tConvert }} </p>
          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{ $t("Tuesday") }} :
            </strong>{{ rows[0].opening_time_2 | tConvert }} - {{ rows[0].closing_time_2 | tConvert }} ,
            {{ rows[0].opening_second_time_2 | tConvert }} - {{ rows[0].closing_second_time_2 | tConvert }} </p>
          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{ $t("Wednesday") }} :
            </strong>{{ rows[0].opening_time_3 | tConvert }} - {{ rows[0].closing_time_3 | tConvert }} ,
            {{ rows[0].opening_second_time_3 | tConvert }} - {{ rows[0].closing_second_time_3 | tConvert }}</p>
          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{ $t("Thursday") }} :
            </strong>{{ rows[0].opening_time_4 | tConvert }} - {{ rows[0].closing_time_4 | tConvert }} ,
            {{ rows[0].opening_second_time_4 | tConvert }} - {{ rows[0].closing_second_time_4 | tConvert }}</p>
          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{ $t("Friday") }} :
            </strong>{{ rows[0].opening_time_5 | tConvert }} - {{ rows[0].closing_time_5 | tConvert }} ,
            {{ rows[0].opening_second_time_5 | tConvert }} - {{ rows[0].closing_second_time_5 | tConvert }}</p>
          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{ $t("Saturday") }} :
            </strong>{{ rows[0].opening_time_6 | tConvert }} - {{ rows[0].closing_time_6 | tConvert }} ,
            {{ rows[0].opening_second_time_6 | tConvert }} - {{ rows[0].closing_second_time_6 | tConvert }}</p>
          <p class="mb-2 text-black"><i class="fa-regular fa-clock"></i> <strong>{{ $t("Sunday") }} :
            </strong>{{ rows[0].opening_time_7 | tConvert }} - {{ rows[0].closing_time_7 | tConvert }} ,
            {{ rows[0].opening_second_time_7 | tConvert }} - {{ rows[0].closing_second_time_7 | tConvert }}</p>
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
  BNavItem,
  BMedia,
  BButton,
  BModal, VBModal, BFormSpinbutton, BImgLazy, BFormRadio, BFormCheckbox, BAlert
} from 'bootstrap-vue'
import VueSlider from 'vue-slider-component'
import { useWindowScroll } from '@vueuse/core'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import store from '@@@/store'
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import 'swiper/css/swiper.css'
import { heightFade } from '@core/directives/animations'

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
    BNavItem,
    BBadge,
    VueSlider,
    BMedia,
    BModal,
    VBModal,
    BFormSpinbutton,
    BImgLazy,
    Swiper,
    SwiperSlide, BFormRadio, BFormCheckbox, BAlert

  },
  directives: {
    'height-fade': heightFade,
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem('webuserData')),
      rows: {},
      business_id: [],
      rows_detail: {},
      rows_detail2: {},
      isLoading: true,
      isfav: false,
      address: '',
      cart: JSON.parse(localStorage.getItem("cart")),
      add_ons: {},
      modal_groups: {},
      food_quantity: {},
      itemid: '',
      selected: {},
      quantity: [],
      promocodes: {},
      top_selling: {},
      isopen: true,
      modal_image: '',
      modal_title: '',
      modal_desc: '',
      s_fq: '',
      s_ad: [],
      ttitle: '',
      pharmacy_id: [],
      pharmacy_file: [],
      swiperOptions2: {

        autoplay: {
          delay: 1500,
          disableOnInteraction: true,
        },
      },
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
          },
          1200: {
            slidesPerView: 3.2,
            spaceBetween: 30
          },
          1440: {
            slidesPerView: 4.2,
            spaceBetween: 30
          },

        },

        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      },
      tabs: [1],
      tabCounter: 1,
      tabIndex: 0,
      Selected_SizeName: 0,
      showDismissibleAlert: false,
      alert_text: '',
      dummy: 0,
      product_type: 0,
      dont_show: false,
      content: {},
      layout_type: 1,
      restaurants_name: '',
      cleared: false,
      coupon_applied: 0
    }
  },
  filters: {
    priceformat(number) {
      var decimals = store.state['defaults'].decimal;
      var dec_point = store.state['defaults'].delimiter;
      var thousands_sep = '';
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

    tConvert(date) {

      var strTime = date.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit' })
        .replace(/(:\d{2}| [AP]M)$/, "");
      return strTime;
    }

  },
  setup() {

  },

  methods: {
    close_img() {
      this.$refs['image-modal'].hide()
    },
    show_image(data) {
      this.modal_image = data.image
      this.modal_title = data.name
      this.modal_desc = data.description
      this.content = data
      this.$refs['image-show'].show()

    },
    check_size(data, add_ons, modal_groups) {

      if (data.addon_group == null && data.addons_id == null) {
        if (add_ons.length || modal_groups.length) {
          this.dont_show = false
        } else {
          this.dont_show = true
        }
      } else {
        this.dont_show = false

      }

    },
    add_sizeaddon(data, data2) {

      var dummy = document.getElementById("hidden_" + data.id).value
      if (dummy < data.max && data.mandatory == 1) {

        document.getElementById("size_control_" + data.id + "_" + data2.id).style.display = "block";
        document.getElementById("size_addon_" + data.id + "_" + data2.id).style.display = "none";

        document.getElementById("hidden_" + data.id).value = ++dummy
        var count = document.getElementById("size_qty_" + data.id + "_" + data2.id).value;
        var inc = ++count;
        document.getElementById("size_qty_" + data.id + "_" + data2.id).value = inc

      } else if (data.mandatory == 0) {
        document.getElementById("size_control_" + data.id + "_" + data2.id).style.display = "block";
        document.getElementById("size_addon_" + data.id + "_" + data2.id).style.display = "none";

        document.getElementById("hidden_" + data.id).value = ++dummy
        var count = document.getElementById("size_qty_" + data.id + "_" + data2.id).value;
        var inc = ++count;
        document.getElementById("size_qty_" + data.id + "_" + data2.id).value = inc

      } else {
        this.alert_text = "Maximum " + data.max + " can be Selected in " + data.name
        this.showDismissibleAlert = true
      }
    },
    increase_size(data, data2) {


      var dummy = document.getElementById("hidden_" + data.id).value

      if (dummy < data.max && data.mandatory == 1) {
        document.getElementById("hidden_" + data.id).value = ++dummy

        var count = document.getElementById("size_qty_" + data.id + "_" + data2.id).value;
        var inc = ++count;
        document.getElementById("size_qty_" + data.id + "_" + data2.id).value = inc

      } else if (data.mandatory == 0) {
        document.getElementById("hidden_" + data.id).value = ++dummy

        var count = document.getElementById("size_qty_" + data.id + "_" + data2.id).value;
        var inc = ++count;
        document.getElementById("size_qty_" + data.id + "_" + data2.id).value = inc

      } else {
        this.alert_text = "Maximum " + data.max + " can be Selected in " + data.name
        this.showDismissibleAlert = true
      }
    },
    decrease_size(data, data2) {
      var dummy = document.getElementById("hidden_" + data.id).value

      document.getElementById("hidden_" + data.id).value = --dummy

      var count = document.getElementById("size_qty_" + data.id + "_" + data2.id).value;
      var dec = --count;
      if (dec == 0) {
        document.getElementById("size_control_" + data.id + "_" + data2.id).style.display = "none";
        document.getElementById("size_addon_" + data.id + "_" + data2.id).style.display = "block";
        document.getElementById("size_qty_" + data.id + "_" + data2.id).value = dec

      } else {
        document.getElementById("size_qty_" + data.id + "_" + data2.id).value = dec
      }

    },
    validate_sizeaddons(data, data2) {


      if (data.mandatory == 1) {
        if (document.querySelectorAll('input[name="faddon' + data.id + '"]:checked').length == data.max) {
          if (data.group_addons.length) {
            for (var i = 0; i < data.group_addons.length; i++) {
              if (document.getElementById("size_" + data.id + "_" + data.group_addons[i].id)) {
                if (document.getElementById("size_" + data.id + "_" + data.group_addons[i].id).checked == false) {
                  document.getElementById("size_" + data.id + "_" + data.group_addons[i].id).disabled = true
                }

              }
            }
          }
        } else {
          if (data.group_addons.length) {
            for (var i = 0; i < data.group_addons.length; i++) {
              if (document.getElementById("size_" + data.id + "_" + data.group_addons[i].id)) {

                document.getElementById("size_" + data.id + "_" + data.group_addons[i].id).disabled = false


              }
            }
          }
        }


      }
    },
    change_tab(val) {
      if (val == 1) {


        var selected = document.getElementById("size_" + this.itemid + "_" + this.s_fq).getAttribute('data-name')

        this.Selected_SizeName = selected
        this.tabIndex++
      } else {
        this.tabIndex--
      }
    },
    closeTab(x) {
      for (let i = 0; i < this.tabs.length; i += 1) {
        if (this.tabs[i] === x) {
          this.tabs.splice(i, 1)
        }
      }
    },
    newTab() {
      this.tabs.push((this.tabCounter += 1))
    },
    open_plus(data, data2) {
      if (data2.menu_available == 0) {
        this.$swal({
          title: '<span class="font-weight-bolder">Sorry, the item is unavailable</span>',
          icon: 'warning',
          html:
            'You can Order on <span class="font-weight-bolder">' + data2.next_available + '</span> '
          ,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonText: 'Great!',
          confirmButtonAriaLabel: 'Thumbs up, great!',
          cancelButtonAriaLabel: 'Thumbs down',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1',
          },
          buttonsStyling: false,
        })
        return
      }
      if (!data.food_quantity.length && !data.add_ons.length && !data.groups.length) {

        this.selected.id = data.food_id
        this.selected.restaurant = this.rows[0].id
        this.selected.isveg = data.is_veg
        this.selected.restaurant_name = this.rows[0].name
        this.selected.restaurant_address = this.rows[0].address
        this.selected.restaurant_image = this.rows[0].image
        this.selected.taxperc = parseFloat(data.item_tax)
        this.selected.price = parseFloat(data.price)
        this.selected.add_ons = data.add_ons
        this.selected.food_quantity = data.food_quantity
        this.selected.food_image = data.image
        this.selected.name = data.name
        this.selected.groups = data.groups
        this.selected.pharmacy_id = this.pharmacy_id
        this.check_cart(this.selected)
        return
      }

      this.s_fq = ''
      this.tabIndex = 0
      this.showDismissibleAlert = false
      this.dont_show = false
      this.itemid = data.food_id
      this.$refs['image-modal'].show()
      this.modal_image = data.image
      this.modal_title = data.name
      this.add_ons = data.add_ons
      this.food_quantity = data.food_quantity
      if (this.food_quantity.length) {
        for (var i = 0; i < this.food_quantity.length; i++) {
          if (this.food_quantity[i].is_default == 1) {
            this.s_fq = this.food_quantity[i].id
            if (this.food_quantity[i].addon_group == null && this.food_quantity[i].addons_id == null && !data.groups.length) {
              this.dont_show = true
            }
          }
        }
        if (data.groups.length || data.add_ons.length) {
          this.dont_show = false

        }
      }
      this.modal_groups = data.groups
      this.modal_desc = data.description
      this.selected.id = data.food_id
      this.selected.restaurant = this.rows[0].id
      this.selected.isveg = data.is_veg
      this.selected.restaurant_name = this.rows[0].name
      this.selected.restaurant_address = this.rows[0].address
      this.selected.restaurant_image = this.rows[0].image
      this.selected.taxperc = parseFloat(data.item_tax)
      this.selected.price = parseFloat(data.price)
      this.selected.add_ons = data.add_ons
      this.selected.food_quantity = data.food_quantity
      this.selected.food_image = data.image
      this.selected.name = data.name
      this.selected.groups = data.groups
      this.selected.pharmacy_id = this.pharmacy_id

    },
    open_image(data, data2) {

      // if (!data.food_quantity.length && !data.add_ons.length && !data.groups.length) {
      //   return
      // }
      if (data2.menu_available == 0) {
        this.$swal({
          title: '<span class="font-weight-bolder">Sorry, the item is unavailable</span>',
          icon: 'warning',
          html:
            'You can Order on <span class="font-weight-bolder">' + data2.next_available + '</span> '
          ,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonText: 'OK',
          confirmButtonAriaLabel: 'Thumbs up, great!',
          cancelButtonAriaLabel: 'Thumbs down',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1',
          },
          buttonsStyling: false,
        })
        return
      }

      this.s_fq = ''
      this.tabIndex = 0
      this.showDismissibleAlert = false
      this.dont_show = false
      this.itemid = data.food_id
      this.$refs['image-modal'].show()
      this.modal_image = data.image
      this.modal_title = data.name
      this.add_ons = data.add_ons
      this.food_quantity = data.food_quantity
      if (this.food_quantity.length) {
        for (var i = 0; i < this.food_quantity.length; i++) {
          if (this.food_quantity[i].is_default == 1) {
            this.s_fq = this.food_quantity[i].id
            if (this.food_quantity[i].addon_group == null && this.food_quantity[i].addons_id == null && !data.groups.length) {
              this.dont_show = true
            }
          }
        }
        if (data.groups.length || data.add_ons.length) {
          this.dont_show = false

        }
      }
      this.modal_groups = data.groups
      this.modal_desc = data.description
      this.selected.id = data.food_id
      this.selected.restaurant = this.rows[0].id
      this.selected.isveg = data.is_veg
      this.selected.restaurant_name = this.rows[0].name
      this.selected.restaurant_address = this.rows[0].address
      this.selected.restaurant_image = this.rows[0].image
      this.selected.taxperc = parseFloat(data.item_tax)
      this.selected.price = parseFloat(data.price)
      this.selected.add_ons = data.add_ons
      this.selected.food_quantity = data.food_quantity
      this.selected.food_image = data.image
      this.selected.name = data.name
      this.selected.groups = data.groups

    },
    show_infomdl() {

      this.$refs['info-mdl'].show()
      var lat = this.rows[0].lat
      var lng = this.rows[0].lng

      var timer = setInterval(function () {
        if (document.getElementById("gmap")) {


          const element = document.getElementById("gmap");
          const mapoptions = {
            zoom: 18,
            disableDefaultUI: true,
            styles: [
              {
                featureType: "administrative",
                elementType: "geometry",
                stylers: [
                  {
                    visibility: "off",
                  },
                ],
              },
              {
                featureType: "poi",
                stylers: [
                  {
                    visibility: "off",
                  },
                ],
              },

              {
                featureType: "road",
                elementType: "labels.icon",
                stylers: [
                  {
                    visibility: "off",
                  },
                ],
              },
              {
                featureType: "transit",
                stylers: [
                  {
                    visibility: "off",
                  },
                ],
              },
            ],
            center: new google.maps.LatLng(lat, lng)
          };
          const map = new google.maps.Map(element, mapoptions);

          var lati = lat;
          var long = lng;

          var myLatlng = new google.maps.LatLng(Number(lati), Number(long));


          var infowindow = new google.maps.InfoWindow();
          var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            draggable: false

          });

          clearInterval(timer);
        }
      }, 1000);
    },
    open_image(data, data2) {

      // if (!data.food_quantity.length && !data.add_ons.length && !data.groups.length) {
      //   return
      // }
      if (data2.menu_available == 0) {
        this.$swal({
          title: '<span class="font-weight-bolder">Sorry, the item is unavailable</span>',
          icon: 'warning',
          html:
            'You can Order on <span class="font-weight-bolder">' + data2.next_available + '</span> '
          ,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonText: 'OK',
          confirmButtonAriaLabel: 'Thumbs up, great!',
          cancelButtonAriaLabel: 'Thumbs down',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1',
          },
          buttonsStyling: false,
        })
        return
      }

      this.s_fq = ''
      this.tabIndex = 0
      this.showDismissibleAlert = false
      this.dont_show = false
      this.itemid = data.food_id
      this.$refs['image-modal'].show()
      this.modal_image = data.image
      this.modal_title = data.name
      this.add_ons = data.add_ons
      this.food_quantity = data.food_quantity
      if (this.food_quantity.length) {
        for (var i = 0; i < this.food_quantity.length; i++) {
          if (this.food_quantity[i].is_default == 1) {
            this.s_fq = this.food_quantity[i].id
            if (this.food_quantity[i].addon_group == null && this.food_quantity[i].addons_id == null && !data.groups.length) {
              this.dont_show = true
            }
          }
        }
        if (data.groups.length || data.add_ons.length) {
          this.dont_show = false

        }
      }
      this.modal_groups = data.groups
      this.modal_desc = data.description
      this.selected.id = data.food_id
      this.selected.restaurant = this.rows[0].id
      this.selected.isveg = data.is_veg
      this.selected.restaurant_name = this.rows[0].name
      this.selected.restaurant_address = this.rows[0].address
      this.selected.restaurant_image = this.rows[0].image
      this.selected.taxperc = parseFloat(data.item_tax)
      this.selected.price = parseFloat(data.price)
      this.selected.add_ons = data.add_ons
      this.selected.food_quantity = data.food_quantity
      this.selected.food_image = data.image
      this.selected.name = data.name
      this.selected.groups = data.groups
      this.selected.pharmacy_id = this.pharmacy_id

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
    handleScroll() {
      var findMe = document.querySelectorAll('#products h3 ');
      if (this.business_id == 1) {
        var li = document.querySelectorAll('.side div');

      }
      else {
        var li = document.querySelectorAll('.side div');
      }


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
    scrollMeTo(refName, id) {
      this.isLoading = true
      if (refName == 0) {
        this.$http.get('/get_food_list_by_menu/' + this.$route.params.id)
          .then(res => {

            this.rows_detail = res.data.items
            this.business_id = res.data.business_id
            this.pharmacy_id = res.data.pharmacy_id
            this.rows_detail2 = res.data.items
            this.isLoading = false
            this.loadcart()
            if (this.business_id == 1) {
              var li = document.querySelectorAll('.side div');

            }
            else {
              var li = document.querySelectorAll('.side div');
            }

            li.forEach(li => {
              li.classList.remove("active");
            })
            this.isLoading = false
            var box = document.querySelector('[id="' + refName + '"]');
            box.classList.add("active");
            const rootEle = document.documentElement
            rootEle.scrollTo({
              top: 0,
              behavior: 'smooth',
            })

          })
      } else {
        this.$http.get('/get_food_list_by_menu_id/' + refName + '/' + this.$route.params.id)
          .then(res => {

            this.rows_detail = res.data.items
            this.business_id = res.data.business_id
            this.pharmacy_id = res.data.pharmacy_id
            this.loadcart()
            if (this.business_id == 1) {
              var li = document.querySelectorAll('.side div');

            }
            else {
              var li = document.querySelectorAll('.side div');
            }
            li.forEach(li => {
              li.classList.remove("active");
            })
            this.isLoading = false

            const rootEle = document.documentElement
            rootEle.scrollTo({
              top: 0,
              behavior: 'smooth',
            })
            var box = document.querySelector('[id="' + this.rows_detail[0].menu_name + '"]');

            box.classList.add("active");

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
    markasfav() {
      if (localStorage.getItem("webuserData")) {
        let loggeduser = JSON.parse(localStorage.getItem("webuserData"));
        this.$http.get('/mark_as_favourite/' + this.$route.params.id + '/' + loggeduser.id)
          .then(res => {
            if (res.data.status == true) {
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
            } else {
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
      } else {
        this.$toast({
          component: ToastificationContent,
          position: 'bottom-right',
          props: {
            title: this.$t('Please Login to Save Your Favorite Restaurant'),
            icon: 'HeartIcon',
            variant: 'danger',
          },
        })
      }
    },

    addcart: function (price, item_tax, name, food_id, is_veg, add_ons, food_quantity, food_image, groups) {
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");

      this.itemid = food_id
      let id = food_id,
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
      this.selected.groups = groups




      if (add_ons.length > 0 || food_quantity.length > 0) {
        this.add_ons = add_ons
        this.food_quantity = food_quantity
        this.$refs['addon-modal'].show()

      } else {
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
            food_image: food_image,
            pharmacy_id: this.pharmacy_id
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
    loadcoupon() {
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
    loadcart() {


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
      localStorage.setItem("pharmacy_file", 0);
      store.commit('deliware_cart/UPDATE_PHARMACY_FILE', this.pharmacy_file)
      if (this.pharmacy_id == 1) {

        for (let i = 0; i < cart.length; i++) {
          let cart = JSON.parse(localStorage.getItem("cart"));
          if (cart[i].isveg == 1) {
            this.pharmacy_file = 1;
            break;
          } else {
            this.pharmacy_file = 0;
          }



        }

        localStorage.setItem("pharmacy_file", this.pharmacy_file);
        store.commit('deliware_cart/UPDATE_PHARMACY_FILE', this.pharmacy_file)
      }

      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');
      // if (localStorage.getItem('webuserData')) {
      //   this.$http.get('/get_checkout_details/' + cart[0].restaurant + '/' + lat + '/' + lng + '/' + this.userData.id)
      //     .then(res => {
      //       localStorage.setItem('DISTANCE', res.data.distance);
      //     })

      // }
      var tips = 0;
      let wallet = localStorage.getItem('WALLET');
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
      if (cart.length === 0) {

      } else {
        let result = Object.values(cart.reduce((a, { id, quantity }) => {
          let key = `${id}_${quantity}`;

          a[key] = a[key] || { id, quantity, count: 0 };
          a[key].count++;
          return a;
        }, {}));

        var resArr = [];
        cart.filter(function (item) {

          var i = resArr.findIndex(x => (x.id == item.id));
          if (i <= -1) {
            resArr.push(item);
          }
          return null;
        });

        for (var i = 0; i < resArr.length; i++) {
          var qq = 0;
          var rr = 0;

          for (var j = 0; j < result.length; j++) {

            if (result[j].id == resArr[i].id) {
              qq = qq + result[j].quantity
              rr = rr + result[j].count

            }

          }
          var elms3 = document.querySelectorAll('[id="food_qty_' + resArr[i].id + 'r"]');
          for (var k = 0; k < elms3.length; k++) {
            if (qq > rr) {
              elms3[k].value = qq;
            } else {
              elms3[k].value = rr;
            }
          }
        }



        cart.forEach(function (item, i) {

          if (document.querySelectorAll('[id="food_control_' + item.id + 'r"]')) {

            var elms = document.querySelectorAll('[id="food_control_' + item.id + 'r"]');

            for (var i = 0; i < elms.length; i++) {
              elms[i].style.display = 'block';
            }
            var elms2 = document.querySelectorAll('[id="food_add_' + item.id + 'r"]');
            for (var i = 0; i < elms2.length; i++) {
              elms2[i].style.display = 'none';
            }

            // var elms3 = document.querySelectorAll('[id="food_qty_' + item.id+'r"]');
            // for(var i = 0; i < elms3.length; i++) {
            //   elms3[i].value=item.quantity;
            // }
            if (document.getElementById("card" + item.id)) {
              var element = document.getElementById("card" + item.id);
              element.classList.add("incart");
            }

          }

          total_price += parseFloat((item.quantity * (item.price + item.addonsPrice)).toFixed(2));
          total_item++;
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
          var delivery_charge_calc = this.calc_delivery_charge(tot_amt);

        } else {
          var delivery_charge_calc = 0;
          var tips = 0;

        }
        var offer_discount = 0;
        var offer_code = 0;

        if (localStorage.getItem("discount_value")) {

          offer_discount = parseFloat(localStorage.getItem("discount_value"))
          offer_code = localStorage.getItem("coupon")

        } else {
          offer_discount = 0
          offer_code = ""
        }


        total_price = tot_amt === 0 ? tot_amt : (tot_amt - parseFloat(offer_discount) + restaurant_packaging_charge + delivery_charge_calc).toFixed(2);
        localStorage.setItem('tot_amt', total_price);
        // var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':0,'bill':total_price};

        if (total_price < parseFloat(wallet)) {
          wallet = total_price;
          total_price = 0;

        }
        else {
          total_price = total_price - wallet;
        }

        var bill = { 'item_total': tot_amt, 'packaging_charge': restaurant_packaging_charge, 'delivery_charge': delivery_charge_calc, 'discount': offer_discount, 'bill': total_price, 'tips': tips, 'dc': dc, 'tax': tot_tax, 'offer_code': offer_code, 'wallet': wallet };
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

        if (deliver_charge_mode == 1) {
          if (
            total_price >= each_delivery_charge.Minimum &&
            (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum)
          ) {
            delivery_charge_calc = parseFloat(each_delivery_charge.Value);
            delivery_charge_type = each_delivery_charge.Type;
          }
        }
        if (deliver_charge_mode == 2) {

          var dc = deliver_distance;
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
          if (dckm_rate) {
            var delivery_charge_calc = parseFloat(delivery_charge_calc3) + parseFloat(dckm_rate);
          } else {
            var delivery_charge_calc = parseFloat(delivery_charge_calc3);
          }

          dc_total += delivery_charge_calc;
        }

      }


      if (deliver_charge_mode == 1 && delivery_charge_type == 2) {
        delivery_charge_calc = parseFloat((total_price / 100) * delivery_charge_calc);
      }

      delivery_charge_calc = parseFloat(parseFloat(delivery_charge_calc).toFixed(2));
      return (delivery_charge_calc);
    },
    check_cart(selected) {
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
      let restaurant = selected.restaurant,
        cart = JSON.parse(localStorage.getItem("cart"));

      if (cart.length && cart[0].restaurant != restaurant) {

        this.$swal({
          title: 'Are you sure?',
          text: "The previously selected items will be deleted!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1',
          },
          buttonsStyling: false,
        }).then(result => {
          if (result.value) {
            cart = [];
            localStorage.setItem("cart", JSON.stringify(cart));
            store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', store.state['deliware_cart'].cartItemsCount - 1)
            this.addCustomizedProduct(selected)
          }
        })
      } else {
        this.addCustomizedProduct(selected)
      }
    },
    addCustomizedProduct(selected) {
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");

      let id = selected.id,
        restaurant = selected.restaurant,
        isveg = selected.isveg,
        restaurant_name = selected.restaurant_name,
        restaurant_address = selected.restaurant_address,
        restaurant_image = selected.restaurant_image,
        taxperc = selected.taxperc,
        name = selected.name,
        food_image = selected.food_image,
        cart = JSON.parse(localStorage.getItem("cart"));
      let price = parseFloat(selected.price);

      let selectedSize = undefined;
      let selectedSizeName = undefined;
      let selectedSizePrice = '';
      if (document.querySelector('input[name="sizesOfItem_' + id + '"]:checked')) {
        selectedSize = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').value;
        selectedSizeName = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').getAttribute('data-name');
        selectedSizePrice = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').getAttribute('data-price');
      }

      selectedSize = selectedSize == undefined ? '' : selectedSize;

      selectedSizePrice = isNaN(selectedSizePrice) ? 0 : selectedSizePrice;
      if (selectedSizePrice > 0) {
        price = selectedSizePrice;
      }
      let selectedAddons = [];
      let selectedAddonsName = [];
      let selectedAddonsPrice = 0;
      let selectedAddonsqty = [];
      let selectedGroups = [];


      if (selected.food_quantity.length) {
        for (var i = 0; i < selected.food_quantity.length; i++) {

          if (selected.food_quantity[i].id == selectedSize) {
            if (selected.food_quantity[i].groups) {
              for (var j = 0; j < selected.food_quantity[i].groups.length; j++) {

                if (selected.food_quantity[i].groups[j].allow_multiple == 1) {
                  if (selected.food_quantity[i].groups[j].mandatory == 1 && document.getElementById("hidden_" + selected.food_quantity[i].groups[j].id).value < selected.food_quantity[i].groups[j].min) {
                    this.alert_text = "Minimum " + selected.food_quantity[i].groups[j].min + " Items has to be Selected in " + selected.food_quantity[i].groups[j].name
                    this.showDismissibleAlert = true
                    return false
                  }

                  for (var k = 0; k < selected.food_quantity[i].groups[j].group_addons.length; k++) {

                    var added = document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).value;
                    if (added > 0) {

                      selectedAddons.push(document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).getAttribute('data-value'));

                      selectedAddonsName.push(document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).getAttribute('data-name'));

                      selectedAddonsPrice += (parseFloat(document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).getAttribute('data-price'))) * document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).value;

                      selectedAddonsqty.push(document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).value);



                      let ob = {
                        'group_id': selected.food_quantity[i].groups[j].id,
                        'add_ons': document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).getAttribute('data-value'),
                        'quantity': document.getElementById("size_qty_" + selected.food_quantity[i].groups[j].id + "_" + selected.food_quantity[i].groups[j].group_addons[k].id).value

                      }
                      selectedGroups.push(ob);
                    }


                  }
                } else {
                  if (selected.food_quantity[i].groups[j].mandatory == 1 && document.querySelectorAll('input[name="faddon' + selected.food_quantity[i].groups[j].id + '"]:checked').length < selected.food_quantity[i].groups[j].min) {
                    this.alert_text = "Minimum " + selected.food_quantity[i].groups[j].min + " Items has to be Selected in " + selected.food_quantity[i].groups[j].name
                    this.showDismissibleAlert = true
                    return false
                  }
                  document.querySelectorAll('input[name="faddon' + selected.food_quantity[i].groups[j].id + '"]:checked').forEach(function (index, value) {

                    selectedAddons.push(index.value);
                    selectedAddonsName.push(index.getAttribute('data-name'));
                    selectedAddonsPrice += parseFloat(index.getAttribute('data-price'));
                    selectedAddonsqty.push(1);

                    let ob = {
                      'group_id': selected.food_quantity[i].groups[j].id,
                      'add_ons': index.value,
                      'quantity': 1
                    }
                    selectedGroups.push(ob);

                  });
                }

              }

            }

            if (selected.food_quantity[i].add_ons) {


              document.querySelectorAll('input[name="saddonsOfItem_' + selected.food_quantity[i].id + '"]:checked').forEach(function (index, value) {

                selectedAddons.push(index.value);
                selectedAddonsName.push(index.getAttribute('data-name'));
                selectedAddonsPrice += parseFloat(index.getAttribute('data-price'));
                selectedAddonsqty.push(1);



              });

            }

          }
        }
      }
      if (selected.groups && selected.groups.length) {
        for (var i = 0; i < selected.groups.length; i++) {
          if (selected.groups[i].allow_multiple == 1) {
            if (selected.groups[i].mandatory == 1 && document.getElementById("hidden_" + selected.groups[i].id).value < selected.groups[i].min) {
              this.alert_text = "Minimum " + selected.groups[i].min + " Items has to be Selected in " + selected.groups[i].name
              this.showDismissibleAlert = true
              return false
            }

            for (var k = 0; k < selected.groups[i].group_addons.length; k++) {

              var added = document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).value;
              if (added > 0) {

                selectedAddons.push(document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).getAttribute('data-value'));

                selectedAddonsName.push(document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).getAttribute('data-name'));

                selectedAddonsPrice += (parseFloat(document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).getAttribute('data-price'))) * document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).value;

                selectedAddonsqty.push(document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).value);


                let ob = {
                  'group_id': selected.groups[i].id,
                  'add_ons': document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).getAttribute('data-value'),
                  'quantity': document.getElementById("size_qty_" + selected.groups[i].id + "_" + selected.groups[i].group_addons[k].id).value

                }
                selectedGroups.push(ob);
              }


            }
          } else {
            if (selected.groups[i].mandatory == 1 && document.querySelectorAll('input[name="faddon' + selected.groups[i].id + '"]:checked').length < selected.groups[i].min) {
              this.alert_text = "Minimum " + selected.groups[i].min + " Items has to be Selected in " + selected.groups[i].name
              this.showDismissibleAlert = true
              return false
            }

            document.querySelectorAll('input[name="faddon' + selected.groups[i].id + '"]:checked').forEach(function (index, value) {

              selectedAddons.push(index.value);
              selectedAddonsName.push(index.getAttribute('data-name'));
              selectedAddonsPrice += parseFloat(index.getAttribute('data-price'));
              selectedAddonsqty.push(1);


              let ob = {
                'group_id': selected.groups[i].id,
                'add_ons': index.value,
                'quantity': 1
              }
              selectedGroups.push(ob);
            });
          }



        }
      }

      if (selected.add_ons && selected.add_ons.length) {
        document.querySelectorAll('input[name="addonsOfItem_' + selected.id + '"]:checked').forEach(function (index, value) {

          selectedAddons.push(index.value);
          selectedAddonsName.push(index.getAttribute('data-name'));
          selectedAddonsPrice += parseFloat(index.getAttribute('data-price'));
          selectedAddonsqty.push(1);

        });

      }



      let to_display = [];
      if (selectedAddonsName && selectedAddonsName.length) {
        let to = '';

        for (var i = 0; i < selectedAddonsName.length; i++) {
          to = selectedAddonsName[i] + ' x ' + selectedAddonsqty[i]
          to_display.push(to);

        }

      }
      let selectedAddonsString = selectedAddons.join(',');
      selectedAddonsName = selectedAddonsName.join(', ');
      selectedAddonsqty = selectedAddonsqty.join(',');
      to_display = to_display.join(',');



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
              if (cart[i].addonsName === selectedAddonsName && cart[i].id === id && cart[i].to_display === to_display) {
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
              addonsqty: selectedAddonsqty,
              selectedGroups: selectedGroups,
              to_display: to_display,
              sizeName: selectedSizeName,
              restaurant_name: restaurant_name,
              restaurant_address: restaurant_address,
              restaurant_image: restaurant_image,
              sizePrice: selectedSizePrice,
              taxperc: taxperc,
              food_image: food_image,
              pharmacy_id: this.pharmacy_id,
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
                if (cart[i].addonsName === selectedAddonsName && cart[i].sizeName === selectedSizeName && cart[i].id === id && cart[i].to_display === to_display) {
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
                    addonsqty: selectedAddonsqty,
                    selectedGroups: selectedGroups,
                    to_display: to_display,
                    sizeName: selectedSizeName,
                    restaurant_name: restaurant_name,
                    restaurant_address: restaurant_address,
                    restaurant_image: restaurant_image,
                    sizePrice: selectedSizePrice,
                    taxperc: taxperc,
                    food_image: food_image,
                    pharmacy_id: this.pharmacy_id,
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
                addonsqty: selectedAddonsqty,
                selectedGroups: selectedGroups,
                to_display: to_display,
                sizeName: selectedSizeName,
                restaurant_name: restaurant_name,
                restaurant_address: restaurant_address,
                restaurant_image: restaurant_image,
                sizePrice: selectedSizePrice,
                taxperc: taxperc,
                food_image: food_image,
                pharmacy_id: this.pharmacy_id,
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
                    addonsqty: selectedAddonsqty,
                    selectedGroups: selectedGroups,
                    to_display: to_display,
                    sizeName: selectedSizeName,
                    restaurant_name: restaurant_name,
                    restaurant_address: restaurant_address,
                    restaurant_image: restaurant_image,
                    sizePrice: selectedSizePrice,
                    taxperc: taxperc,
                    food_image: food_image,
                    pharmacy_id: this.pharmacy_id,
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
                addonsqty: selectedAddonsqty,
                selectedGroups: selectedGroups,
                to_display: to_display,
                sizeName: selectedSizeName,
                restaurant_name: restaurant_name,
                restaurant_address: restaurant_address,
                restaurant_image: restaurant_image,
                sizePrice: selectedSizePrice,
                taxperc: taxperc,
                food_image: food_image,
                pharmacy_id: this.pharmacy_id,
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
          addonsqty: selectedAddonsqty,
          selectedGroups: selectedGroups,
          to_display: to_display,
          sizeName: selectedSizeName,
          restaurant_name: restaurant_name,
          restaurant_address: restaurant_address,
          restaurant_image: restaurant_image,
          sizePrice: selectedSizePrice,
          taxperc: taxperc,
          food_image: food_image,
          pharmacy_id: this.pharmacy_id,
        })
      }
      localStorage.setItem("cart", JSON.stringify(cart));
      let cartn = JSON.parse(localStorage.getItem("cart"));

      store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
      store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
      this.$refs['addon-modal'].hide()
      this.$refs['image-modal'].hide()
      this.s_fq = ''
      this.loadcart()
    },
    removefromcart: function (price, item_tax, name, food_id, is_veg, add_ons, food_quantity, food_image) {
      localStorage.setItem("cart", localStorage.getItem("cart") || "[]");
      this.itemid = food_id
      let id = food_id,

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

          for (var i = 0; i < elms.length; i++) {
            elms[i].style.display = 'none';
          }
          var elms2 = document.querySelectorAll('[id="food_add_' + id + 'r"]');

          for (var i = 0; i < elms2.length; i++) {
            elms2[i].style.display = 'inline-block';
          }

          // document.getElementById('food_control_' + id +'r').style.display = 'none';
          // document.getElementById('food_add_' + id +'r').style.display = 'inline-block';
        }
        if (document.getElementById("card" + id)) {
          var element = document.getElementById("card" + id);
          element.classList.remove("incart");
        }

      }

      localStorage.setItem("cart", JSON.stringify(cart));

      let cartn = JSON.parse(localStorage.getItem("cart"));

      store.commit('deliware_cart/UPDATE_CART_ITEMS_COUNT', cartn.length)
      store.commit('deliware_cart/UPDATE_CART_ITEMS', cartn)
      localStorage.removeItem("discount_value");
      localStorage.removeItem("coupon");
      this.coupon_applied = 0
      this.loadcart()
    },


  },


  created() {
    document.addEventListener('scroll', this.handleScroll);

    this.$http.get('/get_food_list_by_menu/' + this.$route.params.id)
      .then(res => {

        this.rows_detail = res.data.items
        this.business_id = res.data.business_id
        this.pharmacy_id = res.data.pharmacy_id
        this.rows_detail2 = res.data.items
        this.isLoading = false
        localStorage.setItem("pharmacy_id", this.pharmacy_id);
        this.loadcart()
      })
    if (localStorage.getItem("webuserData")) {
      let loggeduser = JSON.parse(localStorage.getItem("webuserData"));

      var user_id = loggeduser.id
    } else {
      var user_id = 0
    }

    this.$http.get('/single_restaurant/' + this.$route.params.id + '/' + user_id)
      .then(res => {

        this.rows = res.data.restaurants
        if (this.rows[0].is_open != 1) {
          this.isopen = false
        }
        this.promocodes = res.data.promocodes
        this.address = 'https://maps.google.com/maps?t=&z=9&ie=UTF8&iwloc=&output=embed&q=' + this.rows[0].address

        if (this.rows[0].is_favourite == 1) {
          this.isfav = true
        }
        this.isLoading = false
        localStorage.setItem("WALLET", res.data.user.wallet_amount);
        this.loadcart()

      })

    this.$http.get('/top_selling/' + this.$route.params.id)
      .then(res => {
        this.top_selling = res.data.items
        this.ttitle = res.data.title
        this.isLoading = false

      })

  },

  computed: {

    statusVariant() {
      const statusColor = {

        1: 'success',

        0: 'danger',



      }

      return status => statusColor[status]
    },

    direction() {
      if (store.state.appConfig.isRTL) {
        this.dir = true
        return this.dir
      }
      this.dir = false
      return this.dir
    },
    tConvert(time) {

      time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

      if (time.length > 1) {
        time = time.slice(1);
        time[5] = +time[0] < 12 ? 'AM' : 'PM';
        time[0] = +time[0] % 12 || 12;
      }
      return time.join('');
    }
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/pages/page-faq.scss';
</style>
<style >
.img_layout {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}

.body_class {
  padding: 0rem 0rem !important;
}

.sticky-div {
  position: sticky;
  top: 0px;
  padding: 10px 0px;
  background-color: #ffffff;
  z-index: 9999;
}

.card_img {
  width: 100%;
}
</style>
<style scoped>
#gmap {
  height: 250px;
  width: 100%;
}

#gmap2 {
  height: 175px;
  width: 100%;
}

.nav-link {
  color: black;
}

.text-yellow {
  color: #FFBD00;
}

.text-white {
  color: white;
}

.nav-tabs {
  display: none !important;
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


.rounded3 {
  cursor: pointer;
}

.categories-radio-group,
.brands-radio-group,
.price-range-defined-radio-group {
  ::v-deep>.custom-control {
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

.sidebar-shop {
  cursor: pointer;
}

.bor {
  border-radius: 10px;
}

.side {
  padding: 0px 30px 0px 0px;
  line-height: 3;
  cursor: pointer;
}

.s-border {

  border-right: 2px solid #e6e6e6;
  min-height: 400px;
}

.active {
  background-color: #F4FFFD;
  border-right: 4px solid #117439;
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

.bg-color {
  background-color: #000 !important;
}

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

.pad0 .card-body {
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
  -webkit-line-clamp: 2;
  /* number of lines to show */
  line-clamp: 2;
  -webkit-box-orient: vertical;
  min-height: 36px
}

.textp2 {
  color: #e6e6e6;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  /* number of lines to show */
  line-clamp: 2;
  -webkit-box-orient: vertical;
}

.btn-icon {
  padding: 5px;
}

.btn-scroll-to-top {
  background-color: #FFBD00 !important;
  border-radius: 100px;
  border: 1px solid #FFBD00 !important;
  color: #000;
  display: inline-block;
  padding: 20px;

}

.swiper-button-prev,
.swiper-button-next {
  color: #117439;
}
</style >
<style scoped>
.swiper-button-next,
.swiper-container-rtl .swiper-button-prev {
  right: 10px;
  left: auto;
  /* top: -30px; 
    margin-top: -68px;*/
  /* z-index: 999999; */
  background-color: #f4fffd90;
  border-radius: 10px;
  border: 2px solid #117439;
  top: 45%;
}

.swiper-button-prev,
.swiper-container-rtl .swiper-button-next {
  left: 10px;
  right: auto;
  background-color: #f4fffd90;
  border-radius: 10px;
  border: 2px solid #117439;
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
</style>

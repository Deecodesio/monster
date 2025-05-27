<template>
    <b-card
      v-if="data"
      class="card-transaction"
     
    >
      <!-- <b-card-header  >
        <b-card-title >Recent Orders</b-card-title>
  
    
      </b-card-header> -->
      <h4>{{$t('Top')}} {{$t('Stores')}}</h4>
      <b-card-body>
        <div
          v-for="transaction in data"
          :key="transaction.restaurant_id"
          class="transaction-item"
        >
          <b-media no-body>
            <b-media-aside>
             
               
                <b-img-lazy id="user-img" :src="transaction.avatar">

                </b-img-lazy>
            
            </b-media-aside>
            <b-media-body>
              <h6 class="transaction-title">
                {{ transaction.restaurant_name }}
              </h6>
              <small>Orders : {{ $t(transaction.count) }}</small>
            </b-media-body>
          </b-media>
          <div
            class="font-weight-bolder text-success"
            
          >
            {{ transaction.earnings }}
          </div>
        </div>
      </b-card-body>
    </b-card>
  </template>
  
  <script>
  import {
    BCard, BCardHeader, BCardTitle, BCardBody, BMediaBody, BMedia, BMediaAside, BAvatar, BDropdown, BDropdownItem,BImgLazy
  } from 'bootstrap-vue'
  
  export default {
    components: {
      BCard,
      BCardHeader,
      BCardTitle,
      BCardBody,
      BMediaBody,
      BMedia,
      BMediaAside,
      BAvatar,
      BDropdown,
      BDropdownItem,
      BImgLazy
    },
    props: {
      data: {
        type: Array,
        default: () => [],
      },
    },
  }
  </script>
  <style lang="scss" >


  #user-img {
  
    width: 36px;
  }
  
  
  </style>
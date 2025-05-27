<template>
  <div class="container pt-5 " style="
    margin-top: 155px;
">
    <b-tabs vertical content-class="col-12 col-md-9 mt-1 mt-md-0 " pills nav-wrapper-class="col-md-3 col-12"
      nav-class="nav-left">

      <!-- general tab -->
      <b-tab active>

        <!-- title -->
        <template #title>
          <feather-icon icon="UserIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("General") }}</span>
        </template>

        <viewprofile v-if="options.data" :general-data="options.data" />
      </b-tab>
      <!--/ general tab -->

      <!-- change password tab -->
      <b-tab>

        <!-- title -->
        <template #title>
          <feather-icon icon="ArchiveIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Current") }} {{ $t("Orders") }}</span>
        </template>

        <pastorders :information-data="options.cur_orders" :view_order_page="view_order_page" :order_status="order_status"
          :food_details="food_details" :req_details="req_details" />
      </b-tab>
      <!--/ change password tab -->

      <!-- info -->
      <b-tab>

        <!-- title -->
        <template #title>
          <feather-icon icon="ClockIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Past") }} {{ $t("Orders") }}</span>
        </template>

        <pastorders :information-data="options.past_orders" :view_order_page="view_order_page"
          :order_status="order_status" :food_details="food_details" :req_details="req_details" />
      </b-tab>

      <!-- social links -->
      <b-tab>

        <!-- title -->
        <template #title>
          <feather-icon icon="DollarSignIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Wallet") }}</span>
        </template>

        <wallet v-if="options.data" :social-data="options.data" />
      </b-tab>

      <b-tab>

        <!-- title -->
        <template #title>
          <feather-icon icon="BriefcaseIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Address") }}</span>
        </template>

        <useraddress v-if="options.address" :social-data="options.address" />

      </b-tab>

      <b-tab>

        <!-- title -->
        <template #title>
          <feather-icon icon="HeartIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t("Favorites") }}</span>
        </template>

        <favorites v-if="options.favourite_list" :social-data="options.favourite_list" />

      </b-tab>


    </b-tabs>
    <b-modal id="modal-center" centered title="Cancel Order" ok-only ok-title="Accept">
      <b-card-text>

      </b-card-text>
    </b-modal>
  </div>
</template>
  
<script>
import { BTabs, BTab } from 'bootstrap-vue'


import wallet from './wallet.vue'
import viewprofile from './viewprofile.vue'
import pastorders from './pastorders.vue'
import useraddress from './useraddress.vue'
import favorites from './favorites.vue'



export default {
  components: {
    BTabs,
    BTab,


    pastorders,
    viewprofile,
    wallet,
    useraddress,
    favorites,
  },
  data() {
    return {
      options: {},
      userData: JSON.parse(localStorage.getItem('webuserData')),
      req_details: [],
      food_details: [],
      order_status: [],
      view_order_page: false,

    }
  },
  created() {
    if (localStorage.getItem("webuserData")) {
      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');
      this.loggedin = true
      this.$http.get('/get_profile/' + this.userData.id + '/' + lat + '/' + lng)
        .then(res => {

          this.options = res.data

        })
    } else {
      this.$router.push({ name: "/" })
    }

  },
}
</script>
<style scoped>
.pt-5 {
  padding-top: 5rem !important;
}
</style>
  
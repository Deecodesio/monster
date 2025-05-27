<template>
  <div style="display: -webkit-inline-box;">
    <!-- <feather-icon icon="ArrowLeftIcon" size="25" v-if="this.$route.name !== 'home'" @click="goback()"
      style="margin-right: 10px;    margin-top: 20px;" /> -->
    <!-- <mobile_header /> -->
    <b-row @click="showModal" class="location ">
      <b-col cols="12" style="margin-top:10px;">
        <h4>Delivering To</h4>
        <p id="demo" class="wrap" style="font-size: 10px; float:left;width: 130px;">{{ address }}</p><b-img
          src="/dropdown.svg" fluid alt="Scan" style="width:10px; opacity: .5;  float:left;" width="10" height="16" />
      </b-col>
      <b-modal class="text-r" id="modal-address" title="Login" ok-only ok-title="Login" cancel-title="Close" hide-footer
        hide-header size="lg" centered ref="my-modal" aria-label="Get address from user">
        <b-row>
          <b-col lg="12" md="6" sm="12" class=''>
            <h4>Change Location</h4>
          </b-col>
        </b-row>
        <b-row>
          <b-col lg="5" md="6" sm="12" class=''>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg" @click="initMap2">
              Detect My Location
            </b-button>
          </b-col>
          <b-col lg="1" md="1" sm="1">
            <h6 class="f-size-0 pt-1 text-center">OR</h6>
          </b-col>
          <b-col lg="6" md="5" sm="12">
            <b-form-input class="ht-1" v-model="user.address" placeholder="Enter Address" id="searchMapInput"
              @input="initMap" />



            <input type="hidden" id="latitude" name="latitude" value="12.9813658">
            <input type="hidden" id="longitude" name="longitude" value="80.2199712">


            <!-- <div class="text-center pt-1 pb-1"><strong>or</strong></div> -->


          </b-col>
          <b-col lg="12"></b-col>
          <b-col lg="12">
            <b-alert v-model="showDismissibleAlert" v-height-fade.appear variant="danger" dismissible class="mb-0">
              <div class="alert-body">
                {{ alert_text }}
              </div>
            </b-alert>
          </b-col>
        </b-row>

      </b-modal>
      <b-modal id="modal-address" title="Login" ok-only ok-title="Login" cancel-title="Close" hide-footer hide-header
        size="lg" centered ref="my-modal-fresh" no-close-on-backdrop aria-label="Get address from user">
        <b-row>
          <b-col lg="12" md="6" sm="12" class=''>
            <h4>Welcome to {{ app_name }}</h4>
            <h6 class="text-muted">
              Please provide your location
            </h6>
          </b-col>
        </b-row>
        <b-row>


          <b-col lg="5" md="6" sm="12" class=''>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
              class="mr-1 btn-block btn-lg .col-form-label-lg" @click="initMap2">
              Detect My Location
            </b-button>
          </b-col>
          <b-col lg="1" md="1" sm="1">
            <h6 class="f-size-0 pt-1 text-center">OR</h6>
          </b-col>
          <b-col lg="6" md="5" sm="12">
            <b-form-input class="ht-1" v-model="user.address" placeholder="Enter Address" id="searchMapInput"
              @input="initMap" type="number" />



            <input type="hidden" id="latitude" name="latitude" value="12.9813658">
            <input type="hidden" id="longitude" name="longitude" value="80.2199712">


            <!-- <div class="text-center pt-1 pb-1"><strong>or</strong></div> -->


          </b-col>
          <b-col lg="12"></b-col>
          <b-col lg="12">
            <b-alert v-model="showDismissibleAlert" v-height-fade.appear variant="danger" dismissible class="mb-0">
              <div class="alert-body">
                {{ alert_text }}
              </div>
            </b-alert>
          </b-col>
        </b-row>

      </b-modal>
    </b-row>
  </div>
</template>

<script>
import {
  BNavbarNav, BNavItem, BTooltip, BNavItemDropdown, BFormInput, BDropdownItem, BRow, BCol, BImg, BModal, VBModal, BButton, BFormGroup, BForm, BAlert
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import Ripple from 'vue-ripple-directive'
import { ref, watch } from '@vue/composition-api'
import { heightFade } from '@core/directives/animations'
import mobile_header from '@@@/views/user/static_pages/mobile_header.vue'

export default {
  components: {
    BNavbarNav, BNavItem, BTooltip, BNavItemDropdown, BFormInput, VuePerfectScrollbar, BDropdownItem, BImg, BCol, BRow, BModal, VBModal, BButton, BFormGroup, BForm, BAlert, mobile_header
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
    'height-fade': heightFade,
  },
  data() {
    return {
      rows: '',
      address: '',
      user: {},
      app_name: '',

      showDismissibleAlert: false,
      alert_text: '',
      loc_text: '',
      to_hide: false
    }
  },
  created() {
    var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');
    this.$http.get('/get_address/' + lat + '/' + lng)
      .then(res => {
        console.log(res);
        this.address = res.data.data
        localStorage.setItem('address', this.address);
      })

    this.$http.get('/get_site_logo')
      .then(res => {
        console.log(res)
        this.app_name = res.data.app_name

      })

  },
  mounted() {
    this.checklocation()
    // this.showmodal()



  },




  methods: {
    goback() {
      return this.$router.go(-1);
    },
    checklocation() {
      if (this.$route.name != "master" && this.$route.name != "faq" && this.$route.name != "about_us" && this.$route.name != "demo") {
        if (localStorage.getItem('latitude') == null || localStorage.getItem('longitude') == null) {

          this.$refs['my-modal-fresh'].show()
        }
      }
    },
    showModal() {


      this.$refs['my-modal'].show()

    },
    closeModal() {
      alert("jk");
    },
    initMap() {
      var input = document.getElementById('searchMapInput');
      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        var address = '';
        if (place.address_components) {
          address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
          ].join(' ');
        }
        localStorage.setItem('latitude', place.geometry.location.lat())
        localStorage.setItem('longitude', place.geometry.location.lng())
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
        geocoder.geocode({
          'latLng': latlng
        },
          function (results, status) {
            if (results[0]) {
              var add = results[0].formatted_address;
              localStorage.setItem('address', add);
            } else {
              alert("address not found");
            }
          }
        );
        var add = localStorage.getItem('address')
        this.rows = add;
        location.reload()
      });
    },

    initMap2() {
      if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(this.showPosition, this.showError);
      }

    },
    showPosition(position) {

      localStorage.setItem('latitude', position.coords.latitude)
      localStorage.setItem('longitude', position.coords.longitude)
      this.displayLocate(position.coords.latitude, position.coords.longitude)
    },

    showError(error) {
      this.to_hide = false

      switch (error.code) {

        case error.PERMISSION_DENIED:
          console.log("User denied the request for Geolocation.")
          this.showDismissibleAlert = true
          this.alert_text = "We do not have permission to determine your location. Kindly Enable Location or enter manually."


          break;
        case error.POSITION_UNAVAILABLE:
          console.log("Location information is unavailable.")
          this.alert_text = "Location information is unavailable.Please enter manually."
          this.showDismissibleAlert = true


          break;
        case error.TIMEOUT:
          console.log("The request to get user location timed out.")
          this.alert_text = "The request to get Your location timed out..Please enter manually."
          this.showDismissibleAlert = true

          break;
        case error.UNKNOWN_ERROR:
          console.log("An unknown error occurred.")
          this.alert_text = "An unknown error occurred.Please enter manually."
          this.showDismissibleAlert = true

          break;
      }
    },
    displayLocate(latitude, longitude) {
      var geocoder;
      geocoder = new google.maps.Geocoder();
      var latlng = new google.maps.LatLng(latitude, longitude);
      geocoder.geocode({
        'latLng': latlng
      },
        function (results, status) {
          if (results[0]) {
            var add = results[0].formatted_address;


            localStorage.setItem('address', add)


          } else {
            alert("address not found");
          }

        }
      );
      this.$refs['my-modal'].hide()
      location.reload()
    },
  },
}
</script>
<style lang="scss" scoped>
.ht-1 {
  min-height: 40px;
}
</style>

<style lang="scss" scoped>
@import '~@resources/scss/base/bootstrap-extended/include';

ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

p {
  margin: 0;
  max-width: 180px;
}

.nav-bookmar-content-overlay {
  position: fixed;
  opacity: 0;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 0, 0, 0.5);
  -webkit-transition: all 0.7s;
  transition: all 0.7s;
  z-index: -1;

  &:not(.show) {
    pointer-events: none;
  }

  &.show {
    cursor: pointer;
    z-index: 10;
    opacity: 1;
  }

  .text-r {
    text-align: right;
  }
}

.wrap {
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}
</style>
<style lang="scss">
.pac-container {
  position: absolute !important;
  z-index: 9999 !important;
}

.modal-dialog-centered2 {
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: center;
  align-items: start;
  margin-left: 200px !important;
  // margin-top: 90px !important;
}

.location {
  cursor: pointer;
}
</style>
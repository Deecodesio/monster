<template>
  <b-card>
    <b-form @submit.prevent="area_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('area')">
            <b-form-input id="searchMadminnput" :placeholder="$t('area')" v-model="areas.area" />
            <br />
            <input type="hidden" id="latitude" name="latitude" value="12.9813658">
            <input type="hidden" id="longitude" name="longitude" value="80.2199712">

          </b-form-group>
        </b-col>


        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select v-model="areas.status" :options="options_status" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />



          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group>

            <div id="gmap" hidden="hidden"></div>
          </b-form-group>


        </b-col>

        <!-- submit and reset -->
        <b-col>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
            {{ $t('save') }}
          </b-button>

        </b-col>



      </b-row>

    </b-form>


  </b-card>
</template>

<script>
// import BCardCode from '@core/components/b-card-code'
import {
  BCard, BRow, BCol, BFormGroup, BFormSelect, BFormInput, BFormCheckbox, BForm, BButton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    // BCardCode,
    BCard,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    vSelect,
  },
  directives: {
    Ripple,
  },
  mounted() {
    this.initMap()
  },
  data() {
    return {
      areas: {},
      country: [],
      state: [],
      selected_status: null,
      options_status: [
        { value: "1", text: this.$t('active') },
        { value: "2", text: this.$t('inactive') },
      ],
    }
  },
  created() {
    if (this.$route.params.id) {
      this.$http.get('/admin/area_edit/' + this.$route.params.id)
        .then(res => {

          this.areas = res.data;
        })
    }

  },
  methods: {
    area_add() {
      var lati = document.getElementById('latitude').value;
      var long = document.getElementById('longitude').value;
      var area = document.getElementById('searchMadminnput').value;
      let data = new FormData();
      data.append('id', this.$route.params.id);
      data.append('area', area);
      data.append('status', this.areas.status);
      data.append('latitude', lati);
      data.append('longitude', long);
      this.$http
        .post("/admin/update_area_list", data)
        .then(
          response => {
            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
              this.$router.push({ name: "city_list" })
              //  this.$router.push({ name: "view_area" })
            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response, icon, variant) {

      this.$toast({
        component: ToastificationContent,
        position: 'bottom-right',
        props: {
          title: this.$t(response.data.message),
          icon: icon,
          variant: variant,
        },
      })
    },

    initMap() {

      const element = document.getElementById("gmap");
      const mapoptions = {
        zoom: 13,
        center: new google.maps.LatLng(12.9813658, 80.2199712)
      };

      const map = new google.maps.Map(element, mapoptions);
      var lati = document.getElementById('latitude').value;
      var long = document.getElementById('longitude').value;
      var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
      var geocoder = new google.maps.Geocoder();
      var input = document.getElementById('searchMadminnput');
      // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);
      var infowindow = new google.maps.InfoWindow();
      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true

      });
      autocomplete.addListener('place_changed', function () {
        //infowindow.close();
        marker.setVisible(true);
        var place = autocomplete.getPlace();

        /* If the place has a geometry, then present it on a map. */
        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          map.setCenter(place.geometry.location);
          map.setZoom(17);
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
          address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
          ].join(' ');
        }

        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);

        /* Location details */
      });
      google.maps.event.addListener(marker, 'dragend',
        function (marker) {
          var latLng = marker.latLng;
          var currentLatitude = latLng.lat();
          var currentLongitude = latLng.lng();

          geocoder.geocode({
            'latLng': latLng
          }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                document.getElementById('searchMadminnput').value = results[0].formatted_address;
                document.getElementById('latitude').value = currentLatitude;
                document.getElementById('longitude').value = currentLongitude;
                infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                infowindow.open(map, marker);
              }
            }
          });
        });
    },
  },
}
</script>
<style scoped>
#gmap {
  height: 400px;
  width: 100%;

}
</style>

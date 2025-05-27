<template>
  <div>
    <div v-if="!socialData.length" class="text-center"  style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
            <lottie-player
                src="/animations/location.json"
                background="#FFFDFD"
                speed="1"
                style="width: 300px; height: 300px"
                loop
                autoplay
            ></lottie-player>
            <h4 class="text-center">NO Address</h4>
            <P>You haven't added any Address</P>
    </div>
    <div>
    <b-card
    v-for="(data,index) in socialData"
            :key="index"
    >
   
                  <div class="media-list" >
                    <b-media vertical-align="top">
                      <template #aside >
                        <feather-icon
                            icon="BriefcaseIcon"
                            size="25"
                            class="mr-50"
                          />
                      </template>
                      <h4 class="media-heading">
                        Flat No : {{data.flat_no}}
                      </h4>
                      <b-card-text class="mb-0 textp">
                        {{data.address}}
                      </b-card-text>
                      <b-row class="pt-4">
              <b-col 
              
              cols="6">
              <b-button
                        v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                        variant="outline-primary"
                        @click="edit_address(data.id)"
                      >
                      {{$t("Edit")}}
                      </b-button>
                      <b-button
                        v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                        variant="outline-danger"
                        @click="deleteaddress(data.id)"
                      >
                      {{$t("Delete")}}
                      </b-button>
                      </b-col>
                      <b-col
              
              cols="6" class="text-center">
              
              </b-col>

            </b-row>
                    </b-media>
                  </div>
               
    </b-card>
    </div>
    <b-modal id="modal-address" title="Login" ok-only ok-title="Login" cancel-title="Close" hide-footer            hide-header            size="lg"            centered            ref="my-modal">
       
       <b-row>
           

           <b-col lg="6" md="6" sm="12">
               <div id="gmap"></div>
           </b-col>
           
           <b-col lg="6" md="5" sm="12">
                   <h4>{{$t("Enter")}} {{$t("Complete")}} {{$t("Address")}}</h4>
                   <p>{{$t("This allow us to find you easily and give you timely delivery experience")}}</p>
                   <validation-observer ref="simpleRules">
                   <b-form
                 
                   >
                   <b-row>
                   <b-col
                       md="12"
                       xl="12"
                       class="mb-1"
                       >
                       <b-form-group
                          
                       >
                       <validation-provider
                         #default="{ errors }"
                         rules="required"
                         name="Name"
                       >
                           <b-form-input
                           id="basicInput"
                           placeholder="Name"
                           v-model="address.name"
                           />
                           <small class="text-danger">{{ errors[0] }}</small>
           </validation-provider>
                       </b-form-group>
                   </b-col>
                   </b-row>
                   <b-row>
                   <b-col
                       md="6"
                       xl="6"
                       class="mb-1"
                       >
                       <b-form-group
                          
                       >
                       <validation-provider
                         #default="{ errors }"
                         rules="required"
                         name="Flat No"
                       >
                           <b-form-input
                           id="basicInput"
                           placeholder="Flat No"
                           v-model="address.flat_no"
                           />
                           <small class="text-danger">{{ errors[0] }}</small>
           </validation-provider>
                       </b-form-group>
                   </b-col>
                   <b-col
                       md="6"
                       xl="6"
                       class="mb-1"
                       >
                       <b-form-group
                          
                       >
                       <validation-provider
                         #default="{ errors }"
                         rules="required"
                         name="Landmark"
                       >
                           <b-form-input
                           id="basicInput"
                           placeholder="Landmark"
                           v-model="address.landmark"

                           />
                           <small class="text-danger">{{ errors[0] }}</small>
           </validation-provider>
                       </b-form-group>
                   </b-col>
                   </b-row>
                   <b-row>
                   <b-col
                       md="12"
                       xl="12"
                       class="mb-1"
                       >
                       <validation-provider
                         #default="{ errors }"
                         rules="required"
                         name="Address"
                       >
                       <b-form-input
                           class="ht-1"
                           v-model="address.address"
                           placeholder="Enter Address"
                           id="searchMapInput"
                          
                           
                           />
                           <small class="text-danger">{{ errors[0] }}</small>
           </validation-provider>
                           <input type="hidden" id="selectedadd" :value="address.address">
                   </b-col>
                   </b-row>
                       
                       <input type="hidden" id="latitude" name="latitude" :value=address.lat>
                       <input type="hidden" id="longitude" name="longitude" :value=address.lng>
                       
                       <b-row>
                       <label class="btn btn-outline-primary tiplabel tiphide tiphide f-size-0"  type="button" style="margin-right: 20px; margin-left: 10px; padding: 10px; "  > <input type="radio" class="checktips" hidden name="addresstype" value="1" checked>{{$t("Home")}}</label>

                       <label class="btn btn-outline-primary tiplabel tiphide  f-size-0"  type="button" style="margin-right: 20px; margin-left: 10px; padding: 10px; "  > <input type="radio" class="checktips" hidden name="addresstype" value="2">{{$t("Work")}}</label>

                       <label class="btn btn-outline-primary tiplabel tiphide  f-size-0"  type="button" style="margin-right: 20px; margin-left: 10px; padding: 10px; "  > <input type="radio" class="checktips" hidden name="addresstype" value="3">{{$t("Others")}}</label>
                       </b-row>
                   <b-col md="12"  class="pad0 pt-1">
                     
                           <b-button
                               style="height:50px;" class="f-size-2"
                               v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                               block
                               variant="primary"
                               type="submit"
                               @click.prevent="save_address"
                           >
                           {{$t("Save")}} {{$t("Address")}}
                           </b-button>
                      
                   </b-col>
               </b-form>
             </validation-observer>
           </b-col>
       </b-row>

   </b-modal>
  </div>
  </template>
  
  <script>
  import {
    BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BCardText, BLink, BAvatar,BMedia,
  } from 'bootstrap-vue'
  import Ripple from 'vue-ripple-directive'
  import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
  import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
  export default {
    components: {
      BButton,
      BForm,
      BFormGroup,
      BFormInput,
      BRow,
      BCol,
      BCard,
      BCardText,
      BLink,
      BAvatar,BMedia,
      ValidationProvider,
        ValidationObserver,
        required,
        confirmed,
        password,
        email,
        min,
        integer,
        url,
        alpha,
        between,
        digits,
        length,
        alphaDash,
    },
    directives: {
      Ripple,
    },
    props: {
      socialData: {
        type: Array,
        default: () => {},
      },
    },
    data() {
      return {
        address:{},
        localOptions: JSON.parse(JSON.stringify(this.socialData)),
      }
    },
    methods: {
      edit_address(id){
        this.$http.get('/get_uaddress/'+id)
          .then(res => {
            
              
                this.address = res.data.address

                this.address.id = id
                let lat = this.address.lat
                let lng = this.address.lng

              this.$refs['my-modal'].show()
              var timer = setInterval(function() {
                
             
            if(document.getElementById("gmap")){
             
                document.getElementById("selectedadd").value = localStorage.getItem('address')
            const element = document.getElementById("gmap");
                const mapoptions = {
                    zoom : 13,
                    center : new google.maps.LatLng(lat,lng)
                };
            const map = new google.maps.Map(element,mapoptions);  

            var lati = document.getElementById('latitude').value;
            var long = document.getElementById('longitude').value;

            var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
            var geocoder = new google.maps.Geocoder();

            var input = document.getElementById('searchMapInput');

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);
            var infowindow = new google.maps.InfoWindow();
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    draggable: true

                });
            autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();



            var address = '';
            if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
            }
           
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
            var geocoder = new google.maps.Geocoder();
    
            var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
            geocoder.geocode({
            'latLng': latlng
            },
            function(results, status) {
               
            if (results[0]) {
            var add = results[0].formatted_address;
            document.getElementById('selectedadd').value = results[0].formatted_address;
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();

            } else {
            alert("address not found");
            }

            }

            );

           
            });
             google.maps.event.addListener(marker, 'dragend',
            function(marker) {
                var latLng = marker.latLng;
                var currentLatitude = latLng.lat();
                var currentLongitude = latLng.lng();
                
                geocoder.geocode({
                    'latLng': latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            document.getElementById('searchMapInput').value = results[0].formatted_address;
                            document.getElementById('selectedadd').value = results[0].formatted_address;

                            document.getElementById('latitude').value = currentLatitude;
                            document.getElementById('longitude').value = currentLongitude;
                            infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                            infowindow.open(map, marker);
                        }
                    }
                });
            });
                clearInterval(timer);
            }
        }, 1000);
      })
      },

      save_address(){
        this.$refs.simpleRules.validate().then(success => {
        if (success) {
        

          var dc = document.querySelector('input[name="addresstype"]:checked').value;
          var sc = document.getElementById('selectedadd').value
          this.rows = sc
          var latitude = document.getElementById('latitude').value;
          var longitude = document.getElementById('longitude').value;
        let city = new FormData(); 
        city.append('address', sc);
        city.append('lat', latitude);
        city.append('lng', longitude);
        city.append('type', dc);
        city.append('landmark', this.address.landmark);
        city.append('flat_no', this.address.flat_no);
      
        city.append('address_id', this.address.id);




        this.$http
                .post("/edit_delivery_address", city)
                .then(
                    (response => {
                        if (response.data.status == true) {
                            
                          this.socialData = response.data.address_detail
                               this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: response.data.message,
                            icon: 'CoffeeIcon',
                            variant: 'success',
                          },
                        })
                        this.$refs['my-modal'].hide()
                        
                        } else {

                          this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: response.data.message,
                            icon: 'CoffeeIcon',
                            variant: 'failure',
                          },
                        })
                        }
                    
                      
                      } )                  
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
                    }
              })
      },
      resetForm() {
        this.localOptions = JSON.parse(JSON.stringify(this.socialData))
      },
      deleteaddress(id){
        this.$http.get('/delete_address/'+id)
          .then(res => {
            
             this.socialData = res.data.address_detail
            this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: res.data.message,
                            icon: 'UserIcon',
                            variant: 'success',
                          },
                        })

              })
      },
    },
  }
  </script>
<style lang="scss" scoped>
.pad0 .card-body{
  padding: 0rem !important;
}
#gmap {
    height: 400px;
    width: 100%;
  }
</style>
  
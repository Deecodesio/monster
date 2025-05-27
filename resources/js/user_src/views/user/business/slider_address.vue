<template>
    <div class="container">
      <div class="text-l">
        <h4>{{$t("My")}} {{$t("Address")}}</h4>

        <b-row @click="showModal" >

            <b-col cols="2" class="text-center"> 
                <i class="fa-solid fa-circle-plus"></i>
            </b-col>
            <b-col cols="8">
                <h4>{{$t("Add")}} {{$t("New")}} {{$t("Address")}}</h4>
                <p id="demo" class="hover">{{rows}}</p>
            
            </b-col> 
        </b-row>
        <div v-if="prop.length ">
        <h4>{{$t("Choose")}} {{$t("Your")}} {{$t("Delivery")}} {{$t("Address")}}</h4>
        <div v-for="(data,index) in prop" :key="index">
        <b-row  @click="setdefault(data.id)" class="hover box">
            <b-col cols="2" class="text-center"> 
                <i class="fa-solid fa-circle-check"></i>
            </b-col>
            <b-col cols="8">
                <div >
                    
                    <h5>{{data.type | add_type}}</h5>
                    <p>{{data.address}}</p>

                </div>
            
            </b-col> 
        </b-row>
        </div>
        </div>
        

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
                            v-model="rows"
                            placeholder="Enter Address"
                            id="searchMapInput"
                           
                            
                            />
                            <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
                            <input type="hidden" id="selectedadd">
                    </b-col>
                    </b-row>
                        
                        <input type="hidden" id="latitude" name="latitude" :value=lat>
                        <input type="hidden" id="longitude" name="longitude" :value=lng>
                        
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
                                @click.prevent="add_address"
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
    BAvatar, BListGroup, BListGroupItem, BCardText,BRow,BModal, VBModal,BButton,  BFormInput,
  BCol,VBToggle,BFormGroup, BForm, 
  } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@@@/store'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
  export default {
    components: {
      ValidationProvider,
        ValidationObserver,
      BAvatar,
      BListGroup,
      BListGroupItem,
      BCardText,
      BRow,
      BFormGroup,
      BCol,BModal, VBModal,BButton,  BFormInput,
      BForm,
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
    'b-toggle': VBToggle,
    Ripple,
  },
    data() {
      return {
        rows: localStorage.getItem('address'),
        address:{},
        userData: store.state['defaults'].username,
        user:{},
        loading:false,
        lat : localStorage.getItem('latitude'),
        lng : localStorage.getItem('longitude'),
        is_loggedin: this.is_loggedinfunc(),

      }
    },
    props: {
        prop: {
      
        default: () => {},
      },
     
    },
    filters: {
    add_type :function (type) {
      
        if(type == 1){
            var ty = "Home";
        }
        if(type == 2){
            var ty = "Work";
        }
        if(type == 3){
            var ty = "Others";
        }
        return ty;
    },
       
      },
    created() {
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
        if(store.state['defaults'].username){
         
        //     this.$http.get('/get_delivery_address/'+this.userData.id)
        //   .then(res => {
           
        //     //   this.address = res.data.data
              
              
        //       })

              this.$http.get('/get_address/'+this.lat+'/'+this.lng)
                .then(res => {
                       
                        this.rows = res.data.data
                      
                        localStorage.setItem('address',  this.rows);
                    })
        }

         
  },
  watch: {
    is_loggedin: function() {
			localStorage.setItem("isChecked", this.is_loggedin);
		}
	},
  methods: {
    get_address(){
        var user = store.state['defaults'].username
        this.$http.get('/get_delivery_address/'+user.id)
          .then(res => {
           
              this.address = res.data.data
              
              
              })
    },
    is_loggedinfunc: function() {
			const stored = localStorage.getItem("webuserData");
			if (stored === null) {
				return true;
			} else {
				return stored == 'true';
			}
		},
    setdefault(id){
        var  user = JSON.parse(localStorage.getItem('webuserData'));
        this.$http.get('/set_delivery_address/'+id+'/'+user.id)
                .then(res => {
                       if(res.data.status == true){
                        localStorage.setItem('latitude', res.data.address.lat)
                        localStorage.setItem('longitude', res.data.address.lng)
                        localStorage.setItem('address', res.data.address.address)
                        this.$emit('clicked-show-detail-pickup', res.data.address);
                        this.$emit('clicked-show-detail-drop', res.data.address);
                       
                       }
                       
                    })

    },
    add_address(){
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
        var  user = JSON.parse(localStorage.getItem('webuserData'));

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
        city.append('user_id', user.id);



        this.$http
                .post("/add_delivery_address", city)
                .then(
                    (response => {
                        if (response.data.status == true) {
                            
                              
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
                        this.$http.get('/get_delivery_address/'+user.id)
                        .then(res => {
                        
                            this.address = res.data.data
                            this.$emit('clicked-show-detail-pickup', sc);
                            this.$emit('clicked-show-detail-drop', sc);
                            })
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
    showModal() {
       
        if(localStorage.getItem('webuserData')){
            this.$refs['my-modal'].show()
        }else{
                      this.$toast({
                          component: ToastificationContent,
                          position: 'bottom-right',
                          props: {
                            title: "Please Login to Continue",
                            icon: 'UserIcon',
                            variant: 'danger',
                          },
                        })
        }
        var timer = setInterval(function() {
            if(document.getElementById("gmap")){
                document.getElementById("selectedadd").value = localStorage.getItem('address')
            const element = document.getElementById("gmap");
                const mapoptions = {
                    zoom : 13,
                    center : new google.maps.LatLng(localStorage.getItem('latitude'),localStorage.getItem('longitude'))
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

     },
   
  },

  }
  </script>
  <style scoped>
  #gmap {
    height: 400px;
    width: 100%;
  }

  .hover{
    cursor: pointer;
  }

  .box {
  transition: box-shadow .3s;
 
 
  
}
.box:hover {
  box-shadow: 0 0 11px rgba(33,33,33,.2); 
}
  </style>
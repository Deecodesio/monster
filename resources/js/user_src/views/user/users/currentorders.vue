<template>
    <b-card>
        <b-col
            v-if="!informationData.length"
            xxl="12"
            lg="12"
            md="12"
            cols="12"
        >
                <h4>{{$t("No Orders Found")}}</h4>
   
        </b-col>
        <b-col
           
            xxl="12"
            lg="12"
            md="12"
            cols="12"
            v-for="(data,index) in informationData"
            :key="index"
            class="col-xxl-3"
           
          >
            <b-card>
                  <div class="media-list" >
                    <b-media vertical-align="top">
                      <template #aside v-if="data.restaurant_image">
                        <b-img class="bor"
                          :src="data.restaurant_image"
                          blank-color="#ccc"
                          width="100"
                        />
                        
                      </template>
                    <b-col>
                      <h4 class="media-heading">
                        {{data.restaurant_name}}
                      </h4>
                      <b-card-text class="mb-0 textp">
                        <feather-icon icon="MapPinIcon" />  {{data.delivery_address}}
                      </b-card-text>
                      <b-card-text class="mb-0 textp">
                        <feather-icon icon="ArchiveIcon" /> Order Id:  {{data.order_id}}
                      </b-card-text>
                      <b-card-text class="mb-0 textp">
                        <feather-icon icon="ClockIcon" />  {{data.ordered_on}}
                      </b-card-text>
                      <b-card-text class="mb-0 textp">
                        <feather-icon icon="DollarSignIcon" /> 
                        <span v-if="data.is_cod === 1">
                            Cash On Delivery
                        </span>
                        <span v-else>
                            Online Payment
                        </span>
                      </b-card-text>
                    </b-col>
                    <hr>
                    <b-col>
                    <b-row>
                      <h4 class="media-heading">Total Paid : </h4><h4>   {{data.bill_amount}} </h4>
                    </b-row>
                    </b-col>
                    </b-media>
                  </div>
            </b-card>
        
        </b-col>
    </b-card>
  </template>
  
  <script>

  import { BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BFormTextarea  ,  BCardText, BMedia,BImg, } from 'bootstrap-vue'
  import vSelect from 'vue-select'
  import flatPickr from 'vue-flatpickr-component'
  import Ripple from 'vue-ripple-directive'
  import Cleave from 'vue-cleave-component'
  
  // eslint-disable-next-line import/no-extraneous-dependencies
  import 'cleave.js/dist/addons/cleave-phone.us'
  
  export default {
    components: {
      BButton,
      BForm,
      BFormGroup,
      BFormInput,
      BRow,
      BCol,
      BCard,
      BFormTextarea,
      vSelect,
      flatPickr,
      Cleave,
      BCardText,
      BMedia, 
      BImg,

    },
    directives: {
      Ripple,
    },
    props: {
      informationData: {
        type: Object,
        default: () => {},
      },
    
    },
    data() {
      return {
        countryOption: ['USA', 'India', 'Canada'],
        localOptions: JSON.parse(JSON.stringify(this.informationData)),
        clevePhone: {
          phone: true,
          phoneRegionCode: 'US',
        },
      }
    },
    methods: {
      resetForm() {
        this.localOptions = JSON.parse(JSON.stringify(this.informationData))
      },
    },
  }
  </script>
  
  <style lang="scss">
  @import '~@resources/scss/vue/libs/vue-select.scss';
  @import '~@resources/scss/vue/libs/vue-flatpicker.scss';
  </style>
  
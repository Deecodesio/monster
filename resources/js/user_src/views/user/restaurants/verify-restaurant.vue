<template>
    <div>
        <div class="shadow-sm" style="padding: 13px 50px 20px 50px; background-color: #fff; height: 70px">
            <div class="container">
                <b-row>

                    <b-col md="4" style="cursor:pointer " @click="route_to">
                        <b-img 
                            :src="rows_detail" 
                            :alt=alt 
                            class="logo_image" 
                            style="max-width: 150px !important;" />
                    </b-col>
                    <b-col md="8" style="text-align: right;padding: 6px 50px 20px 50px;"
                        v-if="$store.state['appConfig'].layout.isRTL === false">
                        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'about_us' })"
                            variant="outline-primary" class="dsp">{{ $t("About Us") }}</b-button>
                        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'contact' })"
                            variant="primary" class="dsp">{{ $t("Contact") }}</b-button>

                    </b-col>
                    <b-col md="8" style="text-align: left;padding: 6px 50px 20px 50px;" v-else>
                        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'about_us' })"
                            variant="outline-primary" class="dsp">{{ $t("About Us") }}</b-button>
                        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'contact' })"
                            variant="primary" class="dsp">{{ $t("Contact") }}</b-button>

                    </b-col>
                </b-row>

            </div>
        </div>
      


        <div class="center-screen" style="">
            <b-row>
                <b-col cols="12">
                    <b-card>
                        <h4>
                            {{message}}
                        </h4>
                        <b-button
                            v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                            variant="primary"
                            v-if="status"
                            @click="redirect()"
                        >
                            {{$t("Login")}}
                        </b-button>
                    </b-card>
                    
                </b-col>
                <b-col cols="12">
                    <b-img 
                        src="/verifyrest.svg" 
                        :alt=alt 
                        class="logo_image" 
                       
                    />
                </b-col>
            </b-row>
            
                
                
            
                
        </div>

    </div>
</template>
  
<script>
import { BImg ,BCard,BCardText,BLink,  BRow, BCol,BFormInput, BFormGroup, BForm,BButton,BFormSelect,BFormCheckbox ,BSpinner} from 'bootstrap-vue'

import Ripple from "vue-ripple-directive";


export default {
    components: {
        BImg,
        BCard,
        BCardText,
        BLink,
        BRow,
        BCol,
        BFormInput, BFormGroup, BForm, BButton,BFormSelect,BFormCheckbox,
        BSpinner
    },
    data() {
        return {

            isloading: false,
            message: '',
            status: false,
            rows_detail: '',
            alt : '',


        }
    },
    directives: {
        Ripple,
    },

    created() {
        this.$http.get('/get_site_logo/')
          .then(res => {
            
              this.rows_detail = res.data.logo

              this.alt = res.data.app_name
           
            
              })
        this.$http
            .get(
                "/verify_restaurant/" +
                this.$route.params.id +
                "/" +
                this.$route.params.code
            )
            .then((res) => {
                this.message = res.data.message
                this.status = res.data.status
            });


    },

    methods:{
        route_to(){
          if (this.$route.name != "home") {
            this.$router.push({
                    name: "master",
                   
                });
          }else{
            this.$router.push({
                    name: "master",
                   
                });
          }
      },
        redirect(){
            var newURL = window.location.origin + "/store/login"
            window.open(newURL);
        }
    }
}
</script>
<style>
.center-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 100vh;
}
</style>
    
<template>
    <div class="pt-1">
        <div v-if="!socialData.length" class="text-center"  style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
            <lottie-player
                src="/animations/fav2.json"
                background="#FFFDFD"
                speed="1"
                style="width: 300px; height: 300px"
                loop
                autoplay
            ></lottie-player>
            <h4 class="text-center">NO FAVORITES</h4>
            <P>You haven't added anything as favorites</P>
        </div>

        <b-row v-if="socialData.length">
            <b-col
                xxl="4"
                lg="4"
                md="6"
                cols="12"
                v-for="(data, index) in socialData"
                :key="index"
                class="col-xxl-3 hover"
                
            >
                <b-card class="cat-card">
                    <b-img :class="data.class" :src="data.img" fluid @click="
                    $router.push({
                        name: 'view_restaurant',
                        params: { id: data.id, name: data.text },
                    })
                "/>
                    <div class="swiper-text pt-md-1 pt-sm-50 pt-4" @click="
                    $router.push({
                        name: 'view_restaurant',
                        params: { id: data.id, name: data.text },
                    })
                ">
                        <h3>{{ data.text }}</h3>
                    </div>
                    <b-col @click="
                    $router.push({
                        name: 'view_restaurant',
                        params: { id: data.id, name: data.text },
                    })
                ">
                        <b-row>
                            <div
                                v-for="(data2, index2) in data.cuisines"
                                :key="index2"
                            >
                                {{ data2.name }},
                            </div>
                        </b-row>
                    </b-col>
                    <b-col>
                        <b-row>
                            <b-card-text cols="3" class="p-1-1">
                              <i class="fa-regular fa-clock"></i>

                                <b>{{ data.travel_time }} Min</b>
                            </b-card-text>
                            <b-card-text cols="3" class="p-1-1">
                              <feather-icon icon="MapPinIcon" size="12"   /> 

                                <b> {{ data.distance }} Kms</b>
                            </b-card-text>
                            <b-card-text cols="3" class="p-1-1">
                              <i class="fa-solid fa-star"></i>

                                <b>{{ data.rating }}</b>
                            </b-card-text>
                            <b-card-text cols="3" class="p-1-1">
                              <i class="fa-solid fa-heart"  @click="markasfav(data.id)"></i>
                            </b-card-text>
                        </b-row>
                    </b-col>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    BAvatar,
    BImg,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
        BAvatar,
        BImg,
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
            localOptions: JSON.parse(JSON.stringify(this.socialData)),
        };
    },
    methods: {
        resetForm() {
            this.localOptions = JSON.parse(JSON.stringify(this.socialData));
        },
        markasfav(id){
          if(localStorage.getItem("webuserData")){
            let loggeduser = JSON.parse(localStorage.getItem("webuserData"));
            var lat = localStorage.getItem('latitude');
           var lng = localStorage.getItem('longitude');
            this.$http.get('/remove_favourite/'+id+'/'+loggeduser.id+'/'+lat+'/'+lng)
              .then(res => {
                       
                        this.socialData = res.data.favorites
                          this.$toast({
                                  component: ToastificationContent,
                                  position: 'bottom-right',
                                  props: {
                                    title: res.data.message,
                                    icon: 'HeartIcon',
                                    variant: 'danger',
                                  },
                                })
                        
                                
                      
                  })
          }else{
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
    },
};
</script>
<style lang="scss" scoped>
.pad0 .card-body {
    padding: 0rem !important;
}
.item-view-radio-group ::v-deep {
    .btn {
        display: flex;
        align-items: center;
    }
}
.cat-card {
    border-radius: 20px;
}
.img-fluid {
    border-radius: 10px;
}

.hover {
    cursor: pointer;
}
</style>

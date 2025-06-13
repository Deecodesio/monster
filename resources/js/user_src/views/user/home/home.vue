<template>
    <b-row style="margin-top:100px;">

        <b-col cols="12">

            <div class="container">
                <swiper-multiple-slides />
                <categories />
                <todays />
                <todaystore />
                <brands />
                <morerestaurant />
                <download class="dsp" />
                <more />





                <!-- <location /> -->
            </div>


        </b-col>
    </b-row>
</template>

<script>
import { BRow, BCol, BCard } from 'bootstrap-vue'

import SwiperMultipleSlides from './SwiperMultipleSlides.vue'
import categories from './categories.vue'
import todays from './todays.vue'
import brands from './brands.vue'
import location from './location.vue'
import download from './download.vue'
import todaystore from './todaystore.vue'
import more from './more.vue'
import morerestaurant from './morerestaurant.vue'








export default {
    components: {
        BRow,
        BCol,
        BCard,

        SwiperMultipleSlides,
        categories,
        todays,
        todaystore,
        brands,
        location,
        download,
        more,
        morerestaurant,
    },
    mounted() {       
        // this.initMap()
        this.showmodal()

    },
    methods: {
        initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(this.showPosition);
            }

        },
        showPosition(position) {

            localStorage.setItem('latitude', position.coords.latitude)
            localStorage.setItem('longitude', position.coords.longitude)
            this.displayLocate(position.coords.latitude, position.coords.longitude)
        },
        showmodal() {

        },
        getlocation() {
            alert("hi");
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

                        //  document.getElementById("label_map").value = add;
                        localStorage.setItem('address', add)
                        document.getElementById("demo").innerHTML = add;

                        // address.value = buffer;
                        //  alert(add);
                    } else {
                        alert("address not found");
                    }

                }
            );
        },
    },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

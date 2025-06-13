<template>
        <b-col cols="12">
  
        <div class="px-3" style="margin-top: 175px">
            <div class="store-name">Location: {{ $route.query.name }}</div>
            <div
                class="store-grid"
                :class="{ 'single-card': restaurantCities.length === 1 }"
                v-if="restaurantCities.length"
            >
                <div
                    v-for="city in restaurantCities"
                    :key="city.id"
                    class="store-card"
                >
                    <div class="store-name mt-4">{{ city.name }}</div>
                    <div class="store-address">{{ city.address }}</div>
                    <div
                        :id="`gmap-${city.id}`"
                        style="
                            height: 322px;
                            width: 620.44px;
                            margin-bottom: 1rem;
                        "
                    ></div>
                </div>
            </div>
        </div>
        <div class="mt-5" style="padding-top: 0% ; padding-left: 0%">
            <Ourshops />
        </div>
   
    </b-col>
</template>

<script>
import { LMap, LTileLayer, LCircle } from "vue2-leaflet";
import Ourshops from "./ourshops.vue";
export default {
    components: { LMap, LTileLayer, LCircle, Ourshops },
    name: "StoreList",
    props: ["city"],
    data() {
        return {
            restaurantCities: [],
        };
    },
    created() {
        this.$http
            .get("/restaurant_by_city/" + this.$route.params.city)
            .then((response) => {
                this.restaurantCities = response.data.data;
                setTimeout(() => {
                    this.restaurantCities.forEach((city) => {
                        // Parse lat/lng as numbers
                        const lat = parseFloat(city.lat);
                        const lng = parseFloat(city.lng);

                        // Create the map
                        const map = new google.maps.Map(
                            document.getElementById(`gmap-${city.id}`),
                            {
                                zoom: 15,
                                center: { lat, lng },
                                styles: [
                                    {
                                        featureType: "administrative",
                                        elementType: "geometry",
                                        stylers: [{ visibility: "off" }],
                                    },
                                    {
                                        featureType: "poi",
                                        stylers: [{ visibility: "off" }],
                                    },
                                    {
                                        featureType: "road",
                                        stylers: [{ visibility: "off" }],
                                    },
                                    {
                                        featureType: "road",
                                        elementType: "labels.icon",
                                        stylers: [{ visibility: "off" }],
                                    },
                                    {
                                        featureType: "transit",
                                        stylers: [{ visibility: "off" }],
                                    },
                                ],
                            }
                        );

                        // Add a marker
                        var marker = new google.maps.Marker({
                            position: { lat, lng },
                            map: map,
                            icon: "/rest-pin2.svg", // or your custom icon
                        });
                        var content =
                            ' <i class="fa-solid fa-motorcycle"></i> ' +
                            city.name;
                        var infowindow = new google.maps.InfoWindow();
                        infowindow.setContent(content);
                        infowindow.open(map, marker);

                        google.maps.event.addListener(
                            marker,
                            "click",
                            (function (marker, i) {
                                return function () {
                                    var content = "<b>" + city.name + "</b>";
                                    var infowindow =
                                        new google.maps.InfoWindow();
                                    infowindow.setContent(content);
                                    infowindow.open(map, marker);
                                };
                            })(marker)
                        );
                    });
                }, 100); // slight delay to ensure DOM is ready
            });
    },
    mounted() {
        this.$nextTick(() => {});
    },
};
</script>

<style scoped>
.location {
    font-weight: 600;
    margin-bottom: 28px;
    font-size: 20px;
    color: #222;
}

.store-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 40px 32px;
}

.store-card {
    background: #fff;
    padding: 0;
    box-shadow: none;
    border-radius: 0;
    display: flex;
    flex-direction: column;
}

.store-name {
    font-size: 25px;
    font-weight: 700;
    color: #000000;
    line-height: 100%;
}

.store-address {
    font-size: 16px;
    color: #000000;
    font-weight: 400;
    min-height: 3em;
    margin-top: 12px;
    margin-bottom: 12px;
}

.map-img {
    width: 100%;
    max-width: 400px;
    height: auto;
    border-radius: 0;
    margin-top: 0;
    margin-bottom: 0;
    display: block;
}
/* Goggle map */
.vue2leaflet-map {
    &.leaflet-container {
        height: 350px;
    }
}
</style>
<style scoped>
#gmap {
    height: 400px;
    width: 40%;
}

@media (max-width: 900px) {
    .store-grid {
        grid-template-columns: 1fr;
        gap: 32px 0;
    }
}
</style>

<template>
    <div>
        <div id="gmap"></div>
    </div>
</template>

<script>
import { LMap, LTileLayer, LCircle } from "vue2-leaflet";
import "leaflet/dist/leaflet.css";

export default {
    components: {
        LMap,
        LTileLayer,
        LCircle,
    },
    data() {
        return {
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            zoom: 8,
            center: [13.08268, 80.270721],
            circle: {
                center: [13.08268, 80.270721],
                radius: 4500,
                color: "#EA5455",
            },
            restaurant: {},
            locations: [],
            image: '',
        };
    },
    mounted() {
        this.track_order();
        this.initMap();
        this.initTrHeight();
        this.importAll(
            require.context("@/assets/images/banner/", true, /\.jpg$/)
        );
    },
    destroyed() {
        window.removeEventListener("resize", this.initTrHeight);
    },

    methods: {
        track_order() {
            this.$http
                .get("/store/driver_map/" + localStorage.id)
                .then((res) => {
                    this.restaurant = res.data.data;
                    this.locations = res.data.locations;
                    console.log(res.data);
                    this.initMap();
                });
        },
        initMap() {
            var lat = this.restaurant.lat; //rider lat
            var lng = this.restaurant.lng;
            var rest_name = this.restaurant.restaurant_name;
            var image = "/rid-pin.svg";
            var image1 = "/anki_store.svg";

            var map = new google.maps.Map(document.getElementById("gmap"), {
                zoom: 12,
                center: new google.maps.LatLng(lat, lng),
                styles: [
                    {
                        featureType: "administrative",
                        elementType: "geometry",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                    {
                        featureType: "poi",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                    {
                        featureType: "road",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                    {
                        featureType: "road",
                        elementType: "labels.icon",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                    {
                        featureType: "transit",
                        stylers: [
                            {
                                visibility: "off",
                            },
                        ],
                    },
                ],
                // mapTypeId: google.maps.MapTypeId.ROADMAP
            });


            var marker, i;

            var lat_lng = new Array();
            var latlngbounds = new google.maps.LatLngBounds();

            var marker2 = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map,
                icon: image1,
            });
            var content2 = ' <i class="fa-solid fa-store"></i> ' + rest_name


            var infowindow2 = new google.maps.InfoWindow();
            infowindow2.setContent(content2);
            infowindow2.open(map, marker2);

            for (i = 0; i < this.locations.length; i++) {


                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(
                        this.locations[i][1],
                        this.locations[i][2]
                    ),
                    map: map,
                    icon: image,
                });
                var content = ' <i class="fa-solid fa-motorcycle"></i> ' + this.locations[i][0]
                var infowindow = new google.maps.InfoWindow();
                infowindow.setContent(content);
                infowindow.open(map, marker);

                google.maps.event.addListener(
                    marker,
                    "click",
                    (function (marker, i) {
                        return function () {
                            var content = '<b>' + this.locations[i][0] + '</b>'
                            var infowindow = new google.maps.InfoWindow();
                            infowindow.setContent(content);
                            infowindow.open(map, marker);
                        };
                    })(marker, i)
                );
            }
        },
    },
    // created() {

    // },
};
</script>

<style lang="scss">
.vue2leaflet-map {
    &.leaflet-container {
        height: 350px;
    }
}
</style>
<style scoped>
#gmap {
    height: 400px;
    width: 100%;
}
</style>

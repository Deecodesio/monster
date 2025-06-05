<template>
    <div>
        <div class="store-wrapper">
            <div id="gmap"></div>
            <div class="px-3 py5" v-if="restaurantCities.length">
                <h4>Restaurant Cities</h4>
                <ul>
                    <li v-for="city in restaurantCities" :key="city.id">
                        {{ city.city }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="container-fluid" style="padding-top: 3rem; padding-left: 0">
            <Ourshops />
        </div>
    </div>
</template>

<script>
import Ourshops from "./ourshops.vue";
export default {
    components: {
        Ourshops,
    },
    name: "StoreList",
    props: ["city"],
    data() {
        return {
            restaurantCities: [],
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            zoom: 8,
            center: [13.08268, 80.270721],
            circle: {
                center: [13.08268, 80.270721],
                radius: 4500,
                color: "#EA5455",
            },
        };
    },
    created() {
        this.$http.get("/restaurant_by_city" + this.$route.params.city).then((response) => {
            this.restaurantCities = response.data;
            console.log("Restaurant Cities", response.data);
        });
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
};
</script>

<style scoped>
.store-wrapper {
    padding: 13rem 24px;
    background: #fff;
    /* min-height: 100vh; */
    margin-left: 2rem;
}

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
    padding-top: 5rem;
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
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 6px;
    margin-top: 0;
    color: #111;
}

.store-address {
    font-size: 15px;
    color: #222;
    margin-bottom: 12px;
    margin-top: 0;
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

@media (max-width: 900px) {
    .store-grid {
        grid-template-columns: 1fr;
        gap: 32px 0;
    }
}
</style>

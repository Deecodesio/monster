<template>
    <div style="padding-right: 0% !important; padding-left: 0% !important">
        <div class="shope-container mb-5 text-center" v-if="shopList.length">
            <h3 class="shope-title mx-3">Our Shops</h3>
            <div class="shop-grid">
                <div
                    class="shop-box"
                    v-for="shop in shopList"
                    :key="shop.id"
                    @click="goToCity(shop.id, shop.city)"
                    style="cursor: pointer"
                >
                    {{ shop.city }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import store from "@@@/store";

export default {
    data() {
        return {
            shopList: [],
        };
    },

    created() {
        store.commit("deliware_cart/UPDATE_FOOTER", true);
        this.$http
            .get("/restaurant_cities")
            .then((response) => {
                console.log("Shop Data", response.data);
                this.shopList = response.data;
                console.log("List", this.shopList);
                store.commit("deliware_cart/UPDATE_FOOTER", true);
            })
            .catch((error) => {
                console.error("Error fetching restaurant cities:", error);
            });
    },
    methods: {
        goToCity(cityId, cityName) {
            this.$http.get("/restaurant_by_city/" + cityId).then((response) => {
                const data = response.data.data;
                if (Array.isArray(data) && data.length > 0) {
                    this.$router.push({
                        name: "StoreList",
                        params: { city: cityId },
                        query: { name: cityName },
                    });
                } else {
                    this.$bvToast &&
                        this.$bvToast.toast(
                            "No restaurants found for this city.",
                            {
                                position: "bottom-right",
                                title: "Info",
                                variant: "danger",
                                solid: true,
                            }
                        );
                }
            });
        },
    },
};
</script>

<style scoped>
.shope-container {
    background-color: #ff81b633;
    padding: 40px 20px;
}

.shope-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #000000;
}

.shop-grid {
    display: grid;
    grid-template-columns: repeat(5, auto);
    gap: 20px 100px;
    justify-content: center;
    padding: 20px;
}

.shop-box {
    background-color: white;
    height: 50px;
    line-height: 50px;
    font-size: 15px;
    border-radius: 20px;
    padding: 0 30px;
    text-align: center;
    font-weight: 500;
    color: #263238;
    white-space: nowrap;
    display: inline-block;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
}

@media (max-width: 1024px) {
    .shop-grid {
        grid-template-columns: repeat(5, auto) !important;
        gap: 30px 80px !important;
    }
}

@media (min-width: 768px) and (max-width: 960px) {
    .shop-grid {
        grid-template-columns: repeat(4, auto) !important;
        gap: 16px 20px !important;
    }
}

@media (max-width: 480px) {
    .shop-grid {
        grid-template-columns: repeat(2, auto) !important;
        gap: 18px 8px !important;
    }
}
</style>

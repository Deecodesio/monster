<template>
    <div style="padding-right: 0% !important; padding-left: 0% !important">
        <div class="shope-container mb-5 text-center">
            <h3 class="shope-title">Our Shops</h3>
            <div class="shop-grid">
                <div class="shop-box" v-for="shop in shopList" :key="shop.id">
                    {{ shop.city }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            shopList: [],
        };
    },

    created() {
        this.$http
            .get("/restaurant_cities")
            .then((response) => {
                console.log("Shop Data", response.data);
                this.shopList = response.data;
                console.log("List", this.shopList);
            })
            .catch((error) => {
                console.error("Error fetching restaurant cities:", error);
            });
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

@media (min-width: 1024px) {
    .shop-grid {
        grid-template-columns: repeat(5, auto) !important;
        gap: 30px 80px !important;
    }
}

@media (min-width: 768px) and (max-width: 960px) {
    .shop-grid {
        grid-template-columns: repeat(4, auto) !important;
        gap: 16px 32px !important;
    }
}


@media (max-width: 480px) {
    .shop-grid {
        grid-template-columns: repeat(2, auto) !important;
        gap: 18px 8px !important;

    }
}
</style>

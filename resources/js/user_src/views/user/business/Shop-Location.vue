<template>
    <div>
        <div class="store-wrapper">
            <h3 class="location">Location: {{ selectedCity }}</h3>
            <div class="store-grid">
                <div v-for="(store, index) in storeList" :key="index" class="store-card">
                    <h4 class="store-name">{{ store.name }}</h4>
                    <p class="store-address">
                        <strong>Address :</strong> {{ store.address }}
                    </p>
                    <img :src="store.mapUrl" alt="Map" class="map-img" />
                </div>
            </div>
        </div>

        <div class="container-fluid" style=" padding-right: 0; padding-left: 0">
            <Ourshops />
        </div>
    </div>
</template>

<script>
import Ourshops from "./OurShops.vue";
export default {
    components: {
        Ourshops
    },
    name: "StoreList",
    props: ["city"],
    data() {
        return {
            selectedCity: this.city || "Bengaluru",
            storeList: []
        };
    },
    computed: {
        selectedLocation() {
            return this.$route.params.city || "Bengaluru";
        },
        filteredStores() {
            // Filter stores to only those that match the selected city (case insensitive)
            return this.stores.filter(store =>
                store.city.toLowerCase() === this.selectedLocation.toLowerCase()
            );
        }
    },
    created() {
        // Dummy data simulation – replace this with your API call
        this.storeList = [
            {
                name: "Store name",
                address: `${this.selectedCity}, Tamil Nadu, India`,
                mapUrl: "https://via.placeholder.com/400x200?text=Map+1"
            },
            {
                name: "Store name",
                address: `${this.selectedCity}, Tamil Nadu, India`,
                mapUrl: "https://via.placeholder.com/400x200?text=Map+2"
            },
            {
                name: "Store name",
                address: `${this.selectedCity}, Tamil Nadu, India`,
                mapUrl: "https://via.placeholder.com/400x200?text=Map+3"
            },
            {
                name: "Store name",
                address: `${this.selectedCity}, Tamil Nadu, India`,
                mapUrl: "https://via.placeholder.com/400x200?text=Map+4"
            },
            {
                name: "Store name",
                address: `${this.selectedCity}, Tamil Nadu, India`,
                mapUrl: "https://via.placeholder.com/400x200?text=Map+5"
            },
            {
                name: "Store name",
                address: `${this.selectedCity}, Tamil Nadu, India`,
                mapUrl: "https://via.placeholder.com/400x200?text=Map+6"
            }
        ];
    }
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

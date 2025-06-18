<template>
    <div class="pt-1" v-if="prop.length">
        <b-row>
            <b-col md="12">
                <div v-if="title == 'Top Selling Products'">
                    <h2 class="text-center mt-5 mb-2">{{ title }}</h2>
                    <div class="hrLine" v-if="title != ''"></div>
                </div>
                <div v-if="title == 'Best Seller'">
                    <h2 class="text-center mb-2 mt-0">{{ title }}</h2>
                    <div class="hrLine1" v-if="title != ''"></div>
                </div>

                <!-- Responsive gap row -->
                 <b-container fluid>
                <b-row class="g-3 gy-4 mb-2">
                    <b-col
                        cols="6"
                        md="4"
                        lg="3"
                        class="mb-3"
                        v-for="(data2, index2) in prop"
                        :key="index2"
                        id="products"
                    >
                        <div
                            class="featured_card"
                            :class="{
                                'rounded3 pad0': data2.menu_available === 1,
                                'rounded3 pad0 closed':
                                    data2.menu_available !== 1,
                            }"
                        >
                            <b-img
                                class="product_card_img"
                                :src="data2.image[0]"
                                blank-color="#ccc"
                                style="height: 180px; width: 100%"
                                @click="
                                    goto_product(
                                        data2.food_id,
                                        data2.name,
                                        data2.slug,
                                        data2
                                    )
                                "
                            />

                            <div class="p10">
                                <h4
                                    class="media-heading pt-0"
                                    @click="open_image(data2, data2)"
                                    style="color: black; font-family: Poppins"
                                >
                                    {{ data2.name }}
                                </h4>

                                <b-card-text
                                    class="mb-0 textp wrap"
                                    @click="open_image(data2, data2)"
                                    style="
                                        color: black;
                                        font-weight: 600;
                                        font-family: Quicksand;
                                    "
                                >
                                    {{ data2.label }}
                                </b-card-text>

                                <b-row class="pt-0">
                                    <b-col cols="6">
                                        <span
                                            class="media-heading text-primary strike"
                                            @click="open_image(data2, data2)"
                                            v-if="data2.price < data2.bprice"
                                        >
                                            {{
                                                $store.state["defaults"]
                                                    .currency
                                            }}
                                            {{ data2.bprice | priceformat }}
                                        </span>
                                        <span
                                            class="media-heading text-primary sprice"
                                            @click="open_image(data2, data2)"
                                        >
                                            {{
                                                $store.state["defaults"]
                                                    .currency
                                            }}
                                            {{ data2.price | priceformat }}
                                        </span>
                                    </b-col>

                                    <b-col cols="6" class="text-r pl-0">
                                        <b-button
                                            v-ripple.400="
                                                'rgba(113, 102, 240, 0.15)'
                                            "
                                            variant="flat-primary"
                                            class="btn-icon add_to_cart"
                                            :id="
                                                'food_add_' +
                                                data2.food_id +
                                                'r'
                                            "
                                            :data-price="data2.price"
                                            @click="
                                                check_product(data2, isopen)
                                            "
                                            v-if="data2.menu_available === 1"
                                        >
                                            <div class="add_cart_btn">
                                                {{ $t("Add to Cart") }}
                                                <b-img
                                                    src="/monster/plate.svg"
                                                    class="add_cart_btn_img"
                                                />
                                            </div>
                                        </b-button>

                                        <div
                                            class="num-block skin-2"
                                            :id="
                                                'food_control_' +
                                                data2.food_id +
                                                'r'
                                            "
                                            style="display: none"
                                            v-if="data2.menu_available === 1"
                                        >
                                            <div class="num-in">
                                                <span
                                                    class="minus dis"
                                                    :id="
                                                        'food_minus_' +
                                                        data2.food_id
                                                    "
                                                    @click="
                                                        removefromcart(
                                                            data2.price,
                                                            data2.item_tax,
                                                            data2.name,
                                                            data2.food_id,
                                                            data2.is_veg,
                                                            data2.add_ons,
                                                            data2.food_quantity,
                                                            data2.image
                                                        )
                                                    "
                                                ></span>
                                                <input
                                                    type="text"
                                                    class="in-num"
                                                    value="1"
                                                    readonly
                                                    :id="
                                                        'food_qty_' +
                                                        data2.food_id +
                                                        'r'
                                                    "
                                                />
                                                <span
                                                    class="plus"
                                                    :id="
                                                        'food_plus_' +
                                                        data2.food_id
                                                    "
                                                    @click="
                                                        check_product(
                                                            data2,
                                                            isopen
                                                        )
                                                    "
                                                ></span>
                                            </div>
                                        </div>
                                    </b-col>
                                </b-row>
                            </div>
                        </div>
                    </b-col>
                    <product_modal :value="modalOpen" :data="data" />
                </b-row>
                </b-container>
            </b-col>
        </b-row>
    </div>
</template>
<script>
import {
    BButton,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
    BImgLazy,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import jsfunctions from "./jsfunctions.js";

import addCustomizedProduct from "./cartfunctions.js";
import goback from "./cartfunctions.js";
import loadcart from "./cartfunctions.js";
import single_restaurant from "./cartfunctions.js";
import product_modal from "./product_modal.vue";
import store from "@@@/store";

export default {
    components: {
        BButton,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
        BImg,
        BImgLazy,
        product_modal,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            modalOpen: false,
            data: {},
        };
    },
    watch: {
        $route(to, from) {
            if (this.$route.name != "home") {
                this.loadcart();
            }
        },
    },
    props: {
        prop: {
            default: () => [],
        },
        business_id: {
            default: 1,
        },
        isopen: {
            default: 0,
        },
        title: {
            default: "Featured",
        },
    },
    created() {
        this.loadcart(), store.commit("deliware_cart/UPDATE_FOOTER", true);
    },
    methods: {
        ...goback,
        ...addCustomizedProduct,
        ...single_restaurant,
        ...loadcart,
        check_product(data, isopen) {
            let selected = {};
            if (
                !data.food_quantity.length &&
                !data.add_ons.length &&
                !data.groups.length
            ) {
                this.open_plus(data, isopen);
                return;
            } else {
                this.modalOpen = !this.modalOpen;
                this.data = data;
            }
        },
        goto_product(id, name, slug, data) {
            console.log("business_category_id", data);
            var str = name;
            str.replace(/\-/g, "");
            var str2 = str.replace(/[^A-Z0-9]+/gi, "-").toLowerCase();
            this.$router.push({
                name: "product",
                params: {
                    slug: slug,
                    category_id: data.business_category_id,
                },
            });
        },
    },
};
</script>
<style scoped>
.pt-10 {
    padding-top: 10rem !important;
}

.hrLine {
    max-width: 75px;
    margin-top: 5px;
    margin-bottom: 25px !important;
    margin-left: auto;
    margin-right: auto;
    border: 3px solid #ffce00;
    border-radius: 5px;
    background-color: #ffce00;
}

.hrLine1 {
    max-width: 75px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 5px;
    margin-bottom: 25px !important;
    border: 3px solid #ffce00;
    border-radius: 5px;
    background-color: #ffce00;
}

.product_card_img {
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
    width: 100%;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0) 50%, #000000 100%);
    object-fit: cover;
}



</style>

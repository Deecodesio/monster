<template>
    <b-row style="margin-top:50px;">

        <b-col cols="12">
            <div>
                <!-- <top_banners /> -->

                <subcategories />
                <!-- <featured :prop="cat_products" :business_id="business_id" :isopen="isopen" :title="title3"
                    v-if="cat_products.length" /> -->

            </div>


        </b-col>
    </b-row>
</template>

<script>
import { BRow, BCol, BCard } from 'bootstrap-vue'

import top_banners from './top_banners.vue'
import categories from './categories.vue'
import subcategories from './subcategories.vue'

import featured from './featured.vue'
import download from '@@@/views/user/home/download.vue'

import store from '@@@/store'

export default {
    components: {
        BRow,
        BCol,
        BCard,

        top_banners,
        categories,
        featured,
        download,
        subcategories

    },
    data() {
        return {
            featured: [],
            cat_products: [],

            lat: localStorage.getItem('latitude'),
            lng: localStorage.getItem('longitude'),
            business_id: localStorage.getItem('single_business_id'),
            isopen: 1,
            title1: "Featured",
            title2: "Recent Products",
            title3: "",
            sub_categories: [],

        }
    },
    watch: {
        $route(to, from) {
            this.sub_cats()


        }
    },
    created() {

        // this.get_products()
        this.sub_cats()





    },
    methods: {
        get_products() {


            this.$http.get('/category_products/' + this.lat + '/' + this.lng + '/' + this.$route.params.id + '/' + this.business_id)
                .then(res => {
                    this.cat_products = res.data.cat_products
                    this.title3 = res.data.title

                })
        },
        sub_cats() {
            store.commit('deliware_cart/UPDATE_FOOTER', false)

            const productSlug = this.$route.params.slug
            console.log(productSlug);

            const productId = productSlug.substring(productSlug.lastIndexOf('-') + 1)
            this.$http.get('/api/get_home_categories')
                .then(res => {
                    if (res.data.categories.length) {
                        for (var i = 0; i < res.data.categories.length; i++) {
                            if (res.data.categories[i].id == productId) {
                                console.log(res.data.categories[i].sub_categories);
                                this.sub_categories = res.data.categories[i].sub_categories
                                this.title3 = res.data.categories[i].name
                            }
                        }
                    }
                    store.commit('deliware_cart/UPDATE_FOOTER', true)

                })
        }

    },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>

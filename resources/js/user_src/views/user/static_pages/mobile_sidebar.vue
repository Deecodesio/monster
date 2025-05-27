<template>
    <div>
        <div class="container" style="margin-top:50px;">
            <b-col cols="12" class="menu-m-bg">
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="flat-primary"
                    @click="$router.push({ name: 'track_user_order' })">
                    <h6 class="menu-m-font"><i class="fa-solid fa-chevron-right"></i> Track Order</h6>
                </b-button>
            </b-col>
            <b-col cols="12" class="menu-m-bg">
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-toggle.collapse-1 variant="flat-primary">
                    <h6 class="menu-m-font"><i class="fa-solid fa-chevron-right"></i> Shop Now</h6>
                </b-button>
                <b-collapse id="collapse-1" class="mt-2" v-for="(cat, cat1) in rows2" :key="cat1">
                    <b-card-text class="card-text menu-m-bg" @click="gotor(cat.slug)">
                        <p class="menu-m-sub"><i class="fa-solid fa-caret-right"></i> {{ cat.text }}</p>
                    </b-card-text>
                </b-collapse>
            </b-col>
            <b-col cols="12" v-for="(data, index) in cats" :key="index" class="mt-1 menu-m-bg">
                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-toggle.collapse-2 variant="flat-primary">
                    <h6 class="menu-m-font"><i class="fa-solid fa-chevron-right"></i> {{ data.name }}</h6>
                </b-button>
                <b-collapse id="collapse-2" class="mt-2" v-for="(data2, index2) in data.to_show" :key="index2">
                    <b-card-text class="card-text" @click="gotopage(data2.id, data2.text)">
                        <p class="menu-m-sub"><i class="fa-solid fa-caret-right"></i> {{ data2.text }}</p>
                    </b-card-text>
                </b-collapse>
            </b-col>
            <b-col cols="12" class="mt-1 menu-m-bg">
                <div v-for="(sh, sh1) in to_show" :key="sh1">
                    <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="flat-primary"
                        class="btn-group btn dropdown-toggle btn-link btn-lg text-decoration-none dropdown-toggle-no-caret">
                        <h6 class="menu-m-font" @click="gotopage(sh.id, sh.text)"><i class="fa-solid fa-chevron-right"></i>
                            {{ sh.text }}</h6>
                    </b-button>
                </div>
            </b-col>
        </div>
    </div>
</template>
<script>
import { BRow, BCol, BCard, BImg, BButton } from 'bootstrap-vue'
import { BDropdown, BDropdownItem, BDropdownDivider, BCollapse, VBToggle, BCardText } from 'bootstrap-vue'


import Ripple from 'vue-ripple-directive'
export default {
    components: {
        BRow,
        BCol,
        BCard,
        BImg,
        BDropdown,
        BDropdownDivider,
        BDropdownItem,
        BCardText,
        BCollapse,

        BButton,
        BCollapse,
    },
    directives: {
        'b-toggle': VBToggle,
        Ripple,
    },
    data() {
        return {
            cats: [],
            rows2: [],
            to_show: []
        }
    },
    created() {
        this.$http.get('/get_header_contents')
            .then(res => {
                this.cats = res.data.cats
                this.to_show = res.data.to_show
            })
        var lat = localStorage.getItem('latitude');
        var lng = localStorage.getItem('longitude');
        var business_id = localStorage.getItem('single_business_id');
        this.$http.get('/category_lists/' + business_id)
            .then(res => {
                this.rows2 = res.data.data
                for (let index = 0; index < this.rows2.length; index++) {
                    var str = this.rows2[index].text
                    str.replace(/\-/g, "")
                    this.rows2[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
                }
            })
    },
    methods: {
        gotopage(id, str) {
            this.$router.push({ name: 'page', params: { id: id, name: str } });
        },
        gotor(slug) {
            this.$router.push({ name: 'cat_list', params: { slug: slug } });
        },
    },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>
   
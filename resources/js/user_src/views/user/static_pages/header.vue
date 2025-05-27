<template>
    <div>
        <div class="">
            <b-row>
                <b-dropdown size="lg" variant="link" toggle-class="text-decoration-none" no-caret>
                    <template #button-content>
                        <h6 class="menu_size">Shop Now</h6>
                    </template>
                    <div v-for="(cat, cat1) in rows2" :key="cat1">
                        <b-dropdown-item @click="gotor(cat.slug)">
                            {{ cat.text }}
                        </b-dropdown-item>
                    </div>
                </b-dropdown>
                <b-dropdown size="lg" variant="link" toggle-class="text-decoration-none" no-caret
                    v-for="(data, index) in cats" :key="index">
                    <template #button-content>
                        <h6 class="menu_size">{{ data.name }}</h6>
                    </template>
                    <div v-for="(data2, index2) in data.to_show" :key="index2">
                        <div>
                            <b-dropdown-item @click="gotopage(data2.id, data2.text, data2.slug)">
                                {{ data2.text }}
                            </b-dropdown-item>
                        </div>
                    </div>
                </b-dropdown>
                <div v-for="(sh, sh1) in to_show" :key="sh1">
                    <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                        class="btn-group btn dropdown-toggle btn-link btn-lg text-decoration-none dropdown-toggle-no-caret">
                        <h6 class="menu_size" @click="gotopage(sh.id, sh.text, sh.slug)">{{ sh.text }}</h6>
                    </b-button>

                </div>
            </b-row>
        </div>
    </div>
</template>
<script>
import { BRow, BCol, BCard, BImg } from 'bootstrap-vue'
import { BDropdown, BDropdownItem, BDropdownDivider } from 'bootstrap-vue'

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
    },
    directives: {
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
        gotopage(id, str2, slug) {
            var str = str2
            str.replace(/\-/g, "")
            str2 = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
            this.$router.push({ name: 'page', params: { slug: slug } });
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
   
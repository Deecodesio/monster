<template>
    <b-row style="margin-top:80px;">
        <b-col cols="12">
            <div class="container">
                <b-row v-if="layout_type == 2">
                    <b-col lg="6" md="6" sm="12">
                        <b-img :src="image" fluid alt="Scan" />
                    </b-col>
                    <b-col lg="6" md="6" sm="12">
                        <div v-html="page_content">
                        </div>
                    </b-col>
                </b-row>
                <b-row v-else>
                    <b-col lg="6" md="6" sm="12">
                        <div v-html="page_content">
                        </div>
                    </b-col>
                    <b-col lg="6" md="6" sm="12">
                        <b-img :src="image" fluid alt="Scan" />
                    </b-col>
                </b-row>
            </div>
        </b-col>
    </b-row>
</template>
    
<script>
import { BRow, BCol, BCard, BImg } from 'bootstrap-vue'
export default {
    components: {
        BRow,
        BCol,
        BCard,
        BImg,
    },
    data() {
        return {
            featured: [],
            recent: [],
            lat: localStorage.getItem('latitude'),
            lng: localStorage.getItem('longitude'),
            business_id: localStorage.getItem('single_business_id'),
            isopen: 0,
            title1: "Featured",
            title2: "Recent Products",
            page_content: "",
            image: "",
            layout_type: 1
        }
    },
    props: {
        prop2: {
            default: () => [],
        },

    },
    created() {
        this.$http.get('/get_page_details/' + this.prop2)
            .then(res => {
                this.image = res.data.page_details.image
                this.layout_type = res.data.page_details.layout_type
                this.page_content = res.data.data[0].data
            })
    },
    methods: {


    },
}
</script>
    
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>
   
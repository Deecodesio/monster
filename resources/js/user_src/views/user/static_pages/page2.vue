<template>
    <b-row style="margin-top:155px;">
        <b-col cols="12">
            <div class="container">
                <div v-for="(item, index) in page.segments" :id="item.id" :key="item.id">
                    <b-row v-if="item.type == 2">
                        <b-col lg="6" md="6" sm="12">
                            <b-img :src="item.image" fluid alt="Scan" />
                        </b-col>
                        <b-col lg="6" md="6" sm="12">
                            <div v-html="item.content">
                            </div>
                        </b-col>
                    </b-row>
                    <b-row v-else>
                        <b-col lg="6" md="6" sm="12">
                            <div v-html="item.content">
                            </div>
                        </b-col>
                        <b-col lg="6" md="6" sm="12">
                            <b-img :src="item.image" fluid alt="Scan" />
                        </b-col>
                    </b-row>
                </div>
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

            isopen: 0,
            title1: "Featured",
            title2: "Recent Products",
            page_content: "",
            image: "",
            layout_type: 1,
            page: {
                name: '',
                category: '',
                sub_category: '',
                segments: [{
                    id: 0,
                    type: 1,
                    image: '',
                    content: '',
                    existing: ''

                }]
            },
        }
    },
    watch: {
        $route(to, from) {
            this.page = {
                name: '',
                category: '',
                sub_category: '',
                segments: [{
                    id: 0,
                    type: 1,
                    image: '',
                    content: '',
                    existing: ''

                }]
            }
            this.fetch_page()

        }
    },
    props: {
        prop2: {
            default: () => [],
        },

    },
    created() {
        this.fetch_page()

    },
    metaInfo() {
        return {
            title: this.page.name,
            meta: [
                { vmid: 'description', name: 'description', content: this.page.name }
            ]
        }
    },
    methods: {
        fetch_page() {
            const productSlug = this.$route.params.slug
            const productId = productSlug.substring(productSlug.lastIndexOf('-') + 1)

            this.$http.get('/fetch_header_page/' + productId)
                .then(res => {
                    console.log(res)
                    this.page.name = res.data.header_pages.page_name
                    this.page.category = res.data.header_pages.header_category
                    this.page.sub_category = res.data.header_pages.header_sub_category
                    if (res.data.page_contents && res.data.page_contents.length) {
                        this.page.segments.splice(0, 1);

                        for (var i = 0; i < res.data.page_contents.length; i++) {
                            var temp = {}
                            temp.type = res.data.page_contents[i].type
                            temp.content = res.data.page_contents[i].content[i].data
                            temp.image = res.data.page_contents[i].image
                            temp.existing = 1
                            this.page.segments.push(temp)
                        }
                    }
                })
        }

    },
}
</script>
    
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>
   
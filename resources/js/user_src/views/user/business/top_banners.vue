<template>
    <b v-if="!isempty">
        <swiper
            class="swiper-multiple pt-6"
            :options="swiperOptions"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        >
            <swiper-slide v-for="(data, index) in rows" :key="index">
                <b-img
                    class="rounded3 top_ban_img"
                    :src="data.img"
                    fluid
                    @click="goto(data)"
                />
            </swiper-slide>
        </swiper>
        <div slot="pagination" class="swiper-pagination"></div>
    </b>
</template>

<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import { BImg, BCard } from "bootstrap-vue";
import "swiper/css/swiper.css";

export default {
    components: {
        Swiper,
        SwiperSlide,
        BImg,
        BCard,
    },
    data() {
        return {
            rows: {},

            isempty: false,

            swiperOptions: {
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 10,
                    },
                    760: {
                        slidesPerView: 1,
                        spaceBetween: 30,
                    },

                    1200: {
                        slidesPerView: 1,
                        spaceBetween: 30,
                    },
                },
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: true,
                },
                pagination: {
                    el: ".swiper-pagination",
                    type: "bullets",
                    bulletClass: "swiper-pagination-bullet",

                    clickable: true,
                },
            },
        };
    },
    created() {
        var lat = localStorage.getItem("latitude");
        var lng = localStorage.getItem("longitude");
        this.isempty = true;
        this.$http.get("/banner_list/" + lat + "/" + lng).then((res) => {
            this.rows = res.data.data;
            for (let index = 0; index < this.rows.length; index++) {
                var str = this.rows[index].name;
                str.replace(/\-/g, "");
                this.rows[index].str = str9
                    .replace(/[^A-Z0-9]+/gi, "-")
                    .toLowerCase();
            }
            if (this.rows.length > 0) {
                this.isempty = false;
            }
        });
    },
    methods: {
        goto(data) {
            console.log(data);
            if (data.redirect_type != 0) {
                if (data.redirect_type == 1) {
                    var str = data.redirect_name;
                    str.replace(/\-/g, "");
                    var str2 = str.replace(/[^A-Z0-9]+/gi, "-").toLowerCase();

                    this.$router.push({
                        name: "cat_list",
                        params: { slug: data.redirect_to },
                    });
                }
                if (data.redirect_type == 2) {
                    var str = data.redirect_name;
                    str.replace(/\-/g, "");
                    var str2 = str.replace(/[^A-Z0-9]+/gi, "-").toLowerCase();

                    this.$router.push({
                        name: "product",
                        params: { id: data.redirect_to, name: str2 },
                    });
                }
            }
        },
    },
};
</script>

<style scoped>
.rounded {
    border-radius: 2px !important;
}

.rounded3 {
    cursor: pointer;
}

.pt-6 {
    padding-top: 5px !important;
}
.swiper-pagination {
    width: 100% !important;
    padding-top: 10px !important;
    text-align: center !important;
    display: block !important;
    position: relative !important;
}
.top_ban_img {
    width: 100%; /* full width of swiper slide */
    height: auto;  
    min-width: 869px; 
    min-height: 208px;  
    object-fit: cover;
}
</style>

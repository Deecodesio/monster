<template>
    <div class="foot pt-2">
        <h2 class="white_text">{{ $t("Download Apps") }}</h2>
        <b-nav vertical v-for="(data, index) in rows" :key="index">
            <b-nav-item>
                <b-link @click="redirect(data.ios)"> <b-img class="down" src="/monster/app_store.png" alt="appstore"
                        aria-label="appstore" /></b-link>

            </b-nav-item>
            <b-nav-item>
                <b-link @click="redirect(data.android)"> <b-img class="down" src="/monster/play_Store.png"
                        alt="playstore" aria-label="playstore" /></b-link>

            </b-nav-item>


        </b-nav>
    </div>
</template>

<script>
import { BNav, BNavItem, BCard, BLink, } from 'bootstrap-vue'
import { BImgLazy, BImg } from 'bootstrap-vue'
export default {
    components: {
        BNav,
        BNavItem,
        BCard,
        BImgLazy,
        BImg,
        BLink,
    },
    data() {
        return {
            rows: {},

        }
    },
    created() {

        this.$http.get('/get_app_url')
            .then(res => {
                this.rows = res.data.data

            })


    },
    methods: {
        redirect(link) {
            window.open(
                link,
                '_blank'
            );
        }
    }
}
</script>
<style scoped>
a {
    color: #1e1d1b;
    text-decoration: none;
}

.foot {
    background: #FF006B;
    color: #ffffff !important;
}

.down {
    width: 146px;
    height: 48px;
}
</style>

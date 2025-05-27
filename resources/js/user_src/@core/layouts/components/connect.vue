<template>
    <b class="foot pt-2">
        <h2 class="white_text">{{ $t("Connect with us") }}</h2>
        <b v-for="(data, index) in rows" :key="index">
            <b-img style="cursor:pointer" @click="redirect(data.facebook)" src="/fb.svg" fluid alt="Scan" width="32"
                height="43" />
            <b-img style="cursor:pointer" @click="redirect(data.instagram)" src="/insta.svg" fluid alt="Scan" width="32"
                height="43" />

            <!-- <b-img style="cursor:pointer" @click="redirect(data.youtube)" src="/yt.svg" fluid alt="Scan" width="32"
                height="43" /> -->


            <b-img style="cursor:pointer" @click="redirect(data.twitter)" src="/twitter.svg" fluid alt="Scan" width="32"
                height="43" />

            <b-img style="cursor:pointer" @click="redirect(data.linkedin)" src="/linkedin.svg" fluid alt="Scan"
                width="32" height="43" />

        </b>

        <!-- <b class="" style="margin-top: 20px;">
      <h4 style="padding-top:30px">{{ $t("Language") }}</h4>
      <Locale />
    </b> -->


    </b>
</template>

<script>
import { BNav, BNavItem, BCard, BLink, } from 'bootstrap-vue'
import { BImgLazy, BImg } from 'bootstrap-vue'
import Locale from '@@@core/layouts/components/app-navbar/components/Locale.vue'

export default {
    components: {
        BNav,
        BNavItem,
        BCard,
        BImgLazy,
        BImg,
        BLink,
        Locale
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
            console.log(link);
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
}
</style>

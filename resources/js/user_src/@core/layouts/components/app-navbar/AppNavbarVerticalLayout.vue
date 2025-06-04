<template>
    <b-row>
        <div class="d-flex justify-content-center align-items-center container-xs px-4" style="padding-left: 12.5rem !important;">
            <!-- md="2" class="pt-1" -->
            <b-col> 
                <b-link class="navbar-brand " to="/">
                    <span class="brand-logo">
                        <b-img :src=rows_detail :alt=alt class="logo_image" />
                    </span>

                </b-link>
            </b-col>

            <b-col md="2" class="pt-3">
                <!-- <Locale /> -->
                <useraddress />
            </b-col>
            <b-col md="4">
                <search-bar />
            </b-col>
            <b-col md="4" class="pt-2 text-r" style="display: flex; flex-direction: row-reverse;margin-top: 10px !important; ">


                <user-dropdown style="margin: 0px 20px 0px 20px;" />
                <cart-dropdown />

                <div>
                    <!-- <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="$router.push({ name: 'track_user_order' })"
                     variant="outline-primary">
                     <feather-icon icon="TruckIcon" class="mr-50 ico-color2" />
                   {{ $t("Track Order") }}
        </b-button> -->

                </div>

            </b-col>
        </div>
    </b-row>
</template>

<script>
import {
    BCol,
    BRow,
    BLink, BNavbarNav, BButton
} from 'bootstrap-vue'
import useraddress from './components/useraddress.vue'
import Ripple from 'vue-ripple-directive'

import Locale from './components/Locale.vue'
import SearchBar from './components/SearchBar.vue'
import DarkToggler from './components/DarkToggler.vue'
import CartDropdown from './components/CartDropdown.vue'
import NotificationDropdown from './components/NotificationDropdown.vue'
import UserDropdown from './components/UserDropdown.vue'
import { BImg } from 'bootstrap-vue'
import { $themeConfig } from '@@@themeConfig'

export default {
    components: {
        BLink,

        // Navbar Components
        BNavbarNav,
        Locale,
        SearchBar,
        DarkToggler,
        CartDropdown,
        NotificationDropdown,
        UserDropdown,
        BImg,
        useraddress,
        BCol,
        BRow,
        BButton,

    },
    data() {
        return {

            rows_detail: '',
            alt: '',

        }
    },
    directives: {

        Ripple,
    },
    setup(props) {


        // App Name
        const { appName, appLogoImage } = $themeConfig.app

        return {

            // App Name
            appName,
            appLogoImage,
        }
    },
    props: {
        toggleVerticalMenuActive: {
            type: Function,
            default: () => { },
        },
    },
    created() {
        this.$http.get('/get_site_logo/')
            .then(res => {

                this.rows_detail = res.data.logo

                this.alt = res.data.app_name


            })
    },

}
</script>
<style lang="scss" scoped>
.logo_image {
    // max-width: 180px !important;
    max-width: 800px !important;
    padding: 0% !important;
    height:  60px !important;

}

.text-r {
    text-align: right;
}

// .nav-search{
//   width: 600px;
// }
.pad-1 {
    padding: 10px 20px !important;
}
 
</style>

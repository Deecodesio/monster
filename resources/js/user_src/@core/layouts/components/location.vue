<template>
    <b class="foot pt-2">
        <b-nav vertical>
            <h2 class="white_text">{{ $t("Quick Links") }}</h2>

            <b-nav-item :to="{ name: 'about_us' }" class="white_text">
                <!-- <b-nav-item class="white_text"> -->
                <span class="white_text font_size"> {{ $t("About Us") }}</span>
            </b-nav-item>

            <!-- <b-nav-item :to="{ name: 'faq' }" class="white_text">
                <span class="white_text">{{ $t("FAQs") }} </span>
            </b-nav-item> -->
            <!-- contact -->
   
            <b-nav-item :to="{ name: 'contact' }" class="white_text">
                <span class="white_text font_size">{{ $t("Contact Us") }} </span>
            </b-nav-item>

            <!-- <b-nav-item @click="redirect(2)" class="white_text"> -->
            <!-- <b-nav-item :to="{ name: 'careers' }" class="white_text"> -->
            <b-nav-item :to="{ name: 'careers' }" class="white_text">
                <span class="white_text font_size">
                    {{ $t("Career") }}
                </span>
            </b-nav-item>

            <!-- <b-nav-item @click="redirect(3)" class="white_text"> -->
            <b-nav-item :to="{ name: 'franchise' }" class="white_text">
                <span class="white_text font_size">
                    {{ $t("Franchise") }}
                </span>
            </b-nav-item>
            <b-nav-item :to="{ name: 'BulkOrders' }" class="white_text">
                <span class="white_text font_size">
                    {{ $t("Bulk Orders") }}
                </span>
            </b-nav-item>
            <!-- <b-nav-item  @click="redirect(1)" class="white_text"> -->
            <b-nav-item :to="{ name: 'blogs-list' }" class="white_text">
                <span class="white_text font_size">
                    {{ $t("Blog") }}
                </span>
            </b-nav-item>
        </b-nav>
    </b>
</template>

<script>
import { BNav, BNavItem, BCard } from "bootstrap-vue";

export default {
    components: {
        BNav,
        BNavItem,
        BCard,
    },
    methods: {
        redirect(link) {
            if (link == 1) {
                // var link1 = "https://monsterfoods.in/blogs/";
            } else if (link == 2) {
                // var link1 = "https://monsterfoods.in/careers/";
            } else {
                // var link1 = "https://monsterfoods.in/franchisee/";
            }
            window.open(link1, "_blank");
        },
    },
};
</script>
<style scoped>
a {
    color: #1e1d1b;
    text-decoration: none;
}

.foot {
    background: #ff006b;
}
.white_text {
    font-size: 18px !important;
    font-weight: 600;
    color: #ffffff !important;
}

h2 {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    margin-bottom: 1rem !important;
}
</style>

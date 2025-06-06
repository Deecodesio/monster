<template>
    <b class="foot pt-2">
        <b-nav vertical>
            <h2 class="white_text">{{ $t("Quick Links") }}</h2>

            <b-nav-item :to="{ name: 'about_us' }" class="white_text">
            <!-- <b-nav-item class="white_text"> -->
                <span class="white_text"> {{ $t("About Us") }}</span>
            </b-nav-item>

            <!-- <b-nav-item :to="{ name: 'faq' }" class="white_text">
                <span class="white_text">{{ $t("FAQs") }} </span>
            </b-nav-item> -->
            <!-- <b-nav-item :to="{ name: 'contact' }" class="white_text">
                <span class="white_text">
                    {{ $t("Contact") }}
                </span>
            </b-nav-item> -->

            <!-- <b-nav-item @click="redirect(2)" class="white_text"> -->
            <!-- <b-nav-item :to="{ name: 'careers' }" class="white_text"> -->
            <b-nav-item :to="{ name: 'careers' }" class="white_text">
                <span class="white_text">
                    {{ $t("Career") }}
                </span>
            </b-nav-item>

            <!-- <b-nav-item @click="redirect(3)" class="white_text"> -->
                   <b-nav-item :to="{ name: 'franchise' }" class="white_text">
                <span class="white_text">
                    {{ $t("Franchise") }}
                </span>
            </b-nav-item>
            <b-nav-item :to="{ name: 'BulkOrders' }" class="white_text">
                <span class="white_text">
                    {{ $t("Bulk Orders") }}
                </span>

            </b-nav-item>
            <!-- <b-nav-item  @click="redirect(1)" class="white_text"> -->
            <b-nav-item :to="{ name: 'blogs' }" class="white_text">
                <span class="white_text">
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
</style>

<template>
    <b class="foot pt-2">

        <b-nav vertical>
            <h2 class="white_text">{{ $t("Policies") }}</h2>
            <!-- <b-nav-item @click="redirect(1)">
        {{ $t("Add a Store") }}
      </b-nav-item>
      <b-nav-item @click="redirect(2)">{{ $t("Deliver with Us") }}</b-nav-item> -->
            <b-nav-item :to="{ name: 'privacypolicy' }">
            <!-- <b-nav-item> -->
                <span class="white_text">
                    {{ $t("Privacy Policy") }}
                </span>
            </b-nav-item>
            <b-nav-item :to="{ name: 'terms_conditions' }">
                <span class="white_text">
                    {{ $t("Terms and Conditions") }}
                </span>
            </b-nav-item>
            <b-nav-item :to="{ name: 'cancellation' }">
                <span class="white_text">
                    {{ $t("Cancellation Policy") }}
                </span>
            </b-nav-item>
            <b-nav-item :to="{ name: 'shipping' }">
                <span class="white_text">
                    {{ $t("Shipping and Delivery Policy") }}
                </span>
            </b-nav-item>
            <!-- <b-nav-item :to="{ name: 'privacypolicy' }">{{$t("Return Policy")}}</b-nav-item> -->
        </b-nav>
    </b>
</template>

<script>
import { BNav, BNavItem, BCard } from 'bootstrap-vue'

export default {
    components: {
        BNav,
        BNavItem,
        BCard,

    },
    methods: {
        redirect(link) {
            if (link == 1) {
                var newURL = "https://teerya.com/business-signup"

                window.open(
                    newURL,
                    '_blank'
                );
            } else {
                var newURL = "https://teerya.com/rider-signup"

                window.open(
                    newURL,
                    '_blank'
                );
            }

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

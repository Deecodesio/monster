<template>
    <div>
        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-toggle.sidebar-1 variant="flat-primary"
            style="padding-left: 10px;">
            <feather-icon class="cursor-pointer" icon="AlignJustifyIcon" size="20" />
        </b-button>
        <b-sidebar id="sidebar-1" shadow bg-variant="white" backdrop class="header_sidebar">
            <mobile_sidebar />
        </b-sidebar>
    </div>
</template>
<script>
import { BButton, BSidebar, VBToggle } from 'bootstrap-vue'
import { BDropdown, BDropdownItem, BDropdownDivider } from 'bootstrap-vue'
import mobile_sidebar from './mobile_sidebar.vue'
import Ripple from 'vue-ripple-directive'

export default {
    components: {
        BButton,
        BSidebar,

        mobile_sidebar
    },
    directives: {
        'b-toggle': VBToggle,
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

    },
    methods: {

    },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/swiper.scss';
</style>
   
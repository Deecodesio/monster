<template>
    <div
        class=" container main-menu menu-fixed menu-accordion menu-shadow"
        :class="[
            {
                expanded:
                    !isVerticalMenuCollapsed ||
                    (isVerticalMenuCollapsed && isMouseHovered),
            },
            skin === 'light' || skin === 'bordered'
                ? 'menu-light'
                : 'menu-dark',
        ]"
        @mouseenter="updateMouseHovered(true)"
        @mouseleave="updateMouseHovered(false)"
    >
        <!-- main menu header-->
        <div class="navbar-header expanded">
            <slot
                name="header"
                :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :toggleCollapsed="toggleCollapsed"
                :collapseTogglerIcon="collapseTogglerIcon"
            >
                <ul class="nav navbar-nav flex-row">
                    <!-- Logo & Text -->
                    <li class="nav-item mr-auto">
                        <b-link
                            class="navbar-brand"
                            :to="{ name: 'dashboard-ecommerce' }"
                        >
                            <span class="brand-logo">
                                <b-img
                                    :src="rows[0].logo"
                                    alt="logo"
                                    v-if="
                                        isVerticalMenuCollapsed &&
                                        !isMouseHovered
                                    "
                                />
                            </span>
                            <span class="brand-text">
                                <b-img
                                    :src="rows[0].logo_img"
                                    alt="logo"
                                    style="
                                        width: 175px;
                                        height: 45px !important;
                                    "
                                />
                            </span>
                        </b-link>
                    </li>

                    <!-- Toggler Button -->
                    <li class="nav-item nav-toggle">
                        <b-link class="nav-link modern-nav-toggle">
                            <feather-icon
                                icon="XIcon"
                                size="20"
                                class="d-block d-xl-none"
                                @click="toggleVerticalMenuActive"
                            />
                            <feather-icon
                                :icon="collapseTogglerIconFeather"
                                size="20"
                                class="d-none d-xl-block collapse-toggle-icon"
                                @click="toggleCollapsed"
                            />
                        </b-link>
                    </li>
                </ul>
            </slot>
        </div>
        <!-- / main menu header-->

        <!-- Shadow -->
        <div :class="{ 'd-block': shallShadowBottom }" class="shadow-bottom" />

        <!-- main menu content-->
        <vue-perfect-scrollbar
            :settings="perfectScrollbarSettings"
            class="main-menu-content scroll-area"
            tagname="ul"
            @ps-scroll-y="
                (evt) => {
                    shallShadowBottom = evt.srcElement.scrollTop > 0;
                }
            "
        >
            <vertical-nav-menu-items
                :items="navMenuItems"
                class="navigation navigation-main"
            />
        </vue-perfect-scrollbar>
        <!-- /main menu content-->
    </div>
</template>

<script>
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import { BLink, BImg } from "bootstrap-vue";
import { provide, computed, ref } from "@vue/composition-api";
import useAppConfig from "@core/app-config/useAppConfig";
import { $themeConfig } from "@themeConfig";
import VerticalNavMenuItems from "./components/vertical-nav-menu-items/VerticalNavMenuItems.vue";
import useVerticalNavMenu from "./useVerticalNavMenu";

export default {
    components: {
        VuePerfectScrollbar,
        VerticalNavMenuItems,
        BLink,
        BImg,
    },
    props: {
        isVerticalMenuActive: {
            type: Boolean,
            required: true,
        },
        toggleVerticalMenuActive: {
            type: Function,
            required: true,
        },
        navMenuItems: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const {
            isMouseHovered,
            isVerticalMenuCollapsed,
            collapseTogglerIcon,
            toggleCollapsed,
            updateMouseHovered,
        } = useVerticalNavMenu(props);

        const { skin } = useAppConfig();

        // Shadow bottom is UI specific and can be removed by user => It's not in `useVerticalNavMenu`
        const shallShadowBottom = ref(false);

        provide("isMouseHovered", isMouseHovered);

        const perfectScrollbarSettings = {
            maxScrollbarLength: 60,
            wheelPropagation: false,
        };

        const collapseTogglerIconFeather = computed(() =>
            collapseTogglerIcon.value === "unpinned" ? "CircleIcon" : "DiscIcon"
        );

        // App Name
        const { appName, appLogoImage } = $themeConfig.app;

        return {
            perfectScrollbarSettings,
            isVerticalMenuCollapsed,
            collapseTogglerIcon,
            toggleCollapsed,
            isMouseHovered,
            updateMouseHovered,
            collapseTogglerIconFeather,

            // Shadow Bottom
            shallShadowBottom,

            // Skin
            skin,

            // App Name
            appName,
            appLogoImage,
        };
    },
    data() {
        return {
            rows: {},
        };
    },
    created() {
        this.$http.get("/admin/get_logo").then((res) => {
            this.rows = res.data;
            console.log(this.rows);
        });
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/base/core/menu/menu-types/vertical-menu.scss";

.brand-text {
    position: fixed !important;
    margin-top: 20px !important; // 28px Existing margin-top
    margin-bottom: 8px !important;
    padding-left: 0px !important;
}
</style>

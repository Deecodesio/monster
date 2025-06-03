<template>
    <div class="vertical-layout h-100" :class="[layoutClasses]" :data-col="isNavMenuHidden ? '1-column' : null">
        <!-- Navbar -->
        <div v-if="!ismobile" :toggleable="false" :variant="navbarBackgroundColor"
            class=" navbar-light shadow-sm" :class="[navbarTypeClass]">
            <slot name="navbar" :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :navbarBackgroundColor="navbarBackgroundColor" :navbarTypeClass="[...navbarTypeClass, 'pad-1']">
                <app-navbar-vertical-layout :toggle-vertical-menu-active="toggleVerticalMenuActive" />
            </slot>
        </div>
        <!-- Mobile -->
        <div v-if="ismobile" :toggleable="false" :variant="navbarBackgroundColor" class="pad-1 navbar-light shadow-sm "
            :class="[navbarTypeClass]">
            <slot name="navbar" :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :navbarBackgroundColor="navbarBackgroundColor" :navbarTypeClass="[...navbarTypeClass, 'pad-1']">
                <AppNavbarVerticalLayoutMobile :toggle-vertical-menu-active="toggleVerticalMenuActive" />
            </slot>
        </div>
        <!--/ Navbar -->

        <!-- Vertical Nav Menu -->
        <vertical-nav-menu v-if="!isNavMenuHidden" :is-vertical-menu-active="isVerticalMenuActive"
            :toggle-vertical-menu-active="toggleVerticalMenuActive" :nav-menu-items="navMenuItems">
            <template #header="slotProps">
                <slot name="vertical-menu-header" v-bind="slotProps" />
            </template>
        </vertical-nav-menu>
        <!-- /Vertical Nav Menu -->

        <!-- Vertical Nav Menu Overlay -->
        <div class="sidenav-overlay" :class="overlayClasses" @click="isVerticalMenuActive = false" />
        <!-- /Vertical Nav Menu Overlay -->

        <!-- Content -->

        <!-- CONTENT TYPE: Left -->
        <transition :name="routerTransition" mode="out-in">
            <component :is="layoutContentRenderer"
                :key="layoutContentRenderer === 'layout-content-renderer-left' ? $route.meta.navActiveLink || $route.name : null">
                <template v-for="(index, name) in $scopedSlots" v-slot:[name]="data">
                    <slot :name="name" v-bind="data" />
                </template>
            </component>
        </transition>
        <!--/ Content -->

        <!-- Footer -->
        <footer class="footer footer-light" :class="[footerTypeClass]" v-if="$store.state['deliware_cart'].show_footer">
            <slot name="footer">
                <app-footer />
            </slot>
        </footer>
        <!-- /Footer -->
        <socket v-if="$store.state['deliware_cart'].show_footer" />
        <slot name="customizer" />
    </div>
</template>

<script>
import { onUnmounted } from '@vue/composition-api'
import AppNavbarVerticalLayout from '@@@core/layouts/components/app-navbar/AppNavbarVerticalLayout.vue'
import AppNavbarVerticalLayoutMobile from '@@@core/layouts/components/app-navbar/AppNavbarVerticalLayoutMobile.vue'

import AppFooter from '@@@core/layouts/components/AppFooter.vue'
import socket from '@@@core/layouts/components/socket.vue'

import useAppConfig from '@@@core/app-config/useAppConfig'
import { BNavbar } from 'bootstrap-vue'
import LayoutContentRendererDefault from '@@@core/layouts/components/layout-content-renderer/LayoutContentRendererDefault.vue'
import LayoutContentRendererLeft from '@@@core/layouts/components/layout-content-renderer/LayoutContentRendererLeft.vue'
import LayoutContentRendererLeftDetached from '@@@core/layouts/components/layout-content-renderer/LayoutContentRendererLeftDetached.vue'
import VerticalNavMenu from './components/vertical-nav-menu/VerticalNavMenu.vue'
import useVerticalLayout from './useVerticalLayout'
import mixinVerticalLayout from './mixinVerticalLayout'

export default {
    components: {
        // AppBreadcrumb,
        AppNavbarVerticalLayout,
        AppNavbarVerticalLayoutMobile,
        AppFooter,
        VerticalNavMenu,
        BNavbar,
        LayoutContentRendererLeftDetached,
        LayoutContentRendererLeft,
        LayoutContentRendererDefault,
        socket,
    },
    mixins: [mixinVerticalLayout],
    props: {
        navMenuItems: {
            type: Array,
            required: true,
        },
    },
    mounted() {
        this.checkdevice()
    },

    methods: {
        checkdevice() {
            if (screen.width <= 840) {
                this.ismobile = true;
            } else {
                this.ismobile = false;


            }
        }
    },
    computed: {
        layoutContentRenderer() {
            const rendererType = this.$route.meta.contentRenderer
            if (rendererType === 'sidebar-left') return 'layout-content-renderer-left'
            if (rendererType === 'sidebar-left-detached') return 'layout-content-renderer-left-detached'
            return 'layout-content-renderer-default'
        },
    },
    data() {
        return {

            ismobile: false,

        }
    },
    setup() {
        const {
            routerTransition, navbarBackgroundColor, navbarType, footerType, isNavMenuHidden,
        } = useAppConfig()

        const {
            isVerticalMenuActive,
            toggleVerticalMenuActive,
            isVerticalMenuCollapsed,
            layoutClasses,
            overlayClasses,
            resizeHandler,
            navbarTypeClass,
            footerTypeClass,
        } = useVerticalLayout(navbarType, footerType)

        // Resize handler
        resizeHandler()
        window.addEventListener('resize', resizeHandler)
        onUnmounted(() => {
            window.removeEventListener('resize', resizeHandler)
        })

        return {
            isVerticalMenuActive,
            toggleVerticalMenuActive,
            isVerticalMenuCollapsed,
            overlayClasses,
            layoutClasses,
            navbarTypeClass,
            footerTypeClass,

            // App Config
            routerTransition,
            navbarBackgroundColor,
            isNavMenuHidden,
        }
    },
}
</script>

<style lang="scss">
@import "~@resources/scss/base/themes/bordered-layout.scss";

.pad-1 {
    padding: 10px 50px;
}
</style>
<style scoped>
.osahan-nav {
    transition: 300ms ease;
}

.osahan-nav-bg {
    background-color: #fff !important;
    background-image: none !important;
    -webkit-box-shadow: 0 3px 5px rgba(57, 63, 72, 0.1);
    -moz-box-shadow: 0 3px 5px rgba(57, 63, 72, 0.1);
    box-shadow: 0 3px 5px rgba(57, 63, 72, 0.1);
}

.dsp2 {
    display: none;
}

.osahan-nav {
    transition: 200ms ease;
}

@media (max-width: 575.98px) {

    .dsp2 {
        display: block;
    }

}

@media (min-width: 768px) and (max-width: 991.98px) {
    .dsp2 {
        display: block;
    }

}
</style>

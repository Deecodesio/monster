<template>
    <div id="app" class="h-100" :class="[skinClasses]">
        <component :is="layout">
            <router-view />
        </component>

        <buy-now v-if="ismobile" />


        <scroll-to-top v-if="enableScrollToTop" />

    </div>
</template>

<script>
import ScrollToTop from '@@@core/components/scroll-to-top/ScrollToTop.vue'
import { $themeColors, $themeBreakpoints, $themeConfig } from '@@@themeConfig'
import { provideToast } from 'vue-toastification/composition'
import { watch } from '@vue/composition-api'
import useAppConfig from '@@@core/app-config/useAppConfig'
import { useWindowSize, useCssVar } from '@vueuse/core'

import store from '@@@/store'
import BuyNow from '@@@/components/BuyNow.vue'

const LayoutVertical = () => import('@@@/layouts/vertical/LayoutVertical.vue')

export default {


    metaInfo: {

        title: "Monster Foods",

        titleTemplate: '%s'
    },
    components: {

        LayoutVertical,
        BuyNow,

        ScrollToTop,
    },
    data() {
        return {

            ismobile: false,

        }
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
        layout() {
            if (this.$route.meta.layout === 'full') return 'layout-full'
            return `layout-${this.contentLayoutType}`
        },
        contentLayoutType() {
            return this.$store.state.appConfig.layout.type
        },
    },
    beforeCreate() {
        const colors = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark']

        for (let i = 0, len = colors.length; i < len; i++) {
            $themeColors[colors[i]] = useCssVar(`--${colors[i]}`, document.documentElement).value.trim()
        }

        const breakpoints = ['xs', 'sm', 'md', 'lg', 'xl']

        for (let i = 0, len = breakpoints.length; i < len; i++) {
            $themeBreakpoints[breakpoints[i]] = Number(useCssVar(`--breakpoint-${breakpoints[i]}`, document.documentElement).value.slice(0, -2))
        }

        const { isRTL } = $themeConfig.layout
        document.documentElement.setAttribute('dir', isRTL ? 'rtl' : 'ltr')

        this.$http.get('/get_defaults')
            .then(res => {

                localStorage.setItem('currency', res.data.default_country.currency_symbol)
                localStorage.setItem('decimal', res.data.decimal)
                localStorage.setItem('delimiter', res.data.delimiter)
                this.app_name = res.data.app_name
            })


        this.$http.get('/landing')
            .then(res => {
                if (res.data.single_business) {
                    localStorage.setItem('single_business', 1)
                    localStorage.setItem('single_business_id', res.data.single_business_id)
                } else {
                    localStorage.setItem('single_business', 0)
                }
            })
    },
    setup() {
        const { skin, skinClasses } = useAppConfig()
        const { enableScrollToTop } = $themeConfig.layout

        if (skin.value === 'dark') document.body.classList.add('light-layout')

        provideToast({
            hideProgressBar: true,
            closeOnClick: false,
            closeButton: false,
            icon: false,
            timeout: 3000,
            transition: 'Vue-Toastification__fade',
        })

        store.commit('app/UPDATE_WINDOW_WIDTH', window.innerWidth)
        const { width: windowWidth } = useWindowSize()
        watch(windowWidth, val => {
            store.commit('app/UPDATE_WINDOW_WIDTH', val)
        })

        return {
            skinClasses,
            enableScrollToTop,
        }
    },
}
</script>
<style scoped>
.dsp {
    display: none;
}

@media (max-width: 575.98px) {

    .dsp {
        display: block;
    }

}

@media (min-width: 768px) and (max-width: 991.98px) {
    .dsp {
        display: block;
    }

}
</style>

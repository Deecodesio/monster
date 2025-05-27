<template>
    <div>
        <banner />
        <div class="container  mb-2">
            <h4>{{ $t("Cancellation Policy") }}</h4>

            <div v-html="rows[0].terms_conditions">

            </div>
        </div>
    </div>
</template>

<script>
import {
    BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import banner from './banner.vue'
import store from '@@@/store'


export default {
    components: {
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
        banner,
    },
    directives: {
        Ripple,
    },

    data() {
        return {

            rows: {},
        }
    },
    created() {
        store.commit('deliware_cart/UPDATE_FOOTER', false)

        this.$http.get('/fetch_cancellation')
            .then(res => {

                this.rows = res.data.data
                store.commit('deliware_cart/UPDATE_FOOTER', true)

            })
    },

}
</script>
<style>
.pt-10 {
    padding-top: 10rem !important;
}
</style>
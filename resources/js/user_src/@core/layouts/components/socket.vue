<template>


    <div class="socket">
        <b-col>
            <p class="text-center f-size-0 pt-4 pb-2 m white_text">

                © COPYRIGHT {{ new Date().getFullYear() }}
                {{ alt }}. All rights Reserved
                <!-- <span class="d-none d-sm-inline-block"></span> -->


            </p>
        </b-col>
    </div>

</template>

<script>
import { BLink } from 'bootstrap-vue'
import { BRow, BCol } from 'bootstrap-vue'
import location from './location.vue'
import joinus from './joinus.vue'
import download from './download.vue'
import connect from './connect.vue'




export default {
    components: {
        BLink,
        location,
        BRow,
        BCol,
        joinus,
        download,
        connect,
    },
    data() {
        return {

            rows_detail: '',
            alt: '',

        }
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
<style scoped>
.match-height {
    background: #EEEEEE;
}

.m {
    margin: 0px;
}

.socket {
    background: #FF006B;
}
</style>

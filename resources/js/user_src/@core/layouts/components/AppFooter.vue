<template>
    <div class="container">
        <b-row class="match-height pb-2">

            <b-col md="3" col sm="6">
                <location />
            </b-col>

            <b-col md="3" col sm="6">
                <joinus />
            </b-col>
            <b-col md="3" col sm="6">
                <download />
            </b-col>
            <b-col md="3" col sm="6">
                <connect />
            </b-col>
        </b-row>

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
}
</script>
<style scoped>
.match-height {
    background: #FF006B;
}

.socket {
    background: #f4f4f4;
}
</style>

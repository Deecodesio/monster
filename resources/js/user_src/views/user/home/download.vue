<template>
  <div>
    <!-- faq search section -->
    <section id="faq-search-filter">
      <b-card class="cat-card rounded3 ">
        <b-row>
          <b-col class="text-center" lg="1" md="1" sm="1">

          </b-col>

          <b-col lg="4" md="4" sm="12">

            <h2 class="text-primary pt-6 f-size-5">
              {{ $t("Get") }} {{ appname }}
            </h2>
            <b-card-text class="mb-2 pt-2">
              <b-nav vertical>
                <b-nav-item>
                  <b-img src="/tick.svg" fluid alt="Scan" width="35" height="37" /> <b class="pl-1 f-size-1">{{ $t("Get")
                  }}
                    {{ $t("Live Order Tracking") }}</b>
                </b-nav-item>
                <b-nav-item class="mb-4"> <b-img src="/tick.svg" fluid alt="Scan" width="35" height="37" /> <b
                    class="pl-1 f-size-1">{{ $t("Get")
                    }} {{ $t("Latest Feature Updates") }}</b></b-nav-item>



              </b-nav>
              <b-card class="cat-card2">
                <b-row>
                  <b-col lg="4" md="6" sm="12">
                    <b-img :src="rows" fluid alt="Scan" v-if="!is_loading" width="100" height="100" />
                  </b-col>
                  <b-col lg="8" md="6" sm="12">
                    <h5 class="f-size-1 pt-2"><b>{{ $t("Simple way") }} {{ $t("to") }} {{ $t("download") }} the {{ appname
                    }}</b>
                    </h5>
                    <h6 class="f-size-0">{{ $t("Scan QR Code and download now") }}</h6>
                  </b-col>
                </b-row>
              </b-card>
            </b-card-text>



          </b-col>
          <b-col class="text-center" lg="1" md="6" sm="12">

          </b-col>
          <b-col class="text-center" lg="4" md="5" sm="12">
            <b-img src="/Group.png" fluid alt="Scan" />

          </b-col>



          <b-col class="text-center" lg="1" md="6" sm="12">

          </b-col>
        </b-row>
      </b-card>
    </section>
    <!--/ faq search section -->

  </div>
</template>
  
<script>
import {
  BCard,
  BCardBody,
  BForm,
  BInputGroup,
  BFormInput,
  BCardText,
  BInputGroupPrepend,
  BTabs,
  BTab,
  BImg,
  BRow,
  BCol,
  BAvatar,
  BNav,
  BNavItem, BImgLazy
} from 'bootstrap-vue'

export default {
  components: {
    BForm,
    BCard,
    BRow,
    BCol,
    BAvatar,
    BCardBody,
    BInputGroup,
    BFormInput,
    BCardText,
    BInputGroupPrepend,
    BTabs,
    BTab,
    BImg,
    BNav,
    BNavItem, BImgLazy
  },
  data() {
    return {
      faqSearchQuery: '',
      faqData: {},
      rows: '',
      appname: '',
      is_loading: true
    }
  },

  created() {
    this.$http.get('/get_qrcode')
      .then(res => {
        this.rows = res.data.qrcode
        this.appname = res.data.app_name
        this.is_loading = false
      })
  },
}
</script>
  
<style lang="scss">
@import '~@resources/scss/vue/pages/page-faq.scss';
</style>
<style scoped>
.nav-link {
  color: black;
}

.ht {
  min-height: 600px;
}

.cat-card {
  background: rgba(234, 93, 39, 0.18);
  border-color: #80BE3F;
  border-style: solid;
}

.cat-card2 {
  background: rgba(234, 93, 39, 0.30);

}
</style>
  
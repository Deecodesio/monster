<template>
  <div>
    <b-form @submit.prevent="sortMenu">
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
          style="margin-bottom: 2%;">
          {{ $t('save') }}
        </b-button>

      </b-col>

      <!-- draggable -->
      <draggable v-model="list" class="list-group  list-group-flush cursor-move" tag="ul">

        <b-list-group-item v-for="listItem in list" :key="listItem.id" tag="li" :data-id="listItem.id" id="barnd_rank">
          <div class="d-flex">

            <div class="ml-25">
              <b-card-text class="font-weight-bold mb-0">
                {{ listItem.name }}
              </b-card-text>

            </div>
          </div>
        </b-list-group-item>
        <b-form-input id="brandRanks" type="hidden" v-model="brand.brandRanks" />
        <input type="hidden" name="brandRanks" id="brandRanks" value="">
      </draggable>

      <!-- code  -->
    </b-form>
  </div>
</template>

<script>
import { BListGroupItem, BAvatar, BForm, BButton, BTooltip } from 'bootstrap-vue'
import draggable from 'vuedraggable'
import Prism from 'vue-prism-component'
import 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


export default {
  components: {
    draggable,
    BListGroupItem,
    BAvatar,
    Prism,
    BTooltip,
    BForm, BButton,
  },
  created() {

    this.$http.get('/admin/brands_list')
      .then(res => {
        this.list = res.data
      })



  },
  data() {
    return {
      list: [],
      brand: {},
    }
  },

  methods: {


    sortMenu() {
      const CONTENEUR_TOPICS = document.getElementsByClassName('list-group-flush');
      let TOPICS;
      if (CONTENEUR_TOPICS.length > 0) {
        TOPICS = CONTENEUR_TOPICS[0].children;
        let result = Array.from(TOPICS).map(el => el.dataset.id);
        this.brand.brandRanks = result;
      }
      this.$http
        .post("/admin/brand_sort", this.brand)
        .then
        (response => {
         
          if (response.data.status == true) {
           
            this.list = response.data.data

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t(response.data.message),
                icon: 'CheckIcon',
                variant: 'success',
              }

            })
            

          } else {


            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'danger',
              }
            })
          }
        })

        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response, icon, variant) {

      this.$toast({
        component: ToastificationContent,
        position: 'bottom-right',
        props: {
          title: response.data.message,
          icon: icon,
          variant: variant,
        },
      })
    },


  },
}
</script>

<style>
.list-group-item {
  transition: all 1s
}
</style>

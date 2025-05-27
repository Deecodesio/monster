<template>
  <b-card>
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
        <draggable v-model="list" tag="ul" id="drag_menu" class="list-group list-group-flush cursor-move">
          <b-list-group-item v-for="(item, index) in list" :key="index" tag="li" id="menu_rank" :data-id="item.id">
            <strong> {{ item.menu_name }}</strong>
          </b-list-group-item>
          <b-form-input id="menuRanks" type="hidden" v-model="user_info.menuRanks" />
          <input type="hidden" name="menuRanks" id="menuRanks" value="">
        </draggable>

        <!-- code -->
      </b-form>
    </div>
  </b-card>
</template>

<script>
import { BListGroupItem, BForm, BButton, BTooltip, } from 'bootstrap-vue'
import draggable from 'vuedraggable'

import 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'
import ToastificationContent from '@@core/components/toastification/ToastificationContent.vue'


export default {
  components: {
    draggable,
    BListGroupItem,
    BForm,
    BButton, BTooltip,

  },
  data() {
    return {
      list: [],
      user_info: {},
    }
  },
  created() {
    this.user_info.id = localStorage.id;
    this.$http.post('/store/menu_sort', this.user_info)
      .then(res => {

        this.list = res.data.data

      })



  },
  methods: {


    sortMenu() {
      const CONTENEUR_TOPICS = document.getElementsByClassName('list-group-flush');
      let TOPICS;
      if (CONTENEUR_TOPICS.length > 0) {
        TOPICS = CONTENEUR_TOPICS[0].children;
        let result = Array.from(TOPICS).map(el => el.dataset.id);
        // document.getElementById("menuRanks").value = result;
        this.user_info.menuRanks = result;
      }
      this.user_info.id = localStorage.id;


      this.$http
        .post("/store/store_menu_rank", this.user_info)
        .then(
          (response => {

            if (response.data.status == true) {
              this.list = response.data.data
              this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: response.data.message,
                      icon: 'CheckIcon',
                      variant: 'success',
                    },
                  })
            } else {

              this.popToast(response)
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response) {

      const h = this.$createElement
      const vNodesMsg = h('p', { class: ['text-center', 'mb-0'] }, [
        response.data.message
      ])
      this.$bvToast.toast([vNodesMsg], {
        solid: false,
        variant: 'info',
      })
    },


  },
}
</script>
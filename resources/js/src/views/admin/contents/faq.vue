<template>
  <div>
    <div>
      <b-form ref="form" :style="{ height: trHeight }" class="repeater-form" @submit.prevent="repeateAgain">

        <!-- Row Loop -->
        <b-row v-for="(item, index) in items" :id="item.id" :key="item.id" ref="row">

          <!-- Item Name -->
          <b-col md="12">
            <b-form-group label="Question" label-for="item-name">
              <b-form-input id="item-name" type="text" placeholder="Question" v-model="item.question" />
            </b-form-group>
          </b-col>
          <b-col md="12">
            <b-form-group label="Answer" label-for="cost">
              <b-form-input id="cost" type="text" placeholder="Answer" v-model="item.answer" />
            </b-form-group>
          </b-col>


          <!-- Remove Button -->
          <b-col lg="2" md="3" class="mb-50">
            <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="outline-danger" class="mt-0 mt-md-2"
              @click="removeItem(index)">
              <feather-icon icon="XIcon" class="mr-25" />
              <span>Delete</span>
            </b-button>
          </b-col>
          <b-col cols="12">
            <hr>
          </b-col>
        </b-row>

      </b-form>
    </div>
    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain" id="add">
      <feather-icon icon="PlusIcon" class="mr-25" />
      <span>Add New</span>
    </b-button>
    <b-col cols="12" class="pt-2">
      <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="success" @click="save">
        <span> {{ $t('save') }}</span>
      </b-button>
    </b-col>
  </div>
</template>

<script>
import {
  BForm, BFormGroup, BFormInput, BRow, BCol, BButton,
} from 'bootstrap-vue'
import { heightTransition } from '@core/mixins/ui/transition'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BForm,
    BRow,
    BCol,
    BButton,
    BFormGroup,
    BFormInput,
  },
  directives: {
    Ripple,
  },
  mixins: [heightTransition],
  data() {
    return {
      items: [{
        id: 1,
        question: '',
        answer: '',
        prevHeight: 0,
      }],
      nextTodoId: 2,
    }
  },
  mounted() {
    // this.initTrHeight()
  },
  created() {
    // window.addEventListener('resize', this.initTrHeight)
    if (this.$route.params.id) {

      this.$http.get('/admin/get_faq/' + this.$route.params.id)
        .then(res => {
          if (res.data.data.length) {
            this.items = res.data.data
            for (let i = 0; i < this.items.length; i++) {
              // this.repeateAgain()
            }
            console.log(this.items);
          }


        })


    }
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {
    repeateAgain() {
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },
    save() {
      let data = new FormData();
      data.append('question', JSON.stringify(this.items));
      data.append('title', this.$route.params.id);
      this.$http
      this.$http.post('/admin/faq', data)

        .then(
          response => {
            this.question = '';
            this.answer = '';
            this.$router.push({ name: "faq_list" })
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t(response.data.message),
                icon: "CheckIcon",
                variant: "success",
              },
            })
          }
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));


    },
  },
}
</script>

<style lang="scss" scoped>
.repeater-form {
  overflow: hidden;
  transition: .35s height;
}
</style>
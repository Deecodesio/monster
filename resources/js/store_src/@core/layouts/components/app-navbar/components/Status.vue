<template>
  <b-nav-item-dropdown id="dropdown-grouped" variant="link"
    class="dropdown-language nav-item dropdown nav-dropdown-custom status">
    <template #button-content>

      <i class="fa-solid fa-circle-check " aria-hidden="true" style="color: #28c76f" v-if="currentLocale.value == 1"></i>
      <i class="fa-solid fa-circle-xmark" aria-hidden="true" style="color: #ea5455" v-if="currentLocale.value == 0"></i>
      <i class="fa-solid fa-calendar-xmark" aria-hidden="true" style="color: #ff9f43" v-if="currentLocale.value == 2"></i>
      <i class="fa-solid fa-clock" aria-hidden="true" style="color: #ff9f43" v-if="currentLocale.value == 3"></i>
      <span class="ml-50 text-body ">{{ currentLocale.name }}</span>
      <i class="fa-solid fa-sort-down"></i>
    </template>







    <b-dropdown-item v-model="status.status" key="0" value="0" @click="doSomething(1)">
      <i class="fa-solid fa-circle-check " aria-hidden="true" style="color: #28c76f"></i>

      <span class="ml-50">
        Accepting Orders
      </span>
    </b-dropdown-item>

    <b-dropdown-item v-model="status.status" key="1" value="1" @click="doSomething(0)">
      <i class="fa-solid fa-circle-xmark" aria-hidden="true" style="color: #ea5455"></i>

      <span class="ml-50">
        Not Accepting Orders
      </span>
    </b-dropdown-item>

    <b-dropdown-item v-model="status.status" key="2" value="2" @click="doSomething(2)">
      <i class="fa-solid fa-calendar-xmark" aria-hidden="true" style="color: #ff9f43"></i>

      <span class="ml-50">
        Closed for Today
      </span>
    </b-dropdown-item>

    <b-dropdown-item v-model="status.status" key="3" value="3" @click="doSomething(3)" v-if="status.noon == '1'">
      <i class="fa-solid fa-clock" aria-hidden="true" style="color: #ff9f43"></i>

      <span class="ml-50">
        Closed for Noon
      </span>
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>
  
<script>
import { BNavItemDropdown, BDropdownItem, BImg, BTooltip, } from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
export default {
  components: {
    BNavItemDropdown,
    BDropdownItem,
    BImg, BTooltip,
  },
  // ! Need to move this computed property to comp function once we get to Vue 3
  computed: {
    currentLocale() {
      return this.locales.find(l => l.value === this.status)
    },

  },
  setup() {
    /* eslint-disable global-require */
    const locales = [
      {
        value: 1,
        name: 'Accepting Orders',
      },
      {
        value: 0,
        name: 'Not Accepting Orders',
      },
      {
        value: 2,
        name: 'Closed for Today',
      },
      {
        value: 3,

        name: 'Closed for Noon',
      },

    ]
    /* eslint-disable global-require */

    return {
      locales,
    }
  },
  data() {
    return {

      status: {},
      type: {},
      store_userData: JSON.parse(localStorage.getItem('store_userData')),


    }
  },
  directives: {
    Ripple,
  },
  created() {

    this.status.id = localStorage.id;
    this.$http.post('/store/restaurant-status-toggle1', this.status)
      .then(res => {
        this.status = res.data.is_open;
        this.status.noon = res.data.noon;
      })


    // this.$http.post('/store/validate_plan', this.status)
    //   .then(res => {
    //     if (res.data.status == true) {

    //     } else {
    //       console.log(res)
    //       this.$swal({
    //         title: 'Hello ' + this.store_userData.fullName + ', Your Plan has be Expired',
    //         text: "Please Upgrade Plan to Continue using Teerya",
    //         icon: 'warning',
    //         showCancelButton: false,
    //         allowOutsideClick: false,
    //         allowEscapeKey: false,
    //         confirmButtonText: 'Upgrade Plan',
    //         customClass: {
    //           confirmButton: 'btn btn-primary',
    //           cancelButton: 'btn btn-outline-danger ml-1',
    //         },
    //         buttonsStyling: false,
    //       }).then(result => {
    //         if (result.value) {
    //           this.$router.push({ name: "upgrade_plan" })

    //         } else {
    //           console.log("closed")
    //         }
    //       })
    //     }
    //   })



  },
  methods: {
    doSomething(value) {
      this.type.id = localStorage.id;
      this.type.status = value;
      this.$http.post('/store/restaurant-status-toggle', this.type)
        .then(res => {
          if (res.data.status == true) {
            this.status = res.data.is_open;
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: res.data.message,
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
          } else {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: res.data.message,
                icon: 'CheckIcon',
                variant: 'danger',
              },
            })
          }
        })
    }
  }
}
</script>
<style lang="css">
@import '~@resources/css/font/fonts/font-awesome/css/all.css';

.status {
  border-radius: 1.5rem !important;
  border: 1px solid #837878 !important;
  color: #070707 !important;
  font-weight: bold;
}
</style>
  
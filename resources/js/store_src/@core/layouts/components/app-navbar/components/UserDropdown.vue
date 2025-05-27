<template>
  <b-nav-item-dropdown right toggle-class="d-flex align-items-center dropdown-user-link" class="dropdown-user">
    <template #button-content>
      <div class="d-sm-flex d-none user-nav">
        <p class="user-name font-weight-bolder mb-0">
          {{ store_userData.fullName || store_userData.username }}
        </p>
        <input type="hidden" id="hidden" :value="store_userData.id">
      </div>
      <b-avatar size="40" :src="store_userData.avatar" variant="light-primary" badge class="badge-minimal"
        badge-variant="success">
        <feather-icon v-if="!store_userData.fullName" icon="UserIcon" size="22" />
      </b-avatar>
    </template>

    <!-- <b-dropdown-item :to="{ name: 'upgrade_plan' }" link-class="d-flex align-items-center">
      <feather-icon size="16" icon="CreditCardIcon" class="mr-50" />
      <span>Plan</span>
    </b-dropdown-item> -->

    <b-dropdown-item link-class="d-flex align-items-center" @click="logout">
      <feather-icon size="16" icon="LogOutIcon" class="mr-50" />
      <span>Logout</span>
    </b-dropdown-item></b-nav-item-dropdown>
</template>
<script>
import {
  BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,
} from 'bootstrap-vue'
import { initialAbility } from '@@/libs/acl/config'
import useJwt from '@@/auth/jwt/useJwt'
import { avatarText } from '@@core/utils/filter'
import { BButton } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
export default {
  components: {
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,
    BButton
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      store_userData: JSON.parse(localStorage.getItem('store_userData')),
      avatarText,
      message: 'Hello, world!'
    }
  },
  mounted() {
    document.addEventListener('visibilitychange', this.handleVisibilityChange);
  },
  beforeDestroy() {
    document.removeEventListener('visibilitychange', this.handleVisibilityChange);
  },
  methods: {
    handleVisibilityChange() {

      if (document.hidden) {

      } else {

        var old = document.getElementById("hidden").value;

        var now = JSON.parse(localStorage.getItem('store_userData'));
        if (old != now.id) {
          this.$swal({
            title: 'Hello ' + this.store_userData.fullName + ', Your Session has be Expired',
            text: "Current Tab will be Closed",
            icon: 'warning',
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Yes, Close it!',
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-outline-danger ml-1',
            },
            buttonsStyling: false,
          }).then(result => {
            if (result.value) {
              // window.close();
              location.reload();
              var newURL = window.location.origin + "/store/dashboard"

              window.location.replace(newURL);
            } else {
              console.log("closed")
            }
          })
        }
      }
    },

    show_close() {

    },
    logout() {

      localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)

      localStorage.removeItem('store_userData')
      this.$ability.update(initialAbility)

      this.$router.push({ name: 'auth-login' })
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>

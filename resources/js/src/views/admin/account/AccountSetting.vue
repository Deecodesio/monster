<template>
  <b-tabs
    vertical
    content-class="col-12 col-md-9 mt-1 mt-md-0"
    pills
    nav-wrapper-class="col-md-3 col-12"
    nav-class="nav-left"
  >

    <!-- general tab -->
    <b-tab active>

      <!-- title -->
      <template #title>
        <feather-icon
          icon="UserIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">General</span>
      </template>

      <account-setting-general
        v-if="options1.user"
        :general-data="options.general"
      />
    </b-tab>
    <!--/ general tab -->

    <!-- change password tab -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon
          icon="LockIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Change Password</span>
      </template>

      <account-setting-password />
    </b-tab>
    <!--/ change password tab -->

   

    <!-- notification -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon
          icon="BellIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Notifications</span>
      </template>

      <account-setting-notification
        v-if="options.notification"
        :notification-data="options.notification"
      />
    </b-tab>
  </b-tabs>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import AccountSettingGeneral from './AccountSettingGeneral.vue'
import AccountSettingPassword from './AccountSettingPassword.vue'
import AccountSettingNotification from './AccountSettingNotification.vue'

export default {
  components: {
    BTabs,
    BTab,
    AccountSettingGeneral,
    AccountSettingPassword,
  
    AccountSettingNotification,
  },
  data() {
    return {
      options: {},
      options1: {},
    }
  },
  beforeCreate() {
    this.$http.get('/account-setting/data').then(res => { this.options = res.data })
    this.$http.get('/admin/user_details/'+localStorage.admin_id)
      .then(res => {
        this.options1 = res.data;
        console.log(this.options);
      })
  },
}
</script>

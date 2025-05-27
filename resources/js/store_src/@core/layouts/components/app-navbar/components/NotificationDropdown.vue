<template>
  <b-nav-item-dropdown
    class="dropdown-notification mr-25"
    menu-class="dropdown-menu-media"
    right
  >
    <template #button-content>
      
        <feather-icon
        v-if="notify_sound1"
      :badge="notify_new"
      badge-classes="bg-danger"
      class="text-body"
      icon="BellIcon"
      size="21"
      id="BellIcon"
      />
     
        <feather-icon
        v-else
      :badge="notify_new"
      badge-classes="bg-danger"
      class="text-body"
      icon="BellOffIcon"
      size="21"
      id="BellOffIcon"
     
      />
     
     
      
    </template>

    <!-- Header -->
    <li class="dropdown-menu-header">
      <div class="dropdown-header d-flex">
        <h4 class="notification-title mb-0 mr-auto">
          {{$t('Notifications')}}
          <b-badge
          pill
          variant="light-primary"
        >
        {{notify_new}}
        </b-badge>
        </h4>
       
        <div>
      <label>
        Sound
      </label>
     
        <b-form-checkbox
        :checked="notify_sound1"
        class="custom-control-primary"
        name="check-button"
        switch
        @change="enable_sound($event)"
       >
        <span class="switch-icon-left">
          <feather-icon icon="BellIcon" />
        </span>
        <span class="switch-icon-right">
          <feather-icon icon="BellOffIcon" />
        </span>
      </b-form-checkbox>
      
    </div>
        
      </div>
    </li>

    <vue-perfect-scrollbar
    
      class="scrollable-container media-list scroll-area"
      tagname="li"
    >
   
      <!-- Account Notification -->
      <b-link
        v-for="notifications in notification_new"
        :key="notifications.order_id"
        :to="{ name: 'new' }"
      >
    
        <b-media>
          <!-- <template #aside>
            <b-avatar
              size="32"
              :src="notification.avatar"
              :text="notification.avatar"
              :variant="notification.type"
            />
          </template> -->
          <p class="media-heading">
            <span class="font-weight-bolder">
             {{$t('new')}} {{$t('order')}} {{$t('received')}}  - {{ notifications.order_id }}
            </span>
          </p>
          <!-- <h5>{{ notifications.restaurants.restaurant_name }}</h5> -->
          <small class="notification-text font-weight-bolder">{{  notifications.restaurants.restaurant_name }}</small> 
        </b-media>
      </b-link>

      <!-- System Notification Toggler -->
      <!-- <div class="media d-flex align-items-center">
        <h6 class="font-weight-bolder mr-auto mb-0">
          System Notifications
        </h6>
        <b-form-checkbox
          :checked="true"
          switch
        />
      </div> -->

      <!-- System Notifications -->
      <!-- <b-link
        v-for="notification in systemNotifications"
        :key="notification.subtitle"
      >
        <b-media>
          <template #aside>
            <b-avatar
              size="32"
              :variant="notification.type"
            >
              <feather-icon :icon="notification.icon" />
            </b-avatar>
          </template>
          <p class="media-heading">
            <span class="font-weight-bolder">
              {{ notification.title }}
            </span>
          </p>
          <small class="notification-text">{{ notification.subtitle }}</small>
        </b-media>
      </b-link> -->
    </vue-perfect-scrollbar>
    <!-- Cart Footer -->
    <!-- <li class="dropdown-menu-footer"><b-button
      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
      variant="primary"
      block
    >Read all notifications</b-button>
    </li> -->
    <audio id="myAudio" src="https://www.deliware.app/audio/notification.mp3" type="audio/mpeg">
    <!-- <source src="https://www.deliware.app/audio/notification.mp3" type="audio/mpeg"> -->
  </audio>
  <b-button
      v-ripple.400="'rgba(113, 102, 240, 0.15)'"
      variant="outline-primary"
      @click="$bvToast.show('example-toast')"
      id="toast"
      hidden="hidden"
    >
    </b-button>
   <!-- toast -->
   <b-toast id="example-toast" toaster="b-toaster-bottom-right" variant="primary" solid >
      <template #toast-title>
        <div class="d-flex flex-grow-1 align-items-center mr-1" >
         
          <strong class="mr-auto"> {{$t('new')}} {{$t('order')}}</strong>
        </div>
      </template>
      <router-link :to="{ name: 'new'}">
        <span>{{$t('you')}} {{$t('have')}} {{$t('new')}} {{$t('order')}}, {{$t('Please')}} {{$t('Check')}}... :)</span>
     </router-link>
     
    </b-toast>
  </b-nav-item-dropdown>
 
</template>

<script>
import {
  BNavItemDropdown, BBadge, BMedia, BLink, BAvatar, BButton, BFormCheckbox,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


export default {
  components: {
    BNavItemDropdown,
    BBadge,
    BMedia,
    BLink,
    BAvatar,
    VuePerfectScrollbar,
    BButton,
    BFormCheckbox,
  },
  directives: {
    Ripple,
  },
  setup() {
    /* eslint-disable global-require */
    const notifications = [
      {
        title: 'Congratulation Sam 🎉',
        avatar: require('@/assets/images/avatars/6-small.png'),
        subtitle: 'Won the monthly best seller badge',
        type: 'light-success',
      },
      {
        title: 'New message received',
        avatar: require('@/assets/images/avatars/9-small.png'),
        subtitle: 'You have 10 unread messages',
        type: 'light-info',
      },
      {
        title: 'Revised Order 👋',
        avatar: 'MD',
        subtitle: 'MD Inc. order updated',
        type: 'light-danger',
      },
    ]
    /* eslint-disable global-require */

    const systemNotifications = [
      {
        title: 'Server down',
        subtitle: 'USA Server is down due to hight CPU usage',
        type: 'light-danger',
        icon: 'XIcon',
      },
      {
        title: 'Sales report generated',
        subtitle: 'Last month sales report generated',
        type: 'light-success',
        icon: 'CheckIcon',
      },
      {
        title: 'High memory usage',
        subtitle: 'BLR Server using high memory',
        type: 'light-warning',
        icon: 'AlertTriangleIcon',
      },
    ]

    const perfectScrollbarSettings = {
      maxScrollbarLength : 60,
      wheelPropagation : false,
    }

    return {
      notifications,
      systemNotifications,
      perfectScrollbarSettings,
    }
  },


  data() {
    return {
      notification_new:[],
      notify_new:[],
      notify_sound1: true,

    }
  },

  created() {
    this.$http.get('/store/get_neworder_alerts/'+localStorage.id)
      .then(res => {
             this.notification_new = res.data.all;
             this.notify_new = this.notification_new.length;
             var sound =   localStorage.notify_sound1;
         
             if(sound != 0){
                this.notify_sound1 = true;
              }
              else
              {
                this.notify_sound1 = false;
              }
      })
    window.setInterval(() => {
      this.$http.get('/store/get_neworder_alerts/'+localStorage.id)
      .then(res => {
             this.notification_new = res.data.all;
             this.notify_new = this.notification_new.length;
            
            if(res.data.data.status === 0)
            {

              //  this.makeToast('success');
              var audio = document.getElementById("myAudio");
              var sound =   localStorage.notify_sound1;
              if(sound != 0){
                this.notify_sound1 = true;
                audio.play();
              }
              else
              {
                this.notify_sound1 = false;
              }
   
                document.getElementById("toast").click();
              
            }
      })
    },60*1000);
  },
  methods: {

   
    enable_sound($event)
    {
      if($event == true)
      {
       localStorage.notify_sound1 = 1;
       this.notify_sound1 = true;
       this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: "Notofication Sound On",
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })

      }
      else{
        localStorage.notify_sound1 = 0;
        this.notify_sound1 = false;

        this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: "Notofication Sound Off",
                  icon: 'CheckIcon',
                  variant: 'warning',
                },
              })
      }
    }
  },
}
</script>

<style>

</style>

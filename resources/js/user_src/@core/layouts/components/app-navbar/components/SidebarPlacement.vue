<template>
  <b-card title="Sidebar Placement">
    <b-card-text>
      <span>By default the sidebar will be placed on the left side fo the viewport. Set the </span>
      <code>right</code>
      <span> prop to </span>
      <code>true</code>
      <span> to have the sidebar appear on the right side of the viewport.</span>
    </b-card-text>

    <div>
      <b-button
        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
        v-b-toggle.sidebar-right
        variant="outline-primary"
      >
        Toggle Sidebar
      </b-button>
      <b-sidebar 
        id="sidebar-right"
        bg-variant="white"
        right
        backdrop
        shadow
      >
        <sidebar-content />
      </b-sidebar>
    </div>

   
  </b-card>
</template>

<script>
import {
  BButton, BSidebar, VBToggle, BCardText, BCard,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import SidebarContent from './SidebarContent.vue'

export default {
  components: {
    BButton,
    BSidebar,
    SidebarContent,
    BCardText,
    BCard,
  },
  directives: {
    'b-toggle': VBToggle,
    Ripple,
  },
  data() {
    return {
    
    }
  },
}
</script>

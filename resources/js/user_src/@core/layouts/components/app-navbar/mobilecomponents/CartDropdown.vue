<template>
      <div class="pt-1">
      <b-button
        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
        v-b-toggle.sidebar-right
        variant="btn btn-gradient-primary btnpad btn-lg"
        v-if="$store.state['deliware_cart'].cartItemsCount && !is_checkout"
      >
      <feather-icon
        icon="ShoppingCartIcon"
        class="mr-50"
      />
      <span class="align-middle" v-if="$store.state['deliware_cart'].cartItemsCount">{{$store.state['deliware_cart'].cartItemsCount}} Items</span>
      </b-button>
      <b-sidebar
        id="sidebar-right"
        bg-variant="white"
        right
        backdrop
        shadow
        no-header
      >
        <sidebar-content  />
      </b-sidebar>
    </div>

</template>

<script>
import {
  BNavItemDropdown, BBadge, BMedia, BLink, BImg, BFormSpinbutton, BButton,BSidebar,VBToggle,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import Ripple from 'vue-ripple-directive'
import SidebarPlacement from './SidebarPlacement.vue'
import SidebarContent from './SidebarContent.vue'

export default {
  components: {
    BNavItemDropdown,
    BBadge,
    BMedia,
    BLink,
    BImg,
    BFormSpinbutton,
    VuePerfectScrollbar,
    BButton,
    SidebarPlacement,
    BSidebar,
    SidebarContent,
  },
  directives: {
    'b-toggle': VBToggle,
    Ripple,
  },
  data() {
    return {
      items: [],
      perfectScrollbarSettings: {
        maxScrollbarLength: 60,
        wheelPropagation: false,
        is_checkout : false,
      },
    }
  },
  computed: {
    totalAmount() {
      let total = 0
      this.items.forEach(i => { total += i.price })
      return total
    },
  },
 
  methods: {
    fetchItems() {
      this.$store.dispatch('app-ecommerce/fetchCartProducts')
        .then(response => {
          this.items = response.data.products
        })
    },
    removeItemFromCart(productId) {
      this.$store.dispatch('app-ecommerce/removeProductFromCart', { productId })
        .then(() => {
          const itemIndex = this.items.findIndex(p => p.id === productId)
          this.items.splice(itemIndex, 1)

          // Update count in cart items state
          this.$store.commit('app-ecommerce/UPDATE_CART_ITEMS_COUNT', this.items.length)
        })
    },
  },
 
}
</script>

<style lang="scss" scoped>
.dropdown-cart {
  .media {
    .media-aside {
      align-items: center;
    }
  }
  .btnpad {padding:15px 10px !important;}
}
</style>

<template class="text-centre">
  <div class="buy-now demo-inline-spacing" v-if="$store.state['deliware_cart'].cartItemsCount && this.$route.name !== 'checkout' && this.$route.name !== 'pay_for_order' && this.$route.name !== 'business-signup' && this.$route.name !== 'rider-signup'">
    <b-button style="    height: 50px;"
      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
      block
      variant="primary"


        v-b-toggle.sidebar-right
      rel="noopener noreferrer"
    >
    <feather-icon
        icon="ShoppingCartIcon"
        class="mr-50"
      />
      <span class="align-middle" v-if="$store.state['deliware_cart'].cartItemsCount">{{$store.state['deliware_cart'].cartItemsCount}} Items</span>

      <span class="align-middle" v-if="!$store.state['deliware_cart'].cartItemsCount">My Cart </span>
    </b-button>
    <b-sidebar
        id="sidebar-right"
        bg-variant="white"
        right
        backdrop
        shadow
      >
      <sidebar-content
        :cart = "$store.state['deliware_cart'].cart_items"

        />
      </b-sidebar>
  </div>
</template>

<script>
import {
  BNavItemDropdown, BBadge, BMedia, BLink, BImg, BFormSpinbutton, BButton,BSidebar,VBToggle,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import SidebarPlacement from '@@@core/layouts/components/app-navbar/components/SidebarPlacement.vue'
import SidebarContent from '@@@core/layouts/components/app-navbar/components/SidebarContent.vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
export default {
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
      },
      cart:{},
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
      if(localStorage.getItem("cart")){
      this.cart = store.state['deliware_cart'].cart_items
      console.log(this.cart)
    }
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
}
</script>

<style>
.buy-now {
    right: 0px;
    left:0px;
    margin:0px 20px;
    width: 90%;

}
[dir] .buy-now .btn {
    -webkit-box-shadow: 0 1px 20px 1px #00a082 !important;
    box-shadow: 0 1px 20px 1px #00a082 !important;

}
</style>

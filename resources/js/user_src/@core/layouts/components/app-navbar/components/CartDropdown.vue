<template>
    <div class="card">
        <!-- <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-toggle.sidebar-right @click="goToCart" -->
        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-toggle.sidebar-right @click="goToCart"
            variant="btn btn-gradient-primary btnpad" v-if="!['checkout', 'my_cart', 'delivery_address'].includes($route.name)">
            <feather-icon icon="ShoppingCartIcon" class="mr-50 ico-color3" />
            <span class="align-middle" v-if="$store.state['deliware_cart'].cartItemsCount">{{
                $store.state['deliware_cart'].cartItemsCount }}
                {{ $t("Items") }}</span>

            <span class="align-middle" v-if="!$store.state['deliware_cart'].cartItemsCount">{{ $t("My Cart") }} </span>
        </b-button>

        <!-- <b-sidebar id="sidebar-right" bg-variant="white" right backdrop shadow ref="sidebar"
            sidebar-class="custom-sidebar">
            <template #header="{ hide }">
                <div class="custom-cart-header">
                    <h4 class="cart_title">{{ $t("My Cart") }}</h4>
                    <feather-icon icon="XIcon" size="30" class="custom-close" @click="hide" />
                </div>
            </template>
            <sidebar-content :cart="$store.state['deliware_cart'].cart_items" />
        </b-sidebar> -->
    </div>

</template>

<script>
import {
    BNavItemDropdown, BBadge, BMedia, BLink, BImg, BFormSpinbutton, BButton, BSidebar, VBToggle,
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
            },
            cart: {},
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
          goToCart() {
    this.$router.push({ name: 'my_cart' }); // use your route name or path here
  },
        fetchItems() {
            if (localStorage.getItem("cart")) {
                this.cart = store.state['deliware_cart'].cart_items
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
        close_sidebar() {
            alert("hii");
        },
    },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/base/pages/app-ecommerce.scss';
@import '~vue-form-wizard/dist/vue-form-wizard.min.css';
</style>
<style lang="scss" scoped>
.dropdown-cart {
    .media {
        .media-aside {
            align-items: center;
        }
    }

    .btnpad {
        padding: 15px 10px !important;
    }
}

.sidebar-content-wrapper {
    max-height: calc(500px - 100px - 60px); 
}

.custom-sidebar {
    max-height: 60vh;
    /* 60% of viewport height */
    overflow-y: auto;
}

.custom-cart-header {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 10px;
    background-color: white;
}

.cart_title {
    font-size: 20px;
    text-align: center;
    margin: 0 0 auto;
    padding-top: 0%;
}

.custom-close {
    position: absolute;
    right: 12px;
    align-items: top;
    cursor: pointer;
    background-color: #FF0000;
    color: white;
    width: 26;
    height: 26;
    border-radius: 50%;


}
</style>

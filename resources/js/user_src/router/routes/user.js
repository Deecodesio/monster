export default [
  {
    path: '/',
    name: 'home',
    component: () => import("@@@/views/user/business/home.vue"),
    // component: () => import('@@@/views/user/home/home.vue'),
    meta: {
      resource: 'dashboard',
      action: 'read',
    },
  },

  {
    path: '/comingsoon',
    name: 'demo',
    component: () => import('@@@/views/user/home/demo.vue'),
    meta: {
      resource: 'dashboard',
      action: 'read',
    },
  },


  {
    path: "/restaurant/:id/:name",
    name: "view_restaurant",
    component: () => import("@@@/views/user/restaurants/view_restaurant.vue"),


  },

  {
    path: "/product/:slug",
    name: "product",
    component: () => import("@@@/views/user/business/product_view.vue"),


  },

  {
    path: "/cancellation",
    name: "cancellation",
    component: () => import("@@@/views/user/users/cancellation.vue"),


  },

  {
    path: "/shippinganddeliverypolicy",
    name: "shipping",
    component: () => import("@@@/views/user/users/shipping.vue"),


  },

  {
    path: "/business-signup",
    name: "business-signup",
    component: () => import("@@@/views/user/restaurants/add_restaurant.vue"),


  },


  {
    path: "/rider-signup",
    name: "rider-signup",
    component: () => import("@@@/views/user/restaurants/add_driver.vue"),


  },
  {
    path: "/checkout",
    name: "checkout",
    component: () => import("@@@/views/user/restaurants/checkout.vue"),


  },

  {
    path: "/more_restaurant",
    name: "more_restaurant",
    component: () => import("@@@/views/user/restaurants/more_restaurant.vue"),


  },
  {
    path: "/brand/:id/:name",
    name: "brand_rest",
    component: () => import("@@@/views/user/restaurants/brand_rest.vue"),


  },

  {
    path: "/profile",
    name: "profile",
    component: () => import("@@@/views/user/users/profile.vue"),
  },

  {
    path: "/category/:id/:name",
    name: "cat_rest",
    component: () => import("@@@/views/user/restaurants/categories.vue"),
  },

  {
    path: "/categories/:slug",
    name: "cat_list",
    component: () => import("@@@/views/user/business/category_list.vue"),
  },

  {
    path: "/my_cart",
    name: "my_cart",
    component: () => import("@@@/views/user/business/my_cart.vue"),
  },

   {
    path: "/delivery_address",
    name: "delivery_address",
    component: () => import("@@@/views/user/business/address.vue"),
  },

  {
    path: "/sub_categories/:slug",
    name: "sub_cat_list",
    component: () => import("@@@/views/user/business/category_list.vue"),
  },

  {
    path: "/track_user_order",
    name: "track_user_order",
    component: () => import("@@@/views/user/business/track.vue"),


  },


  {
    path: "/about_us",
    name: "about_us",
    component: () => import("@@@/views/user/users/aboutus.vue"),


  },
  {
    path: "/privacypolicy",
    name: "privacypolicy",
    component: () => import("@@@/views/user/users/privacy_policy.vue"),


  },
  {
    path: "/terms_conditions",
    name: "terms_conditions",
    component: () => import("@@@/views/user/users/terms.vue"),


  },
  {
    path: "/contact",
    name: "contact",
    component: () => import("@@@/views/user/users/contact.vue"),


  },
  {
    path: "/faq",
    name: "faq",
    component: () => import("@@@/views/user/users/faq.vue"),


  },
  {
    path: "/pay_for_order/:id/:name",
    name: "pay_for_order",
    component: () => import("@@@/views/user/restaurants/pay_for_order.vue"),

  },
  {
    path: "/pay_for_order_razorpay/:id",
    name: "pay_for_order_razorpay",
    component: () => import("@@@/views/user/restaurants/pay_for_order_razorpay.vue"),

  },
  {
    path: "/trackorder/:id",
    name: "trackorder",
    component: () => import("@@@/views/user/restaurants/track_order.vue"),


  },

  {
    path: "/offers",
    name: "offers",
    component: () => import("@@@/views/user/restaurants/offers.vue"),


  },

  {
    path: "/login",
    name: "login",
    component: () => import("@@@/views/user/users/login.vue"),


  },

  {
    path: "/signup",
    name: "signup",
    component: () => import("@@@/views/user/users/signup.vue"),


  },

  {
    path: '/google-callback',
    component: {
      template: '<div class="auth-component"></div>'
    }
  },
  {
    path: "/business/:id/:name",
    name: "cat_list",
    component: () => import("@@@/views/user/business/home.vue"),


  },
  {
    path: "/pickupdrop/:id/:name",
    name: "pickupdrop",
    component: () => import("@@@/views/user/business/pickupdrop.vue"),


  },

  {
    path: "/pickupdrop_checkout",
    name: "pickupdrop_checkout",
    component: () => import("@@@/views/user/restaurants/pickupdrop_checkout.vue"),


  },

  {
    path: "/pickupdrop_trackorder/:id",
    name: "pickupdrop_trackorder",
    component: () => import("@@@/views/user/restaurants/pickupdrop_trackorder.vue"),


  },
  {
    path: "/verify-restaurant/:id/:code",
    name: "verify-restaurant",
    component: () => import("@@@/views/user/restaurants/verify-restaurant.vue"),


  },
  {
    path: "/rider-verify/:id/:code",
    name: "rider-verify",
    component: () => import("@@@/views/user/restaurants/verify-driver.vue"),


  },
  {
    path: "/pay_for_wallet/:id/:name",
    name: "pay_for_wallet",
    component: () => import("@@@/views/user/restaurants/add_wallet.vue"),

  },
  {
    path: '/wallet',
    name: 'wallet',
    component: () => import("@@@/views/user/restaurants/wallet_msg.vue"),

  },
  {
    path: "/restaurant_checkout/:plan/:trans/:id",
    name: "restaurant_checkout",
    component: () => import("@@@/views/user/restaurants/restaurant_checkout.vue"),

  },

  {
    path: "/pages/:slug",
    name: "page",
    component: () => import("@@@/views/user/static_pages/page2.vue"),

  },


  // Blog routes
  {
    path: '/blogs',
    name: 'blogs',
    // component: () => import('@@@/views/user/business/blog/BlogList.vue'),

  },
  {
    path: '/blogs/:slug',
    name: 'blog-detail',
    // component: () => import('@@@/views/user/business/blog/BlogDetail.vue'),


  },
  // Career routes
  {
    path: '/careers',
    name: 'careers',
    // component: () => import('@@@/views/user/business/career/CareerList.vue'),
    meta: {
      publicRoute: true,
      contentRenderer: 'sidebar-left',
      permission: 'public',
      layout: 'full' // Added to ensure consistent layout
    }
  },
  {
    path: '/careers/:id',
    name: 'career-detail',
    // component: () => import('@@@/views/user/business/career/CareerDetail.vue'),
    props: true,
    meta: {
      publicRoute: true,
      permission: 'public',
      layout: 'full' // Added to ensure consistent layout
    }
  },
  // Franchise route
  {
    path: '/franchise',
    name: 'franchise',
    // component: () => import('@@@/views/user/business/franchise/FranchiseList.vue'),

  },
    {
    path: '/Bulk-Orders',
    name: 'BulkOrders',
    // component: () => import('@@@/views/user/business/Bulk-Orders.vue'),
   props: true,
  meta: {
    resource: 'dashboard',
    action: 'read',
  }
  },

 {
  path: "/shop-location/:city",
  name: "StoreList", // Must match name used in `this.$router.push({ name: 'StoreList', ... })`
  component: () => import("@@@/views/user/business/Shop-Location.vue"), // Must match the correct file name



  props: true,
  meta: {
    resource: 'dashboard',
    action: 'read',
  }
}


]

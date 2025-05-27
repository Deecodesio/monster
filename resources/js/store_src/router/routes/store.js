export default [
  {
    path: '/store/dashboard/analytics',
    name: 'dashboard-analytics',
    component: () => import('@@/views/store/dashboard/analytics/Analytics.vue'),
    meta: {
      resource: 'store_dashboard',
      action: 'read',
    },
  },
  {
    path: '/store/dashboard',
    name: 'dashboard-ecommerce',
    component: () => import('@@/views/store/dashboard/ecommerce/Ecommerce.vue'),
    meta: {
      resource: 'store_dashboard',
      action: 'read',
    },
  },

  {
    path: '/store/add_addons',
    name: 'edit_addon',
    component: () => import('@@/views/store/addon/add_addon.vue'),
    meta: {
      pageTitle: 'Edit Addon',
      breadcrumb: [
        {
          text: 'Addons',
        },
        {
          text: 'Edit Addon',
          active: true,
        },
      ],
      resource: 'store_addons-view',
      action: 'read',
    },

  },
  {
    path: '/store/addons_list',
    name: 'addons_list',
    component: () => import('@@/views/store/addon/addons_list.vue'),
    meta: {
      pageTitle: 'Addons List',
      breadcrumb: [
        {
          text: 'Addons',
        },
        {
          text: 'Addons List',
          active: true,
        },
      ],
      resource: 'store_addons-view',
      action: 'read',
    },

  },

  {
    path: '/store/restaurant',
    name: 'add_restaurant',
    component: () => import('@@/views/store/restaurants/add_restaurant.vue'),
    meta: {
      pageTitle: 'Store',
      breadcrumb: [
        {
          text: 'Store List',
        },
        {
          text: 'Add Store',
          active: true,
        },
      ],
      resource: 'store_restaurant',
      action: 'read',
    },

  },
  {
    path: '/store/cuisines',
    name: 'cuisines_list',
    component: () => import('@@/views/store/cuisine/cuisines.vue'),
    meta: {
      pageTitle: 'Cuisines List',
      breadcrumb: [
        {
          text: 'Cuisines',
        },
        {
          text: 'Cuisines List',
          active: true,
        },
      ],
      resource: 'store_cuisines-view',
      action: 'read',
    },

  },

  {
    path: '/store/add_cuisines',
    name: 'add_cuisine',
    component: () => import('@@/views/store/cuisine/add_cuisine.vue'),
    meta: {
      pageTitle: 'Add Cuisines',
      breadcrumb: [
        {
          text: 'Cuisines',
        },
        {
          text: 'Add Cuisines',
          active: true,
        },
      ],
      resource: 'store_cuisines-view',
      action: 'read',
    },

  },
  {
    path: '/store/edit_cuisines/:id',
    name: 'edit_cuisines',
    component: () => import('@@/views/store/cuisine/add_cuisine.vue'),
    meta: {
      pageTitle: 'Edit Cuisines',
      breadcrumb: [
        {
          text: 'Cuisines',
        },
        {
          text: 'Edit Cuisines',
          active: true,
        },
      ],
      resource: 'store_cuisines-view',
      action: 'read',
    },

  },
  {
    path: '/store/menu_list',
    name: 'menu_list',
    component: () => import('@@/views/store/menu/menu_list.vue'),
    resource: 'menu-view',
    action: 'read',
    meta: {
      pageTitle: 'Menu List',
      breadcrumb: [
        {
          text: 'Menu',
        },
        {
          text: 'Menu List',
          active: true,
        },
      ],
      resource: 'store_menus',
      action: 'read',
    },

  },

  {
    path: '/store/menus_ranking',
    name: 'menu_ranking',
    component: () => import('@@/views/store/menu/menu_ranking.vue'),
    resource: 'menu-view',
    action: 'read',
    meta: {
      pageTitle: 'Menu Ranking',
      breadcrumb: [
        {
          text: 'Menu',
        },
        {
          text: 'Menu Ranking',
          active: true,
        },
      ],
      resource: 'store_menus',
      action: 'read',
    },

  },

  {
    path: '/store/products_list',
    name: 'product_list',
    component: () => import('@@/views/store/product/product_list.vue'),
    resource: 'product-view',
    action: 'read',
    meta: {
      pageTitle: 'Product List',
      breadcrumb: [
        {
          text: 'Product',
        },
        {
          text: 'Product List',
          active: true,
        },
      ],
      resource: 'store_product',
      action: 'read',
    },

  },

  {
    path: '/store/product',
    name: 'add_product',
    component: () => import('@@/views/store/product/add_product.vue'),

    meta: {
      pageTitle: 'Product Add',
      breadcrumb: [
        {
          text: 'Product',
        },
        {
          text: 'Product Add',
          active: true,
        },
      ],
      resource: 'store_product',
      action: 'read',
    },

  },
  {
    path: '/store/edit_product/:id',
    name: 'edit_product',
    component: () => import('@@/views/store/product/edit_product.vue'),

    meta: {
      pageTitle: 'Product Edit',
      breadcrumb: [
        {
          text: 'Product',
        },
        {
          text: 'Product Edit',
          active: true,
        },
      ],
      resource: 'store_product',
      action: 'read',
    },

  },
  {
    path: '/store/promocode_list',
    name: 'promocode_list',
    component: () => import('@@/views/store/promocodes/promocode_list.vue'),
    meta: {
      pageTitle: 'Promocode List',
      breadcrumb: [
        {
          text: 'Promocodes',
        },
        {
          text: 'Promocode List',
          active: true,
        },
      ],
      resource: 'store_promocodes',
      action: 'read',
    },

  },

  {
    path: '/store/add_promocode',
    name: 'add_promocode',
    component: () => import('@@/views/store/promocodes/add_promocode.vue'),
    meta: {
      pageTitle: 'Add Promocode',
      breadcrumb: [
        {
          text: 'Promocode',
        },
        {
          text: 'Add Promocode',
          active: true,
        },
      ],
      resource: 'store_promocodes',
      action: 'read',
    },

  },
  {
    path: '/store/edit_promocodes/:id',
    name: 'edit_promocode',
    component: () => import('@@/views/store/promocodes/add_promocode.vue'),
    meta: {
      pageTitle: 'Edit Promocode',
      breadcrumb: [
        {
          text: 'Promocode',
        },
        {
          text: 'Edit Promocode',
          active: true,
        },
      ],
      resource: 'store_promocodes',
      action: 'read',
    },

  },

  {
    path: '/store/restaurant_report',
    name: 'restaurant_report',
    component: () => import('@@/views/store/reports/order_report.vue'),
    meta: {
      pageTitle: 'Store Report',
      breadcrumb: [
        {
          text: 'Store Report',
        },
        {
          text: 'Store Report',
          active: true,
        },
      ],


      resource: 'store_report-view',
      action: 'read',
    },

  },


  {
    path: '/store/restaurants_timings',
    name: 'restaurant_timing',
    component: () => import('@@/views/store/restaurants/restaurant_timing.vue'),
    meta: {
      pageTitle: 'Store Timing',
      breadcrumb: [
        {
          text: 'Store Timing',
        },
        {
          text: 'Store Timing',
          active: true,
        },
      ],


      resource: 'store_restaurant_timing',
      action: 'read',
    },

  },
  {
    path: '/store/riders_in_map',
    name: 'rider_in_map',
    component: () => import('@@/views/store/rider_map/rider_in_map.vue'),
    meta: {
      pageTitle: 'Rider Map',
      breadcrumb: [
        {
          text: 'Rider Map',
        },
        {
          text: 'Rider Map',
          active: true,
        },
      ],


      resource: 'store_map',
      action: 'read',
    },

  },

  {
    path: '/store/tables_list',
    name: 'table_list',
    component: () => import('@@/views/store/table/table_list.vue'),
    meta: {
      pageTitle: 'Table List',
      breadcrumb: [
        {
          text: 'Table',
        },
        {
          text: 'Table List',
          active: true,
        },
      ],


      resource: 'rider-view',
      action: 'read',
    },

  },

  {
    path: '/store/new',
    name: 'new',
    component: () => import('@@/views/store/ordermanagement/new_orders.vue'),

    meta: {
      pageTitle: 'New Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'New Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/processing',
    name: 'processing',
    component: () => import('@@/views/store/ordermanagement/processing.vue'),
    meta: {
      pageTitle: 'Processing Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'Processing Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/pickup',
    name: 'pickup',
    component: () => import('@@/views/store/ordermanagement/pickup.vue'),
    meta: {
      pageTitle: 'Pickup Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'Pickup Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/delivered',
    name: 'delivered',
    component: () => import('@@/views/store/ordermanagement/delivered.vue'),
    meta: {
      pageTitle: 'Delivered Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'Delivered Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/cancelled',
    name: 'cancelled',
    component: () => import('@@/views/store/ordermanagement/cancelled.vue'),
    meta: {
      pageTitle: 'Cancelled Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'Cancelled Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/failed',
    name: 'failed',
    component: () => import('@@/views/store/ordermanagement/failed.vue'),
    meta: {
      pageTitle: 'Failed Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'Failed Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/abandon',
    name: 'abandon',
    component: () => import('@@/views/store/ordermanagement/abandon.vue'),
    meta: {
      pageTitle: 'Abandon Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'Abandon Orders',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/view_orders/:id',
    name: 'view_order',
    // component: () => import('@/views/ordermanagement/view_order.vue'),
    component: () => import('@@/views/apps/invoice/invoice-preview/view_order.vue'),

    meta: {
      pageTitle: 'Orders',
      breadcrumb: [
        {
          text: 'Order Management',
        },
        {
          text: 'View Order',
          active: true,
        },
      ],
      resource: 'store_order-view',
      action: 'read',
    },

  },
  {
    path: '/store/revise_order/:id',
    name: 'revise_order',
    // component: () => import('@/views/ordermanagement/view_order.vue'),
    component: () => import('@@/views/store/ordermanagement/revise_order.vue'),
    meta: {
      pageTitle: 'POS',

      resource: 'addon',
      action: 'read',
    },
  },
  {
    path: '/store/pos',
    name: 'pos',
    // component: () => import('@/views/ordermanagement/view_order.vue'),
    component: () => import('@@/views/store/ordermanagement/pos.vue'),
    meta: {
      pageTitle: 'POS',

      resource: 'addon',
      action: 'read',
    },
  },
  {
    path: '/store/add_menu',
    name: 'add_menu',
    component: () => import('@@/views/store/menu/add_menu.vue'),
    meta: {
      pageTitle: 'Add Menu',
      breadcrumb: [
        {
          text: 'Add Menu',
        },
        {
          text: 'Add Menu',
          active: true,
        },
      ],
      resource: 'addon',
      action: 'read',
    },

  },
  {
    path: '/store/edit_menu/:id',
    name: 'edit_menu',
    component: () => import('@@/views/store/menu/edit_menu.vue'),
    meta: {
      pageTitle: 'Menu',
      breadcrumb: [
        {
          text: 'Edit Menu',
        },
        {
          text: 'Edit Menu',
          active: true,
        },
      ],
      resource: 'addon',
      action: 'read',
    },

  },

  {
    path: '/store/add_tables',
    name: 'add_table',
    component: () => import('@@/views/store/table/add_table.vue'),
    meta: {
      pageTitle: 'Table',
      breadcrumb: [
        {
          text: 'Add Table',
        },
        {
          text: 'Add Table',
          active: true,
        },
      ],
      resource: 'table',
      action: 'read',
    },

  },

  {
    path: '/store/print_table/:id',
    name: 'print_table',
    component: () => import('@@/views/store/table/print_table.vue'),
    meta: {
      layout: 'full',
    },
  },

  {
    path: '/store/category_lists',
    name: 'category_list',
    component: () => import('@@/views/store/category/category_list.vue'),
    meta: {
      pageTitle: 'Category List',
      breadcrumb: [
        {
          text: 'Category',
        },
        {
          text: 'Category List',
          active: true,
        },
      ],
      resource: 'store_category-view',
      action: 'read',
    },

  },
  {
    path: '/store/add_category',
    name: 'add_category',
    component: () => import('@@/views/store/category/add_category.vue'),
    meta: {
      pageTitle: 'Add Category',
      breadcrumb: [
        {
          text: 'Category',
        },
        {
          text: 'Add Category',
          active: true,
        },
      ],
      resource: 'store_category-view',
      action: 'read',
    },

  },
  {
    path: '/store/edit_categorys/:id',
    name: 'edit_category',
    component: () => import('@@/views/store/category/add_category.vue'),
    meta: {
      pageTitle: 'Edit Category',
      breadcrumb: [
        {
          text: 'Category',
        },
        {
          text: 'Edit Category',
          active: true,
        },
      ],
      resource: 'store_category-view',
      action: 'read',
    },

  },
  {
    path: '/store/category_sort',
    name: 'category_sort',
    component: () => import('@@/views/store/category/category_sort.vue'),
    meta: {
      pageTitle: 'Category Sort',
      breadcrumb: [
        {
          text: 'Category',
        },
        {
          text: 'Category Sort',
          active: true,
        },
      ],
      resource: 'store_category-view',
      action: 'read',
    },

  },


  {
    path: '/store/upgrade_plan',
    name: 'upgrade_plan',
    component: () => import('@@/views/store/restaurants/upgrade_plan.vue'),
    meta: {
      pageTitle: 'Plan',
      breadcrumb: [
        {
          text: 'upgrade Plan',
        },
        {
          text: 'Upgrade Plan',
          active: true,
        },
      ],
      resource: 'store_restaurant',
      action: 'read',
    },

  },

  {
    path: '/store/plan_details/:id',
    name: 'plan_details',
    component: () => import('@@/views/store/restaurants/plan_details.vue'),
    meta: {
      pageTitle: 'Plan',
      breadcrumb: [
        {
          text: 'Upgrade Plan',
        },
        {
          text: 'Plan Details',
          active: true,
        },
      ],
      resource: 'store_restaurant',
      action: 'read',
    },

  },

  {
    path: "/restaurant_checkout/:plan/:trans/:id",
    name: 'restaurant_checkout',
    component: () => import('@@/views/store/restaurants/restaurant_checkout.vue'),
    meta: {
      pageTitle: 'Plan',
      breadcrumb: [
        {
          text: 'Upgrade Plan',
        },
        {
          text: 'Checkout',
          active: true,
        },
      ],
      resource: 'store_restaurant',
      action: 'read',
    },

  },



]
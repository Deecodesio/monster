export default [
 

 
  // *===============================================---*
  // *--------- ECOMMERCE  ---------------------------------------*
  // *===============================================---*
  {
    path: '/apps/e-commerce/shop',
    name: 'apps-e-commerce-shop',
    component: () => import('@/views/apps/e-commerce/e-commerce-shop/ECommerceShop.vue'),
    meta: {
      contentRenderer: 'sidebar-left-detached',
      contentClass: 'ecommerce-application',
      pageTitle: 'Shop',
      breadcrumb: [
        {
          text: 'ECommerce',
        },
        {
          text: 'Shop',
          active: true,
        },
      ],
    },
  },
  {
    path: '/apps/e-commerce/wishlist',
    name: 'apps-e-commerce-wishlist',
    component: () => import('@/views/apps/e-commerce/e-commerce-wishlist/ECommerceWishlist.vue'),
    meta: {
      pageTitle: 'Wishlist',
      contentClass: 'ecommerce-application',
      breadcrumb: [
        {
          text: 'ECommerce',
        },
        {
          text: 'Wishlist',
          active: true,
        },
      ],
    },
  },
  {
    path: '/apps/e-commerce/checkout',
    name: 'apps-e-commerce-checkout',
    component: () => import('@/views/apps/e-commerce/e-commerce-checkout/ECommerceCheckout.vue'),
    meta: {
      pageTitle: 'Checkout',
      contentClass: 'ecommerce-application',
      breadcrumb: [
        {
          text: 'ECommerce',
        },
        {
          text: 'Checkout',
          active: true,
        },
      ],
    },
  },
  {
    path: '/apps/e-commerce/:slug',
    name: 'apps-e-commerce-product-details',
    component: () => import('@/views/apps/e-commerce/e-commerce-product-details/ECommerceProductDetails.vue'),
    meta: {
      pageTitle: 'Product Details',
      contentClass: 'ecommerce-application',
      breadcrumb: [
        {
          text: 'ECommerce',
        },
        {
          text: 'Shop',
          active: true,
        },
        {
          text: 'Product Details',
          active: true,
        },
      ],
    },
  },

  // Invoice
  {
    path: '/apps/invoice/list',
    name: 'apps-invoice-list',
    component: () => import('@/views/apps/invoice/invoice-list/InvoiceList.vue'),
  },
  {
    path: '/apps/invoice/preview/:id',
    name: 'apps-invoice-preview',
    component: () => import('@/views/apps/invoice/invoice-preview/InvoicePreview.vue'),
  },
  {
    path: '/apps/invoice/add/',
    name: 'apps-invoice-add',
    component: () => import('@/views/apps/invoice/invoice-add/InvoiceAdd.vue'),
  },
  {
    path: '/apps/invoice/edit/:id',
    name: 'apps-invoice-edit',
    component: () => import('@/views/apps/invoice/invoice-edit/InvoiceEdit.vue'),
  },

  
]

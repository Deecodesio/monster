export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Product',
      icon: 'PackageIcon',
      children: [
        {
          title: 'Product List',
          route: 'product_list',
          resource: 'store_product',
          action: 'read',
        },
        // {
        //   title: 'Add Product',
        //   route: 'add_product',
        //  resource: 'store_product',
        //   action: 'read',
        // },
      
      ],
    },

  ]
  
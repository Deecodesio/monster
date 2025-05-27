export default [
  // {
  //   header: 'Order management',
  // },

  {
    title: 'Stores',
    icon: 'ShoppingCartIcon',
    children: [
      {
        title: 'Stores List',
        route: 'restaurant_list',
        resource: 'restaurant-view',
        action: 'read',
      },
      {
        title: 'Add Stores',
        route: 'add_restaurant',
        resource: 'restaurant-edit',
        action: 'read',
      },
      {
        title: 'Eagles View',
        route: 'eagle_view',
        resource: 'restaurant-edit',
        action: 'read',
      },

    ],
  },

]

export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Restaurants',
      icon: 'CoffeeIcon',
      children: [
        {
          title: 'Restaurants List',
          route: 'restaurant_list',
          resource: 'restaurant-view',
          action: 'read',
        },
        {
          title: 'Add Restaurant',
          route: 'add_restaurants',
          resource: 'restaurant-edit',
          action: 'read',
        },
       
      ],
    },

  ]
  
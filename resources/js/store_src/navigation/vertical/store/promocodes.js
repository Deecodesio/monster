export default [

    {
      title: 'Promocodes',
      icon: 'GiftIcon',
      children: [
        {
          title: 'Promocodes List',
          route: 'promocode_list',
          resource: 'store_promocodes',
          action: 'read',
        },
 
        {
            title: 'Add Promocode',
            route: 'add_promocode',
             resource: 'store_promocodes',
          action: 'read',
          },
      ],
    },

  ]
  
export default [

    {
      title: 'Promocodes',
      icon: 'GiftIcon',
      children: [
        {
          title: 'Promocodes List',
          route: 'promocode_list',
          resource: 'promocodes',
          action: 'read',
        },
 
        {
            title: 'Add Promocodes',
            route: 'add_promocode',
            resource: 'promocodes',
            action: 'read',
          },
          {
            title: 'Custom Push',
            route: 'custom_push',
            resource: 'promocodes',
            action: 'read',
          },
          {
            title: 'Admin Custom Push',
            route: 'admin_custom_push',
            resource: 'promocodes',
            action: 'read',
          },
       
      ],
    },

  ]
  
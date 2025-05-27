export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Driver Management',
      icon: 'TruckIcon',
      children: [
        {
          title: 'Delivery People',
          route: 'driver_list',
          resource: 'driver',
          action: 'read',
        },
        {
          title: 'New Driver',
          route: 'new_driver_list',
          resource: 'driver',
          action: 'read',
      
        },
        {
            title: 'Add Driver',
            route: 'add-driver',
            resource: 'driver',
          action: 'read',
        
          },
          {
            title: 'Riders In Map',
            route: 'rider_in_map',
            resource: 'driver',
            action: 'read',
          },
       
      ],
    },

  ]
  
export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Driver Management',
      icon: 'TruckIcon',
      children: [
        {
          title: 'Driver List',
          route: 'driver_list',
          resource: 'driver-view',
          action: 'read',
        },
        {
          title: 'Pending Driver List',
          route: 'new_driver_list',
          resource: 'driver-view',
          action: 'read',
      
        },
        {
            title: 'Add Driver',
            route: 'add-driver',
            resource: 'driver-edit',
          action: 'read',
        
          },
          {
            title: 'Riders In Map',
            route: 'rider_in_map',
            resource: 'driver-view',
            action: 'read',
          },
       
      ],
    },

  ]
  
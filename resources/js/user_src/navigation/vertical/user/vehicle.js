export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Vehicle management',
      icon: 'TruckIcon',
      children: [
        {
          title: 'Vehicle List',
          route: 'vehicles_list',
          resource: 'vehicles',
          action: 'read',
        },
        {
          title: 'Add Vehicle',
          route: 'add_vehicle',
          resource: 'vehicles',
          action: 'read',
        },
       
      ],
    },

  ]
  
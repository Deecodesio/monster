export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Vehicle Management',
      icon: 'TruckIcon',
      children: [
        {
          title: 'Vehicle List',
          route: 'vehicles_list',
          resource: 'vehicle-view',
          action: 'read',
        },
        {
          title: 'Add Vehicle',
          route: 'add_vehicle',
          resource: 'vehicle-edit',
          action: 'read',
        },
       
      ],
    },

  ]
  
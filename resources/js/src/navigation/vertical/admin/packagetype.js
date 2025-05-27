export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Package Type',
      icon: 'BarChart2Icon',
      children: [
        {
          title: 'Package List',
          route: 'package_list',
          resource: 'package-view',
          action: 'read',
        },
        {
          title: 'Add Package',
          route: 'add_package',
          resource: 'package-edit',
          action: 'read',
        },
        // {
        //     title: 'Carrier List',
        //     route: 'carrier_list',
        //     resource: 'restaurant-view',
        //     action: 'read',
        //   },
        //   {
        //     title: 'Add Carrier',
        //     route: 'add_carrier',
        //     resource: 'restaurant-view',
        //     action: 'read',
        //   },
      ],
    },

  ]
  
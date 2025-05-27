export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Carrier Type',
      icon: 'FileTextIcon',
      children: [
       
        {
            title: 'Carrier List',
            route: 'carrier_list',
            resource: 'carrier-view',
            action: 'read',
          },
          {
            title: 'Add Carrier',
            route: 'add_carrier',
            resource: 'carrier-edit',
            action: 'read',
          },
      ],
    },

  ]
  
export default [
    {
      // title: 'City Management',
      title: 'Location Management',
      icon: 'MapIcon',
      children: [
        {
          title: 'City List',
          route: 'city_list',
          resource: 'city-view',
          action: 'read',
        },
        {
          title: 'Add City',
          route: 'add_city',
          resource: 'city-edit',
          action: 'read',
        },
        {
            title: 'Country List',
            route: 'country_list',
            resource: 'city-view',
            action: 'read',
        },
        {
            title: 'State List',
            route: 'state_list',
            resource: 'city-view',
            action: 'read',
        },
       
      ],
    },

  ]
  
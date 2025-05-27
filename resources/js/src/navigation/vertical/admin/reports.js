export default [

    {
      title: 'Reports',
      icon: 'TrendingUpIcon',
      children: [
        {
          title: 'Store Reports',
          route: 'restaurant_report',
          resource: 'report-view',
          action: 'read',
        },
 
        {
            title: 'Delivery People Reports',
            route: 'delivery_people_report',
            resource: 'roles',
            action: 'read',
          },
          {
            title: 'Order Reports',
            route: 'order_report',
            resource: 'report-view',
            action: 'read',
          },
          {
            title: 'Top Users Reports',
            route: 'top_users',
            resource: 'roles',
            action: 'read',
          },
          {
            title: 'Wallet Reports',
            route: 'wallet_report',
            resource: 'roles',
            action: 'read',
          },
         
      ],
    },

  ]
  
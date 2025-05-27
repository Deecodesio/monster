export default [

    {
      title: 'Payout',
      icon: 'CreditCardIcon',
      children: [
        {
          title: 'Restaurant Payout',
          route: 'restaurant_payout_list',
          resource: 'payout',
          action: 'read',
        },
 
        {
            title: 'Driver Payout',
            route: 'driver_payout_list',
            resource: 'payout',
            action: 'read',
          },
          {
            title: 'Restaurant Transaction History',
            route: 'restaurant_transaction_history',
            resource: 'payout',
            action: 'read',
          },
          {
            title: 'Driver Transaction History',
            route: 'driver_transaction_history',
            resource: 'payout',
            action: 'read',
          },
         
      ],
    },

  ]
  
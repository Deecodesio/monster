export default [

    {
      title: 'Settings',
      icon: 'SettingsIcon',
      
      children: [
        {
          title: 'Site Settings',
          route: 'site_settings',
          resource: 'settings',
          action: 'read',
        },
 
        {
            title: 'Google Settings',
            route: 'google_settings',
            resource: 'settings',
            action: 'read',
          },
          {
            title: 'Email Settings',
            route: 'email_settings',
            resource: 'settings',
            action: 'read',
          },
        
          {
            title: 'SMS Settings',
            route: 'sms_settings',
            resource: 'settings',
            action: 'read',
          },
          {
            title: 'Payment Settings',
            route: 'payment_settings',
            resource: 'settings',
            action: 'read',
          },
          {
            title: 'Delivery Partner Settings',
            route: 'deliverypartner_settings',
            resource: 'settings',
            action: 'read',
          },
         
      ],
    },

  ]
  
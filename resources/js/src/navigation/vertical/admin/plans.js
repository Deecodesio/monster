

export default [

    {
      title: 'Plans',
      icon: 'AlignRightIcon',
      
      children: [
        {
          title: 'Add Plan',
          route: 'add_plans',
          resource: 'settings',
          action: 'read',
        },
 
        {
            title: 'Plans List',
            route: 'plans_list',
            resource: 'settings',
            action: 'read',
          },

          
          
         
      ],
    },

  ]
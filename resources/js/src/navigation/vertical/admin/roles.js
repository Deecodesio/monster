export default [

    {
      title: 'Roles',
      icon: 'UsersIcon',
      children: [
        {
          title: 'Create Role',
          route: 'create_role',
          resource: 'roles',
          action: 'read',
        },
 
        {
            title: 'Roles List',
            route: 'roles_list',
            resource: 'roles',
            action: 'read',
          },
          {
            title: 'Create Staff',
            route: 'create_staff',
            resource: 'roles',
            action: 'read',
          },
          {
            title: 'Staff List',
            route: 'show_admins',
            resource: 'roles',
            action: 'read',
          },
         
      ],
    },

  ]
  
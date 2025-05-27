
  export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Addons',
      icon: 'AlignRightIcon',
      children: [
        {
          title: 'Addons List',
          route: 'addons_list',
          resource: 'store_addons-view',
          action: 'read',
         
        },
        {
          title: 'Add Addon',
          route: 'edit_addon',
          resource: 'store_addons-view',
          action: 'read',
         
        },
      
      ],
    },

  ]
  
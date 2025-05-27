export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Menu',
      icon: 'MenuIcon',
      children: [
        {
          title: 'Menu List',
          route: 'menu_list',
          resource: 'store_menus',
          action: 'read',
        },
        {
          title: 'Menu Ranking',
          route: 'menu_ranking',
          resource: 'store_menus',
          action: 'read',
        },
        {
          title: 'Add Menu',
          route: 'add_menu',
           resource: 'store_menus',
          action: 'read',
        },
      
      ],
    },

  ]
  
export default [

    {
      title: 'Restaurant Banner',
      icon: 'ImageIcon',
      children: [
        {
          title: 'Restaurant Banner List',
          route: 'banner_list',
          resource: 'banners',
          action: 'read',
        },
 
        {
            title: 'Add Restaurant Banner',
            route: 'add_banner',
            resource: 'banners',
            action: 'read',
          },
         
      ],
    },

  ]
  
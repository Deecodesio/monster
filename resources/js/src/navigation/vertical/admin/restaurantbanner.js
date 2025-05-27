export default [

  {
    title: 'Banner',
    icon: 'ImageIcon',
    children: [
      {
        title: 'Banner List',
        route: 'banner_list',
        resource: 'banners',
        action: 'read',
      },

      {
        title: 'Add Banner',
        route: 'add_banner',
        resource: 'banners',
        action: 'read',
      },

    ],
  },

]

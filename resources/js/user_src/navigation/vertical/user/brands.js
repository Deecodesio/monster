export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Brands',
      icon: 'BarChart2Icon',
      children: [
        {
          title: 'Brand List',
          route: 'brands_list',
          resource: 'brands',
          action: 'read',
        },
        {
          title: 'Add Brand',
          route: 'add_Brand',
          resource: 'brands',
          action: 'read',
        },
        {
            title: 'Brand Sort',
            route: 'brand_sort',
            resource: 'brands',
            action: 'read',
          },
      ],
    },

  ]
  
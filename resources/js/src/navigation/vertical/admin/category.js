export default [

  {
    title: 'Category',
    icon: 'CopyIcon',
    children: [
      {
        title: 'Category  List',
        route: 'business_category_list',
        resource: 'category-view',
        action: 'read',
      },
      {
        title: 'Add Category',
        route: 'business_category_add',
        resource: 'category-edit',
        action: 'read',
      },
      // {
      //   title: 'Sub Category List',
      //   route: 'category_list',
      //   resource: 'category-view',
      //   action: 'read',
      // },

      // {
      //   title: 'Add Sub Category',
      //   route: 'add_category',
      //   resource: 'category-edit',
      //   action: 'read',
      // },
      // {
      //   title: 'Sub Category Sort',
      //   route: 'category_sort',
      //   resource: 'category-edit',
      //   action: 'read',
      // },

    ],
  },

]

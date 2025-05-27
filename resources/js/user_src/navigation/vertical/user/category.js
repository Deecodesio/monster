export default [

    {
      title: 'Category',
      icon: 'CopyIcon',
      children: [
        {
          title: 'Category List',
          route: 'category_list',
          resource: 'category-view',
          action: 'read',
        },
 
        {
            title: 'Add Category',
            route: 'add_category',
            resource: 'category-view',
            action: 'read',
          },
          {
            title: 'Category Sort',
            route: 'category_sort',
            resource: 'category-view',
            action: 'read',
          },
         
      ],
    },

  ]
  
export default [


    {
        title: 'Business',
        icon: 'BarChart2Icon',
        children: [
          {
            title: 'Business List',
            route: 'business_list',
            resource: 'business',
            action: 'read',
          },
          {
            title: 'Add Business',
            route: 'add_business',
            resource: 'business',
            action: 'read',
          },
         
         
        ],
      },
]
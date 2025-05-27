export default [

    {
      title: 'Food Quantity',
      icon: 'DatabaseIcon',
      children: [
        {
          title: 'Food Quantity List',
          route: 'food_quantity_list',
          resource: 'food',
          action: 'read',
        },
 
        {
            title: 'Add Food Quantity',
            route: 'add_food_quantity',
            resource: 'food',
            action: 'read',
          },
 
         
      ],
    },

  ]
  
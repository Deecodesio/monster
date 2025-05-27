export default [

    {
      title: 'Cancellation Reasons',
      icon: 'FolderMinusIcon',
      children: [
        {
          title: 'Reason List',
          route: 'reason_list',
          resource: 'reason',
          action: 'read',
        },
 
        {
            title: 'Add Reason',
            route: 'add_reason',
            resource: 'reason',
            action: 'read',
          },

       
      ],
    },

  ]
  
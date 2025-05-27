export default [
    // {
    //   header: 'Order management',
    // },

    {
      title: 'Document Management',
      icon: 'BookOpenIcon',
      children: [
        {
          title: 'Document List',
          route: 'document_list',
          resource: 'document-view',
          action: 'read',
        },
 
        {
            title: 'Add Document',
            route: 'add_document',
            resource: 'document-edit',
            action: 'read',
          },

       
      ],
    },

  ]
  
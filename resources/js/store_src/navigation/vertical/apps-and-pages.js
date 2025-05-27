

export default [

  {
    title: 'Order Management',
    icon: 'ArchiveIcon',
   
    children: [
      {
        title: 'Order Dashboard',
        route: 'dashboard-analytics',
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
       
      },
      {
        title: 'New Order',
        route: 'new',
        // tag: '5',
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
      },
      {
        title: 'Processing Orders',
        route: 'processing',
        // tag: '10',
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
      },
      {
        title: 'Pickup Orders',
        route: 'pickup',
        // tag: '15',  
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
      },
      {
        title: 'Delivered Orders',
        route: 'delivered',
      
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
      },
      {
        title: 'Cancelled Orders',
        route: 'cancelled',
       
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
      },
      {
        title: 'Failed Orders',
        route: 'failed',
        
        tagVariant: 'light-warning',
        resource: 'store_order-view',
        action: 'read',
      },
    
      // {
      //   title: 'Abandon Cart',
      //   route: 'abandon',
       
      //   tagVariant: 'light-warning',
      //   resource: 'store_order-view',
      //   action: 'read',
      // },
     
    ],
  },
  
]



export default [

  {
    title: 'Order Management',
    icon: 'ArchiveIcon',
   
    children: [
      {
        title: 'Order dashboard',
        route: 'dashboard-analytics',
        tagVariant: 'light-warning',
      },
      {
        title: 'New Order',
        route: 'new',
        tag: '5',
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Processing Orders',
        route: 'processing',
        tag: '10',
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Pickup Orders',
        route: 'pickup',
        tag: '15',  
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Delivered orders',
        route: 'delivered',
      
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Cancelled orders',
        route: 'cancelled',
       
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Failed orders',
        route: 'failed',
        
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
    
      {
        title: 'Abandon Cart',
        route: 'abandon',
       
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Refund orders',
        route: 'refund',
        
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
    ],
  },
  
]

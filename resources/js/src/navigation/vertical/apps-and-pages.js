

export default [

  {
    title: 'Order Management',
    icon: 'ArchiveIcon',
   
    children: [
      {
        title: 'Order Dashboard',
        route: 'dashboard-analytics',
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'New Order',
        route: 'new',
       
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Processing Orders',
        route: 'processing',
      
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Pickup Orders',
        route: 'pickup',
      
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Delivered Orders',
        route: 'delivered',
      
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Cancelled Orders',
        route: 'cancelled',
       
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
      {
        title: 'Failed Orders',
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
        title: 'Refund Orders',
        route: 'refund',
        
        tagVariant: 'light-warning',
        resource: 'order-view',
        action: 'read',
      },
    ],
  },
  
  


  
]

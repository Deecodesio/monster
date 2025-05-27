export default [

  {
    title: 'Content Management',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'Privacy Policy',
        route: 'privacy_policy',
        resource: 'contents',
        action: 'read',
      },

      {
        title: 'Terms and Conditions',
        route: 'terms_conditions',
        resource: 'contents',
        action: 'read',
      },
      {
        title: 'Cancellation Policy',
        route: 'cancellation',
        resource: 'contents',
        action: 'read',
      },
      {
        title: 'Shipping and delivery Policy',
        route: 'shipping',
        resource: 'contents',
        action: 'read',
      },
      {
        title: 'FAQ',
        route: 'faq_list',
        resource: 'contents',
        action: 'read',
      },
      {
        title: 'About Us',
        route: 'about-us',
        resource: 'contents',
        action: 'read',
      },
      {
        title: 'Contact',
        route: 'contact',
        resource: 'contents',
        action: 'read',
      },

      // {
      //   title: 'Homepage Contents',
      //   route: 'home_settings',
      //   resource: 'contents',
      //   action: 'read',
      // },

      // {
      //   title: 'Header',
      //   route: 'header',
      //   resource: 'contents',
      //   action: 'read',
      // },

    ],
  },

]

export default [
    {
        header: "Others",
    },
    {
        title: "Franchise",
        icon: "ImageIcon",
        route: "franchise",
        resource: "franchise",
        action: "read",
    },
    {
        title: "Document Management",
        icon: "BookOpenIcon",
        children: [
            {
                title: "Document List",
                route: "document_list",
                resource: "document-view",
                action: "read",
            },

            {
                title: "Add Document",
                route: "add_document",
                resource: "document-edit",
                action: "read",
            },
        ],
    },
    {
        title: "Cancellation Reasons",
        icon: "FolderMinusIcon",
        children: [
            {
                title: "Reason List",
                route: "reason_list",
                resource: "reason",
                action: "read",
            },

            {
                title: "Add Reason",
                route: "add_reason",
                resource: "reason",
                action: "read",
            },
        ],
    },
    {
        title: "Blogs",
        icon: "ImageIcon",
        children: [
            {
                title: "Blog",
                route: "blog",
                resource: "reason",
                action: "read",
            },
            {
                title: "Blog category",
                route: "blog_category",
                resource: "reason",
                action: "read",
            },

        ],
    },
    {
        title: "Promocodes",
        icon: "GiftIcon",
        children: [
            {
                title: "Promocodes List",
                route: "promocode_list",
                resource: "promocodes-view",
                action: "read",
            },

            {
                title: "Add Promocodes",
                route: "add_promocode",
                resource: "promocodes-edit",
                action: "read",
            },
            {
                title: "Custom Push",
                route: "custom_push",
                resource: "promocodes",
                action: "read",
            },
            {
                title: "Admin Custom Push",
                route: "admin_custom_push",
                resource: "promocodes",
                action: "read",
            },
        ],
    },
    {
        title: "Addons List",
        icon: "AlignRightIcon",
        route: "addons_list",
        resource: "addon",
        action: "read",
    },
    {
        title: "Brands",
        icon: "BarChart2Icon",
        children: [
            {
                title: "Brand List",
                route: "brands_list",
                resource: "brands",
                action: "read",
            },
            {
                title: "Add Brand",
                route: "add_Brand",
                resource: "brands",
                action: "read",
            },
            {
                title: "Brand Sort",
                route: "brand_sort",
                resource: "brands",
                action: "read",
            },
        ],
    },
    {
        title: "Sizes",
        icon: "DatabaseIcon",
        children: [
            {
                title: "Sizes List",
                route: "food_quantity_list",
                resource: "food",
                action: "read",
            },

            {
                title: "Add Size",
                route: "add_food_quantity",
                resource: "food",
                action: "read",
            },
        ],
    },
    {
        title: "Content Management",
        icon: "FileTextIcon",
        children: [
            {
                title: "Privacy Policy",
                route: "privacy_policy",
                resource: "contents",
                action: "read",
            },

            {
                title: "Terms and Conditions",
                route: "terms_conditions",
                resource: "contents",
                action: "read",
            },
            {
                title: "Cancellation Policy",
                route: "cancellation",
                resource: "contents",
                action: "read",
            },
            {
                title: "Shipping and delivery Policy",
                route: "shipping",
                resource: "contents",
                action: "read",
            },
            {
                title: "FAQ",
                route: "faq_list",
                resource: "contents",
                action: "read",
            },
            {
                title: "About Us",
                route: "about-us",
                resource: "contents",
                action: "read",
            },
            {
                title: "Contact",
                route: "contact",
                resource: "contents",
                action: "read",
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
    {
        title: "Settings",
        icon: "SettingsIcon",

        children: [
            {
                title: "Site Settings",
                route: "site_settings",
                resource: "settings",
                action: "read",
            },

            {
                title: "Google Settings",
                route: "google_settings",
                resource: "settings",
                action: "read",
            },
            {
                title: "Email Settings",
                route: "email_settings",
                resource: "settings",
                action: "read",
            },

            {
                title: "SMS Settings",
                route: "sms_settings",
                resource: "settings",
                action: "read",
            },
            {
                title: "Payment Settings",
                route: "payment_settings",
                resource: "settings",
                action: "read",
            },
            // {
            //   title: 'Delivery Partner Settings',
            //   route: 'deliverypartner_settings',
            //   resource: 'settings',
            //   action: 'read',
            // },
        ],
    },
    // {
    //   title: 'Access Control',
    //   route: 'access-control',
    //   icon: 'ShieldIcon',
    //   // acl: {
    //   action: 'read',
    //   resource: 'ACL',
    //   // },
    // },
    // {
    //   title: 'Menu Levels',
    //   icon: 'MenuIcon',
    //   children: [
    //     {
    //       title: 'Menu Level 2.1',
    //       route: null,
    //     },
    //     {
    //       title: 'Menu Level 2.2',
    //       children: [
    //         {
    //           title: 'Menu Level 3.1',
    //           route: null,
    //         },
    //         {
    //           title: 'Menu Level 3.2',
    //           route: null,
    //         },
    //       ],
    //     },
    //   ],
    // },
    // {
    //   title: 'Disabled Menu',
    //   route: null,
    //   icon: 'EyeOffIcon',
    //   disabled: true,
    // },
    // {
    //   title: 'Raise Support',
    //   href: 'https://pixinvent.ticksy.com/',
    //   icon: 'LifeBuoyIcon',
    // },
    // {
    //   title: 'Documentation',
    //   href:
    //     'https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/documentation/guide/development/installation.html',
    //   icon: 'FileTextIcon',
    // },
];

export default [
    // {
    //   header: 'Order management',
    // },

    {
        title: 'Pages',
        icon: 'PaperclipIcon',
        children: [
            {
                title: 'Header Menu List',
                route: 'header_menu',
                resource: 'contents',
                action: 'read',
            },
            {
                title: 'Pages List',
                route: 'header',
                resource: 'contents',
                action: 'read',
            },
            {
                title: 'Add Page',
                route: 'add_header_page',
                resource: 'contents',
                action: 'read',
            },

            // {
            //     title: 'Header Sub Category List',
            //     route: 'header_subcat_list',
            //     resource: 'contents',
            //     action: 'read',
            // },
        ],
    },

]

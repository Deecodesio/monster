export default [
    {
        path: "/admin/analytics",
        name: "dashboard-analytics",
        component: () =>
            import("@/views/admin/dashboard/analytics/Analytics.vue"),
        meta: {
            resource: "dashboard-view",
            action: "read",
        },
    },
    {
        path: "/admin/dashboard",
        name: "dashboard-ecommerce",
        component: () =>
            import("@/views/admin/dashboard/ecommerce/Ecommerce.vue"),
        meta: {
            resource: "dashboard-view",
            action: "read",
        },
    },
    {
        path: "/admin/enquirys",
        name: "enquirys",
        component: () => import("@/views/admin/ordermanagement/enquirys.vue"),
        resource: "order-view",
        action: "read",
        meta: {
            pageTitle: "Enquirys",
            breadcrumb: [
                {
                    text: "Order Management",
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/new",
        name: "new",
        component: () => import("@/views/admin/ordermanagement/new_orders.vue"),
        resource: "order-view",
        action: "read",
        meta: {
            pageTitle: "New Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "New Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/processing",
        name: "processing",
        component: () => import("@/views/admin/ordermanagement/processing.vue"),
        meta: {
            pageTitle: "Processing Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Processing Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/pickup",
        name: "pickup",
        component: () => import("@/views/admin/ordermanagement/pickup.vue"),
        meta: {
            pageTitle: "Pickup Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Pickup Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/delivered",
        name: "delivered",
        component: () => import("@/views/admin/ordermanagement/delivered.vue"),
        meta: {
            pageTitle: "Delivered Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Delivered Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/cancelled",
        name: "cancelled",
        component: () => import("@/views/admin/ordermanagement/cancelled.vue"),
        meta: {
            pageTitle: "Cancelled Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Cancelled Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/failed",
        name: "failed",
        component: () => import("@/views/admin/ordermanagement/failed.vue"),
        meta: {
            pageTitle: "Failed Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Failed Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/abandon",
        name: "abandon",
        component: () => import("@/views/admin/ordermanagement/abandon.vue"),
        meta: {
            pageTitle: "Abandon Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Abandon Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },
    {
        path: "/admin/refund",
        name: "refund",
        component: () => import("@/views/admin/ordermanagement/refund.vue"),
        meta: {
            pageTitle: "Refund Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "Refund Orders",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },

    {
        path: "/admin/users",
        name: "user_management",
        component: () => import("@/views/admin/users/Usermanagement.vue"),
        meta: {
            pageTitle: "User Management",
            breadcrumb: [
                {
                    text: "User Management",
                },
                {
                    text: "users",
                    active: true,
                },
            ],
            resource: "user-view",
            action: "read",
        },
    },
    {
        path: "/admin/city_lists",
        name: "city_list",
        component: () => import("@/views/admin/city/city_list.vue"),
        meta: {
            pageTitle: "City List",
            breadcrumb: [
                {
                    text: "City List",
                },
                {
                    text: "City",
                    active: true,
                },
            ],
            resource: "city-view",
            action: "read",
        },
    },
    {
        path: "/admin/country_lists",
        name: "country_list",
        component: () => import("@/views/admin/city/country_list.vue"),
        meta: {
            pageTitle: "Country List",
            breadcrumb: [
                {
                    text: "Country List",
                },
                {
                    text: "Country",
                    active: true,
                },
            ],
            resource: "city-view",
            action: "read",
        },
    },
    {
        path: "/admin/add_country",
        name: "add_country",
        component: () => import("@/views/admin/city/add_country.vue"),
        meta: {
            pageTitle: "Add Country",
            breadcrumb: [
                {
                    text: "Country",
                },
                {
                    text: "Add Country",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },
    {
        path: "/admin/add_state",
        name: "add_state",
        component: () => import("@/views/admin/city/add_state.vue"),
        meta: {
            pageTitle: "Add State",
            breadcrumb: [
                {
                    text: "State",
                },
                {
                    text: "Add State",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },
    {
        path: "/admin/states_list",
        name: "state_list",
        component: () => import("@/views/admin/city/state_list.vue"),
        meta: {
            pageTitle: "State List",
            breadcrumb: [
                {
                    text: "State List",
                },
                {
                    text: "State",
                    active: true,
                },
            ],
            resource: "city-view",
            action: "read",
        },
    },
    {
        path: "/admin/restaurants",
        name: "restaurant_list",
        component: () => import("@/views/admin/restaurants/restaurants.vue"),
        meta: {
            pageTitle: "Store List",
            breadcrumb: [
                {
                    text: "Store List",
                },
                {
                    text: "Store",
                    active: true,
                },
            ],
            resource: "restaurant-view",
            action: "read",
        },
    },
    {
        path: "/admin/add_restaurants",
        name: "add_restaurant",
        component: () => import("@/views/admin/restaurants/add_restaurant.vue"),
        meta: {
            pageTitle: "Store",
            breadcrumb: [
                {
                    text: "Store List",
                },
                {
                    text: "Add Store",
                    active: true,
                },
            ],
            resource: "restaurant-edit",
            action: "read",
        },
    },
    {
        path: "/admin/eagle_view",
        name: "eagle_view",
        component: () => import("@/views/admin/restaurants/eagle.vue"),
        meta: {
            pageTitle: "Store",
            breadcrumb: [
                {
                    text: "Eagle View",
                },
            ],
            resource: "restaurant-edit",
            action: "read",
        },
    },

    {
        path: "/admin/edit_restaurants/:id",
        name: "edit_restaurant",
        component: () =>
            import("@/views/admin/restaurants/edit_restaturant.vue"),
        meta: {
            pageTitle: "Store",
            breadcrumb: [
                {
                    text: "Edit Store ",
                },
                {
                    text: "Edit Store",
                    active: true,
                },
            ],
            resource: "restaurant-edit",
            action: "read",
        },
    },
    {
        path: "/admin/view_restaurants/:id",
        name: "restaurant_view",
        component: () =>
            import("@/views/admin/restaurants/restaurant_view.vue"),
        meta: {
            pageTitle: "Store",
            breadcrumb: [
                {
                    text: "Store View",
                },
                {
                    text: "View Store",
                    active: true,
                },
            ],
            resource: "restaurant-edit",
            action: "read",
        },
    },
    {
        path: "/admin/vehicles",
        name: "vehicles_list",
        component: () => import("@/views/admin/vehicles/vehicles_list.vue"),
        meta: {
            pageTitle: "Vehicles List",
            breadcrumb: [
                {
                    text: "Vehicles List",
                },
                {
                    text: "Vehicles",
                    active: true,
                },
            ],
            resource: "vehicle-view",
            action: "read",
        },
    },

    {
        path: "/admin/brands",
        name: "brands_list",
        component: () => import("@/views/admin/brands/brands_list.vue"),
        meta: {
            pageTitle: "Brands List",
            breadcrumb: [
                {
                    text: "Brands List",
                },
                {
                    text: "Brands",
                    active: true,
                },
            ],
            resource: "brands",
            action: "read",
        },
    },

    {
        path: "/admin/drivers",
        name: "driver_list",
        component: () => import("@/views/admin/driver/driver_list.vue"),
        meta: {
            pageTitle: "Driver List",
            breadcrumb: [
                {
                    text: "Driver List",
                },
                {
                    text: "Driver",
                    active: true,
                },
            ],
            resource: "driver-view",
            action: "read",
        },
    },
    {
        path: "/admin/view_drivers/:id",
        name: "view_driver",
        component: () => import("@/views/admin/driver/view_driver.vue"),
        meta: {
            pageTitle: "View Driver",
            breadcrumb: [
                {
                    text: "View Driver",
                },
                {
                    text: "Driver",
                    active: true,
                },
            ],
            resource: "driver-view",
            action: "read",
        },
    },
    {
        path: "/admin/new_drivers",
        name: "new_driver_list",
        component: () => import("@/views/admin/driver/new_driver_list.vue"),
        meta: {
            pageTitle: "New Driver List",
            breadcrumb: [
                {
                    text: "Driver List",
                },
                {
                    text: "New Driver",
                    active: true,
                },
            ],
            resource: "driver-view",
            action: "read",
        },
    },
    {
        path: "/admin/rider_in_map",
        name: "rider_in_map",
        component: () => import("@/views/admin/driver/rider_in_map.vue"),
        meta: {
            pageTitle: "Drivers in Map",
            breadcrumb: [
                {
                    text: "Drivers ",
                },
                {
                    text: "Drivers in Map",
                    active: true,
                },
            ],
            resource: "driver-view",
            action: "read",
        },
    },

    {
        path: "/admin/documents",
        name: "document_list",
        component: () => import("@/views/admin/documents/document_list.vue"),
        meta: {
            pageTitle: "Document List",
            breadcrumb: [
                {
                    text: "Document List",
                },
                {
                    text: "Document",
                    active: true,
                },
            ],
            resource: "document-view",
            action: "read",
        },
    },
    {
        path: "/admin/add_document",
        name: "add_document",
        component: () => import("@/views/admin/documents/add_document.vue"),
        meta: {
            pageTitle: "Add Document",
            breadcrumb: [
                {
                    text: "Document",
                },
                {
                    text: "Add Document",
                    active: true,
                },
            ],
            resource: "document-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_document/:id",
        name: "edit_document",
        component: () => import("@/views/admin/documents/add_document.vue"),
        meta: {
            pageTitle: "Edit Document",
            breadcrumb: [
                {
                    text: "Document",
                },
                {
                    text: "Edit Document",
                    active: true,
                },
            ],
            resource: "document-edit",
            action: "read",
        },
    },

    {
        path: "/admin/reason_lists",
        name: "reason_list",
        component: () => import("@/views/admin/reason_list/reason_list.vue"),
        meta: {
            pageTitle: "Cancellation Reason List",
            breadcrumb: [
                {
                    text: "Cancellation Reason List",
                },
                {
                    text: "Cancellation Reasons",
                    active: true,
                },
            ],
            resource: "reason",
            action: "read",
        },
    },
    {
        path: "/admin/add_reason",
        name: "add_reason",
        component: () => import("@/views/admin/reason_list/add_reason.vue"),
        meta: {
            pageTitle: "Add Cancellation Reason",
            breadcrumb: [
                {
                    text: "Add Cancellation Reason ",
                },
                {
                    text: "Add Cancellation Reasons",
                    active: true,
                },
            ],
            resource: "reason",
            action: "read",
        },
    },
    {
        path: "/admin/edit_reason/:id",
        name: "edit_reason",
        component: () => import("@/views/admin/reason_list/add_reason.vue"),
        meta: {
            pageTitle: "Edit Cancellation Reason",
            breadcrumb: [
                {
                    text: "Cancellation Reason ",
                },
                {
                    text: "Edit Cancellation Reasons",
                    active: true,
                },
            ],
            resource: "reason",
            action: "read",
        },
    },

    {
        path: "/admin/blog",
        name: "blog",
        component: () => import("@/views/admin/blog/blog.vue"),
        meta: {
            pageTitle: "Blog",
            breadcrumb: [
                {
                    text: "Blog",
                },
                {
                    text: "Blog",
                    active: true,
                },
            ],
            resource: "blog",
            action: "read",
        },
    },

    {
        path: "/admin/add_blog",
        name: "add_blog",
        component: () => import("@/views/admin/Blog/add_blog.vue"),
        meta: {
            pageTitle: "Add Blog",
            breadcrumb: [
                { text: "Blog" },
                { text: "Add Blog", active: true },
            ],
            resource: "blog",
            action: "read",
        },
    },
    {
        path: "/admin/add_blog_category",
        name: "add_blog_category",
        component: () => import("@/views/admin/Blog/add_blog_category.vue"),
        meta: {
            pageTitle: "Add Blog Category",
            breadcrumb: [
                { text: "Blog" },
                { text: "Add Blog Category", active: true },
            ],
            resource: "blog",
            action: "read",
        },
    },

    {
        path: "/admin/blog_category",
        name: "blog_category",
        component: () => import("@/views/admin/blog/blog_category.vue"),
        meta: {
            pageTitle: "Blog Category",
            breadcrumb: [
                {
                    text: "Blog",
                },
                {
                    text: "Blog Category",
                    active: true,
                },
            ],
            resource: "blog",
            action: "read",
        },
    },



    {
        path: "/admin/promocode_lists",
        name: "promocode_list",
        component: () => import("@/views/admin/promocodes/promocode_list.vue"),
        meta: {
            pageTitle: "Promocode List",
            breadcrumb: [
                {
                    text: "Promocodes",
                },
                {
                    text: "Promocode List",
                    active: true,
                },
            ],
            resource: "promocodes-view",
            action: "read",
        },
    },

    {
        path: "/admin/add_promocode",
        name: "add_promocode",
        component: () => import("@/views/admin/promocodes/add_promocode.vue"),
        meta: {
            pageTitle: "Add Promocode",
            breadcrumb: [
                {
                    text: "Promocode",
                },
                {
                    text: "Add Promocode",
                    active: true,
                },
            ],
            resource: "promocodes-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_promocodes/:id",
        name: "edit_promocode",
        component: () => import("@/views/admin/promocodes/add_promocode.vue"),
        meta: {
            pageTitle: "Edit Promocode",
            breadcrumb: [
                {
                    text: "Promocode",
                },
                {
                    text: "Edit Promocode",
                    active: true,
                },
            ],
            resource: "promocodes-edit",
            action: "read",
        },
    },

    {
        path: "/admin/custom_push",
        name: "custom_push",
        component: () => import("@/views/admin/promocodes/custompush.vue"),
        meta: {
            pageTitle: "Custom Push",
            breadcrumb: [
                {
                    text: "Promocode",
                },
                {
                    text: "Push Notification",
                    active: true,
                },
            ],
            resource: "promocodes",
            action: "read",
        },
    },
    {
        path: "/admin/admin_custom_push",
        name: "admin_custom_push",
        component: () =>
            import("@/views/admin/promocodes/admin_custompush.vue"),
        meta: {
            pageTitle: "Admin Custompush",
            breadcrumb: [
                {
                    text: "Promocode",
                },
                {
                    text: "Admin Custompush",
                    active: true,
                },
            ],
            resource: "promocodes",
            action: "read",
        },
    },
    {
        path: "/admin/banner_lists",
        name: "banner_list",
        component: () => import("@/views/admin/banner/banner_list.vue"),
        meta: {
            pageTitle: "Banner List",
            breadcrumb: [
                {
                    text: "Banners",
                },
                {
                    text: "Banner List",
                    active: true,
                },
            ],
            resource: "banners",
            action: "read",
        },
    },
    {
        path: "/admin/add_banner",
        name: "add_banner",
        component: () => import("@/views/admin/banner/add_banner.vue"),
        meta: {
            pageTitle: "Add Banner",
            breadcrumb: [
                {
                    text: "Banners",
                },
                {
                    text: "Add Banner",
                    active: true,
                },
            ],
            resource: "banners",
            action: "read",
        },
    },
    {
        path: "/admin/edit_banner/:id",
        name: "edit_banner",
        component: () => import("@/views/admin/banner/add_banner.vue"),
        meta: {
            pageTitle: "Edit Banner",
            breadcrumb: [
                {
                    text: "Banners",
                },
                {
                    text: "Edit Banner",
                    active: true,
                },
            ],
            resource: "banners",
            action: "read",
        },
    },

    {
        path: "/admin/cuisines",
        name: "cuisines_list",
        component: () => import("@/views/admin/itemmaster/cuisines.vue"),
        meta: {
            pageTitle: "Cuisines List",
            breadcrumb: [
                {
                    text: "Cuisines",
                },
                {
                    text: "Cuisines List",
                    active: true,
                },
            ],
            resource: "cuisines-view",
            action: "read",
        },
    },

    {
        path: "/admin/edit_cuisine/:id",
        name: "edit_cuisine",
        component: () => import("@/views/admin/itemmaster/add_cuisine.vue"),
        meta: {
            pageTitle: "Edit Cuisines",
            breadcrumb: [
                {
                    text: "Cuisines",
                },
                {
                    text: "Edit Cuisines",
                    active: true,
                },
            ],
            resource: "cuisines-edit",
            action: "read",
        },
    },
    {
        path: "/admin/add_cuisine",
        name: "add_cuisine",
        component: () => import("@/views/admin/itemmaster/add_cuisine.vue"),
        meta: {
            pageTitle: "Add Cuisines",
            breadcrumb: [
                {
                    text: "Cuisines",
                },
                {
                    text: "Add Cuisines",
                    active: true,
                },
            ],
            resource: "cuisines-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_addon/:id",
        name: "edit_addon",
        component: () => import("@/views/admin/itemmaster/add_addon.vue"),
        meta: {
            pageTitle: "Edit Addon",
            breadcrumb: [
                {
                    text: "Cuisines",
                },
                {
                    text: "Edit Addon",
                    active: true,
                },
            ],
            resource: "addon",
            action: "read",
        },
    },
    {
        path: "/admin/add_addon",
        name: "add_addon",
        component: () => import("@/views/admin/itemmaster/add_addon.vue"),
        meta: {
            pageTitle: "Add Addon",
            breadcrumb: [
                {
                    text: "Addon",
                },
                {
                    text: "Add Addon",
                    active: true,
                },
            ],
            resource: "addon",
            action: "read",
        },
    },
    {
        path: "/admin/addons_lists",
        name: "addons_list",
        component: () => import("@/views/admin/itemmaster/addons_list.vue"),
        meta: {
            pageTitle: "Addons List",
            breadcrumb: [
                {
                    text: "Addons",
                },
                {
                    text: "Addons List",
                    active: true,
                },
            ],
            resource: "addon",
            action: "read",
        },
    },
    {
        path: "/admin/restaurant_payout_list",
        name: "restaurant_payout_list",
        component: () =>
            import("@/views/admin/payouts/restaurant_payout_list.vue"),
        meta: {
            pageTitle: "Store Payout List",
            breadcrumb: [
                {
                    text: "Store Payout List",
                },
                {
                    text: "Store Payout",
                    active: true,
                },
            ],
            resource: "payout",
            action: "read",
        },
    },

    {
        path: "/admin/driver_payout_list",
        name: "driver_payout_list",
        component: () => import("@/views/admin/payouts/driver_payout_list.vue"),
        meta: {
            pageTitle: "Driver Payout List",
            breadcrumb: [
                {
                    text: "Driver Payout List",
                },
                {
                    text: "Driver Payout",
                    active: true,
                },
            ],
            resource: "payout",
            action: "read",
        },
    },

    {
        path: "/admin/restaurant_transaction_history",
        name: "restaurant_transaction_history",
        component: () => import("@/views/admin/payouts/restaurant_history.vue"),
        meta: {
            pageTitle: "Store Transaction History",
            breadcrumb: [
                {
                    text: "Transaction History",
                },
                {
                    text: "History",
                    active: true,
                },
            ],
            resource: "payout",
            action: "read",
        },
    },

    {
        path: "/admin/driver_transaction_history",
        name: "driver_transaction_history",
        component: () => import("@/views/admin/payouts/driver_history.vue"),
        meta: {
            pageTitle: "Driver Transaction History",
            breadcrumb: [
                {
                    text: "Driver Transaction History",
                },
                {
                    text: "Driver Transaction History",
                    active: true,
                },
            ],
            resource: "payout",
            action: "read",
        },
    },

    {
        path: "/admin/food_quantity_lists",
        name: "food_quantity_list",
        component: () =>
            import("@/views/admin/food_quantity/food_quantity_list.vue"),
        meta: {
            pageTitle: "Sizes List",
            breadcrumb: [
                {
                    text: "Sizes List",
                },
                {
                    text: "Sizes List",
                    active: true,
                },
            ],
            resource: "food",
            action: "read",
        },
    },

    {
        path: "/admin/add_food_quantity",
        name: "add_food_quantity",
        component: () =>
            import("@/views/admin/food_quantity/add_food_quantity.vue"),
        meta: {
            pageTitle: "Add New Size",
            breadcrumb: [
                {
                    text: "Size",
                },
                {
                    text: "Add New Size",
                    active: true,
                },
            ],
            resource: "food",
            action: "read",
        },
    },

    {
        path: "/admin/edit_food_quantity/:id",
        name: "edit_food_quantity",
        component: () =>
            import("@/views/admin/food_quantity/add_food_quantity.vue"),
        meta: {
            pageTitle: "Edit Size",
            breadcrumb: [
                {
                    text: "Size",
                },
                {
                    text: "Edit Size",
                    active: true,
                },
            ],
            resource: "food",
            action: "read",
        },
    },

    {
        path: "/admin/category_lists",
        name: "category_list",
        component: () => import("@/views/admin/category/category_list.vue"),
        meta: {
            pageTitle: "Category List",
            breadcrumb: [
                {
                    text: "Category",
                },
                {
                    text: "Category List",
                    active: true,
                },
            ],
            resource: "category-view",
            action: "read",
        },
    },
    {
        path: "/admin/add_category",
        name: "add_category",
        component: () => import("@/views/admin/category/add_category.vue"),
        meta: {
            pageTitle: "Add Category",
            breadcrumb: [
                {
                    text: "Category",
                },
                {
                    text: "Add Category",
                    active: true,
                },
            ],
            resource: "category-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_categorys/:id",
        name: "edit_category",
        component: () => import("@/views/admin/category/add_category.vue"),
        meta: {
            pageTitle: "Edit Category",
            breadcrumb: [
                {
                    text: "Category",
                },
                {
                    text: "Edit Category",
                    active: true,
                },
            ],
            resource: "category-edit",
            action: "read",
        },
    },
    {
        path: "/admin/category_sort",
        name: "category_sort",
        component: () => import("@/views/admin/category/category_sort.vue"),
        meta: {
            pageTitle: "Category Sort",
            breadcrumb: [
                {
                    text: "Category",
                },
                {
                    text: "Category Sort",
                    active: true,
                },
            ],
            resource: "category-edit",
            action: "read",
        },
    },

    {
        path: "/admin/products_list",
        name: "product_list",
        component: () => import("@/views/admin/product/product_list.vue"),
        resource: "product-view",
        action: "read",
        meta: {
            pageTitle: "Product List",
            breadcrumb: [
                {
                    text: "Product",
                },
                {
                    text: "Product List",
                    active: true,
                },
            ],
            resource: "store_product",
            action: "read",
        },
    },

    {
        path: "/admin/product",
        name: "add_product",
        component: () => import("@/views/admin/product/add_product.vue"),

        meta: {
            pageTitle: "Product Add",
            breadcrumb: [
                {
                    text: "Product",
                },
                {
                    text: "Product Add",
                    active: true,
                },
            ],
            resource: "store_product",
            action: "read",
        },
    },
    {
        path: "/admin/edit_product/:id",
        name: "edit_product",
        component: () => import("@/views/admin/product/edit_product.vue"),

        meta: {
            pageTitle: "Product Edit",
            breadcrumb: [
                {
                    text: "Product",
                },
                {
                    text: "Product Edit",
                    active: true,
                },
            ],
            resource: "store_product",
            action: "read",
        },
    },
    {
        path: "/admin/roles_list",
        name: "roles_list",
        component: () => import("@/views/admin/roles/roles_list.vue"),
        meta: {
            pageTitle: "Roles List",
            breadcrumb: [
                {
                    text: "Roles",
                },
                {
                    text: "Roles List",
                    active: true,
                },
            ],
            resource: "roles",
            action: "read",
        },
    },

    {
        path: "/admin/edit_privilege",
        name: "edit_privilege",
        component: () => import("@/views/admin/roles/add_privilege.vue"),
        meta: {
            pageTitle: "Edit Privilege",
            breadcrumb: [
                {
                    text: "Privilege",
                },
                {
                    text: "Edit Privilege",
                    active: true,
                },
            ],
            resource: "roles",
            action: "read",
        },
    },

    {
        path: "/admin/show_admin",
        name: "show_admins",
        component: () => import("@/views/admin/roles/show_admins.vue"),
        meta: {
            pageTitle: "Admins List",
            breadcrumb: [
                {
                    text: "Admins",
                },
                {
                    text: "Admins List",
                    active: true,
                },
            ],
            resource: "roles",
            action: "read",
        },
    },

    {
        path: "/admin/restaurant_reports",
        name: "restaurant_report",
        component: () => import("@/views/admin/reports/restaurant_report.vue"),
        meta: {
            pageTitle: "Store Report",
            breadcrumb: [
                {
                    text: "Store Report",
                },
                {
                    text: "Store Report",
                    active: true,
                },
            ],

            resource: "report-view",
            action: "read",
        },
    },

    {
        path: "/admin/delivery_people_reports",
        name: "delivery_people_report",
        component: () =>
            import("@/views/admin/reports/delivery_people_report.vue"),
        meta: {
            pageTitle: "Delivery People Report",
            breadcrumb: [
                {
                    text: "Delivery People Report",
                },
                {
                    text: "Delivery People Report",
                    active: true,
                },
            ],
            resource: "report-view",
            action: "read",
        },
    },

    {
        path: "/admin/order_reports",
        name: "order_report",
        component: () => import("@/views/admin/reports/order_report.vue"),
        meta: {
            pageTitle: "Order Report",
            breadcrumb: [
                {
                    text: "Order Report",
                },
                {
                    text: "Order Report",
                    active: true,
                },
            ],
            resource: "report-view",
            action: "read",
        },
    },
    {
        path: "/admin/top_user",
        name: "top_users",
        component: () => import("@/views/admin/reports/top_users_report.vue"),
        meta: {
            pageTitle: "Top Users Report",
            breadcrumb: [
                {
                    text: "Top Users Report",
                },
                {
                    text: "Top Users Report",
                    active: true,
                },
            ],
            resource: "report-view",
            action: "read",
        },
    },

    {
        path: "/admin/wallet_reports",
        name: "wallet_report",
        component: () => import("@/views/admin/reports/wallet_report.vue"),
        meta: {
            pageTitle: "Wallet Report",
            breadcrumb: [
                {
                    text: "Wallet Report",
                },
                {
                    text: "Wallet Report",
                    active: true,
                },
            ],
            resource: "report-view",
            action: "read",
        },
    },
    {
        path: "/admin/add_driver",
        name: "add-driver",
        component: () => import("@/views/admin/driver/add_driver.vue"),
        meta: {
            pageTitle: "Add Driver",
            breadcrumb: [
                {
                    text: "Driver",
                },
                {
                    text: "Add Driver",
                    active: true,
                },
            ],
            resource: "driver-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_driver/:id",
        name: "edit-driver",
        component: () => import("@/views/admin/driver/edit_driver.vue"),
        meta: {
            pageTitle: "Edit Driver",
            breadcrumb: [
                {
                    text: "Driver",
                },
                {
                    text: "Edit Driver",
                    active: true,
                },
            ],
            resource: "driver-edit",
            action: "read",
        },
    },
    {
        path: "/admin/delivery_boy_list/:id",
        name: "deliveryboy_list",
        component: () => import("@/views/admin/driver/deliver_people_list.vue"),
        meta: {
            pageTitle: "Deliveryboy List",
            breadcrumb: [
                {
                    text: "List",
                },
                {
                    text: "Deliveryboy_List",
                    active: true,
                },
            ],
            resource: "driver-view",
            action: "read",
        },
    },

    {
        path: "/admin/add_city",
        name: "add_city",
        component: () => import("@/views/admin/city/add_city.vue"),
        meta: {
            pageTitle: "Add City",
            breadcrumb: [
                {
                    text: "City",
                },
                {
                    text: "Add City",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_cities/:id",
        name: "edit_city",
        component: () => import("@/views/admin/city/add_city.vue"),
        meta: {
            pageTitle: "Edit City",
            breadcrumb: [
                {
                    text: "City",
                },
                {
                    text: "Edit City",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },

    {
        path: "/admin/add_vehicle",
        name: "add_vehicle",
        component: () => import("@/views/admin/vehicles/add_vehicle.vue"),
        meta: {
            pageTitle: "Add Vehicle",
            breadcrumb: [
                {
                    text: "Vehicle",
                },
                {
                    text: "Add Vehicle",
                    active: true,
                },
            ],
            resource: "vehicle-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_vehicle/:id",
        name: "edit_vehicle",
        component: () => import("@/views/admin/vehicles/add_vehicle.vue"),
        meta: {
            pageTitle: "Edit Vehicle",
            breadcrumb: [
                {
                    text: "Vehicle",
                },
                {
                    text: "Edit Vehicle",
                    active: true,
                },
            ],
            resource: "vehicle-edit",
            action: "read",
        },
    },

    {
        path: "/admin/add_Brand",
        name: "add_Brand",
        component: () => import("@/views/admin/brands/add_Brand.vue"),
        meta: {
            pageTitle: "Add Brands",
            breadcrumb: [
                {
                    text: "Brands",
                },
                {
                    text: "Add Brands",
                    active: true,
                },
            ],
            resource: "brands",
            action: "read",
        },
    },
    {
        path: "/admin/edit_Brand/:id",
        name: "edit_Brand",
        component: () => import("@/views/admin/brands/add_Brand.vue"),
        meta: {
            pageTitle: "Edit Brand",
            breadcrumb: [
                {
                    text: "Brand",
                },
                {
                    text: "Edit Brand",
                    active: true,
                },
            ],
            resource: "brands",
            action: "read",
        },
    },
    {
        path: "/admin/brand_sort",
        name: "brand_sort",
        component: () => import("@/views/admin/brands/brands_sort.vue"),
        meta: {
            pageTitle: "Brand Sort",
            breadcrumb: [
                {
                    text: "Brand",
                },
                {
                    text: "Brand Sort",
                    active: true,
                },
            ],
            resource: "brands",
            action: "read",
        },
    },

    {
        path: "/admin/create_role",
        name: "create_role",
        component: () => import("@/views/admin/roles/create_role.vue"),
        meta: {
            pageTitle: "Create Role",
            breadcrumb: [
                {
                    text: "Roles",
                },
                {
                    text: "Create Role",
                    active: true,
                },
            ],
            resource: "roles",
            action: "read",
        },
    },
    {
        path: "/admin/create_staff",
        name: "create_staff",
        component: () => import("@/views/admin/roles/create_staff.vue"),
        meta: {
            pageTitle: "Create Staff",
            breadcrumb: [
                {
                    text: "Roles",
                },
                {
                    text: "Create Staff",
                    active: true,
                },
            ],
            resource: "roles",
            action: "read",
        },
    },
    {
        path: "/admin/edit_staff/:id",
        name: "edit_staff",
        component: () => import("@/views/admin/roles/create_staff.vue"),
        meta: {
            pageTitle: "Edit Staff",
            breadcrumb: [
                {
                    text: "Roles",
                },
                {
                    text: "Edit Staff",
                    active: true,
                },
            ],
            resource: "roles",
            action: "read",
        },
    },
    {
        path: "/admin/privacy_policys",
        name: "privacy_policy",
        component: () => import("@/views/admin/contents/privacy_policy.vue"),
        meta: {
            pageTitle: "Privacy Policy",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Privacy Policy",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/cancellation",
        name: "cancellation",
        component: () => import("@/views/admin/contents/cancellation.vue"),
        meta: {
            pageTitle: "Cancellation Policy",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Cancellation Policy",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/shipping",
        name: "shipping",
        component: () => import("@/views/admin/contents/shipping.vue"),
        meta: {
            pageTitle: "Shipping and Delivery Policy",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Shipping and Delivery Policy",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/terms_condition",
        name: "terms_conditions",
        component: () => import("@/views/admin/contents/terms_conditions.vue"),
        meta: {
            pageTitle: "Terms and Conditions",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Terms and Conditions",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/faqs/:id",
        name: "faq",
        component: () => import("@/views/admin/contents/faq.vue"),
        meta: {
            pageTitle: "FAQ",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "FAQ",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/faq_list",
        name: "faq_list",
        component: () => import("@/views/admin/contents/faq_list.vue"),
        meta: {
            pageTitle: "FAQ",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "FAQ List",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/add_faq",
        name: "add_faq",
        component: () => import("@/views/admin/contents/add_faq.vue"),
        meta: {
            pageTitle: "FAQ",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Add FAQ",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/home_settings",
        name: "home_settings",
        component: () => import("@/views/admin/contents/home.vue"),
        meta: {
            pageTitle: "Home",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Home",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/add_section",
        name: "add_section",
        component: () => import("@/views/admin/contents/add_page.vue"),
        meta: {
            pageTitle: "Home",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Add Section",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/edit_page/:id",
        name: "edit_page",
        component: () => import("@/views/admin/contents/add_page.vue"),
        meta: {
            pageTitle: "Home",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Edit Page",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/header",
        name: "header",
        component: () => import("@/views/admin/contents/header.vue"),
        meta: {
            pageTitle: "Header",
            breadcrumb: [
                {
                    text: "Pages",
                },
                {
                    text: "Pages List",
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/add_header_page",
        name: "add_header_page",
        component: () => import("@/views/admin/contents/add_header_page.vue"),
        meta: {
            pageTitle: "Home",
            breadcrumb: [
                {
                    text: "Pages",
                },
                {
                    text: "Add Page",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/edit_header_page/:id",
        name: "edit_header_page",
        component: () => import("@/views/admin/contents/add_header_page.vue"),
        meta: {
            pageTitle: "Home",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Edit Page",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/view_header_page/:id",
        name: "view_header_page",
        component: () => import("@/views/admin/contents/view_header_page.vue"),
        meta: {
            pageTitle: "Home",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "View Page",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/header_menu",
        name: "header_menu",
        component: () => import("@/views/admin/contents/header_cat_list.vue"),
        meta: {
            pageTitle: "Menu List",
            breadcrumb: [
                {
                    text: "Pages",
                },
                {
                    text: "Header Menu List",
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/add_page_to_category/:id",
        name: "add_page_to_category",
        component: () =>
            import("@/views/admin/contents/add_page_to_category.vue"),
        meta: {
            pageTitle: "Header",
            breadcrumb: [
                {
                    text: "Header category List",
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/header_subcat_list",
        name: "header_subcat_list",
        component: () =>
            import("@/views/admin/contents/header_subcat_list.vue"),
        meta: {
            pageTitle: "Header",
            breadcrumb: [
                {
                    text: "Header Sub category List",
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/view_cat_sub_page/:id",
        name: "view_cat_sub_page",
        component: () => import("@/views/admin/contents/view_cat_sub_page.vue"),
        meta: {
            pageTitle: "Header",
            breadcrumb: [
                {
                    text: "Category",
                },
                {
                    text: "View Category",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },

    {
        path: "/admin/edit_faq/:id",
        name: "edit_faq",
        component: () => import("@/views/admin/contents/add_faq.vue"),
        meta: {
            pageTitle: "FAQ",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Add FAQ",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/about_us",
        name: "about-us",
        component: () => import("@/views/admin/contents/about-us.vue"),
        meta: {
            pageTitle: "About Us",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "About Us",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/contacts",
        name: "contact",
        component: () => import("@/views/admin/contents/contact.vue"),
        meta: {
            pageTitle: "Contact",
            breadcrumb: [
                {
                    text: "Contents",
                },
                {
                    text: "Contact",
                    active: true,
                },
            ],
            resource: "contents",
            action: "read",
        },
    },
    {
        path: "/admin/site_settings",
        name: "site_settings",
        component: () => import("@/views/admin/settings/site_settings.vue"),
        meta: {
            pageTitle: "Site Settings",
            breadcrumb: [
                {
                    text: "Settings",
                },
                {
                    text: "Site Settings",
                    active: true,
                },
            ],
            resource: "settings",
            action: "read",
        },
    },
    {
        path: "/admin/google_settings",
        name: "google_settings",
        component: () => import("@/views/admin/settings/google_settings.vue"),
        meta: {
            pageTitle: "Google Settings",
            breadcrumb: [
                {
                    text: "Settings",
                },
                {
                    text: "Google Settings",
                    active: true,
                },
            ],
            resource: "settings",
            action: "read",
        },
    },
    {
        path: "/admin/email_settings",
        name: "email_settings",
        component: () => import("@/views/admin/settings/email_settings.vue"),
        meta: {
            pageTitle: "Email Settings",
            breadcrumb: [
                {
                    text: "Settings",
                },
                {
                    text: "Email Settings",
                    active: true,
                },
            ],
            resource: "settings",
            action: "read",
        },
    },
    {
        path: "/admin/sms_settings",
        name: "sms_settings",
        component: () => import("@/views/admin/settings/sms_Settings.vue"),
        meta: {
            pageTitle: "SMS Settings",
            breadcrumb: [
                {
                    text: "Settings",
                },
                {
                    text: "SMS Settings",
                    active: true,
                },
            ],
            resource: "settings",
            action: "read",
        },
    },
    {
        path: "/admin/payment_settings",
        name: "payment_settings",
        component: () => import("@/views/admin/settings/payment_settings.vue"),
        meta: {
            pageTitle: "Payment Settings",
            breadcrumb: [
                {
                    text: "Settings",
                },
                {
                    text: "Payment Settings",
                    active: true,
                },
            ],
            resource: "settings",
            action: "read",
        },
    },
    // {
    //   path: "/admin/deliverypartner_settings",
    //   name: "deliverypartner_settings",
    //   component: () => import("@/views/admin/settings/deliverypartner_settings.vue"),
    //   meta: {
    //       pageTitle: 'Delivery Partner Settings',
    //       breadcrumb: [
    //         {
    //           text: 'Settings',
    //         },
    //         {
    //           text: 'Delivery Partner Settings',
    //           active: true,
    //         },
    //       ],
    //       resource: 'settings',
    //       action: 'read',
    //     },

    // },
    {
        path: "/admin/view_orders/:id",
        name: "view_order",
        // component: () => import('@/views/ordermanagement/view_order.vue'),
        component: () =>
            import("@/views/apps/invoice/invoice-preview/view_order.vue"),

        meta: {
            pageTitle: "Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "View Order",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },

    {
        path: "/admin/view_enquiry/:id",
        name: "view_enquiry",
        // component: () => import('@/views/ordermanagement/view_order.vue'),
        component: () =>
            import(
                "@/views/apps/invoice/invoice-preview/view_order_enquiry.vue"
            ),

        meta: {
            pageTitle: "Orders",
            breadcrumb: [
                {
                    text: "Order Management",
                },
                {
                    text: "View Order",
                    active: true,
                },
            ],
            resource: "order-view",
            action: "read",
        },
    },

    {
        path: "/admin/add_areas",
        name: "add_area",
        component: () => import("@/views/admin/city/add_area.vue"),
        meta: {
            pageTitle: "Add Area",
            breadcrumb: [
                {
                    text: "Area",
                },
                {
                    text: "Add Area",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_areas/:id",
        name: "edit_area",
        component: () => import("@/views/admin/city/edit_area.vue"),
        meta: {
            pageTitle: "Edit Area",
            breadcrumb: [
                {
                    text: "Area",
                },
                {
                    text: "Edit Area",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },
    {
        path: "/admin/view_city_areas/:id",
        name: "view_area",
        component: () => import("@/views/admin/city/view_area.vue"),
        meta: {
            pageTitle: "View Area",
            breadcrumb: [
                {
                    text: "View",
                },
                {
                    text: "View Area",
                    active: true,
                },
            ],
            resource: "city-edit",
            action: "read",
        },
    },
    {
        path: "/admin/account-setting",
        name: "account_setting",
        component: () => import("@/views/admin/account/AccountSetting.vue"),
        meta: {
            pageTitle: "Account Settings",
            breadcrumb: [
                {
                    text: "Pages",
                },
                {
                    text: "Account Settings",
                    active: true,
                },
            ],
        },
    },
    {
        path: "/admin/print_order/:id",
        name: "print_order",
        component: () =>
            import("@/views/admin/ordermanagement/print_order.vue"),
        meta: {
            pageTitle: "Print Order",
            breadcrumb: [
                {
                    text: "Print",
                },
                {
                    text: "Print Order",
                    active: true,
                },
            ],
        },
    },
    {
        path: "/admin/business_list",
        name: "business_list",
        component: () => import("@/views/admin/Business/business_list.vue"),
        meta: {
            pageTitle: "Business List",
            breadcrumb: [
                {
                    text: "Business",
                },
                {
                    text: "Business List",
                    active: true,
                },
            ],
            resource: "business",
            action: "read",
        },
    },

    {
        path: "/admin/add_business",
        name: "add_business",
        component: () => import("@/views/admin/Business/add_business.vue"),
        meta: {
            pageTitle: "Add Business",
            breadcrumb: [
                {
                    text: "Business",
                },
                {
                    text: "Add Business",
                    active: true,
                },
            ],
            resource: "business",
            action: "read",
        },
    },
    {
        path: "/admin/edit_business/:id",
        name: "edit_business",
        component: () => import("@/views/admin/Business/add_business.vue"),
        meta: {
            pageTitle: "Edit Business",
            breadcrumb: [
                {
                    text: "Business",
                },
                {
                    text: "Edit Business",
                    active: true,
                },
            ],
            resource: "business",
            action: "read",
        },
    },

    {
        path: "/admin/business_category_list",
        name: "business_category_list",
        component: () =>
            import("@/views/admin/category/business_category_list.vue"),
        meta: {
            pageTitle: " Business Category List",
            breadcrumb: [
                {
                    text: "Business Category",
                },
                {
                    text: "Business Category List",
                    active: true,
                },
            ],
            resource: "category-view",
            action: "read",
        },
    },
    {
        path: "/admin/business_category_edit/:id",
        name: "business_category_edit",
        component: () =>
            import("@/views/admin/category/business_category_add.vue"),
        meta: {
            pageTitle: "Edit Business Category",
            breadcrumb: [
                {
                    text: "Business",
                },
                {
                    text: "Edit Business Category",
                    active: true,
                },
            ],
            resource: "category-edit",
            action: "read",
        },
    },
    {
        path: "/admin/business_category_add",
        name: "business_category_add",
        component: () =>
            import("@/views/admin/category/business_category_add.vue"),
        meta: {
            pageTitle: "Add Business Category",
            breadcrumb: [
                {
                    text: "Business",
                },
                {
                    text: "Add Business Category",
                    active: true,
                },
            ],
            resource: "category-edit",
            action: "read",
        },
    },

    {
        path: "/admin/add_package",
        name: "add_package",
        component: () => import("@/views/admin/package/add_package.vue"),
        meta: {
            pageTitle: "Add Package Type",
            breadcrumb: [
                {
                    text: "Package",
                },
                {
                    text: "Add Package",
                    active: true,
                },
            ],
            resource: "package-edit",
            action: "read",
        },
    },

    {
        path: "/admin/package",
        name: "package_list",
        component: () => import("@/views/admin/package/package_list.vue"),
        meta: {
            pageTitle: "Package Type List",
            breadcrumb: [
                {
                    text: "Package",
                },
                {
                    text: "Package List",
                    active: true,
                },
            ],
            resource: "package-view",
            action: "read",
        },
    },

    {
        path: "/admin/edit_package/:id",
        name: "edit_package",
        component: () => import("@/views/admin/package/add_package.vue"),
        meta: {
            pageTitle: "Edit Package Type",
            breadcrumb: [
                {
                    text: "Package",
                },
                {
                    text: "Edit Package",
                    active: true,
                },
            ],
            resource: "package-edit",
            action: "read",
        },
    },

    {
        path: "/admin/carrier",
        name: "carrier_list",
        component: () => import("@/views/admin/carrier/carrier_list.vue"),
        meta: {
            pageTitle: "Carrier Type List",
            breadcrumb: [
                {
                    text: "Carrier",
                },
                {
                    text: "Carrier List",
                    active: true,
                },
            ],
            resource: "carrier-view",
            action: "read",
        },
    },

    {
        path: "/admin/add_carrier",
        name: "add_carrier",
        component: () => import("@/views/admin/carrier/add_carrier.vue"),
        meta: {
            pageTitle: "Add Carrier Type",
            breadcrumb: [
                {
                    text: "Carrier",
                },
                {
                    text: "Add Carrier",
                    active: true,
                },
            ],
            resource: "carrier-edit",
            action: "read",
        },
    },

    {
        path: "/admin/edit_carrier",
        name: "edit_carrier",
        component: () => import("@/views/admin/carrier/add_carrier.vue"),
        meta: {
            pageTitle: "Edit Carrier Type",
            breadcrumb: [
                {
                    text: "Carrier",
                },
                {
                    text: "Edit Carrier",
                    active: true,
                },
            ],
            resource: "carrier-edit",
            action: "read",
        },
    },

    // {
    //   path: "/admin/edit_areas",
    //   name: "edit_area",
    //   component: () => import("@/views/admin/city/edit_area.vue"),
    //   meta: {
    //       pageTitle: 'edit Area',
    //       breadcrumb: [
    //         {
    //           text: 'Area',
    //         },
    //         {
    //           text: 'edit Area',
    //           active: true,
    //         },
    //       ],
    //     resource: 'city-edit',
    //     action: 'read',
    //     },

    // },

    {
        path: "/admin/user_detail/:id/",
        name: "user_detail",
        component: () => import("@/views/admin/users/user_detail.vue"),
        meta: {
            pageTitle: "User Detail",
            breadcrumb: [
                {
                    text: "User Management",
                },
                {
                    text: "users",
                    active: true,
                },
            ],
            resource: "user-view",
            action: "read",
        },
    },
    {
        path: "/admin/plans_list",
        name: "plans_list",
        component: () => import("@/views/admin/plans/plans_list.vue"),
        meta: {
            pageTitle: "Plans",
            breadcrumb: [
                {
                    text: "Plans",
                },
                {
                    text: "Plans List",
                    active: true,
                },
            ],
            resource: "cuisines-edit",
            action: "read",
        },
    },
    {
        path: "/admin/add_plans",
        name: "add_plans",
        component: () => import("@/views/admin/plans/add_plans.vue"),
        meta: {
            pageTitle: "Add Plans",
            breadcrumb: [
                {
                    text: "Plans",
                },
                {
                    text: "Add Plans",
                    active: true,
                },
            ],
            resource: "cuisines-edit",
            action: "read",
        },
    },
    {
        path: "/admin/edit_plans/:id",
        name: "edit_plans",
        component: () => import("@/views/admin/plans/add_plans.vue"),
        meta: {
            pageTitle: "Add Plans",
            breadcrumb: [
                {
                    text: "Plans",
                },
                {
                    text: "Edit Plan",
                    active: true,
                },
            ],
            resource: "cuisines-edit",
            action: "read",
        },
    },
    {
        path: "/admin/bulk_orders",
        name: "bulk_orders",
        component: () => import("@/views/admin/bulkOrder/bulk_orders.vue"),
        meta: {
            pageTitle: "Bulk Orders",
            breadcrumb: [
                {
                    text: "Bulk Orders",
                },
                {
                    text: "Update",
                    active: true,
                },
            ],
            resource: "bulk-order",
            action: "read",
        },
    },

    {
        path: "/admin/add_bulk",
        name: "add_bulk",
        component: () => import("@/views/admin/bulkOrder/add_bulk.vue"),
        meta: {
            pageTitle: "Add Bulk Orders",
            breadcrumb: [
                {
                    text: "Bulk Orders",
                },
                {
                    text: "Add Bulk Orders",
                    active: true,
                },
            ],
            resource: "bulk-order",
            action: "read",
        },
    },

     {
        path: "/admin/edit_bulk",
        name: "edit_bulk",
        component: () => import("@/views/admin/bulkOrder/add_bulk.vue"),
        meta: {
            pageTitle: "Edit Bulk Orders",
            breadcrumb: [
                {
                    text: "Bulk Orders",
                },
                {
                    text: "Edit Bulk Orders",
                    active: true,
                },
            ],
            resource: "bulk-order",
            action: "read",
        },
    },


    {
        path: "/admin/franchise",
        name: "franchise",
        component: () => import("@/views/admin/franchise/franchise.vue"),
        meta: {
            pageTitle: "Franchise",
            breadcrumb: [
                {
                    text: "Franchise",
                },
                {
                    text: "Update",
                    active: true,
                },
            ],
            resource: "franchise",
            action: "read",
        },
    },

    {
        path: "/admin/add_franchise",
        name: "add_franchise",
        component: () => import("@/views/admin/franchise/add_franchise.vue"),
        meta: {
            pageTitle: "Add Franchise",
            breadcrumb: [
                {
                    text: "Franchise",
                },
                {
                    text: "Add Franchise",
                    active: true,
                },
            ],
            resource: "franchise",
            action: "read",
        },
    },

    {
        path: "/admin/job_vacancy",
        name: "job_vacancy",
        component: () => import("@/views/admin/job_vacancy/job_vacancy.vue"),
        meta: {
            pageTitle: "Job Vacancy",
            breadcrumb: [
                {
                    text: "Job Vacancy",
                },
                {
                    text: "Update",
                    active: true,
                },
            ],
            resource: "job-vacancy",
            action: "read",
        },
    },

    {
        path: "/admin/add_job_vacancy",
        name: "add_job_vacancy",
        component: () => import("@/views/admin/job_vacancy/add_job_vacancy.vue"),
        meta: {
            pageTitle: "Add Job Vacancy",
            breadcrumb: [
                {
                    text: "Job Vacancy",
                },
                {
                    text: "Add Job Vacancy",
                    active: true,
                },
            ],
            resource: "job-vacancy",
            action: "read",
        },
    },

      {
        path: "/admin/edit_job_vacancy",
        name: "edit_job_vacancy",
        component: () => import("@/views/admin/job_vacancy/add_job_vacancy.vue"),
        meta: {
            pageTitle: "Edit Job Vacancy",
            breadcrumb: [
                {
                    text: "Job Vacancy",
                },
                {
                    text: "Edit Job Vacancy",
                    active: true,
                },
            ],
            resource: "job-vacancy",
            action: "read",
        },
    },

      {
        path: '/admin/consumer/add',
        name: 'add-consumer',
        component: () => import('@/views/admin/consumer/addconsumer.vue'),
        meta: {
            pageTitle: 'Add Consumer',
            breadcrumb: [
                { text: 'Consumer Management' },
                { text: 'Add Consumer', active: true },
            ],
            resource: 'user-create',
            action: 'create',
        },
    }
];

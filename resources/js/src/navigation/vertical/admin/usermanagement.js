export default [
    {
        header: "SALES",
    },
    {
        title: "Consumer Management",
        icon: "UserIcon",
        route: "user_management",
        resource: "user-view",
        action: "read",
    },
    {
        title: "Order Management",
        icon: "ArchiveIcon",

        children: [
            {
                title: "Order Dashboard",
                route: "dashboard-analytics",
                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "New Order",
                route: "new",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "Processing Orders",
                route: "processing",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "Pickup Orders",
                route: "pickup",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "Delivered Orders",
                route: "delivered",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "Cancelled Orders",
                route: "cancelled",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "Failed Orders",
                route: "failed",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },

            {
                title: "Abandon Cart",
                route: "abandon",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
            {
                title: "Refund Orders",
                route: "refund",

                tagVariant: "light-warning",
                resource: "order-view",
                action: "read",
            },
        ],
    },
    {
        title: "Reports",
        icon: "TrendingUpIcon",
        children: [
            {
                title: "Store Reports",
                route: "restaurant_report",
                resource: "report-view",
                action: "read",
            },

            {
                title: "Delivery People Reports",
                route: "delivery_people_report",
                resource: "roles",
                action: "read",
            },
            {
                title: "Order Reports",
                route: "order_report",
                resource: "report-view",
                action: "read",
            },
            {
                title: "Top Users Reports",
                route: "top_users",
                resource: "roles",
                action: "read",
            },
            {
                title: "Wallet Reports",
                route: "wallet_report",
                resource: "roles",
                action: "read",
            },
        ],
    },
    {
        title: "Bulk Orders",
        icon: "CopyIcon",
        route: "bulk_orders",
        resource: "bulk-order",
        action: "read",
    },
    {
        title: "Payout",
        icon: "CreditCardIcon",
        children: [
            {
                title: "Store Payout",
                route: "restaurant_payout_list",
                resource: "payout",
                action: "read",
            },

            {
                title: "Driver Payout",
                route: "driver_payout_list",
                resource: "payout",
                action: "read",
            },
            {
                title: "Store Transaction History",
                route: "restaurant_transaction_history",
                resource: "payout",
                action: "read",
            },
            {
                title: "Driver Transaction History",
                route: "driver_transaction_history",
                resource: "payout",
                action: "read",
            },
        ],
    },
];

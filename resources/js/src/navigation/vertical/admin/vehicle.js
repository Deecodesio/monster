export default [
    {
        header: "Delivery Partner",
    },
    {
        title: "Delivery Management",
        icon: "TruckIcon",
        children: [
            {
                title: "Driver List",
                route: "driver_list",
                resource: "driver-view",
                action: "read",
            },
            {
                title: "Pending Driver List",
                route: "new_driver_list",
                resource: "driver-view",
                action: "read",
            },
            {
                title: "Add Driver",
                route: "add-driver",
                resource: "driver-edit",
                action: "read",
            },
            {
                title: "Riders In Map",
                route: "rider_in_map",
                resource: "driver-view",
                action: "read",
            },
        ],
    },
    {
        title: "Vehicle Management",
        icon: "TruckIcon",
        children: [
            {
                title: "Vehicle List",
                route: "vehicles_list",
                resource: "vehicle-view",
                action: "read",
            },
            {
                title: "Add Vehicle",
                route: "add_vehicle",
                resource: "vehicle-edit",
                action: "read",
            },
        ],
    },
    {
        title: "Carrier Type",
        icon: "FileTextIcon",
        children: [
            {
                title: "Carrier List",
                route: "carrier_list",
                resource: "carrier-view",
                action: "read",
            },
            {
                title: "Add Carrier",
                route: "add_carrier",
                resource: "carrier-edit",
                action: "read",
            },
        ],
    },
];

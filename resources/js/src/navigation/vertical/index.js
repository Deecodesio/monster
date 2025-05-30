import dashboard from "./admin/dashboard";
import appsAndPages from "./apps-and-pages";
import enquirys from "./admin/enquirys";
import restaurants from "./admin/restaurants";
import city from "./admin/city";
import vehicle from "./admin/vehicle";
import brands from "./admin/brands";
import driver from "./admin/driver";
import document from "./admin/document";
import cancellation from "./admin/cancellation";
import promocodes from "./admin/promocodes";
import restaurantbanner from "./admin/restaurantbanner";
import usermanagement from "./admin/usermanagement";
import bulkOrders from "./admin/bulkOrder";
// import cuisines from './admin/cuisines'
import addonslist from "./admin/addonslist";
import payout from "./admin/payout";
import foodquantity from "./admin/foodquantity";
import productManagement from "./admin/productmanagement";
import category from "./admin/category";
import product from "./admin/product";
import contents from "./admin/contents";
import settings from "./admin/settings";
import roles from "./admin/roles";
import reports from "./admin/reports";
// import business from './admin/business'
// import packagetype from './admin/packagetype'
import carrier from "./admin/carrier";
import franchise from "./admin/franchise";
import jobvacancy from "./admin/jobvacancy";
import others from "./admin/others";
// import header from './admin/header'

// import plans from './admin/plans'

export default [
    ...dashboard,
    ...restaurantbanner,
    ...productManagement,
    ...city,
    ...restaurants,
    ...usermanagement,
    ...vehicle,
    ...roles,
    ...others,
    //     ...category,
    //     ...product,
    // ...appsAndPages,
    // ...enquirys,
    // ...business,
    
    // ...bulkOrders,
    
    //  ...packagetype,
    // ...carrier,
    // ...brands,
    // ...driver,
    // ...document,
    // ...cancellation,
    // ...promocodes,
    // ...franchise,
    // ...jobvacancy,
    

    //  ...cuisines,
    // ...addonslist,
    // ...payout,
    // ...foodquantity,
    // ...header,
    // ...contents,
    
    // ...reports,
    // ...settings,
    // ...plans
];

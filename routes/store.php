<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Authorization, Content-Type');

Route::group(
  ['namespace' => 'App\Http\Controllers\store'],
  function () {
    Route::POST('/login', 'LoginController@login');

    Route::POST('/dashboard_statistics', 'DashboardController@dashboard_statistics');
    Route::POST('/order_dashboard_statistics', 'DashboardController@order_dashboard_statistics');

    Route::POST('Orders_list/{type}/{id}', 'OrderController@Orders_list');
    Route::POST('/promocodes_list', 'PromocodeController@promocodes_list');
    Route::post('/add_to_promocode', 'PromocodeController@add_to_promocode');
    Route::post('/addons_list', 'ItemmasterController@list_addons');
    Route::POST('/edit_time', 'RestaurantController@edit_my_restaurant_timing1');
    Route::POST('/restaurant_report', 'RestaurantController@restaurant_report');
    Route::POST('/cuisines_list', 'RestaurantmasterController@restaurant_cuisines');
    Route::post('/delete_cuisine/{id}', 'RestaurantmasterController@delete_restaurant_cuisine');
    Route::get('/edit_addons/{id}', 'ItemmasterController@edit_addons');
    Route::post('/store_addons', 'ItemmasterController@store_addons');
    Route::get('vehicles_list', 'RestaurantController@vehicle_list');
    Route::get('city_list', 'RestaurantController@city_list');
    Route::post('area_list1', 'RestaurantController@area_list1');
    // Route::get('brands_list', 'RestaurantController@brands_list');
    Route::POST('/add_to_restaurants_tab1', 'RestaurantController@add_to_restaurants_tab1');
    Route::POST('/add_to_restaurants_tab2', 'RestaurantController@add_to_restaurants_tab2');
    Route::POST('/add_to_restaurants_tab3', 'RestaurantController@add_to_restaurants_tab3');
    Route::POST('/add_to_restaurants_tab4', 'RestaurantController@add_to_restaurants_tab4');
    Route::POST('/menu_list', 'RestaurantController@restaurant_menu');
    Route::get('/view_order/{id}', 'OrderController@view_order1');
    Route::post('/delete_menu/{id}', 'RestaurantController@del_restaurant_menu');
    Route::post('/store_menu', 'RestaurantController@add_restaurant_menu');
    Route::get('/edit_menus/{id}', 'RestaurantController@edit_menu');
    Route::post('/edit_store_menu', 'RestaurantController@edit_restaurant_menu');
    Route::post('/store_menu_rank', 'RestaurantController@menu_sort_save');
    Route::post('/menu_sort', 'RestaurantController@menu_sort');
    Route::post('/product_list', 'RestaurantController@product_list');
    Route::get('/product-status-toggle/{id}', 'RestaurantController@productStatus');
    Route::get('/delete_product/{id}', 'RestaurantController@delete_product_list');
    Route::get('/category_list_one/{id}/{restaurant_id}', 'RestaurantController@category_lists');
    Route::post('/addons_list', 'RestaurantController@addons_list');
    Route::get('/food_quantity/{id}', 'RestaurantController@food_quantity');

    Route::get('/edit_promocode/{id}', 'PromocodeController@edit_promocode');
    Route::POST('/delete_promocode/{id}', 'PromocodeController@delete_promocode');

    Route::get('/edit_cuisine/{id}', 'RestaurantmasterController@edit_cuisines');
    Route::get('/cuisines_list', 'RestaurantmasterController@cuisines_list');
    Route::POST('/add_to_restaurant_cuisines', 'RestaurantmasterController@add_to_restaurant_cuisines');

    Route::get('/get_currency', 'SettingController@get_currency');
    Route::post('/upload_product_csv', 'RestaurantController@upload_product_csv');
    Route::post('/generate_product_csv', 'RestaurantController@generate_product_csv');
    Route::post('/download', 'RestaurantController@download');
    Route::post('/restaurant-status-toggle1', 'RestaurantController@toggleStatus1');
    Route::post('/restaurant-status-toggle', 'RestaurantController@toggleStatus');
    Route::post('/driver_map', 'RestaurantController@driver_map');
    Route::get('/product-stock-toggle/{id}', 'RestaurantController@productStock');
    Route::post('/tables', 'RestaurantController@tables');
    Route::post('/print_tables', 'RestaurantController@print_table');
    Route::post('/add_table', 'RestaurantController@add_table');
    Route::get('document_view', 'RestaurantController@document_view');
    Route::get('cuisines_list1', 'RestaurantmasterController@cuisines_list');
    Route::get('/edit_restaurant/{id}', 'RestaurantController@edit_restaurant');

    Route::get('/assignRiderList/{id}/{Order_id}', 'RestaurantController@assignRiderList');
    Route::get('/cancel_request/{role}/{id}', 'OrderController@cancel_request');
    Route::get('/assign_request/{id}', 'OrderController@assign_request');
    Route::get('/assign_driver_manually/{order_id}/{delivery_id}', 'OrderController@assign_driver_manually');

    Route::get('/accept_request/{id}', 'OrderController@accept_request');
    Route::get('/cancell_resons', 'OrderController@cancell_reson');
    Route::get('/cancel_request_reson/{order_id}/{reason}', 'OrderController@cancel_request_with_reason');

    Route::post('/add_to_product', 'RestaurantController@add_to_product');
    Route::get('edit_product_list/{product_id}/{res_id}', 'RestaurantController@edit_product_list');

    Route::POST('/menu_list', 'RestaurantController@restaurant_menu');
    Route::get('/get_neworder_alerts/{id}', 'OrderController@get_neworder_alert');
    Route::get('/driver_map/{id}', 'RestaurantController@driver_map');

    Route::get('/delay_order/{delayMinutes}/{delayOrderID}', 'OrderController@delay_order');
    Route::get('/takeaway_complete/{request_id}/{takeaway_code}', 'OrderController@takeaway_complete');

    Route::get('/get_logo', 'SettingController@get_logo');
    Route::get('/get_food_list_by_menu/{restaurant_id}/{order_id}', 'RestaurantController@get_food_list_by_menu');
    Route::get('/fetchorder/{id}', 'RestaurantController@fetchorder');
    Route::get('/print_order_bill/{id}', 'RestaurantController@printOrderBill');

    Route::get('/single_restaurant/{restaurant_id}', 'RestaurantController@single_restaurant');
    Route::post('/revice', 'RestaurantController@revice')->name('store.revice');
    Route::get('/search_products/{rest_id}/{key_word}', 'RestaurantController@search_products');
    Route::get('/search_products_from_name/{restaurant_id}/{key_word}', 'RestaurantController@search_products_from_name');
    Route::get('/search_users/{key_word}', 'RestaurantController@search_users');

    Route::get('/get_food_list_by_menu_pos/{restaurant_id}', 'RestaurantController@get_food_list_by_menu_pos');
    Route::post('create_pos', 'RestaurantController@create_pos');
    Route::post('add_customer', 'RestaurantController@add_customer');

    Route::get('set_featured/{id}', 'RestaurantController@set_featured');
    Route::get('business_type/{id}', 'ItemmasterController@business_type');
    Route::get('business_type_rest/{id}', 'ItemmasterController@business_type_rest');
    Route::get('/business_category_list/{id}', 'ItemmasterController@business_category_list');
    Route::get('product_imgs/{id}', 'ItemmasterController@product_imgs');

    Route::get('/business_lists', 'ItemmasterController@business_list');
    Route::get('get_banner_image', 'ItemmasterController@get_banner_image');
    Route::get('get_logo_image', 'ItemmasterController@get_logo_image');

    Route::get('/category_list/{id}', 'ItemmasterController@get_category_list');
    Route::get('/delete_category/{id}', 'ItemmasterController@delete_category');
    Route::get('/category_status/{id}', 'ItemmasterController@category_status');
    Route::POST('/add_to_category', 'ItemmasterController@add_to_category');
    Route::get('/business_category_lists/{id}', 'ItemmasterController@business_category_lists');
    Route::get('edit_category/{id}', 'ItemmasterController@edit_category');
    Route::POST('/category_sort_save', 'ItemmasterController@category_sort_save');

    Route::post('/store_size', 'ItemmasterController@store_size');
    Route::post('/get_addons_groups', 'ItemmasterController@get_addons_groups');
    Route::get('/edit_addon_group/{id}', 'ItemmasterController@edit_addon_group');
    Route::post('/store_agroups', 'ItemmasterController@store_agroups');
    Route::get('/get_lang', 'SettingController@get_lang');

    Route::get('/get_default_lat_lng', 'RestaurantController@get_default_lat_lng');
    Route::post('/out_of_stock', 'RestaurantController@out_of_stock');

    Route::get('/get_defaults', 'ItemmasterController@get_defaults');
    Route::get('/setlocale/{lang}', 'ItemmasterController@setlocale');
    Route::get('/setlocale_lang', 'ItemmasterController@setlocale_lang');

    Route::post('/store_menu', 'ItemmasterController@add_menu');
    Route::post('/store_cat', 'ItemmasterController@add_to_category');


    Route::POST('order_csv', 'ItemmasterController@order_csv');
    Route::POST('order_pdf', 'ItemmasterController@order_pdf');
    Route::POST('order_excel', 'ItemmasterController@order_excel');
    Route::post('Date_Filter', 'ItemmasterController@Date_Filter');


    Route::get('current_plan/{id}', 'RestaurantController@current_plan');

    Route::get('get_plan_details/{id}', 'RestaurantController@get_plan_details');
    Route::post('pay_for_plan', 'RestaurantController@pay_for_plan');
    Route::post('validate_plan', 'RestaurantController@validate_plan');


    Route::any('/rest_payment_details/{orderID}', 'RestaurantController@rest_payment_details');
  }
);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

  Route::group(
        ['namespace' => 'App\Http\Controllers\user'],
        function () {
            Route::post('area_list1', 'RestaurantController@area_list1');

            Route::get('Orders_list/{type}', 'OrderController@Orders_list');

            Route::get('user_list', 'UserController@user_list');

            Route::get('restaurant_list', 'RestaurantController@restaurant_list');
            Route::get('restaurant_cities', 'RestaurantController@allCityList');
            Route::get('brands_list', 'RestaurantController@brands_list');

            Route::get('vehicles_list', 'RestaurantController@vehicle_list');

            Route::get('driver_list', 'DriverController@driver_list');

            Route::get('document_list', 'RestaurantController@document_list');
            Route::get('reason_list', 'RestaurantController@reason_list');


            Route::get('/promocodes_list', 'PromocodeController@promocodes_list');

            Route::get('/banner_list', 'BannerlistController@banner_list');

            Route::get('/cuisines_list', 'ItemmasterController@get_cuisines_list');

            Route::get('/addons_list', 'ItemmasterController@list_addons');
        
            Route::GET('/payout/{type}', 'TransactionController@Getpayout');

            Route::GET('/payout_history/{type}', 'TransactionController@Getpayout_history');

            Route::get('/food-quantity-list', 'ItemmasterController@list_food_quantity');

            Route::get('/category_list', 'ItemmasterController@get_category_list');
            Route::GET('/roles', 'SettingController@get_roles');
            Route::GET('/admins', 'SettingController@show_admin');

            Route::GET('/get_roles_byid/{type}', 'SettingController@get_roles_byid');

            Route::get('/admin_restaurant_report', 'RestaurantController@admin_restaurant_report');

            Route::get('/delivery_boy_reports', 'RestaurantController@delivery_boy_report');

            Route::get('/restaurant_report', 'RestaurantController@restaurant_report');

            Route::get('/top_users_report', 'UserController@top_users_report');
            Route::get('/wallet_report', 'UserController@wallet_report');

            Route::get('city_list', 'RestaurantController@city_list');

            Route::GET('/country_list', 'NationController@Getcountrylist');

            Route::GET('/state_list', 'NationController@Getstatelist');
            Route::POST('/state_list_by_country', 'NationController@state_list_by_country');



            Route::post('add_driver', 'RestaurantController@add_driver');
            Route::post('add_driver_tab', 'RestaurantController@add_driver_tab');
            Route::get('area_list', 'RestaurantController@area_list');
            Route::get('res_list', 'RestaurantController@res_list');
            Route::post('/city_add', 'RestaurantController@add_city');
            Route::post('country_list', 'RestaurantController@country_list');
            Route::post('/vehicle_add', 'RestaurantController@vehicle_add');
            Route::post('/store_brand', 'RestaurantController@store_brand');
            Route::post('/add_reason', 'RestaurantController@add_reason');
            Route::get('/edit_reason/{id}', 'RestaurantController@edit_reason');

            Route::post('/add_to_promocode', 'PromocodeController@add_to_promocode');
            Route::post('/document_add', 'RestaurantController@document_add');
            Route::get('edit_document/{id}', 'RestaurantController@edit_document');

            Route::post('/add_to_banners', 'BannerlistController@add_to_banners');
            Route::GET('/edit_banner/{id}', 'BannerlistController@edit_banner');
            Route::GET('/delete_banner/{banner_id}', 'BannerlistController@delete_banner');

            Route::get('/add-food-quantity', 'ItemmasterController@add_food_quantity');

            Route::POST('/store-food-quantity', 'ItemmasterController@store_food_quantity');
            Route::POST('/add_to_category', 'ItemmasterController@add_to_category');

            Route::get('/get_roles', 'SettingController@get_roles_new');

            Route::post('/add_admin', 'SettingController@add_admin');

            Route::get('/edit_brand/{id}', 'RestaurantController@edit_brand');
            Route::get('edit_city/{id}', 'RestaurantController@edit_city');

            Route::GET('/settings/{type}', 'SettingController@Getsettings');

            Route::get('/dashboard_statistics', 'DashboardController@dashboard_statistics');
            Route::post('/login', 'LoginController@login');

            Route::get('/deliverypeople_list', 'DeliverypeopleController@deliverypeople_list');
            Route::get('/order_dashboard_statistics', 'DashboardController@order_dashboard_statistics');

            Route::get('/new_count', 'DashboardController@new_count');

            Route::get('/view_order/{id}', 'OrderController@view_order');

            Route::get('/role_privilege/{id}', 'SettingController@get_roleprivilege');

            Route::GET('/edit_country/{id}', 'NationController@AddEditcountry');
            Route::POST('/save_country', 'NationController@Savecountry');
            Route::GET('/edit_state/{id}', 'NationController@AddEditstate');
            Route::POST('/save_state', 'NationController@Savestate');
            Route::GET('/default_country/{id}', 'NationController@Defaultcountry');
            Route::get('/vehicle_edit/{id}', 'RestaurantController@editvehicle');
            Route::post('/role_add', 'SettingController@role_add');

            Route::post('/add_to_cuisines', 'ItemmasterController@add_to_cuisines');
            Route::get('/edit_cuisines/{id}', 'ItemmasterController@edit_cuisines');

            Route::post('/delete_cuisine/{id}', 'ItemmasterController@delete_cuisine');

            Route::get('/edit_addons/{id}', 'ItemmasterController@edit_addons');
            Route::post('/store_addons', 'ItemmasterController@store_addons');

            Route::get('/edit-food-quantity/{id}', 'ItemmasterController@edit_food_quantity');
            Route::get('edit_category/{id}', 'ItemmasterController@edit_category');

            Route::get('edit_admin/{id}', 'SettingController@edit_admin');
            Route::post('/add_payout', 'TransactionController@addpayment');

            
        });
    

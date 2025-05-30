<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(
    ['namespace' => 'App\Http\Controllers\admin'],
    function () {
        Route::POST('/login', 'LoginController@login');

        // Route::group(['middleware' => 'superadminLogin'], function () {

        Route::get('Orders_list/{type}/{user}', 'OrderController@Orders_list');

        Route::get('fetch_enquirys', 'OrderController@fetch_enquirys');


        Route::get('user_list', 'UserController@user_list');

        Route::get('restaurant_list/{user}', 'RestaurantController@restaurant_list');
        Route::get('brands_list', 'RestaurantController@brands_list');
        Route::get('brands_list1', 'RestaurantController@brands_list1');


        Route::get('vehicles_list', 'RestaurantController@vehicle_list');
        Route::get('vehicles_list_drive', 'RestaurantController@vehicles_list_drive');

        Route::get('driver_list', 'DriverController@driver_list');

        Route::get('document_list', 'RestaurantController@document_list');

        Route::POST('/category_sort_save', 'RestaurantController@category_sort_save');
        Route::get('reason_list', 'RestaurantController@reason_list');

        Route::post('/brand_sort', 'RestaurantController@brand_sort_save');
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
        Route::GET('/get_role_id/{id}', 'SettingController@get_role_id');

        Route::POST('/admin_restaurant_report', 'RestaurantController@admin_restaurant_report');

        Route::POST('/delivery_boy_reports', 'RestaurantController@delivery_boy_report');

        Route::POST('/restaurant_report', 'RestaurantController@restaurant_report');

        Route::get('/top_users_report', 'UserController@top_users_report');
        Route::get('/wallet_report', 'UserController@wallet_report');

        Route::get('/city_list', 'RestaurantController@city_list');
        Route::get('/city_list1', 'RestaurantController@city_list1');

        Route::get('/city_list_by_state/{id}', 'RestaurantController@stateWiseCityList');
        Route::get('/product_list_by_category/{id}', 'RestaurantController@categoryWiseProductList');


        Route::GET('/country_list', 'NationController@Getcountrylist');

        Route::get('/state_list', 'NationController@Getstatelist');
        Route::POST('/state_list_by_country', 'NationController@state_list_by_country');


        Route::post('/update_area_list', 'RestaurantController@update_area_list');
        Route::post('/delete_area_list/{id}', 'RestaurantController@delete_area_list');
        Route::get('view_areas/{city}', 'RestaurantController@area_list1');
        Route::get('area_edit/{id}', 'RestaurantController@area_edit');
        Route::get('edit_area/{id}', 'RestaurantController@edit_area');
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

        Route::POST('/dashboard_statistics', 'DashboardController@dashboard_statistics');

        Route::get('/deliverypeople_list', 'DeliverypeopleController@deliverypeople_list');
        Route::get('/order_dashboard_statistics', 'DashboardController@order_dashboard_statistics');

        Route::get('/new_count', 'DashboardController@new_count');

        Route::get('/view_order/{id}', 'OrderController@view_order');
        Route::get('/fetch_enquiry_order/{id}', 'OrderController@fetch_enquiry_order');


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
        Route::get('document_view', 'RestaurantController@document_view');
        Route::post('add_driver_tab1', 'RestaurantController@add_driver_tab1');
        Route::POST('/add_to_restaurants_tab1', 'RestaurantController@add_to_restaurants_tab1');
        Route::POST('/add_to_restaurants_tab2', 'RestaurantController@add_to_restaurants_tab2');
        Route::POST('/add_to_restaurants_tab3', 'RestaurantController@add_to_restaurants_tab3');
        Route::POST('/add_to_restaurants_tab4', 'RestaurantController@add_to_restaurants_tab4');
        Route::POST('/add_to_restaurants_tab5', 'RestaurantController@add_to_restaurants_tab5');
        Route::POST('/add_to_restaurants_tab6', 'RestaurantController@add_to_restaurants_tab6');


        Route::post('area_list1', 'RestaurantController@area_list1');
        Route::post('area_list2', 'RestaurantController@area_list2');


        Route::get('store_login/{id}', 'RestaurantController@store_login');

        Route::get('/new_update/{request_id}/{status}', 'OrderController@new_update');
        Route::get('/user_payout/{request_id}/{status}', 'OrderController@user_payout');
        Route::post('add_userpayout', 'OrderController@add_userpayout');
        Route::post('/area_add', 'RestaurantController@add_area');

        Route::get('/edit_coupon/{id}', 'PromocodeController@edit_coupon');
        Route::POST('/delete_coupon/{id}', 'PromocodeController@delete_coupon');

        Route::POST('/send_custumpush', 'NoteboardController@send_custumpush');
        Route::POST('/admin_custumpush', 'NoteboardController@admin_custumpush');


        Route::post('/privilege_add', 'SettingController@privilege_add');
        Route::GET('/edit_privilege/{id}', 'SettingController@show_privilege');

        Route::post('/topupWallet', 'UserController@topupWallet');
        Route::post('/walletVerify', 'UserController@walletVerify');
        Route::POST('/delete_delivery_partner', 'DeliverypeopleController@delete_delivery_partner');
        Route::GET('/edit_delivery_partner/{id}', 'DeliverypeopleController@edit_delivery_partner');
        Route::get('/rider-status-toggle/{id}', 'RestaurantController@riderStatus');
        Route::get('view_delivery_boy_order_details/{id}', 'RestaurantController@view_deliveryboy_order_details');

        Route::post('/update_restaurant_menu', 'RestaurantController@edit_restaurant_menu');
        Route::post('/delete_restaurant_menu/{id}', 'RestaurantController@del_restaurant_menu');

        Route::GET('/edit_restaurant/{id}', 'RestaurantController@edit_restaurant');
        Route::POST('/delete_restaurant', 'RestaurantController@delete_restaurant');
        Route::get('/restaurant_view/{id}', 'RestaurantController@restaurant_view');
        Route::POST('/document_view_doc', 'RestaurantController@document_view_doc');

        Route::post('/content_policy', 'NoteboardController@page_save');
        Route::get('/privacy_policy', 'NoteboardController@privacy_policy');
        Route::get('/cancellation_policy', 'NoteboardController@cancellation_policy');
        Route::get('/shipping_policy', 'NoteboardController@shipping_policy');

        Route::post('/content_policy_shipping', 'NoteboardController@content_policy_shipping');

        Route::post('/content_policy_cancel', 'NoteboardController@content_policy_cancel');

        Route::post('/page_save_about', 'NoteboardController@page_save_about');
        Route::get('/about_us', 'NoteboardController@about');

        Route::post('/page_save_terms', 'NoteboardController@page_save_terms');
        Route::get('/terms_conditions', 'NoteboardController@terms_conditions');

        Route::post('/page_save_contact', 'NoteboardController@page_save_contact');
        Route::get('/contact', 'NoteboardController@contact');



        Route::POST('/update-setting', 'SettingController@Updatesetting');
        Route::post('/enable-sms-toggle', 'SettingController@enablesmstoggle');

        Route::get('view_delivery_boy/{id}', 'RestaurantController@view_delivery_boy');
        Route::get('/restaurant_view/{id}', 'RestaurantController@restaurant_view');
        Route::get('/get_currency', 'SettingController@get_currency');

        Route::get('/delete_category/{id}', 'RestaurantController@delete_category');
        Route::get('/delete_reason/{id}', 'RestaurantController@delete_reason');
        Route::get('/reason_status/{id}', 'RestaurantController@reason_status');
        Route::get('/restaurant_status/{id}', 'RestaurantController@restaurant_status');
        Route::get('/banner_status/{id}', 'RestaurantController@banner_status');
        Route::get('/city_status/{id}', 'RestaurantController@city_status');
        Route::get('/area_status/{id}', 'RestaurantController@area_status');
        Route::get('/vehicle_status/{id}', 'RestaurantController@vehicle_status');
        Route::get('/brand_status/{id}', 'RestaurantController@brand_status');
        Route::get('/document_status/{id}', 'RestaurantController@document_status');
        Route::get('/promocode_status/{id}', 'RestaurantController@promocode_status');
        Route::get('/category_status/{id}', 'RestaurantController@category_status');
        Route::get('/get_logo', 'SettingController@get_logo');
        Route::get('/faq_lists', 'SettingController@faq_list');
        Route::POST('/add_faq', 'SettingController@add_faq');
        Route::get('/edit_faq/{id}', 'SettingController@edit_faq');
        Route::get('/delete_faq/{id}', 'SettingController@delete_faq');
        Route::get('/get_faq/{id}', 'NoteboardController@get_faq');
        Route::post('/faq', 'NoteboardController@faq');
        Route::get('/get_neworder_alerts', 'OrderController@get_neworder_alert');

        Route::post('/product_list', 'RestaurantController@product_list');


        Route::get('/fetchwallet/{id}', 'UserController@fetch_wallet');
        Route::get('/get_geofence', 'SettingController@get_geofence');
        Route::get('/user_details/{id}', 'UserController@user_details');

        Route::get('/print_order_bill/{id}', 'OrderController@printOrderBill');
        Route::get('/eagles_view', 'RestaurantController@eagle_view');
        Route::post('/clear_cache', 'UserController@clear_cache');


        Route::get('/business_edit/{id}', 'ItemmasterController@edit_business');
        Route::post('/business_add', 'ItemmasterController@add_business');
        Route::get('/delete_business/{id}', 'ItemmasterController@delete_business');
        Route::get('/business_lists', 'ItemmasterController@business_list');
        Route::get('/business_lists1', 'ItemmasterController@business_list1');
        Route::get('/business_status/{id}', 'ItemmasterController@business_status');

        Route::get('/edit_business_category/{id}', 'ItemmasterController@edit_business_category');
        Route::post('/add_business_category', 'ItemmasterController@add_business_category');
        Route::get('/delete_business_category/{id}', 'ItemmasterController@delete_business_category');
        Route::get('/business_category_lists', 'ItemmasterController@business_category_lists');
        Route::get('/business_category_status/{id}', 'ItemmasterController@business_category_status');

        Route::get('/layout', 'ItemmasterController@layout');
        Route::get('/layout_driver', 'ItemmasterController@layout_driver');
        Route::get('/business_type/{id}', 'ItemmasterController@business_type');
        Route::get('banner_imgs', 'NoteboardController@banner_imgs');
        Route::get('get_banner_image', 'ItemmasterController@get_banner_image');
        Route::get('get_logo_image', 'ItemmasterController@get_logo_image');
        Route::get('/get_lang', 'SettingController@get_lang');
        Route::get('edit_pacakge/{id}', 'ItemmasterController@edit_pacakge');
        Route::post('add_to_pacakge', 'ItemmasterController@add_to_pacakge');
        Route::get('pacakge_list', 'ItemmasterController@pacakge_list');
        Route::get('delete_package/{id}', 'ItemmasterController@delete_package');


        Route::get('edit_carrier/{id}', 'ItemmasterController@edit_carrier');
        Route::post('add_to_carrier', 'ItemmasterController@add_to_carrier');
        Route::get('carrier_list', 'ItemmasterController@carrier_list');
        Route::get('carrier_list_1/{id}', 'ItemmasterController@carrier_list_1');
        Route::POST('carrier_list_new', 'ItemmasterController@carrier_list_new');
        Route::get('delete_carrier/{id}', 'ItemmasterController@delete_carrier');
        Route::get('/carrier_status/{id}', 'ItemmasterController@carrier_status');
        Route::get('set_featured/{id}', 'RestaurantController@set_featured');

        Route::post('update-email', 'SettingController@update_email');
        Route::get('get_mails', 'SettingController@get_mails');
        Route::get('get_contact_reasons', 'SettingController@get_contact_reasons');
        Route::post('update_contact', 'SettingController@update_contact');

        Route::post('edit_mails', 'SettingController@edit_mails');
        Route::get('/get_mail_contents/{page}', 'SettingController@get_mail_contents');

        Route::get('/pacakge_status/{id}', 'ItemmasterController@pacakge_status');

        Route::get('/user_data/{id}', 'UserController@user_data');

        Route::get('/get_tips', 'SettingController@get_tips');
        Route::POST('/store_tips', 'SettingController@store_tips');

        Route::get('/get_default_lat_lng', 'RestaurantController@get_default_lat_lng');
        Route::POST('/delete_role', 'SettingController@delete_role');


        Route::get('/setlocale/{lang}', 'ItemmasterController@setlocale');
        Route::get('/setlocale_lang', 'ItemmasterController@setlocale_lang');

        Route::GET('/get_place_details/{id}', 'RestaurantController@get_place_details');

        Route::post('restaurant_csv', 'NoteboardController@restaurant_csv');
        Route::post('delivery_csv', 'NoteboardController@delivery_csv');
        Route::post('order_csv', 'NoteboardController@order_csv');
        Route::post('user_csv', 'NoteboardController@user_csv');
        Route::post('wallet_csv', 'NoteboardController@wallet_csv');


        Route::post('restaurant_pdf', 'NoteboardController@restaurant_pdf');
        Route::post('delivery_pdf', 'NoteboardController@delivery_pdf');
        Route::post('order_pdf', 'NoteboardController@order_pdf');
        Route::post('user_pdf', 'NoteboardController@user_pdf');
        Route::post('wallet_pdf', 'NoteboardController@wallet_pdf');

        Route::post('restaurant_excel', 'NoteboardController@restaurant_excel');
        Route::post('delivery_excel', 'NoteboardController@delivery_excel');
        Route::post('order_excel', 'NoteboardController@order_excel');
        Route::post('user_excel', 'NoteboardController@user_excel');
        Route::post('wallet_excel', 'NoteboardController@wallet_excel');

        Route::post('Date_Filter', 'NoteboardController@Date_Filter');


        Route::post('/add_to_plans', 'UserController@add_to_plans');
        Route::get('/plan_list', 'UserController@plans_list');
        Route::get('/plan_status/{id}', 'UserController@plan_status');

        Route::get('/edit_plans/{id}', 'UserController@edit_plan');
        Route::get('/delete_plan/{id}', 'UserController@delete_plan');

        Route::post('add_new_page', 'NoteboardController@add_new_page');
        Route::get('/get_pages', 'NoteboardController@get_pages');
        Route::get('/edit_pages/{id}', 'NoteboardController@edit_pages');


        Route::post('page_sort_save', 'NoteboardController@page_sort_save');
        Route::get('/get_header_categories', 'RestaurantController@get_header_categories');
        Route::get('/get_header_sub_categories/{id}', 'RestaurantController@get_header_sub_categories');


        Route::post('add_new_header_page', 'RestaurantController@add_new_header_page');

        Route::get('get_header_pages', 'RestaurantController@get_header_pages');
        Route::get('/change_page_status/{id}', 'RestaurantController@change_page_status');
        Route::get('/fetch_header_page/{id}', 'RestaurantController@fetch_header_page');


        Route::post('add_new_header_category', 'RestaurantController@add_new_header_category');
        Route::get('/change_header_cat_status/{id}', 'RestaurantController@change_header_cat_status');
        Route::get('get_header_subcategories', 'RestaurantController@get_header_subcategories');
        Route::post('add_new_header_subcategory', 'RestaurantController@add_new_header_subcategory');
        Route::get('/change_header_subcat_status/{id}', 'RestaurantController@change_header_subcat_status');
        Route::post('header_cat_sort_save', 'RestaurantController@header_cat_sort_save');
        Route::post('subcat_sort_save', 'RestaurantController@subcat_sort_save');

        Route::get('get_products', 'RestaurantController@get_products');
        Route::get('/fetch_header_menu/{id}', 'RestaurantController@fetch_header_menu');
        Route::post('save_page_category', 'RestaurantController@save_page_category');

        Route::post('/add_to_product', 'RestaurantController@add_to_product');
        Route::get('edit_product_list/{product_id}', 'RestaurantController@edit_product_list');
    }
);

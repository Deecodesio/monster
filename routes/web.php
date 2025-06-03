<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    ['namespace' => 'App\Http\Controllers\admin'],
    function () {
        Route::get('/admin', 'ApplicationController@index');
        Route::get('/admin/{any}', 'ApplicationController@index')->where('any', '.*');
    }
);


Route::group(
    ['namespace' => 'App\Http\Controllers\store'],
    function () {
        Route::get('/store', 'ApplicationController@index');
        Route::get('/store/{any}', 'ApplicationController@index')->where('any', '.*');
    }
);



Route::group(
    ['namespace' => 'App\Http\Controllers\web'],
    function () {
        Route::get('/', 'ApplicationController@index');
        Route::get('/get_site_logo', 'UserController@get_site_logo');
        Route::get('/get_defaults', 'UserController@get_defaults');
        Route::get('/get_qrcode', 'UserController@get_qrcode');
        Route::get('/sitemap.xml', 'RestaurantController@sitemap_xml');


        Route::get('/banner_list/{lat}/{lng}', 'UserController@banner_list');
        Route::get('/category_lists/{id}', 'UserController@category_lists');
        Route::get('/category_list', 'UserController@category_list');
        Route::get('/todays_special_restaurant/{lat}/{lng}', 'UserController@todays_special_restaurant');
        Route::get('/todays_special_store/{lat}/{lng}', 'UserController@todays_special_store');


        Route::get('/get_near_brands/{lat}/{lng}', 'UserController@get_near_brands');
        Route::get('/get_more_restaurant/{lat}/{lng}', 'UserController@get_more_restaurant');
        Route::get('/search_restaurants1/{lat}/{lng}/{key_word}', 'UserController@search_restaurants1');
        Route::post('/doLogin', 'UserController@doLogin');
        Route::get('/single_restaurant/{restaurant_id}/{user_id}', 'RestaurantController@single_restaurant');
        Route::get('/todays_special_business/{lat}/{lng}/{id}', 'UserController@todays_special_business');
        Route::get('/get_more_restaurant_business/{lat}/{lng}/{id}', 'UserController@get_more_restaurant_business');
        Route::get('/get_more_restaurant_business_new/{lat}/{lng}/{id}', 'UserController@get_more_restaurant_business_new');

        Route::get('/top_selling/{restaurant_id}', 'RestaurantController@top_selling');
        Route::get('/restaurant_cities', 'RestaurantController@allCityList');


        Route::get('/get_food_list_by_menu/{restaurant_id}', 'RestaurantController@get_food_list_by_menu');
        Route::get('/get_food_list_by_menu_id/{menu_id}/{restaurant_id}', 'RestaurantController@get_food_list_by_menu_id');

        Route::get('/get_brand_rest/{lat}/{lng}/{brand_id}', 'RestaurantController@get_brand_rest');

        Route::get('/get_profile/{user_id}/{lat}/{lng}', 'UserController@get_profile');

        Route::post('update_profile', 'UserController@update_profile');
        Route::get('get_app_url', 'UserController@get_app_url');


        Route::get('/view_order/{id}', 'OrderController@view_order');
        Route::post('cancel_order/{cancelOrderID}/{reason}', 'OrderController@cancel_order');
        Route::get('/get_category_wise_food_list/{lat}/{lng}/{key_word}', 'RestaurantController@get_category_wise_food_list');
        Route::get('/mark_as_favourite/{restaurant_id}/{user_id}', 'UserController@mark_as_favourite');
        Route::get('/remove_favourite/{restaurant_id}/{user_id}/{lat}/{lng}', 'UserController@remove_favourite');


        Route::get('/get_category_wise_food_list_business/{lat}/{lng}/{key_word}', 'RestaurantController@get_category_wise_food_list_business');

        Route::get('/aboutus', 'UserController@aboutus');
        Route::get('/fetch_contact', 'UserController@fetch_contact');

        Route::get('/privacy_policy', 'UserController@privacy_policy');
        Route::get('/terms', 'UserController@terms');
        Route::get('/fetch_cancellation', 'UserController@fetch_cancellation');

        Route::get('/fetch_shipping', 'UserController@fetch_shipping');


        Route::get('/get_checkout_details/{restaurant_id}/{lat}/{lng}/{user_id}', 'RestaurantController@get_checkout_details');
        Route::get('/get_delivery_address/{user_id}', 'UserController@get_delivery_address');
        Route::post('paynow', 'RestaurantController@paynow');
        Route::get('track_order/{user_id}/{request_id}', 'OrderController@track_order');
        Route::post('register', 'UserController@register');
        Route::post('pay_for_order/{orderID}', 'RestaurantController@pay_for_order');

        Route::any('/check_pay_for_order/{orderID}', 'RestaurantController@check_pay_for_order');
        // Route::post('/order_ratings/{delivery_boy_rating}/{restaurant_rating}/{request_id}/{restaurant_feedback}', 'RestaurantController@order_ratings');
        Route::post('/forgot_password', 'UserController@forgot_password');
        Route::post('/reset_password', 'UserController@reset_password');
        Route::post('/reset_password_submit', 'UserController@reset_password_submit');

        Route::get('offers/{authId}', 'UserController@offers_page');
        Route::get('delete_address/{id}', 'OrderController@delete_address');
        Route::get('/city_list', 'RestaurantController@city_list');

        Route::post('area_list1', 'RestaurantController@area_list1');

        Route::post('signup_form_submit', 'RestaurantController@signup_form_submit');

        Route::get('/cuisines_list', 'ItemmasterController@get_cuisines_list');
        Route::get('/business_type_list', 'ItemmasterController@business_type_list');
        Route::get('/business_types/{id}', 'ItemmasterController@business_types');

        Route::get('document_view', 'RestaurantController@document_view');

        Route::post('new-driver', 'DriverController@signup_form_submit');
        Route::get('getcontactreasons', 'UserController@getcontactreasons');
        Route::post('contacts', 'UserController@contact_admin');
        Route::get('faqs', 'UserController@faq');

        Route::post('/add_delivery_address', 'UserController@add_delivery_address');
        Route::post('/edit_delivery_address', 'UserController@edit_delivery_address');

        Route::post('/getpaymentmethods', 'UserController@getpaymentmethods');
        Route::post('sociallogin/{provider}', 'UserController@SocialSignup');
        Route::post('/google-callback', 'UserController@SocialSignup');
        Route::get('/get_address/{lat}/{lng}', 'UserController@get_address');
        Route::get('/get_default_address/{lat}/{lng}/{user_id}', 'UserController@get_default_address');
        Route::get('/get_near_address/{lat}/{lng}/{user_id}', 'UserController@get_near_address');

        Route::get('/get_uaddress/{id}', 'UserController@get_uaddress');
        Route::get('/business_banner/{id}', 'UserController@business_banner');

        Route::post('/order_cancel', 'UserController@order_cancel');


        Route::get('set_delivery_address/{address_id}/{id}', 'UserController@set_delivery_address');
        Route::get('/checkradius/{restaurant_id}/{user_lat}/{user_lng}', 'UserController@checkradius');
        Route::post('/add_phone_number', 'UserController@add_phone_number');

        Route::post('/stripe_Response', 'RestaurantController@stripe_Response');
        Route::get('/get_more_restaurant_new/{lat}/{lng}', 'UserController@get_more_restaurant_new');

        //  pickupdrop
        Route::post('/checkradius1', 'UserController@checkradius1');
        Route::get('/get_business/{id}', 'UserController@get_business');
        Route::get('/get_product_list', 'UserController@get_pickupdrop_product_list');
        Route::get('/get_carrier', 'UserController@get_carrier');
        Route::post('/get_distance_calculate', 'UserController@get_distance_calculate');
        Route::post('/pickup_drop_checkout', 'UserController@pickup_drop_checkout');
        Route::get('/get_address_details_pickup/{id}', 'UserController@get_address_details_pickup');
        Route::get('/get_address_details_drop/{id}', 'UserController@get_address_details_drop');


        Route::post('paynow_pickuporder', 'RestaurantController@paynow_pickuporder');


        Route::get('/layout', 'RestaurantController@layout');
        Route::get('/get_tips', 'UserController@get_tips');
        Route::get('/get_cancellation_reasons', 'UserController@get_cancellation_reasons');

        Route::post('/order_ratings/{delivery_boy_rating}/{restaurant_rating}/{request_id}/{restaurant_feedback}', 'RestaurantController@order_ratings');
        Route::get('/verify_restaurant/{id}/{code}', 'RestaurantController@verify_restaurant');

        Route::get('/verify_driver/{id}/{code}', 'RestaurantController@verify_driver');

        // Basic CareerJobController CRUD operations
        Route::get('career-jobs/', 'CareerJobController@index'); // GET /api/career-jobs
        Route::post('career-jobs/', 'CareerJobController@store'); // POST /api/career-jobs
        Route::get('career-jobs/{id}', 'CareerJobController@show'); // GET /api/career-jobs/{id}
        Route::put('/career-jobs{id}', 'CareerJobController@update'); // PUT /api/career-jobs/{id}
        Route::delete('career-jobs/{id}', 'CareerJobController@destroy'); // DELETE /api/career-jobs/{id}

        // Additional routes for CareerJobController
        Route::patch('career-jobs/{id}/toggle-status', 'CareerJobController@toggleStatus'); // PATCH /api/career-jobs/{id}/toggle-status
        Route::get('career-jobs/active/list', 'CareerJobController@getActiveJobs'); // GET /api/career-jobs/active/list
        Route::get('career-jobs/statistics/overview', 'CareerJobController@getJobsStats'); // GET /api/career-jobs/statistics/overview

        // Blog Category Routes
        Route::get('/blog-categories', 'BlogCategoryController@index');
        Route::get('/blog-categories/{id}', 'BlogCategoryController@show');
        Route::get('/blog-categories/{id}/blogs', 'BlogCategoryController@blogs');

        // Blog Routes
        Route::get('/blogs', 'BlogController@index');
        Route::get('/blogs/{slug}', 'BlogController@show');
        Route::get('/blogs/category/{categoryId}', 'BlogController@byCategory');

        // Job Application Routes
        Route::get('/job-applications', 'JobApplicationController@index');
        Route::post('/job-applications', 'JobApplicationController@store');
        Route::get('/job-applications/{id}', 'JobApplicationController@show');
        Route::put('/job-applications/{id}', 'JobApplicationController@update');
        Route::delete('/job-applications/{id}', 'JobApplicationController@destroy');
        Route::get('/job-applications/stats', 'JobApplicationController@getApplicationStats');
        Route::get('/job-applications/{id}/download-resume', 'JobApplicationController@downloadResume');
        Route::delete('/job-applications/bulk-destroy', 'JobApplicationController@bulkDestroy');
        Route::get('/job-applications/export', 'JobApplicationController@exportApplications');
        Route::get('/job-applications/job/{careerJobId}', 'JobApplicationController@getApplicationsByJob');
        Route::get('/active-jobs', 'JobApplicationController@getActiveJobs');

        // Franchise Routes
        Route::get('/franchises', 'FranchiseController@index');
        Route::post('/franchises', 'FranchiseController@store');
        Route::get('/franchises/{id}', 'FranchiseController@show');
        Route::put('/franchises/{id}', 'FranchiseController@update');
        Route::delete('/franchises/{id}', 'FranchiseController@destroy');
        Route::get('/franchises/stats', 'FranchiseController@getFranchiseStats');


        Route::POST('/add_wallet', 'UserController@add_wallet');
        Route::POST('/add_wallet_1', 'UserController@add_wallet_1');
        Route::any('/check_pay_for_wallet/{amount}/{id}', 'UserController@check_pay_for_wallet');
        Route::POST('/topupWallet', 'UserController@topupWallet');
        Route::get('/setlocale/{lang}', 'UserController@setlocale');
        Route::get('/setlocale_lang', 'UserController@setlocale_lang');

        Route::get('/plans', 'UserController@plans');

        Route::post('/get_plan_details/{plan}/{rest_id}', 'UserController@get_plan_details');
        Route::get('/get_plan_byid/{plan}', 'UserController@get_plan_byid');
        Route::any('/rest_payment_details/{orderID}', 'RestaurantController@rest_payment_details');

        Route::any('/restaurant_subscription/{id}/{status}', 'RestaurantController@restaurant_subscription');


        Route::get('/landing', 'UserController@landing');
        Route::get('/single_restaurant_products/{lat}/{lng}/{id}', 'RestaurantController@single_restaurant_products');
        Route::get('/category_products/{lat}/{lng}/{id}/{business_id}', 'RestaurantController@category_products');
        Route::get('/sub_category_products/{lat}/{lng}/{id}/{business_id}', 'RestaurantController@sub_category_products');


        // Route::get('/get_product_details/{id}', 'RestaurantController@get_product_details');
        Route::get('/get_product_details/{lat}/{lng}/{id}/{business_id}', 'RestaurantController@get_product_details');

        Route::get('calc_delivery_charge/{restaurant_id}/{lat}/{lng}/{itemtotal}', 'UserController@calc_delivery_charge');


        Route::get('/get_pages', 'RestaurantController@get_pages');
        Route::get('get_page_details/{id}', 'UserController@get_page_details');

        Route::get('get_header_contents', 'UserController@get_header_contents');

        Route::get('/fetch_header_page/{id}', 'RestaurantController@fetch_header_page');

        Route::get('/validate_pincode/{lat}/{lng}/{rest}', 'UserController@validate_pincode');
        Route::post('enquire_now', 'RestaurantController@enquire_now');

        Route::get('pay_for_razor/{orderID}', 'RazorpayController@razorpay');
        Route::post('/razorpaypayment', 'RazorpayController@payment');

        Route::post('/track_user_order', 'UserController@track_user_order');

        Route::get('/{any}', function () {
            return view('application');
        })->where('any', '.*');
    }
);

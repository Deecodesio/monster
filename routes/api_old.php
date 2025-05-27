<?php



// header('Access-Control-Allow-Origin: *');
// header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
// header('Access-Control-Allow-Credentials: true');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
//date_default_timezone_set("Asia/Kolkata");

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::group(  ['namespace' => 'App\Http\Controllers\api'],function(){

	Route::get('/service_status','LoginController@service_status');
	Route::get('/payment-methods','LoginController@payment_methods');
	Route::get('/android-version','LoginController@android_version');
	Route::get('/ios-version','LoginController@ios_version');
	Route::POST('/register','LoginController@register');
	Route::post('/login','LoginController@user_login');
	Route::POST('/social_login','LoginController@social_login');
	Route::POST('/send_otp','LoginController@send_otp_login');
	Route::POST('/forgot_password','LoginController@forgot_password');
	Route::POST('/reset_password','LoginController@reset_password');
	Route::POST('/deactivate_account','LoginController@deactivate_account');
	Route::POST('/update_profile','LoginController@update_profile');
	Route::post('/payment-callback', 'UserController@payment_callback')->name('user.payment_callback');
	Route::get('/content/{type}', 'UserController@content');
	Route::post('/get_near_address', 'UserController@get_near_address');
	

	// Route::group(['middleware'=>'authCheck'],function(){
		
		Route::GET('get_default_address','UserController@get_default_address');
		Route::POST('set_delivery_address','UserController@set_delivery_address');
		Route::POST('track_order_detail','UserController@track_order_detail');
		Route::GET('/get_current_order_status','UserController@get_current_order_status');
		Route::GET('/get_delivery_address','UserController@get_delivery_address');
		Route::POST('/add_delivery_address','UserController@add_delivery_address');
		Route::GET('/get_profile','LoginController@get_profile');
		Route::GET('/get_filter_list/{filter_type}','UserController@get_filter_list');	// filter_type =1 - Cusines table else relevance table
		Route::POST('/delete_user_address','UserController@delete_user_address');

		Route::POST('/get_relevance_restaurant','UserController@get_relevance_restaurant');
		Route::POST('/get_menu','UserController@get_menu');
		Route::GET('/get_nearby_restaurant','UserController@get_nearby_restaurant');
		Route::GET('/get_near_restaurant','UserController@get_near_restaurant');
		Route::GET('/get_more_restaurant','UserController@get_more_restaurant');
		Route::GET('/get_near_brands','UserController@get_near_brands');
		Route::GET('/get_brand_rest','UserController@get_brand_rest');


		Route::GET('/get_banners','UserController@get_banners');
		Route::GET('/get_popular_brands','UserController@get_popular_brands');
		Route::POST('/search_restaurants','RestaurantController@search_restaurants');
		Route::POST('/search_restaurants1','RestaurantController@search_restaurants1');
		Route::POST('/restaurants-for-schedule-delivery','RestaurantController@restaurants_for_schedule_delivery');
		Route::get('/get_restaurants_by_cuisine','RestaurantController@get_restaurants_by_cuisine');
		// Route::GET('/get_restaurant_list','UserController@get_restaurant_list');
		Route::GET('/get_favourite_list','UserController@get_favourite_list');
		Route::POST('/update_favourite','UserController@update_favourite');
		Route::POST('/filter_relevance_restaurant','UserController@filter_relevance_restaurant');
		Route::POST('/single_restaurant','RestaurantController@single_restaurant');
		Route::POST('/add_to_cart','RestaurantController@add_to_cart');
		Route::POST('/reduce_from_cart','RestaurantController@reduce_from_cart');
		Route::GET('/check_cart','RestaurantController@check_cart');
		Route::GET('/get_menu/{restaurant_id}','RestaurantController@get_menu');
		Route::GET('/get_category/{restaurant_id}','RestaurantController@get_category');
		Route::GET('/get_category_list','RestaurantController@get_category_list');
		Route::POST('/get_category_wise_food_list','RestaurantController@get_category_wise_food_list');
		Route::POST('/get_food_list','RestaurantController@get_food_list');
		Route::POST('/get_food_list_by_menu','RestaurantController@get_food_list_by_menu');
		Route::get('/get_brands','RestaurantController@get_brands');
		Route::post('/get_restaurantby_brand','RestaurantController@get_restaurantby_brand');
		//search
		Route::POST('/get_food_list_by_search','RestaurantController@get_food_list_by_search');
		Route::POST('/checkout','RestaurantController@checkout');
		Route::POST('/paynow','RestaurantController@paynow');
		Route::POST('/CreateOrder','OrderController@CreateOrder');
		Route::POST('/checkradius','OrderController@checkradius');
		Route::POST('/ConfirmOrder','OrderController@ConfirmOrder');
		Route::GET('/order_history','UserController@order_history');
		Route::POST('/order_cancel','UserController@order_cancel');
		Route::GET('/get_order_status','UserController@get_order_status');
		Route::GET('/get_cancellation_reasons','UserController@get_cancellation_reasons');
		Route::post('/payment_intent','RestaurantController@payment_intent');
		Route::GET('/logout','LoginController@logout');
       
        Route::GET('/get_user_language','UserController@get_user_language');
        Route::POST('/set_user_language','UserController@set_user_language');

		//get offers list
		Route::GET('/get_promo_list','UserController@get_promo_list');
		Route::GET('/get_rest_promo_list','UserController@get_rest_promo_list');


		Route::POST('/dahab_order','OrderController@dahab_order');
		
		//update ratings for order
		Route::POST('/order_ratings','OrderController@order_ratings');
		Route::POST('/order_review','OrderController@order_review');
		//validate promocode
		Route::POST('/check_promocode','OrderController@check_promocode');
		Route::POST('/ApplyCoupon','OrderController@applyCoupon');

	// });
	//mybank
	Route::POST('/order_response','OrderController@response_mybank');
	Route::POST('/order_response1','OrderController@response_mybank1');
	Route::POST('/edahab_response','OrderController@edahab_response');


	Route::group(['prefix'=>'providerApi'],function(){

		Route::get('/android-version','Provider_LoginController@android_version');
		Route::get('/ios-version','Provider_LoginController@ios_version');
		Route::POST('/register','Provider_LoginController@register');
		Route::POST('/login','Provider_LoginController@provider_login');
		Route::POST('/send_otp','Provider_LoginController@send_otp_login');
		Route::POST('/forgot_password','Provider_LoginController@forgot_password');
		Route::POST('/reset_password','Provider_LoginController@reset_password');

		
		Route::group(['middleware'=>'authCheck'],function(){

			Route::GET('/get_profile','Provider_LoginController@get_profile');
			Route::POST('/update_profile','Provider_LoginController@update_profile');
			Route::POST('/get_address_detail','OrderController@get_address_detail');

			Route::POST('/update_request','OrderController@update_request');
			Route::POST('/cancel_request','OrderController@cancel_request');
			Route::GET('/get_order_status','OrderController@get_order_status');
			Route::GET('/get_order_status_new','OrderController@get_order_status_new');
			Route::GET('/order_history','OrderController@order_history');
			Route::GET('/get_order_list','OrderController@get_driver_order_list');
			
			//earnings api
			Route::POST('/today_earnings','Provider_LoginController@today_earnings');
			Route::POST('/weekly_earnings','Provider_LoginController@weekly_earnings');
			Route::POST('/monthly_earnings','Provider_LoginController@monthly_earnings');

			//payout details api
			Route::GET('/payout_details','Provider_LoginController@payout_details');

		});

	});


	Route::group(['prefix'=>'admin'],function(){
		Route::post('/login', 'AdminController@login');
		Route::get('/android-version','AdminController@android_version');
		Route::get('/ios-version','AdminController@ios_version');

		
		Route::post('/orders/{type}', 'AdminController@orders');
		Route::post('/order/{id}', 'AdminController@order');
		Route::post('/order-accept/{id}', 'AdminController@order_accept');
		Route::post('/order-cancel/{id}', 'AdminController@order_cancel');
		Route::post('/order-assign/{id}', 'AdminController@order_assign');
		Route::post('/total-new-orders', 'AdminController@order_new_total');
		Route::post('/orders-total', 'AdminController@order_total');

		Route::post('/products', 'AdminController@products');
		Route::post('/product/enable/{id}', 'AdminController@product_enable');
		Route::post('/product/disable/{id}', 'AdminController@product_disable');

		Route::get('/change-open-status/{status}', 'AdminController@change_open_status');

		Route::get('/category_list', 'AdminController@get_category_list');
		Route::get('/menu_list','AdminController@restaurant_menu');
		Route::get('/addon_list', 'AdminController@list_addons');

		Route::post('/order_picked/','AdminController@order_picked');
		Route::post('/new_requests','AdminController@new_requests');
		Route::post('/delay_order','AdminController@delayOrder');
		Route::post('/product/multi_product_enable/{id}', 'AdminController@multi_product_enable');
		Route::post('/product/multi_product_disable/{id}', 'AdminController@multi_product_disable');

		
		Route::post('/product_stock/enable/{id}', 'AdminController@stock_enable');
		Route::post('/product_stock/disable/{id}', 'AdminController@stock_disable');

		Route::get('/restaurant_timing', 'AdminController@restaurant_timing');


	});


});




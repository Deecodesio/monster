<?php

namespace App\Providers;

use Config;
use DB;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $setting_value = DB::table('settings')->pluck('value', 'key_word')->toArray();
        $country = DB::table('country')->where('is_default', 1)->first();

        if (isset($setting_value)) {

            if (!defined('ADMIN_COMMISSION')) define('ADMIN_COMMISSION', (isset($setting_value['admin_commission']) ? $setting_value['admin_commission'] : 0));
            if (!defined('DEFAULT_LATITUDE')) define('DEFAULT_LATITUDE', (isset($setting_value['DEFAULT_LATITUDE']) ? $setting_value['DEFAULT_LATITUDE'] : 0));
            if (!defined('DEFAULT_LONGITUDE')) define('DEFAULT_LONGITUDE', (isset($setting_value['DEFAULT_LONGITUDE']) ? $setting_value['DEFAULT_LONGITUDE'] : 0));


            if (!defined('DEFAULT_LONGITUDE')) define('DEFAULT_LONGITUDE', (isset($setting_value['DEFAULT_LONGITUDE']) ? $setting_value['DEFAULT_LONGITUDE'] : 0));

            if (!defined('DEFAULT_LONGITUDE')) define('DEFAULT_LONGITUDE', (isset($setting_value['DEFAULT_LONGITUDE']) ? $setting_value['DEFAULT_LONGITUDE'] : 0));

            if (!defined('DEFAULT_LONGITUDE')) define('DEFAULT_LONGITUDE', (isset($setting_value['DEFAULT_LONGITUDE']) ? $setting_value['DEFAULT_LONGITUDE'] : 0));


            if (!defined('G_CLIENT_ID')) define('G_CLIENT_ID', (isset($setting_value['google_client_id']) ? $setting_value['google_client_id'] : 0));

            if (!defined('G_CLIENT_SECRET')) define('G_CLIENT_SECRET', (isset($setting_value['google_client_secret']) ? $setting_value['google_client_secret'] : 0));

            if (!defined('G_REDIRECT_URL')) define('G_REDIRECT_URL', (isset($setting_value['google_redirect_url']) ? $setting_value['google_redirect_url'] : 0));



            if (!defined('STRIPE_SK_KEY')) define('STRIPE_SK_KEY', (isset($setting_value['stripe_sk_key']) ? $setting_value['stripe_sk_key'] : ''));

            if (!defined('STRIPE_PK_KEY')) define('STRIPE_PK_KEY', (isset($setting_value['stripe_pk_key']) ? $setting_value['stripe_pk_key'] : ''));

            if (!defined('STRIPE_VERSION')) define('STRIPE_VERSION', (isset($setting_value['stripe_version']) ? $setting_value['stripe_version'] : ''));

            if (!defined('APP_NAME')) define('APP_NAME', (isset($setting_value['app_name']) ? $setting_value['app_name'] : 'Eatzilla'));

            if (!defined('USER_NOTIFICATION_KEY')) define('USER_NOTIFICATION_KEY', (isset($setting_value['user_notification_key']) ? $setting_value['user_notification_key'] : 'AIzaSyD5tCcm18TB_StcOlo5rNOcponUHxzX9Gg'));

            if (!defined('PARTNER_NOTIFICATION_KEY')) define('PARTNER_NOTIFICATION_KEY', (isset($setting_value['partner_notification_key']) ? $setting_value['partner_notification_key'] : 'AIzaSyD5tCcm18TB_StcOlo5rNOcponUHxzX9Gg'));

            if (!defined('DEFAULT_RADIUS')) define('DEFAULT_RADIUS', (isset($setting_value['default_radius']) ? $setting_value['default_radius'] : 5));

            if (!defined('DEFAULT_RES_RADIUS')) define('DEFAULT_RES_RADIUS', (isset($setting_value['restaurant_distance']) ? $setting_value['restaurant_distance'] : 2));


            if (!defined('HIGHLIGHT_COLOR')) define('HIGHLIGHT_COLOR', (isset($setting_value['highlight_color']) ? $setting_value['highlight_color'] : '#23262f'));

            if (!defined('MENU_COLOR')) define('MENU_COLOR', (isset($setting_value['menu_color']) ? $setting_value['menu_color'] : '#2C303B'));

            if (!defined('SITE_CONTACT')) define('SITE_CONTACT', (isset($setting_value['site_contact']) ? $setting_value['site_contact'] : ''));

            if (!defined('SITE_EMAIL')) define('SITE_EMAIL', (isset($setting_value['site_email']) ? $setting_value['site_email'] : ''));

            if (!defined('SITE_FAVICON')) define('SITE_FAVICON', (isset($setting_value['site_favicon']) ? $setting_value['site_favicon'] : 'facicon.ico'));

            if (!defined('SITE_LOGO')) define('SITE_LOGO', (isset($setting_value['site_logo']) ? $setting_value['site_logo'] : 'eatzilla1.jpg'));

            if (!defined('DEFAULT_UNIT')) define('DEFAULT_UNIT', (isset($setting_value['default_unit']) ? $setting_value['default_unit'] : 'KM'));

            if (!defined('EMAIL_ENABLE')) define('EMAIL_ENABLE', (isset($setting_value['email_enable']) ? $setting_value['email_enable'] : 0));

            if (!defined('SMS_ENABLE')) define('SMS_ENABLE', (isset($setting_value['sms_enable']) ? $setting_value['sms_enable'] : 0));

            if (!defined('EMAIL_USER_NAME')) define('EMAIL_USER_NAME', (isset($setting_value['email_user_name']) ? $setting_value['email_user_name'] : ''));

            if (!defined('EMAIL_PASSWORD')) define('EMAIL_PASSWORD', (isset($setting_value['email_password']) ? $setting_value['email_password'] : ''));

            if (!defined('TIME_ZONE')) define('TIME_ZONE', (isset($setting_value['time_zone']) ? $setting_value['time_zone'] : 'UTC'));

            if (!defined('FIREBASE_URL')) define('FIREBASE_URL', (isset($setting_value['firebase_url']) ? $setting_value['firebase_url'] : 'https://joldiasho-10cd9.firebaseio.com'));

            if (!defined('GOOGLE_API_KEY')) define('GOOGLE_API_KEY', (isset($setting_value['google_api_key']) ? $setting_value['google_api_key'] : 'AIzaSyBWIomenTvO9o1V8ZfCbQBXV_UG9iDcNsg'));
            if (!defined('ANALYTICS_SCRIPT')) define('ANALYTICS_SCRIPT', (isset($setting_value['analytics_script']) ? $setting_value['analytics_script'] : ''));
            if (!defined('SHARING_SCRIPT')) define('SHARING_SCRIPT', (isset($setting_value['sharing_script']) ? $setting_value['sharing_script'] : ''));
            if (!defined('SCRIPT')) define('SCRIPT', (isset($setting_value['script']) ? $setting_value['script'] : ''));
            if (!defined('SITE_NAME')) define('SITE_NAME', (isset($setting_value['app_name']) ? $setting_value['app_name'] : ''));
            if (!defined('IOS_APP_LINK')) define('IOS_APP_LINK', (isset($setting_value['ios_app_link']) ? $setting_value['ios_app_link'] : '#'));
            if (!defined('ANDROID_APP_LINK')) define('ANDROID_APP_LINK', (isset($setting_value['android_app_link']) ? $setting_value['android_app_link'] : '#'));

            if (!defined('META_TITLE')) define('META_TITLE', (isset($setting_value['meta_title']) ? $setting_value['meta_title'] : ''));
            if (!defined('GOOGLE_SITE_VERIFY')) define('GOOGLE_SITE_VERIFY', (isset($setting_value['google_site_verification']) ? $setting_value['google_site_verification'] : ''));
            if (!defined('META_DESC')) define('META_DESC', (isset($setting_value['meta_description']) ? $setting_value['meta_description'] : ''));
            if (!defined('ORDER_ID_PREFIX')) define('ORDER_ID_PREFIX', (isset($setting_value['order_prefix']) ? $setting_value['order_prefix'] : 'EATZILLA'));

            View::share('SCRIPT', SCRIPT);
            View::share('ANALYTICS_SCRIPT', ANALYTICS_SCRIPT);
            View::share('SHARING_SCRIPT', SHARING_SCRIPT);

            View::share('GOOGLE_API_KEY', GOOGLE_API_KEY);


            Config::set('app.timezone', TIME_ZONE);
            date_default_timezone_set(TIME_ZONE);



            Config::set('services.google.client_id', G_CLIENT_ID);
            Config::set('services.google.client_secret', G_CLIENT_SECRET);
            Config::set('services.google.redirect', G_REDIRECT_URL);
        }

        if ($setting_value == null) {

            if (!defined('ADMIN_COMMISSION')) define('ADMIN_COMMISSION', '0');
            if (!defined('DELIVERY_BOY_COMMISSION')) define('DELIVERY_BOY_COMMISSION', '0');
            if (!defined('RESTAURANT_COMMISSION')) define('RESTAURANT_COMMISSION', '0');
            if (!defined('STRIPE_SK_KEY')) define('STRIPE_SK_KEY', 'sk_test_BlD4SrbP60Qa94PrQ1pTHYtB');
            if (!defined('STRIPE_PK_KEY')) define('STRIPE_PK_KEY', 'pk_test_uzFnOtl3tNwStqKIi5Vflq61');
            if (!defined('APP_NAME')) define('APP_NAME', 'Deliware');
            if (!defined('USER_NOTIFICATION_KEY')) define('USER_NOTIFICATION_KEY', 'AIZASYD5TCCM18TB_STCOLO5RNOCPONUHXZX9GG');
            if (!defined('PARTNER_NOTIFICATION_KEY')) define('PARTNER_NOTIFICATION_KEY', 'AIzaSyD5tCcm18TB_StcOlo5rNOcponUHxzX9Gg');
            if (!defined('DEFAULT_RADIUS')) define('DEFAULT_RADIUS', 5);
            if (!defined('HIGHLIGHT_COLOR')) define('HIGHLIGHT_COLOR', '#23262f');
            if (!defined('MENU_COLOR')) define('MENU_COLOR', '#2C303B');
            if (!defined('SITE_CONTACT')) define('SITE_CONTACT', '');
            if (!defined('SITE_EMAIL')) define('SITE_EMAIL', '');
            if (!defined('SITE_FAVICON')) define('SITE_FAVICON', 'facicon.ico');
            if (!defined('SITE_LOGO')) define('SITE_LOGO', 'eatzilla1.jpg');
            if (!defined('DEFAULT_UNIT')) define('DEFAULT_UNIT', 'KM');
            if (!defined('EMAIL_ENABLE')) define('EMAIL_ENABLE', 0);
            if (!defined('SMS_ENABLE')) define('SMS_ENABLE', 0);
            if (!defined('EMAIL_USER_NAME')) define('EMAIL_USER_NAME', '');
            if (!defined('EMAIL_PASSWORD')) define('EMAIL_PASSWORD', '');
            if (!defined('TIME_ZONE')) define('TIME_ZONE', 'UTC');
            if (!defined('FIREBASE_URL')) define('FIREBASE_URL', 'https://joldiasho-10cd9.firebaseio.com');
            if (!defined('GOOGLE_API_KEY')) define('GOOGLE_API_KEY', 'AIzaSyBWIomenTvO9o1V8ZfCbQBXV_UG9iDcNsg');
            if (!defined('ORDER_ID_PREFIX')) define('ORDER_ID_PREFIX', 'EATZILLA');
            if (!defined('ANALYTICS_SCRIPT')) define('ANALYTICS_SCRIPT', '');
            if (!defined('SHARING_SCRIPT')) define('SHARING_SCRIPT', '');
            if (!defined('SCRIPT')) define('SCRIPT', '');
            if (!defined('SITE_NAME')) define('SITE_NAME', '');
            if (!defined('META_TITLE')) define('META_TITLE', '');
            if (!defined('GOOGLE_SITE_VERIFY')) define('GOOGLE_SITE_VERIFY', '');
            if (!defined('META_DESC')) define('META_DESC', '');
            if (!defined('IOS_APP_LINK')) define('IOS_APP_LINK', '#');
            if (!defined('ANDROID_APP_LINK')) define('ANDROID_APP_LINK', '#');
        }

        if (isset($country)) {

            if (!defined('DEFAULT_COUNTRY')) define('DEFAULT_COUNTRY', (isset($country->country) ? $country->country : 'India'));

            if (!defined('DEFAULT_COUNTRY_CODE')) define('DEFAULT_COUNTRY_CODE', (isset($country->country_code) ? $country->country_code : '+91'));

            if (!defined('DEFAULT_CURRENCY')) define('DEFAULT_CURRENCY', (isset($country->currency_code) ? $country->currency_code : 'USD'));

            if (!defined('DEFAULT_CURRENCY_SYMBOL')) define('DEFAULT_CURRENCY_SYMBOL', (isset($country->currency_symbol) ? $country->currency_symbol : '$'));
        } else {

            if (!defined('DEFAULT_COUNTRY')) define('DEFAULT_COUNTRY', 'India');

            if (!defined('DEFAULT_COUNTRY_CODE')) define('DEFAULT_COUNTRY_CODE', '+91');

            if (!defined('DEFAULT_CURRENCY')) define('DEFAULT_CURRENCY', 'USD');

            if (!defined('DEFAULT_CURRENCY_SYMBOL')) define('DEFAULT_CURRENCY_SYMBOL', '$');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

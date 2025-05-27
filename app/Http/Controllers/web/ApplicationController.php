<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use File;

class ApplicationController extends BaseController
{
    public function index()
    {
        $app = DB::table('settings')->where('key_word', "app_name")->first();
        $favicon = DB::table('settings')->where('key_word', "site_favicon")->first();
        $app_name = $app->value;
        $site_favicon = BASE_URL . RESTAURANT_UPLOADS_PATH . $favicon->value;
        $gapi = DB::table('settings')->where('key_word', 'google_api_key')->first();
        $google_key = $gapi->value;
        $meta_tit = DB::table('settings')->where('key_word', "meta_title")->first();
        $meta_de = DB::table('settings')->where('key_word', "meta_description")->first();
        $meta_title = $meta_tit->value;
        $meta_desc = $meta_de->value;

        $title = $app_name;

        $meta = [
            "title" => $meta_tit->value,
            "description" => $meta_de->value,
            "url" => BASE_URL,
            "image" => $site_favicon
        ];

        $path = request()->path();
        if (str_contains($path, 'pages/')) {
            $post_id = str_replace('pages/', '', $path);
            $strArray = explode('-', $post_id);
            $page_id = $strArray[count($strArray) - 1];
            $page_details = DB::table('header_page_contents')->where('id', $page_id)->first();
            if ($page_details) {
                $meta = [
                    "title" => $page_details->meta_title,
                    "description" => $page_details->meta_desc,
                    "url" => request()->url(),
                    "image" => BASE_URL . "uploads/" . $page_details->meta_image
                ];
                $title = $page_details->name;
            }
        }

        if (str_contains($path, 'product/')) {
            $post_id = str_replace('product/', '', $path);
            $strArray = explode('-', $post_id);
            $page_id = $strArray[count($strArray) - 1];
            $product_details = DB::table('food_list')->where('id', $page_id)->first();
            if ($product_details) {
                $meta = [
                    "title" => $product_details->name,
                    "description" => $product_details->description,
                    "url" => request()->url(),
                    "image" => ""
                ];
                $title = $product_details->name;
            }
        }
        $is_product_page = 0;

        $schema = [];
        if (str_contains($path, 'product/')) {
            $is_product_page = 1;
            $post_id = str_replace('product/', '', $path);
            $strArray = explode('-', $post_id);
            $page_id = $strArray[count($strArray) - 1];
            $product_details = DB::table('food_list')->where('id', $page_id)->first();

            $category = DB::table('business_category')->where('id', $product_details->business_category_id)->first();
            $subcategory = DB::table('category')->where('id', $product_details->category_id)->first();

            $category_name = $category->category_name;

            $sub_category_name = $subcategory->category_name;



            if ($product_details->image != "") {
                $image = File::exists(public_path('product_image/') . $product_details->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $product_details->image : (File::exists(public_path('restaurant_uploads/') . $product_details->restaurant_id . '/' . 'Product/' . $product_details->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $product_details->restaurant_id . '/' . 'Product/' . $product_details->image : (File::exists(public_path('common_images/') . $product_details->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $product_details->image : BASE_URL  . $product_details->image));
            } else {
                $image[0] = BASE_URL  . "fork.svg";
            }

            $cat_slug =  strtolower(str_replace(' ', '-', $category->category_name) . '-' . $category->id);
            $sub_cat_slug =  strtolower(str_replace(' ', '-', $category->category_name) . '-' . $category->id . '-' . $subcategory->id);
            $schema = [
                'name' => $product_details->name,
                'url' => request()->url(),
                'image' => $image,
                'cat_name' => $category_name,
                'cat_url' => BASE_URL . "categories/" . $cat_slug,
                'sub_category_name' => $sub_category_name,
                'subcat_url' => BASE_URL . "sub_categories/" . $sub_cat_slug


            ];
        }
        return view('user', compact('app_name', 'site_favicon', 'google_key', 'meta_title', 'meta_desc', 'meta', 'title', 'is_product_page', 'schema'));
    }
}

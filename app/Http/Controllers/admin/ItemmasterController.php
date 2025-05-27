<?php

namespace App\Http\Controllers\admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Illuminate\Validation\Rule;
use File;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;


class ItemmasterController extends BaseController
{


	/**
	 * get add category page
	 * 
	 * @return view page
	 * 
	 */
	public function index()
	{

		return view('admin/add_category');
	}


	public function get_category_list(Request $request)
	{
		$cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();

		$data = DB::table('category')
			->join('business_category', 'business_category.id', '=', 'category.business_category_id')
			->select('category.*', 'business_category.category_name as business_name', 'business_category.id as business_id',)
			->orderBy('rank', 'asc')->get();


		$url = "";
		$acc_hash = "";
		if ($cloudflare->value == 1) {
			$get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
			$url = $get_url->value;

			$get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
			$acc_hash = $get_acchash->value;
		}


		foreach ($data as $key => $val) {
			if ($cloudflare->value == 1 && $val->cloudflare_imageid != null) {
				$data[$key]->image = $url . '/' . $acc_hash . '/' . $val->cloudflare_imageid . '/' . "w=100";
			} else {
				if ($val->image != "") {
					if (file_exists(public_path('/')  . $val->image)) {
						$data[$key]->image =  BASE_URL  . $val->image;
					} else {
						$data[$key]->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
					}
				} else {
					$data[$key]->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
				}
			}

			if ($val->category_name) {
				$data[$key]->category_name = $this->secondLanguage($val->category_name, $val->category_secondaryname);
			}
		}

		// print_r($data); exit;

		return response()->json($data);
	}

	public function add_to_category(Request $request)
	{
		//  dd($request->category_image);

		$validator = Validator::make($request->all(), [
			'category_name' => 'required',
			'category_image' => 'required',
			'business_category_id' => 'required'
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);


			return $response_Array;
		} else {
			$category_name = $this->secound_lang_name($request->category_name);
			if (isset($request->category_secondaryname)) {
				$category_secondaryname =  $this->secound_lang_name($request->category_secondaryname);
			} else {
				$category_secondaryname = "";
			}
			$status = $request->status ?? 0;
			$category_list = $this->category;

			$category_data  = [
				'category_name' => $category_name,
				'category_secondaryname' => $category_secondaryname,
				'status' => $status,
				'business_category_id' => $request->business_category_id
			];


			if ($request->existing == "undefined") {
				$image_upload  = basename($request->category_image);
			} else {
				if ($request->existing == 1) {
					$image_upload = $request->category_image;
				} else {

					$image_upload = $this->custom->common_upload_images_cat($request, 'category_image', public_path('category_image/'));
				}
			}


			// $image_upload = $this->custom->common_upload_images($request, 'category_image', public_path('category_image/'));


			if ($request->id) {
				$image = $image_upload ? 'category_image/' . $image_upload : '';
			} else {
				$image = $image_upload ? 'category_image/' . $image_upload : '';
			}

			if ($image) {
				$category_data['image'] = $image;
			}

			if ($request->id) {
				$category_list->where('id', $request->id)->update($category_data);
			} else {

				$data = [];

				$data[] = [
					'category_name' => $category_name,
					'category_secondaryname' => $category_secondaryname,
					'image' => $image,
					'status' => $status,
					'business_category_id' => $request->business_category_id
				];

				$category_list->insert($data);
			}
		}
		$message = "Category added Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}

	public function edit_category($category_id)
	{
		$category_list = $this->category;

		$data = $category_list->where('id', $category_id)->first();
		if ($data->category_name) {
			$data->category_name = $this->secound_name($data->category_name);
		}
		if ($data->category_secondaryname) {
			$data->category_secondaryname = $this->secound_name($data->category_secondaryname);
		}
		if (file_exists(public_path('/') . $data->image)) {
			$data->image = BASE_URL . $data->image;
		} else {
			$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
		}
		// if ($data->image != "") {
		// 	$data->image =  BASE_URL . $data->image;

		// } else {
		// 	$data->image = "";
		// }
		$message = $data;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);


		return $response_Array;
	}

	public function delete_category($category_id)
	{
		$this->category->where('id', $category_id)->delete();

		return redirect('/admin/category_list')->with('success', 'Category Deleted Successfully');
	}

	public function get_cuisines_list(Request $request)
	{
		$data = DB::table('cuisines')->get();
		foreach ($data as $d) {
			$d->name = $this->secondLanguage($d->name, $d->secondary_name);
		}

		// if ($data->cuisine_image != "") {
		// 	$data->cuisine_image =  BASE_URL . UPLOADS_PATH . $data->cuisine_image;

		// } else {
		// 	$data->cuisine_image = "";
		// }

		return response()->json($data);
	}

	public function add_cuisines(Request $request)
	{

		return view('admin/add_cuisines');
	}

	public function add_to_cuisines(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'cuisine_name' => 'required',
		]);

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		$custom = $this->custom;
		$cuisine_name = $this->secound_lang_name($request->cuisine_name);
		$secondary_name = $this->secound_lang_name($request->secondary_name);
		$data = [];
		$data['name'] = $cuisine_name;
		$data['secondary_name'] = $secondary_name ?? '';
		if ($request->cuisine_image) {
			$cuisine_image = $custom->upload_image_common($request, 'cuisine_image');

			$data['cuisine_image'] = $cuisine_image;
		}
		if ($request->id != "undefined") {

			$this->cuisines->where('id', $request->id)
				->update($data);
			$message = "Cusisine Updated Successfully";
		} else {
			$this->cuisines->insert($data);
			$message = "Cusisine Added Successfully";
		}


		// $message = "Cusisine Added Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}
	public function edit_cuisines(Request $request)
	{
		$cuisines = $this->cuisines->where('id', $request->id)->first();
		if ($cuisines->cuisine_image != "") {
			if (file_exists(public_path('/') . UPLOADS_PATH . $cuisines->cuisine_image)) {
				$cuisines->cuisine_image =  BASE_URL . UPLOADS_PATH . $cuisines->cuisine_image;
			} else {
				$cuisines->cuisine_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
		} else {
			$cuisines->cuisine_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
		}
		if ($cuisines->name) {
			$cuisines->name = $this->secound_name($cuisines->name);
		}
		if ($cuisines->secondary_name) {
			$cuisines->secondary_name = $this->secound_name($cuisines->secondary_name);
		}
		// dd($cuisines);
		$message = $cuisines;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}
	public function delete_cuisine(Request $request)
	{
		// $validator = Validator::make($request->all(), [
		// 	'id' => 'required',
		// ]);

		// if ($validator->fails()) {

		// 	$error_messages = implode(',', $validator->messages()->all());
		// 	$message = $error_messages;
		// 	$status = true;
		// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);


		// 	return $response_Array;

		// } else {
		$id = $request->id;
		$cuisines = $this->cuisines;

		$cuisines->where('id', $id)->delete();

		$message = "Cuisine Removed";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;

		// }
	}



	public function store_addons(Request $request)
	{

		$name = $this->secound_lang_name($request->name);
		$id = $request->id;
		$restaurant_id = $request->restaurant_id;
		$secondaryname = $this->secound_lang_name($request->secondary_name);
		if ($request->id != "undefined") {
			$validator = Validator::make($request->all(), [

				'restaurant_id' => 'required',
				'name' => [
					'required',
					Rule::unique('add_ons')->where(function ($query) use ($id, $name, $restaurant_id) {
						return $query->where('restaurant_id', $restaurant_id)
							->where('name', $name)
							->where('id', '!=', $id);
					}),
				],
				'price' => 'required'
			]);
		} else {
			$validator = Validator::make($request->all(), [
				'restaurant_id' => 'required',
				'name' => [
					'required',
					Rule::unique('add_ons')->where(function ($query) use ($name, $restaurant_id) {
						return $query->where('restaurant_id', $restaurant_id)
							->where('name', $name);
					}),
				],
				'price' => 'required'
			]);
		}

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());
			$message =  $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);


			return $response_Array;
		} else {
			if ($request->id != "undefined") {
				$addons = $this->add_ons->find($request->id);
				$addons->restaurant_id = $restaurant_id;
				$addons->name = $request->name;
				$addons->secondary_name = $secondaryname ?? '';
				$addons->price = $request->price;
				$addons->save();
				$trans_msg = "Updated Successfully";
			} else {
				$this->add_ons->restaurant_id = $restaurant_id;
				$this->add_ons->name = $request->name;
				$this->add_ons->price = $request->price;
				if (isset($request->secondaryname)) {
					$this->add_ons->secondary_name = $secondaryname;
				} else {
					$this->add_ons->secondary_name = "";
				}
				$this->add_ons->save();
				$trans_msg = "Add Successfully";
			}
		}

		$message = $trans_msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}


	/**
	 * add view of add_ons
	 * 
	 * @param object $request
	 * 
	 * @return add view page
	 */
	public function add_addons(Request $request)
	{
		$restaurant = $this->restaurants->get();
		// print_r($data); exit;

		return view('admin/add_addons', ['restaurant' => $restaurant]);
	}


	/**
	 * List view of add_ons
	 * 
	 * @return list view page
	 */
	public function list_addons(Request $request)
	{

		$data = $this->add_ons->leftjoin('restaurants', 'restaurants.id', '=', 'add_ons.restaurant_id')
			->select('add_ons.*', 'restaurants.restaurant_name', 'restaurants.restaurant_secondary_name')->get();

		foreach ($data as $d) {
			$d->name = $this->secondLanguage($d->name, $d->secondary_name);
			$d->restaurant_name = $this->secondLanguage($d->restaurant_name, $d->restaurant_secondary_name);
		}
		return response()->json($data);
	}


	/**
	 * edit view of add_ons
	 * 
	 * @param int $id
	 * 
	 * @return edit view page
	 */
	public function edit_addons($id)
	{
		$data = $this->add_ons->with('Restaurant')->find($id);
		if ($data->restaurant_id == 0) {

			$data->restaurant_id = '';
		}
		if ($data->name) {
			$data->name = $this->secound_name($data->name);
		}

		if ($data->secondary_name) {
			$data->secondary_name = $this->secound_name($data->secondary_name);
		}

		$message = $data;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);


		return $response_Array;
	}

	/**
	 * List view of food_quantity
	 * 
	 * @return list view page
	 */
	public function list_food_quantity()
	{
		$data = $this->food_quantity->get();
		//dd($data); 
		foreach ($data as $d) {
			$d->name = $this->secondLanguage($d->name, $d->secondary_name);
		}

		return response()->json($data);
	}


	/**
	 * edit view of food_quantity
	 * 
	 * @param int $id
	 * 
	 * @return edit view page
	 */
	public function edit_food_quantity($id)
	{
		$data = $this->food_quantity->find($id);
		// print_r($data); exit;
		$data->name = $this->secound_name($data->name);
		$data->secondary_name = $this->secound_name($data->secondary_name);

		$message = $data;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $data]);


		return $response_Array;
	}


	/**
	 * add view of food_quantity
	 * 
	 * @param object $request
	 * 
	 * @return add view page
	 */
	public function add_food_quantity(Request $request)
	{

		return view('admin/add_food_quantity');
	}



	public function store_food_quantity(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',

		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());
			$status = false;
			$response_Array = json_encode(['message' => $error_messages, 'status' => $status]);


			return $response_Array;
		}
		if ($request->id) {
			$food_quantity = $this->food_quantity->find($request->id);
			$food_quantity->name = $this->secound_lang_name($request->name);
			$food_quantity->secondary_name = $this->secound_lang_name($request->secondary_name);

			$food_quantity->save();
			$trans_msg = "Food Quantity Updated Successfully";
		} else {
			$this->food_quantity->name = $this->secound_lang_name($request->name);
			$this->food_quantity->secondary_name = $this->secound_lang_name($request->secondary_name);
			$this->food_quantity->save();
			$trans_msg = "Food Quantity Added Successfully";
		}

		$message = $trans_msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}

	public function add_group()
	{

		return view('admin/add_group');
	}

	public function add_to_group(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'group_name' => 'required',
			'status' => 'required'
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			return back()->with('error', $error_messages);
		} else {
			$group_name = $request->group_name;
			if (isset($request->group_secondaryname)) {
				$group_secondaryname = $request->group_secondaryname;
			} else {
				$group_secondaryname = "";
			}
			$status = $request->status;


			$group_data  = [
				'group_name' => $group_name,
				'group_secondaryname' => $group_secondaryname,
				'status' => $status
			];


			if ($request->id) {
				DB::table('groups')->where('id', $request->id)->update($group_data);
			} else {

				$data = [];

				$data[] = [
					'group_name' => $group_name,
					'group_secondaryname' => $group_secondaryname,

					'status' => $status
				];

				DB::table('groups')->insert($group_data);
			}
		}


		return redirect('/admin/group_list')->with('success', 'Group added Successfully');
	}

	public function get_group_list(Request $request)
	{

		$data = DB::table('groups')->get();

		// print_r($data); exit;

		return view('admin/group_list', ['data' => $data]);
	}

	public function edit_group($group_id)
	{


		$data = DB::table('groups')->where('id', $group_id)->first();

		return view('admin/add_group', ['data' => $data]);
	}


	public function add_tags()
	{
		$groups = DB::table('groups')->get();
		return view('admin/add_tags', ['groups' => $groups]);
	}

	public function add_to_tags(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'tags_name' => 'required',
			'status' => 'required',

			'group' => 'required',
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			return back()->with('error', $error_messages);
		} else {
			$tags_name = $request->tags_name;
			if (isset($request->tags_secondaryname)) {
				$tags_secondaryname = $request->tags_secondaryname;
			} else {
				$tags_secondaryname = "";
			}
			$status = $request->status;


			$group_data  = [
				'name' => $tags_name,
				'secondary_name' => $tags_secondaryname,
				'group_id' => $request->group,
				'status' => $status
			];

			$image_upload = $this->custom->common_upload_images($request, 'image', public_path('tag_image/'));
			if ($request->id) {
				$image = $image_upload ?  $image_upload : '';
			} else {
				$image = $image_upload ?  $image_upload : '';
			}

			if ($image) {
				$group_data['image'] = $image;
			}
			if ($request->id) {
				DB::table('tags')->where('id', $request->id)->update($group_data);
			} else {
				$check_tag = DB::table('tags')->where('name', $request->tags_name)->first();
				if ($check_tag) {
					return back()->with('error', 'Tag already exists');
				}
				$data = [];

				$data[] = [
					'group_name' => $tags_name,
					'secondary_name' => $tags_secondaryname,
					'group_id' => $request->group,

					'status' => $status
				];

				DB::table('tags')->insert($group_data);
			}
		}


		return redirect('/admin/tags_list')->with('success', 'Tags added Successfully');
	}

	public function get_tags_list(Request $request)
	{

		$data = DB::table('tags')
			->join('groups', 'groups.id', '=', 'tags.group_id')
			->select('tags.*', 'groups.group_name')
			->get();
		// print_r($data); exit;

		return view('admin/tags_list', ['data' => $data]);
	}

	public function edit_tags($group_id)
	{


		$data = DB::table('tags')->where('id', $group_id)->first();
		$groups = DB::table('groups')->get();
		return view('admin/add_tags', ['data' => $data, 'groups' => $groups]);
	}
	public function add_business(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'banner_image' => 'required',
			'layout_id' => 'required',
		]);
		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		if ($request->layout_id == 4) {
			$l_id = 2;
			$p_id = 1;
		} else {
			$l_id = $request->layout_id;
			$p_id = 0;
		}
		if ($request->id != "undefined") {

			if($request->banner_image)
			{
				if ($request->existing1 == "undefined" || !$request->existing1) {
					$image_upload1  = basename($request->banner_image);
				} else {
					if ($request->existing1 == 1) {
						$image_upload1 = $request->banner_image;
					} else {

						$image_upload1 = $this->custom->common_upload_images_cat_1($request, 'banner_image', public_path('category_image/'));
					}
				}
			}else{
				$image_upload1 = $request->banner_image;
			}

			if ($request->image) {
				if ($request->existing == "undefined" || !$request->existing) {
					$image_upload  = basename($request->image);
				} else {
					if ($request->existing == 1) {
						$image_upload = $request->image;
					} else {

						$image_upload = $this->custom->common_upload_images_cat($request, 'image', public_path('category_image/'));
					}
				}
				// $image_upload = $this->custom->common_upload_images($request, 'image', public_path('category_image/'));
				$data = DB::table('business_type')->where('id', $request->id)->update([
					'name' => $this->secound_lang_name($request->name),
					'secondary_name' => $this->secound_lang_name($request->secondary_name),
					'image' => $image_upload ? 'category_image/' . $image_upload : '',
					'banner_image' => $image_upload1 ? 'category_image/' . $image_upload1 : '',
					'status' => $request->status ?? 0,
					'layout_id' => $l_id,
					'p_status' => $p_id,
					'cloudflare_imageid' => null,
					'cloudflare_bannerid' => null
				]);
			} else {


				$data = DB::table('business_type')->where('id', $request->id)->update([
					'name' => $this->secound_lang_name($request->name),
					'secondary_name' => $this->secound_lang_name($request->secondary_name),
					'banner_image' => $image_upload1 ? 'category_image/' . $image_upload1 : '',

					'status' => $request->status ?? 0,
					'layout_id' => $l_id,
					'p_status' => $p_id,
					'cloudflare_imageid' => null,
					'cloudflare_bannerid' => null

				]);
			}


			$msg = "Business Type Updated Successfully";
		} else {

			if($request->banner_image)
			{
				
				if ($request->existing1 == "undefined" || !$request->existing1) {
					$image_upload1  = basename($request->banner_image);
				} else {
					
					if ($request->existing1 == 1) {
						$image_upload1 = $request->banner_image;
					} else {
						
						$image_upload1 = $this->custom->common_upload_images_cat_1($request, 'banner_image', public_path('category_image/'));
					}
				}
			}else{
				$image_upload1 = $request->banner_image;
			}
			if ($request->image) {

				if ($request->existing == "undefined"  || !$request->existing) {
					$image_upload  = basename($request->image);
				} else {
					if ($request->existing == 1) {
						$image_upload = $request->image;
					} else {

						$image_upload = $this->custom->common_upload_images_cat($request, 'image', public_path('category_image/'));
					}
				}


				// $image_upload = $this->custom->common_upload_images($request, 'image', public_path('category_image/'));
				$data = DB::table('business_type')->insert([
					'name' => $this->secound_lang_name($request->name),
					'secondary_name' => $this->secound_lang_name($request->secondary_name),
					'image' => $image_upload ? 'category_image/' . $image_upload : '',
					'banner_image' => $image_upload1 ? 'category_image/' . $image_upload1 : '',
                    'status' => $request->status,
					'layout_id' => $l_id,
					'p_status' => $p_id,
					'cloudflare_imageid' => null,
					'cloudflare_bannerid' => null

				]);
			} else {
				$data = DB::table('business_type')->insert([
					'name' => $this->secound_lang_name($request->name),
					'secondary_name' => $this->secound_lang_name($request->secondary_name),
					'status' => $request->status,
					'layout_id' => $l_id,
					'p_status' => $p_id,
					'cloudflare_imageid' => null,
					'cloudflare_bannerid' => null,
					'banner_image' => $image_upload1 ? 'category_image/' . $image_upload1 : '',


				]);
			}

			$msg = "Business Type Add Successfully";
		}



		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
		// return back()->with('success', trans('constants.sms_updated'));
	}
	public function business_list(Request $request)
	{

		// $data1 = DB::table('business_type')->get();
		$data1 = DB::table('business_type')
			->join('layout', 'layout.id', '=', 'business_type.layout_id')
			->where('business_type.status', 1)
			->select('business_type.*', 'layout.layout_name as layout_name')
			->get();

		foreach ($data1 as $data) {
			if ($data->image != null) {
				if (file_exists(public_path('/')  . $data->image)) {
					$data->image = BASE_URL .  $data->image;
				} else {
					$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
				}
			} else {

				$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}

			if ($data->name) {
				$data->name = $this->secondLanguage($data->name, $data->secondary_name);
			}
		}

		return response()->json($data1);
	}
	public function business_list1(Request $request)
	{

		// $data1 = DB::table('business_type')->get();
		$data1 = DB::table('business_type')
			->join('layout', 'layout.id', '=', 'business_type.layout_id')
			->select('business_type.*', 'layout.layout_name as layout_name')
			->get();
		$cloudflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
		foreach ($data1 as $data) {
			if ($cloudflare->value == 1 && $data->cloudflare_imageid == null) {

				if ($data->image != null) {
					if (file_exists(public_path('/')  . $data->image)) {
						$ban =   $data->image;
						$response = CloudflareApi::images()->upload(public_path($ban));
						$cimage = $response->result;

						$image_id = $cimage->id;

						DB::table('business_type')->where('id', $data->id)->update(['cloudflare_imageid' => $image_id]);
					}
				}
			}
		}
		$data1 = DB::table('business_type')
			->join('layout', 'layout.id', '=', 'business_type.layout_id')
			->select('business_type.*', 'layout.layout_name as layout_name')
			->get();
		$url = "";
		$acc_hash = "";
		if ($cloudflare->value == 1) {
			$get_url = DB::table('settings')->where('key_word', 'cloudflare_url')->first();
			$url = $get_url->value;

			$get_acchash = DB::table('settings')->where('key_word', 'ACCOUNT_HASH')->first();
			$acc_hash = $get_acchash->value;
		}

		foreach ($data1 as $data) {
			if ($data->image != null) {
				if ($cloudflare->value == 1 && $data->cloudflare_imageid != null) {
					$data->image = $url . '/' . $acc_hash . '/' . $data->cloudflare_imageid . '/' . "w=100";
				} else {
					if (file_exists(public_path('/')  . $data->image)) {
						$data->image = BASE_URL .  $data->image;
					} else {
						$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
					}
				}
			} else {

				$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}

			if ($data->name) {
				$data->name = $this->secondLanguage($data->name, $data->secondary_name);
			}
		}

		return response()->json($data1);
	}
	public function delete_business($id)
	{

		try {
			$delete = DB::table('business_type')->where('id', $id)->delete();
		} catch (\Exception $e) {
			$message = "Please Remove Stores ";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		$message = "Business Deleted Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function edit_business($id)
	{
		$data = DB::table('business_type')->where('id', $id)->first();

		if ($data->image != null) {
			if (file_exists(public_path('/')  . $data->image)) {
				$data->image = BASE_URL .  $data->image;
			} else {
				$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
		} else {
			$data->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
		}
		if ($data->banner_image != null) {
			if (file_exists(public_path('/')  . $data->banner_image)) {
				$data->banner_image = BASE_URL .  $data->banner_image;
			} else {
				$data->banner_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
		} else {
			$data->banner_image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
		}
		$data->name = $this->secound_name($data->name);
		$data->secondary_name = $this->secound_name($data->secondary_name);
		return response()->json($data);
	}

	public function business_status($id)
	{

		$business = DB::table('business_type')->where('id', $id)->first();
		if ($business->status == 1) {
			$status = 0;
			$msg = "Status Inactive";
		} else {
			$status = 1;
			$msg = "Status Active";
		}

		$banner_list = DB::table('business_type')->where('id', $id)
			->update(['status' => $status]);

		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);

		return $response_Array;
	}

	public function add_business_category(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'category_name' => 'required',
			'image' => 'required',
			'business_id' => 'required',
		]);
		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		$category_name = $this->secound_lang_name($request->category_name);
		$secondary_categoryname = $this->secound_lang_name($request->secondary_categoryname);

		if ($request->id != "undefined") {
			if ($request->image) {


				if ($request->existing == "undefined") {
					$image_upload  = basename($request->image);
				} else {
					if ($request->existing == 1) {
						$image_upload = $request->image;
					} else {

						$image_upload = $this->custom->common_upload_images_cat($request, 'image', public_path('category_image/'));
					}
				}



				// $image_upload = $this->custom->common_upload_images($request, 'image', public_path('category_image/'));

				$data = DB::table('business_category')->where('id', $request->id)->update([
					'category_name' => $category_name,
					'secondary_categoryname' => $secondary_categoryname ?? '',
					'image' => $image_upload ? 'category_image/' . $image_upload : '',
					'business_id' => $request->business_id,
					'status' => $request->status ?? 0,
					'cloudflare_imageid' => null

				]);
			} else {
				$data = DB::table('business_category')->where('id', $request->id)->update([
					'category_name' => $category_name,
					'secondary_categoryname' => $secondary_categoryname ?? '',
					'business_id' => $request->business_id,
					'status' => $request->status ?? 0,
					'cloudflare_imageid' => null

				]);
			}


			$msg = "Business Category Updated Successfully";
		} else {
			if ($request->image) {


				if ($request->existing == "undefined") {
					$image_upload  = basename($request->image);
				} else {
					if ($request->existing == 1) {
						$image_upload = $request->image;
					} else {

						$image_upload = $this->custom->common_upload_images_cat($request, 'image', public_path('category_image/'));
					}
				}


				// $image_upload = $this->custom->common_upload_images($request, 'image', public_path('category_image/'));

				$data = DB::table('business_category')->insert([
					'category_name' => $category_name,
					'secondary_categoryname' => $secondary_categoryname ?? '',
					'image' =>  $image_upload ? 'category_image/' . $image_upload : '',
					'business_id' => $request->business_id,
					'status' => $request->status ?? 0,
					'cloudflare_imageid' => null

				]);
			} else {
				$data = DB::table('business_category')->insert([
					'category_name' => $category_name,
					'secondary_categoryname' => $secondary_categoryname ?? '',
					'business_id' => $request->business_id,
					'status' => $request->status ?? 0,
					'cloudflare_imageid' => null

				]);
			}

			$msg = "Business Category Add Successfully";
		}



		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
		// return back()->with('success', trans('constants.sms_updated'));
	}
	public function business_category_lists(Request $request)
	{

		$data = DB::table('business_category')
			->join('business_type', 'business_type.id', '=', 'business_category.business_id')
			->select('business_category.*', 'business_type.name as business_name', 'business_type.id as business_id',)
			->get();
		foreach ($data as $d) {
			if (file_exists(public_path('/')  . $d->image)) {
				$d->image = BASE_URL .  $d->image;
			} else {
				$d->image = BASE_URL .  "def_logo.jpg";
			}
			$d->category_name = $this->secondLanguage($d->category_name, $d->secondary_categoryname);
		}
		return response()->json($data);
	}
	public function delete_business_category($id)
	{

		try {
			$delete = DB::table('business_category')->where('id', $id)->delete();
		} catch (\Exception $e) {
			$message = "Please Remove Sub Category";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		$message = "Business Category Deleted Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function edit_business_category($id)
	{
		$data = DB::table('business_category')->where('id', $id)->first();
		$data->category_name = $this->secound_name($data->category_name);
		$data->secondary_categoryname = $this->secound_name($data->secondary_categoryname);

		if (file_exists(public_path('/')  . $data->image)) {
			$data->image = BASE_URL .  $data->image;
		} else {
			$data->image = BASE_URL . "no_image.png";
		}
		return response()->json($data);
	}

	public function business_category_status($id)
	{

		$business = DB::table('business_category')->where('id', $id)->first();
		if ($business->status == 1) {
			$status = 0;
			$msg = "Status Inactive";
		} else {
			$status = 1;
			$msg = "Status Active";
		}

		$banner_list = DB::table('business_category')->where('id', $id)
			->update(['status' => $status]);

		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);

		return $response_Array;
	}
	public function layout(Request $request)
	{

		$data = DB::table('layout')->get();
		return response()->json($data);
	}
	public function layout_driver(Request $request)
	{

		$data = DB::table('layout')->where('id', '!=', 4)->get();
		return response()->json($data);
	}
	public function business_type(Request $request)
	{

		$data = DB::table('business_type')->where('id', $request->id)
			->first();
		return response()->json($data);
	}

	public function get_banner_image(Request $request)
	{

		$data = [];
		foreach (File::glob(public_path('restaurant_banner') . '/*') as $path) {
			$product_image  = basename($path);
			$data[] =  BASE_URL . 'restaurant_banner/' . $product_image;
		}

		return response()->json($data);
	}
	public function get_logo_image(Request $request)
	{

		$data = [];
		foreach (File::glob(public_path('restaurant_logo') . '/*') as $path) {
			$product_image  = basename($path);
			$data[] =  BASE_URL . 'restaurant_logo/' . $product_image;
		}

		return response()->json($data);
	}

	public function add_to_pacakge(Request $request)
	{
		if ($request->id) {
			$data = DB::table('package_type')->where('id', $request->id)->update([
				'name' => $this->secound_lang_name($request->name),
				'secondary_name' => $this->secound_lang_name($request->secondary_name),
				'status' => $request->status,
			]);
		} else {
			$data = DB::table('package_type')->insert([
				'name' => $this->secound_lang_name($request->name),
				'secondary_name' => $this->secound_lang_name($request->secondary_name),
				'status' => $request->status,
			]);
		}


		return response()->json($data);
	}

	public function edit_pacakge(Request $request)
	{

		$data = DB::table('package_type')->where('id', $request->id)->first();
		$data->name = $this->secound_name($data->name);
		$data->secondary_name = $this->secound_name($data->secondary_name);
		return response()->json($data);
	}

	public function delete_package($id)
	{


		$data = DB::table('package_type')->where('id', $id)->delete();
		$message = "Deleted Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function pacakge_list(Request $request)
	{

		$data = DB::table('package_type')->get();
		foreach ($data as $d) {
			$d->name = $this->secondLanguage($d->name, $d->secondary_name);
		}
		return response()->json($data);
	}


	public function pacakge_status($id)
	{

		$business = DB::table('package_type')->where('id', $id)->first();
		if ($business->status == 1) {
			$status = 0;
			$msg = "Status Inactive";
		} else {
			$status = 1;
			$msg = "Status Active";
		}

		$banner_list = DB::table('package_type')->where('id', $id)
			->update(['status' => $status]);

		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);

		return $response_Array;
	}



	public function add_to_carrier(Request $request)
	{
		if ($request->id) {

			if ($request->file('image')) {
				$image = $this->custom->upload_image_common($request, 'image');
				$data = DB::table('carrier_package')->where('id', $request->id)->update([
					'package_name' => $this->secound_lang_name($request->package_name),
					'secondary_package_name' => $this->secound_lang_name($request->secondary_package_name),
					'description' => $this->secound_lang_name($request->description),
					'second_description' => $this->secound_lang_name($request->second_description),
					'image' => $image,
					'status' => $request->status,
					'start_range' => $this->secound_lang_name($request->start_range),
				]);
			} else {
				$data = DB::table('carrier_package')->where('id', $request->id)->update([
					'package_name' => $this->secound_lang_name($request->package_name),
					'secondary_package_name' => $this->secound_lang_name($request->secondary_package_name),
					'description' => $this->secound_lang_name($request->description),
					'second_description' => $this->secound_lang_name($request->second_description),

					'status' => $request->status,
					'start_range' => $this->secound_lang_name($request->start_range),
				]);
			}
		} else {
			if ($request->file('image')) {
				$image = $this->custom->upload_image_common($request, 'image');
			} else {
				$image = NULL;
			}
			$data = DB::table('carrier_package')->insert([
				'package_name' => $this->secound_lang_name($request->package_name),
				'secondary_package_name' => $this->secound_lang_name($request->secondary_package_name),
				'description' => $this->secound_lang_name($request->description),
				'second_description' => $this->secound_lang_name($request->second_description),

				'image' => $image,
				'status' => $request->status,
				'start_range' => $this->secound_lang_name($request->start_range),
			]);
		}


		return response()->json($data);
	}

	public function edit_carrier(Request $request)
	{

		$data = DB::table('carrier_package')->where('id', $request->id)->first();

		if (file_exists(public_path('/') . 'uploads/' . $data->image)) {
			$data->image = BASE_URL . UPLOADS_PATH . $data->image;
		} else {
			$data->image = BASE_URL . UPLOADS_PATH . "no_image.png";
		}
		$data->package_name = $this->secound_name($data->package_name);
		$data->secondary_package_name = $this->secound_name($data->secondary_package_name);
		$data->description = $this->secound_name($data->description);
		$data->second_description = $this->secound_name($data->second_description);
		$data->start_range = $this->secound_name($data->start_range);

		return response()->json($data);
	}

	public function delete_carrier($id)
	{


		$data = DB::table('carrier_package')->where('id', $id)->delete();
		$message = "Deleted Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function carrier_list(Request $request)
	{

		$data = DB::table('carrier_package')->get();

		foreach ($data as $datas) {
			if (file_exists(public_path('/') . 'uploads/' . $datas->image)) {
				$datas->image = BASE_URL . UPLOADS_PATH . $datas->image;
			} else {
				$datas->image = BASE_URL . UPLOADS_PATH . "no_image.png";
			}

			if ($datas->package_name) {
				$datas->package_name = $this->secondLanguage($datas->package_name, $datas->secondary_package_name);
			}

			$datas->description = $this->secondLanguage($datas->description, $datas->second_description);
		}


		return response()->json($data);
	}
	public function carrier_list1(Request $request)
	{

		$data = DB::table('carrier_package')->where('status', 1)->get();
		return response()->json($data);
	}
	public function carrier_status($id)
	{

		$business = DB::table('carrier_package')->where('id', $id)->first();
		if ($business->status == 1) {
			$status = 0;
			$msg = "Status Inactive";
		} else {
			$status = 1;
			$msg = "Status Active";
		}

		$banner_list = DB::table('carrier_package')->where('id', $id)
			->update(['status' => $status]);

		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);

		return $response_Array;
	}

	public function carrier_list_1(Request $request)
	{

		$carrier1 = DB::table('restaurants')->where('id', $request->id)->first();
		$layout = DB::table('business_type')->where('id', $carrier1->business_type)->first();

		$invoice = array();
		$data = [];
		if ($carrier1->carrier_type) {
			$carrier2 = array_map('intval', json_decode($carrier1->carrier_type, true));
			$carrier = DB::table('carrier_package')->whereIn('id', $carrier2)->get();

			foreach ($carrier as $d) {

				$datas = DB::table('delivery_charge_limits')
					->where('delivery_charge_limits.restaurant_id', $request->id)
					->where('delivery_charge_limits.carrier_type', $d->id)
					->get();

				if ($layout->layout_id == 3) {
					if (count($datas) == 0) {
						$datas1 = DB::table('delivery_charge_limits')->insert([
							'Maximum' => 0,
							'Minimum' => 0,
							'Type' => 1,
							'Value' => 0,
							'carrier_type' => $d->id,
							'restaurant_id' => $request->id,
						]);

						$datas = DB::table('delivery_charge_limits')
							->where('delivery_charge_limits.restaurant_id', $request->id)
							->where('delivery_charge_limits.carrier_type', $d->id)
							->get();
						$data[] = [
							'package_name' => $d->package_name,
							'id' => $d->id,
							'invoice' =>  $datas
						];
					} else {
						$data[] = [
							'package_name' => $d->package_name,
							'id' => $d->id,
							'invoice' =>  $datas
						];
					}
				}
			}
		}




		return response()->json($data);
	}

	public function carrier_list_new(Request $request)
	{



		$invoice = array();
		$data = [];
		if ($request->carrier_type) {

			$carrier = DB::table('carrier_package')->whereIn('id', $request->carrier_type)->get();

			foreach ($carrier as $d) {

				$datas = DB::table('delivery_charge_limits')
					->where('delivery_charge_limits.restaurant_id', $request->rest_id)
					->where('delivery_charge_limits.carrier_type', $d->id)
					->get();


				if (count($datas) == 0) {
					$datas1 = DB::table('delivery_charge_limits')->insert([
						'Maximum' => 0,
						'Minimum' => 0,
						'Type' => 1,
						'Value' => 0,
						'carrier_type' => $d->id,
						'restaurant_id' => $request->rest_id,
					]);

					$datas = DB::table('delivery_charge_limits')
						->where('delivery_charge_limits.restaurant_id', $request->rest_id)
						->where('delivery_charge_limits.carrier_type', $d->id)
						->get();
					$data[] = [
						'package_name' => $d->package_name,
						'id' => $d->id,
						'invoice' =>  $datas
					];
				} else {
					$data[] = [
						'package_name' => $d->package_name,
						'id' => $d->id,
						'invoice' =>  $datas
					];
				}
			}
		}




		return response()->json($data);
	}

	public function setlocale($locale)
	{
		if ($locale) {
			session(['locale_admin' => $locale]);
		}
		$data = session()->get('locale_admin');

		$response_array = array('status' => true,);
		return response()->json($response_array, 200);
	}
	public function setlocale_lang(Request $request)
	{
		$data = session('locale_admin');
		$response_array = array('data' => $data);

		return response()->json($response_array, 200);
	}
}

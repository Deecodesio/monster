<?php

namespace App\Http\Controllers\store;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Illuminate\Validation\Rule;
use File;
use PDF;
use Excel;
use App\Exports\ExportResOrder;
use Illuminate\Support\Facades\Session;

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

		return view('store/add_category');
	}





	public function get_cuisines_list(Request $request)
	{
		$data = DB::table('cuisines')->get();

		return response()->json($data);
	}

	public function add_cuisines(Request $request)
	{

		return view('store/add_cuisines');
	}

	public function add_to_cuisines(Request $request)
	{


		$custom = $this->custom;


		$cuisine_name = $request->cuisine_name;
		$data = [];
		$data['name'] = $cuisine_name;
		if ($request->cuisine_image) {
			$cuisine_image = $custom->upload_image($request, 'cuisine_image');
			$data['cuisine_image'] = $cuisine_image;
		}
		if ($request->id) {

			$this->cuisines->where('id', $request->id)
				->update($data);
		} else {
			$this->cuisines->insert($data);
		}


		$message = "Cusisine Added Successfully";
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

		// 	return back()->with('error', $error_messages);
		// } else {
		// 	$id = $request->id;
		// 	$cuisines = $this->cuisines;

		// 	$cuisines->where('id', $id)->delete();
		// 		return redirect('/store/cuisines_list')->with('success', 'Cuisine Removed');

		// }

		$id = $request->id;
		$cuisines = $this->cuisines;

		$cuisines->where('id', $id)->delete();

		$message = "Cuisine Removed";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}


	/**
	 * Store or update add-ons data in db
	 * 
	 * 
	 * 
	 * @return add page
	 */
	public function store_addons(Request $request)
	{
		// if ($request->session()->get('res_role') == 1) {
		// 	$restaurant_id = $request->restaurant_name;
		// } else {
		// 	$restaurant_id = $request->session()->get('res_id');
		// }
		// $name = $request->name;
		// $id = $request->id;
		// if ($request->id) {
		// 	$validator = Validator::make($request->all(), [
		// 		'name' => [
		// 			'required',
		// 			Rule::unique('add_ons')->where(function ($query) use ($id, $name, $restaurant_id) {
		// 				return $query->where('restaurant_id', $restaurant_id)
		// 					->where('name', $name)
		// 					->where('id', '!=', $id);
		// 			}),
		// 		],
		// 		'price' => 'required'
		// 	]);
		// } else {
		// 	$validator = Validator::make($request->all(), [
		// 		'name' => [
		// 			'required',
		// 			Rule::unique('add_ons')->where(function ($query) use ($name, $restaurant_id) {
		// 				return $query->where('restaurant_id', $restaurant_id)
		// 					->where('name', $name);
		// 			}),
		// 		],
		// 		'price' => 'required'
		// 	]);
		// }

		// if ($validator->fails()) {
		// 	$error_messages = implode(',', $validator->messages()->all());
		// 	return back()->with('error', $error_messages);
		// } else {
		// 	if ($request->id) {
		// 		$addons = $this->add_ons->find($request->id);
		// 		$addons->restaurant_id = $restaurant_id;
		// 		$addons->name = $request->name;
		// 		$addons->secondary_name = $request->secondaryname;
		// 		$addons->price = $request->price;
		// 		$addons->save();
		// 		$trans_msg = "update_success_msg";
		// 	} else {
		// 		$this->add_ons->restaurant_id = $restaurant_id;
		// 		$this->add_ons->name = $request->name;
		// 		$this->add_ons->price = $request->price;
		// 		if (isset($request->secondaryname)) {
		// 			$this->add_ons->secondary_name = $request->secondaryname;
		// 		} else {
		// 			$this->add_ons->secondary_name = "";
		// 		}
		// 		$this->add_ons->save();
		// 		$trans_msg = "add_success_msg";
		// 	}
		// }

		// 	return redirect('/store/addons_list')->with('success', trans('constants.' . $trans_msg, ['param' => 'Add-ons']));


		$name = $this->secound_lang_name($request->name);
		$id = $request->id;

		$restaurant_id = $request->restaurant;
		$secondaryname = $this->secound_lang_name($request->secondary_name);
		if ($request->id) {
			$validator = Validator::make($request->all(), [
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
			if ($request->id && $request->id != "undefined") {
				$addons = $this->add_ons->find($request->id);
				$addons->restaurant_id = $restaurant_id;
				$addons->name = $name;
				$addons->secondary_name = $secondaryname ?? '';
				$addons->price = $request->price;
				$addons->save();
				$trans_msg = "Updated Successfully";
			} else {
				$this->add_ons->restaurant_id = $restaurant_id;
				$this->add_ons->name = $name;
				$this->add_ons->price = $request->price;
				if (isset($request->secondaryname)) {
					$this->add_ons->secondary_name = $secondaryname ?? '';
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
		$privilege = $request->session()->get('privilege');
		$permission = permission($privilege, 'addons-edit');
		if ($permission == 1 || $privilege == -1) {
			return view('store/add_addons', ['restaurant' => $restaurant]);
		} else {
			return view('store/restricted');
		}
	}


	/**
	 * List view of add_ons
	 * 
	 * @return list view page
	 */
	public function list_addons(Request $request)
	{
		// if ($request->session()->get('res_role') == 1) {
		// 	$data = $this->add_ons->with('Restaurant')->get();
		// } else {
		// 	$restaurant_id = $request->session()->get('res_id');
		// 	$data = $this->add_ons->with('Restaurant')->where('restaurant_id', $restaurant_id)->get();
		// }

		// //dd($data); 
		// $privilege = $request->session()->get('privilege');
		// $permission = permission($privilege, 'addons-view');
		// if ($permission == 1 || $privilege == -1) {
		// return view('store/addons_list', ['addons_list' => $data]);
		// }else{
		// 	return view('store/restricted');
		// }

		$data = $this->add_ons->with('Restaurant')->where('restaurant_id', $request->id)->get();
		foreach ($data as $d) {
			$d->name = $this->secondLanguage_store($d->name, $d->secondary_name);
			$d->restaurant_name = $this->secondLanguage_store($d->restaurant_name, $d->restaurant_secondary_name);
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
		// $data = $this->add_ons->with('Restaurant')->find($id);
		// $restaurant = $this->restaurants->get();
		// // print_r($data); exit;
		// $privilege = session()->get('privilege');
		// $permission = permission($privilege, 'addons-edit');
		// if ($permission == 1 || $privilege == -1) {
		// return view('store/add_addons', ['data' => $data, 'restaurant' => $restaurant]);
		// }else{
		//     return view('store/restricted');
		// }
		// dd($id);
		$data = $this->add_ons->with('Restaurant')->find($id);

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

		return view('store/food_quantity_list', ['food_quantity_list' => $data]);
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

		return view('store/add_food_quantity', ['data' => $data]);
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

		return view('store/add_food_quantity');
	}


	/**
	 * Store or update add-ons data in db
	 * 
	 * @param array $request
	 * 
	 * @return view page
	 */
	public function store_food_quantity(Request $request)
	{
		if ($request->id) {
			$validator = Validator::make($request->all(), [
				'name' => 'required|unique:food_quantity,name,' . $request->id,
			]);
		} else {
			$validator = Validator::make($request->all(), [
				'name' => 'required|unique:food_quantity,name',
			]);
		}

		if ($validator->fails()) {
			$error_messages = implode(',', $validator->messages()->all());
			return back()->with('error', $error_messages);
		} else {
			if ($request->id) {
				$food_quantity = $this->food_quantity->find($request->id);
				$food_quantity->name = $request->name;
				$food_quantity->secondary_name = (isset($request->secondary_name)) ? $request->secondary_name : "";
				$food_quantity->save();
				$trans_msg = "Update Success Message";
			} else {
				$this->food_quantity->name = $request->name;
				$this->food_quantity->secondary_name = (isset($request->secondary_name)) ? $request->secondary_name : "";
				$this->food_quantity->save();
				$trans_msg = "Add Success Message";
			}
		}

		return redirect('/store/food-quantity-list')->with('success', trans('constants.' . $trans_msg, ['param' => 'Food Quantity']));
	}

	public function edit_cuisines($id, Request $request)
	{
		$data = DB::table('cuisines')->where('id', $id)->get();
		$privilege = session()->get('privilege');
		$permission = permission($privilege, 'cuisines-view');
		if ($permission == 1 || $privilege == -1) {
			return view('admin/cuisines_list', ['data' => $data]);
		} else {
			return view('admin/restricted');
		}
	}
	public function business_type(Request $request)
	{

		$data1 = DB::table('restaurants')->where('id', $request->id)
			->first();

		$data = DB::table('business_type')->where('id', $data1->business_type)
			->first();

		return response()->json($data);
	}

	public function business_type_rest(Request $request)
	{

		$data = DB::table('business_type')->where('id', $request->id)
			->first();
		return response()->json($data);
	}
	public function business_category_list(Request $request)
	{

		$data1 = DB::table('restaurants')->where('id', $request->id)
			->first();

		$data = DB::table('business_category')
			->join('business_type', 'business_type.id', '=', 'business_category.business_id')
			->where('business_category.business_id', $data1->business_type)
			->select('business_category.*', 'business_type.name as business_name', 'business_type.id as business_id',)
			->get();
		foreach ($data as $d) {
			if (file_exists(public_path('/')  . $d->image)) {
				$d->image = BASE_URL .  $d->image;
			} else {
				$d->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
		}

		return response()->json($data);
	}

	public function product_imgs(Request $request)
	{
		$restaurant_detail = DB::table('food_list')->where('restaurant_id', $request->id)->get();

		$data = [];
		foreach (File::glob(public_path('common_images') . '/*') as $path) {
			$product_image  = basename($path);
			$data[] =  BASE_URL . 'common_images/' . $product_image;
		}
		foreach ($restaurant_detail as $res) {
			if ($res->image != '') {
				// $data[] =  BASE_URL .'restaurant_uploads/'. $res->image;
				$data[] = File::exists(public_path('product_image/') . $res->image) ? BASE_URL  . UPLOADS_PATH_PRODUCT . $res->image : (File::exists(public_path('restaurant_uploads/') . $res->restaurant_id . '/' . 'Product/' . $res->image) ? BASE_URL  . RESTAURANT_UPLOADS_PATH . $res->restaurant_id . '/' . 'Product/' . $res->image : (File::exists(public_path('common_images/') . $res->image) ? BASE_URL  .  UPLOADS_PATH_COMMON . $res->image : BASE_URL  . $res->image));
			}
		}
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

	public function business_list(Request $request)
	{

		// $data1 = DB::table('business_type')->get();
		$data1 = DB::table('business_type')
			->join('layout', 'layout.id', '=', 'business_type.layout_id')
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
		}

		return response()->json($data1);
	}

	public function get_category_list(Request $request)
	{
		$rest =  DB::table('restaurants')->where('id', $request->id)->first();
		$data = DB::table('category')
			->where('category.restaurant_id', $request->id)
			->orwhere('category.restaurant_id', null)
			->join('business_category', 'business_category.id', '=', 'category.business_category_id')
			->where('business_category.business_id', $rest->business_type)
			->select('category.*', 'business_category.category_name as business_name', 'business_category.secondary_categoryname as secondary_categoryname', 'business_category.id as business_id',)
			->orderBy('rank', 'asc')->get();



		foreach ($data as $key => $val) {

			if ($val->image != "") {
				if (file_exists(public_path('/')  . $val->image)) {
					$data[$key]->image =  BASE_URL  . $val->image;
				} else {
					$data[$key]->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
				}
			} else {
				$data[$key]->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
			$val->category_name = $this->secondLanguage_store($val->category_name, $val->category_secondaryname);
			$val->business_name = $this->secondLanguage_store($val->business_name, $val->secondary_categoryname);
		}

		// print_r($data); exit;
		return response()->json($data);
	}

	public function add_to_category(Request $request)
	{
		// dd($request);

		$validator = Validator::make($request->all(), [
			'category_name' => 'required',
			'status' => 'required',
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
			$status = $request->status;
			$category_list = $this->category;

			$category_data  = [
				'category_name' => $category_name,
				'category_secondaryname' => $category_secondaryname,
				'status' => $status,
				'business_category_id' => $request->business_category_id,
				'restaurant_id' => $request->restaurant_id
			];

			$image_upload = $this->custom->common_upload_images($request, 'category_image', public_path('category_image/'));


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
					'business_category_id' => $request->business_category_id,
					'restaurant_id' => $request->restaurant_id

				];

				$category_list->insert($data);
			}
		}
		$message = "Category added Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}
	public function business_category_lists(Request $request)
	{
		$rest =  DB::table('restaurants')->where('id', $request->id)->first();

		// $business_type =  DB::table('business_type')->where('id',$rest->business_type)->first();
		$data = DB::table('business_category')
			->join('business_type', 'business_type.id', '=', 'business_category.business_id')
			->where('business_category.business_id', $rest->business_type)
			->select('business_category.*', 'business_type.name as business_name', 'business_type.id as business_id',)
			->get();

		foreach ($data as $d) {
			if (file_exists(public_path('/')  . $d->image)) {
				$d->image = BASE_URL .  $d->image;
			} else {
				$d->image = BASE_URL . RESTAURANT_UPLOADS_PATH . "no_image.png";
			}
		}
		return response()->json($data);
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
	public function delete_category($id)
	{

		// $data = $this->restaurants->with(['city_list', 'Area'])->find($id);
		$data = DB::table('food_list')->where('category_id', $id)->get()->count();

		if ($data == 0) {
			$data = DB::table('category')->where('id', $id)->delete();
			$message = "Deleted Successfully";
			$status = true;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		} else {
			$message = "Please Remove Product";
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}

		// return response()->json($data) ;
		// return view('admin/restaurant_view', ['restaurant' => $restaurant_view]);
	}

	public function category_status($id)
	{

		$area_list = DB::table('category')->where('id', $id)->first();
		if ($area_list->status == 1) {
			$status = 0;
			$msg = "Status Inactive";
		} else {
			$status = 1;
			$msg = "Status Active";
		}

		$banner_list = DB::table('category')->where('id', $id)
			->update(['status' => $status]);

		$message = $msg;
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);


		return $response_Array;
	}

	public function category_sort_save(Request $request)
	{

		$categoryRanks = $request->categoryRanks;

		// $category_ranks = explode(',', $categoryRanks);

		foreach ($categoryRanks as $rank => $id) {
			DB::table('category')->where('id', $id)->update(['rank' => $rank]);
		}

		// $categories = get_category_by_rank();
		$categories = DB::table('category')->orderBy('rank', 'asc')->get();
		$message = "Updated Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'data' => $categories]);
		return $response_Array;
		// return view('admin/category_sort')->with('categories', $categories);
	}
	public function store_size(Request $request)
	{

		$name = $request->name;
		$res = $request->res_id;

		$store = DB::table('food_quantity')->insert([
			'name' => $name,
			'restaurant_id' => $res
		]);

		$message = "Size added";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}
	public function get_addons_groups(Request $request)
	{

		$data = DB::table('addon_group')->where('restaurant_id', $request->id)->get();
		$add_group = [];


		foreach ($data as $key => $group) {
			$array_group = json_decode($group->add_ons);
			foreach ($array_group as $ag) {
				$addon_detail = DB::table('add_ons')->where('id', $ag)->first();

				$gadd_ons[] = array(
					'id' => $ag,
					'name' => $addon_detail->name,
					'secondary_name' => $addon_detail->secondary_name,
					'price' => $addon_detail->price

				);
			}
			$data[$key]->group_addons = $gadd_ons;
			$gadd_ons = array();
		}

		return response()->json($data);
	}

	public function store_agroups(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'add_ons' => 'required'
		]);
		if ($request->name == "undefined") {
			$error_messages = "Group Name is required";
			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}
		if ($request->mandatory == 1) {
			if ($request->min == "undefined" || $request->max == "undefined") {
				$error_messages = "Minimum and Maximum is required";
				$message = $error_messages;
				$status = false;
				$response_Array = json_encode(['message' => $message, 'status' => $status]);
				return $response_Array;
			}
		}
		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;
		}

		$min = $request->min;
		$max = $request->max;
		$restaurant = $request->res_id;
		$name = $request->name;
		$secondaryname = $request->secondary_name ? $request->secondary_name : '';
		$multiple = $request->multiple;
		$mandatory = $request->mandatory;

		$array_addon = explode(',', $request->add_ons);
		$addon = $array_addon;
		if ($request->id) {
			$update = DB::table('addon_group')->where('id', $request->id)->update([
				'name' => $name,
				'secondary_name' => $secondaryname,
				'min' => $min,
				'max' => $max,
				'restaurant_id' => $restaurant,
				'add_ons' => json_encode($addon),
				'allow_multiple' => $multiple,
				'mandatory' => $mandatory
			]);
			$message = "Group Edited Successfully";
		} else {
			$check_name = DB::table('addon_group')->where('restaurant_id', $restaurant)->where('name', $name)->first();
			// if($check_name){
			// 	$message = "Group Name Already Exists";
			// 	$status = false;
			// 	$response_Array = json_encode(['message'=>$message,'status'=>$status]);
			// 	return $response_Array;
			// }
			$insert = DB::table('addon_group')->insert([
				'name' => $name,
				'secondary_name' => $secondaryname,
				'min' => $min,
				'max' => $max,
				'restaurant_id' => $restaurant,
				'add_ons' => json_encode($addon),
				'allow_multiple' => $multiple,
				'mandatory' => $mandatory
			]);
			$message = "Group Created Successfully";
		}


		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
	}

	public function edit_addon_group(Request $request)
	{
		$id = $request->id;

		$group = DB::table('addon_group')->where('id', $id)->first();
		$array_group = json_decode($group->add_ons);
		foreach ($array_group as $ar_g) {
			$addon_detail = DB::table('add_ons')->where('id', $ar_g)->first();

			$gadd_ons[] = array(
				'id' => $ar_g,
				'name' => $addon_detail->name,
				'secondary_name' => $addon_detail->secondary_name,
				'price' => $addon_detail->price

			);
		}
		$group->grouped = $gadd_ons;

		$status = true;
		$response_Array = json_encode(['status' => $status, 'group' => $group]);
		return $response_Array;
	}
	public function setlocale($locale)
	{
		if ($locale) {
			session(['locale_store' => $locale]);
		}
		$data = session()->get('locale_store');
		\Session::save();

		$response_array = array('status' => true,);
		return response()->json($response_array, 200);
	}
	public function setlocale_lang(Request $request)
	{
		$data = session('locale_store');
		$response_array = array('data' => $data);

		return response()->json($response_array, 200);
	}
	public function add_menu(Request $request)
	{

		$restaurant_id = $request->res_id;
		$menu_name = $this->secound_lang_name($request->menu_name);
		$secondaryname = $this->secound_lang_name($request->secondary_menu_name);


		$restaurant_menu = $this->menu;

		$data_primary = array();
		$data_secondary = array();

		$data_primary[] = array(
			'restaurant_id' => $restaurant_id,
			'secondary_menu_name' =>  $secondaryname,
			'menu_name' => $menu_name,
		);


		$check = $restaurant_menu->where('restaurant_id', $restaurant_id)->where('menu_name', $menu_name)->count();
		if ($check == 0) {
			$restaurant_menu->insert($data_primary);
		} else {
			$message = "Menu  Already Exist";
			$status = true;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);
			return $response_Array;

			// return redirect('/store/restaurant_menu')->with('error', trans('constants.menu_already_exist'));
		}


		$message = "Menu  Add Successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status]);
		return $response_Array;
		// return redirect('/store/restaurant_menu')->with('success', trans('constants.menu_added_successfully'));
	}
	public function Date_Filter(Request $request)
	{

		$restaurant_id = $request->id;

		if ($request->to_date) {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} else {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		}


		return response()->json($restaurant_details);
	}

	public function order_csv(Request $request)
	{
		$restaurant_id = $request->id;

		if ($request->from_date == null && $request->to_date == null) {
			$restaurant_details = DB::table('requests')
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} elseif ($request->to_date != null) {

			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} else {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		}
		// dd($restaurant_details);
		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$productList = [];

		$array_keys_template = [
			"Order Id", "Order Time", "Customer Name",
			"Phone Number", "DeliveryBoy Name", "DeliveryBoy Phone", "Restaurant Name",
			"Item Totals", "Tax", "Offer Discount", "Admin Commision", "DeliveryBoy Commision",
			"Restaurant Commision", "Status"
		];
		$productList[] = $array_keys_template;


		foreach ($restaurant_details as $eachProduct) {
			if ($eachProduct->order_status == "0") {
				$status = "New Order";
			}
			if ($eachProduct->order_status == "1") {
				$status = "Order Accepted";
			}
			if ($eachProduct->order_status == "2") {
				$status = "Driver Assigned";
			}
			if ($eachProduct->order_status == "3") {
				$status = "Delivered to Driver";
			}
			if ($eachProduct->order_status == "4") {
				$status = "Towards Customer";
			}
			if ($eachProduct->order_status == "5") {
				$status = "Reached Customer";
			}
			if ($eachProduct->order_status == "6") {
				$status = "Delivered To Customer";
			}
			if ($eachProduct->order_status == "7") {
				$status = "Completed";
			}
			if ($eachProduct->order_status >= "9") {
				$status = "Cancelled";
			}

			if ($eachProduct->order_status == "-1") {
				$status = "Failed";
			}
			if ($eachProduct->order_status == "-2") {
				$status = "Failed";
			}
			$tax = $data->value . ' ' . $eachProduct->tax;
			$offer_discount = $data->value . ' ' . $eachProduct->offer_discount;
			$admin_commision = $data->value . ' ' . $eachProduct->admin_commision;
			$delivery_boy_commision = $data->value . ' ' . $eachProduct->delivery_boy_commision;
			$restaurant_commision = $data->value . ' ' . $eachProduct->restaurant_commision;
			$item_total = $data->value . ' ' . $eachProduct->item_total;
			$thisNewProduct = [
				$eachProduct->order_id,
				$eachProduct->ordered_time,
				$eachProduct->customer_name,
				$eachProduct->phones,
				$eachProduct->delivery_boy_name,
				$eachProduct->delivery_boy_phone,
				$eachProduct->restaurant_name,

				$eachProduct->item_total,
				$eachProduct->tax,
				$eachProduct->offer_discount,
				$eachProduct->admin_commision,
				$eachProduct->delivery_boy_commision,
				$eachProduct->restaurant_commision,
				$status,


			];

			$productList[] = $thisNewProduct;
		}
		$filePath = (public_path() . '/reports/');
		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}
		$fileName = $filePath . '' . $date . '_order_report.csv';
		$fp = fopen($fileName, 'w+');
		foreach ($productList as $eachRow) {
			fputcsv($fp, $eachRow);
		}
		fclose($fp);
		$path =  BASE_URL . 'reports/' . $date . '_order_report.csv';
		$message = "Download CSV successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function order_pdf(Request $request)
	{
		$restaurant_id = $request->id;

		if ($request->from_date == null && $request->to_date == null) {
			$restaurant_details = DB::table('requests')
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} elseif ($request->to_date != null) {

			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.created_at', '<=', $request->to_date)
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		} else {
			$restaurant_details = DB::table('requests')
				->where('requests.created_at', '>=', $request->from_date)
				->where('requests.restaurant_id', $restaurant_id)
				->leftjoin('restaurants', 'restaurants.id', '=', 'requests.restaurant_id')
				->leftjoin('users', 'users.id', '=', 'requests.user_id')
				->leftjoin('delivery_partners', 'delivery_partners.id', '=', 'requests.delivery_boy_id')
				->select('users.name as customer_name', 'users.phone as phones', 'requests.status as order_status', 'delivery_partners.name as delivery_boy_name', 'delivery_partners.phone as delivery_boy_phone', 'requests.id as request_id', 'users.*', 'requests.*', 'delivery_partners.*', 'restaurants.restaurant_name as restaurant_name', 'restaurants.admin_commision as res_admin_commision')
				->get();
		}
		$data = DB::table('country')->where('is_default', 1)->first();
		$data->value =  $data->currency_symbol;
		$date = now()->timestamp;

		$res_fields = [];
		foreach ($restaurant_details as $eachProduct) {
			if ($eachProduct->order_status == "0") {
				$status = "New Order";
			}
			if ($eachProduct->order_status == "1") {
				$status = "Order Accepted";
			}
			if ($eachProduct->order_status == "2") {
				$status = "Driver Assigned";
			}
			if ($eachProduct->order_status == "3") {
				$status = "Delivered to Driver";
			}
			if ($eachProduct->order_status == "4") {
				$status = "Towards Customer";
			}
			if ($eachProduct->order_status == "5") {
				$status = "Reached Customer";
			}
			if ($eachProduct->order_status == "6") {
				$status = "Delivered To Customer";
			}
			if ($eachProduct->order_status == "7") {
				$status = "Completed";
			}
			if ($eachProduct->order_status >= "9") {
				$status = "Cancelled";
			}

			if ($eachProduct->order_status == "-1") {
				$status = "Failed";
			}
			if ($eachProduct->order_status == "-2") {
				$status = "Failed";
			}
			$tax = $eachProduct->tax;
			$offer_discount = $eachProduct->offer_discount;
			$admin_commision = $eachProduct->admin_commision;
			$delivery_boy_commision = $eachProduct->delivery_boy_commision;
			$restaurant_commision = $eachProduct->restaurant_commision;
			$item_total = $eachProduct->item_total;


			$res_fields[] = [
				'order_id' => $eachProduct->order_id,
				'ordered_time' => $eachProduct->ordered_time,
				'customer_name' => $eachProduct->customer_name,
				'phones' => $eachProduct->phones,
				'driver_name' => $eachProduct->delivery_boy_name,
				'driver_phone' => $eachProduct->delivery_boy_phone,
				'restaurant_name' => $eachProduct->restaurant_name,
				'item_total' => $item_total,
				'tax' => $tax,

				'offer_discount' => $offer_discount,
				'admin_commision' => $admin_commision,
				'delivery_boy_commision' => $delivery_boy_commision,
				'restaurant_commision' => $restaurant_commision,
				'status' => $status,

			];
		}
		$pdf = PDF::loadView('pdf.order_pdf', compact('res_fields'));
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_order_report.pdf';
		$pdf->save($filePath . '/' . $fileName);
		$path =  BASE_URL . 'reports/' . $date . '_order_report.pdf';
		$message = "Download PDF successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}

	public function order_excel(Request $request)
	{
		$from = $request->from_date;
		$to = $request->to_date;
		$restaurant_id = $request->id;
		$date = now()->timestamp;
		$filePath = (public_path() . '/' . 'reports/');
		$fileName = $date . '_order_report.xlsx';
		Excel::store(new ExportResOrder($restaurant_id, $from, $to), $date . '_order_report.xlsx');
		$path =  BASE_URL . 'reports/' . $date . '_order_report.xlsx';
		$message = "Download Excel successfully";
		$status = true;
		$response_Array = json_encode(['message' => $message, 'status' => $status, 'path' => $path]);
		return $response_Array;
	}
}

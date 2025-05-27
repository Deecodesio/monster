<?php

namespace App\Http\Controllers\web;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;
use DB;
use Illuminate\Validation\Rule;


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

		$data = DB::table('category')->get();

		// print_r($data); exit;

		return response()->json($data);
	}

	public function add_to_category(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'category_name' => 'required',
			'status' => 'required'
		]);

		if ($validator->fails()) {

			$error_messages = implode(',', $validator->messages()->all());

			$message = $error_messages;
			$status = false;
			$response_Array = json_encode(['message' => $message, 'status' => $status]);


			return $response_Array;
		} else {
			$category_name = $request->category_name;
			if (isset($request->category_secondaryname)) {
				$category_secondaryname = $request->category_secondaryname;
			} else {
				$category_secondaryname = "";
			}
			$status = $request->status;
			$category_list = $this->category;

			$category_data  = [
				'category_name' => $category_name,
				'category_secondaryname' => $category_secondaryname,
				'status' => $status
			];

			$image_upload = $this->custom->common_upload_images($request, 'image', public_path('category_image/'));
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
					'status' => $status
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

		return response()->json($data);
	}
	public function business_type_list(Request $request)
	{
		$data = DB::table('business_type')
			->where('status', 1)
			->get();
		return response()->json($data);
	}
	public function business_types(Request $request)
	{
		$data = DB::table('business_type')->where('id', $request->id)
			->first();
		return response()->json($data);
	}
	public function add_cuisines(Request $request)
	{

		return view('admin/add_cuisines');
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
	public function edit_cuisines(Request $request)
	{
		$cuisines = $this->cuisines->where('id', $request->id)->first();
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


	/**
	 * Store or update add-ons data in db
	 * 
	 * @param array $request
	 * 
	 * @return view page
	 */
	public function store_addons(Request $request)
	{

		$name = $request->name;
		$id = $request->id;
		$restaurant_id = $request->restaurant_id;
		$secondaryname = "";
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
			if ($request->id) {
				$addons = $this->add_ons->find($request->id);
				$addons->restaurant_id = $restaurant_id;
				$addons->name = $request->name;
				$addons->secondary_name = "";
				$addons->price = $request->price;
				$addons->save();
				$trans_msg = "update_success_msg";
			} else {
				$this->add_ons->restaurant_id = $restaurant_id;
				$this->add_ons->name = $request->name;
				$this->add_ons->price = $request->price;
				if (isset($request->secondaryname)) {
					$this->add_ons->secondary_name = $request->secondaryname;
				} else {
					$this->add_ons->secondary_name = "";
				}
				$this->add_ons->save();
				$trans_msg = "add_success_msg";
			}
		}

		$message = "Added";
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
		// if ($request->session()->get('role') == 1) {
		// 	$data = $this->add_ons->with('Restaurant')->get();
		// } else {
		// 	$restaurant_id = $request->session()->get('userid');
		// 	$data = $this->add_ons->with('Restaurant')->where('restaurant_id', $restaurant_id)->get();
		// }
		$data = $this->add_ons->leftjoin('restaurants', 'restaurants.id', '=', 'add_ons.restaurant_id')->get();

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
			$food_quantity = $this->food_quantity->find($request->id);
			$food_quantity->name = $request->name;
			$food_quantity->secondary_name = (isset($request->secondary_name)) ? $request->secondary_name : "";
			$food_quantity->save();
			$trans_msg = "update_success_msg";
		} else {
			$this->food_quantity->name = $request->name;
			$this->food_quantity->secondary_name = (isset($request->secondary_name)) ? $request->secondary_name : "";
			$this->food_quantity->save();
			$trans_msg = "add_success_msg";
		}

		$message = "Food Quantity Added Successfully";
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
}

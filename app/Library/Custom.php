<?php

namespace App\Library;

use Illuminate\Http\Request;
use DB;
use File;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;

class Custom
{


    /**
     * upload image and move to folder
     *
     * @param array $request, string $param
     *
     * @return string filename with path
     */

    public static function upload_image_common($request, $param)
    {

        if (!$request->file($param)) {
            return null;
        }

        $extension = $request->file($param)->getClientOriginalExtension();
        $mime = $request->file($param)->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }

        $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
        $imageName =  str_replace(' ', '', $imageName);
        $request->file($param)->move('uploads/', $imageName);
        return $imageName;
    }

    public static function upload_pickupdrop($request, $param)
    {



        $image1 = $request->$param; // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image1);
        $image2 = str_replace(' ', '+', $image);
        $image_parts = explode(";base64,", $image1);
        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];
        //  dd($image_type);
        if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
            $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

            $imageName = $filename . self::random_string(4) . '.' . $image_type;

            \File::put(public_path() . '/' . 'uploads/' . $imageName, base64_decode($image2));
            $imageName =  str_replace(' ', '', $imageName);

            $output_file = public_path('uploads/' . $imageName);
            // file_put_contents($output_file, file_get_contents($request->$param));

            $extension = pathinfo(public_path('uploads/' . $imageName), PATHINFO_EXTENSION);

            $input['imagename'] = $imageName;

            $destinationPath = public_path('/uploads');
            // $img = Image::make($output_file);
            // $img->resize(1920, 500, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' . $input['imagename']);
        }


        return $imageName;
    }

    public static function upload_image_ques($request, $param)
    {
        if ($request['existing'] == 2) {
            $image1 = $request[$param]; // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {

                $imageName = self::random_string(4) . '.' . $image_type;
                $imageName =  str_replace(' ', '', $imageName);
                \File::put(public_path() . '/' . 'uploads/' . $imageName, base64_decode($image2));


                $output_file = public_path('uploads/' . $imageName);

                $extension = pathinfo(public_path('uploads/' . $imageName), PATHINFO_EXTENSION);

                $input['imagename'] = $imageName;

                $destinationPath = public_path('/uploads');
            }
        } else {

            if ($request[file($param)]) {
                $extension = $request[file($param)]->getClientOriginalExtension();
                $mime = $request[file($param)]->getMimeType();

                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                    return null;
                }

                $imageName = self::random_string(32) . '.' . $request[file($param)]->getClientOriginalExtension();
                $imageName =  str_replace(' ', '', $imageName);
                $request[file($param)]->move('uploads/', $imageName);
                return $imageName;
            }
        }

        return $imageName;
    }
    public static function upload_image($request, $param)
    {


        if ($request->existing == 2) {
            $image1 = $request->$param; // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];
            //  dd($image_type);
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

                $imageName = $filename . self::random_string(4) . '.' . $image_type;

                \File::put(public_path() . '/' . 'uploads/' . $imageName, base64_decode($image2));

                $imageName =  str_replace(' ', '', $imageName);
                $output_file = public_path('uploads/' . $imageName);
                // file_put_contents($output_file, file_get_contents($request->$param));

                $extension = pathinfo(public_path('uploads/' . $imageName), PATHINFO_EXTENSION);

                $input['imagename'] = $imageName;

                $destinationPath = public_path('/uploads');
                // $img = Image::make($output_file);
                // $img->resize(1920, 500, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($destinationPath . '/' . $input['imagename']);
            }
        } else {

            if ($request->file($param)) {
                $extension = $request->file($param)->getClientOriginalExtension();
                $mime = $request->file($param)->getMimeType();

                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                    return null;
                }

                $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
                $imageName =  str_replace(' ', '', $imageName);
                $request->file($param)->move('uploads/', $imageName);
                return $imageName;
            }
        }

        return $imageName;
    }
    public static function move_uploaded_file($file)
    {
        $extension = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }

        $imageName = self::random_string(32) . '.' . $file->getClientOriginalExtension();
        $imageName =  str_replace(' ', '', $imageName);
        $v =  $file->move('uploads/', $imageName);
        //dd('addd', $v);
        return $imageName;
    }

    /**
     * upload image and move to folder
     *
     * @param array $request, string $param
     *
     * @return string filename
     */
    public static function restaurant_upload_image($request, $param)
    {
        // dd($request->file($param));
        $extension = $request->file($param)->getClientOriginalExtension();
        $mime = $request->file($param)->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }

        $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
        $imageName =  str_replace(' ', '', $imageName);
        $request->file($param)->move('restaurant_uploads/', $imageName);
        return $imageName;
    }

    public static function driver_image_upload($request, $param)
    {
        if ($request->file($param)) {
            $extension = $request->file($param)->getClientOriginalExtension();
            $mime = $request->file($param)->getMimeType();

            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            // if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            //     return null;
            // }
            $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
            $imageName =  str_replace(' ', '', $imageName);
            $request->file($param)->move('uploads/Profile/', $imageName);
            return 'uploads/Profile/' . $imageName;
        }
    }

    public static function delete_image($image_path)
    {
        \File::delete($image_path);
        return true;
    }

    public function sendEmail($subject, $html)
    {

        $url = 'https://api.elasticemail.com/v2/email/send';


        try {
            $post = array(
                'from' => 'mailer@mrcity.in',
                'fromName' => 'SPARKOUT',
                'apikey' => '38868678-37e6-4a31-aacf-7e8a95e5b9de',
                'subject' => $subject,
                'to' => 'tickets@mrcity.in:leads@sparkouttech.com',
                'bodyHtml' => $html,
                'bodyText' => 'Text Body',
                'isTransactional' => false
            );

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_SSL_VERIFYPEER => false
            ));

            $result = curl_exec($ch);
            curl_close($ch);

            //echo $html;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public static function random_string($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * upload image and move to folder
     *
     * @param array $request, string $param
     *
     * @return string filename with path
     */
    public static function common_upload_images($request, $param, $path)
    {
        if ($request->file($param)) {

            $extension = $request->file($param)->getClientOriginalExtension();
            $mime = $request->file($param)->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                return null;
            }

            $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
            $imageName =  str_replace(' ', '', $imageName);
            $request->file($param)->move($path, $imageName);

            return $imageName;
        }
    }

    public static function common_upload_images_cat($request, $param, $path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if ($request->existing == 2) {
            $image1 = $request->$param; // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];
            //  dd($image_type);
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

                $imageName = $filename . self::random_string(4) . '.' . $image_type;

                \File::put($path . '/' . $imageName, base64_decode($image2));


                $output_file = $path . '/' . $imageName;





                // file_put_contents($output_file, file_get_contents($request->$param));

                $extension = pathinfo($path . '/' . $imageName, PATHINFO_EXTENSION);

                $input['imagename'] = $imageName;

                $destinationPath = public_path('/uploads');
            }
        } else {

            if ($request->file($param)) {

                $extension = $request->file($param)->getClientOriginalExtension();
                $mime = $request->file($param)->getMimeType();
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                    return null;
                }

                $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
                $request->file($param)->move($path, $imageName);
            }
        }

        return $imageName;
    }
    public static function common_upload_images_cat_1($request, $param, $path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if ($request->existing1 == 2) {
            $image1 = $request->$param; // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];
            //  dd($image_type);
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

                $imageName = $filename . self::random_string(4) . '.' . $image_type;

                \File::put($path . '/' . $imageName, base64_decode($image2));


                $output_file = $path . '/' . $imageName;





                // file_put_contents($output_file, file_get_contents($request->$param));

                $extension = pathinfo($path . '/' . $imageName, PATHINFO_EXTENSION);

                $input['imagename'] = $imageName;

                $destinationPath = public_path('/uploads');
            }
        } else {

            if ($request->file($param)) {

                $extension = $request->file($param)->getClientOriginalExtension();
                $mime = $request->file($param)->getMimeType();
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                    return null;
                }

                $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
                $request->file($param)->move($path, $imageName);
            }
        }

        return $imageName;
    }


    public static function common_upload_images_new($request, $param, $id)
    {

        $path = public_path('restaurant_uploads/' . $id . '/Product');


        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        // dd( $path );
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        //dd($request->ex_img);
        if ($request->ex_img == "1") {
            $imageName = $request->$param;
        } else if ($request->ex_img == "2") {

            if (!$request->file('Banner_name1')) {
                return null;
            }

            $extension = $request->file('Banner_name1')->getClientOriginalExtension();
            $mime = $request->file('Banner_name1')->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                return null;
            }
            $filename1 = $request->file('Banner_name1')->getClientOriginalName();
            $filename = pathinfo($filename1, PATHINFO_FILENAME);

            $imageNames = $filename . self::random_string(4) . '.' . $request->file('Banner_name1')->getClientOriginalExtension();
            $imageName = str_replace(" ", "", $imageNames);
            $request->file('Banner_name1')->move($path, $imageName);
        } else {
            $image1 = $request->$param; // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            //  dd($image_type);
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

                $imageNames = $filename . self::random_string(4) . '.' . $image_type;
                $imageName = str_replace(" ", "", $imageNames);
                \File::put($path . '/' . $imageName, base64_decode($image2));


                $output_file = $path . '/' . $imageName;
                // file_put_contents($output_file, file_get_contents($request->$param));



                $input['imagename'] = $imageName;

                $destinationPath = $path;
                // $img = Image::make($output_file);
                // $img->resize(1920, 500, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($destinationPath . '/' . $input['imagename']);
            }
        }


        // $imageName = self::random_string(32) . '.' . $request->file($param)->getClientOriginalExtension();
        // $request->file($param)->move($path, $imageName); //dd($imageName);
        return $imageName;
    }

    public function base_image_upload_license($req)
    {
        $extension = $req->getClientOriginalExtension();
        $mime = $req->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        // if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
        //     return null;
        // }

        $imageName = $req->getClientOriginalName(); //dd("df");
        $ext = $req->getClientOriginalExtension();
        $imageName = self::random_string() . '.' . $ext;
        $imageName =  str_replace(' ', '', $imageName);
        $req->move(public_path('uploads/License/'), $imageName);
        return $image_url = "uploads/License/" . $imageName;
    }


    public static function restaurant_upload_image_banner($request, $param, $id)
    {
        $path = public_path('restaurant_uploads/' . $id . '/Banner');


        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if ($request->existing == 3) {
            $extension = $request->file('banner')->getClientOriginalExtension();
            $mime = $request->file('banner')->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                return null;
            }
            $filename1 = $request->file('banner')->getClientOriginalName();
            $filename = pathinfo($filename1, PATHINFO_FILENAME);

            $imageName = $filename . self::random_string(4) . '.' . $request->file('banner')->getClientOriginalExtension();
            $request->file('banner')->move($path, $imageName);
        } else {
            $image1 = $request->$param; // your base64 encoded

            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            //  dd($image_type);
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

                $imageName = $filename . self::random_string(4) . '.' . $image_type;
                $imageName =  str_replace(' ', '', $imageName);
                \File::put($path . '/' . $imageName, base64_decode($image2));



                $output_file = $path . '/' . $imageName;
                // file_put_contents($output_file, file_get_contents($request->$param));



                $input['imagename'] = $imageName;

                $destinationPath =  $path;
                // $img = Image::make($output_file);
                // $img->resize(1920, 500, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($destinationPath . '/' . $input['imagename']);
            }
        }


        return $imageName;
    }

    public static function restaurant_upload_image_logo($request, $param, $id)
    {

        $path = public_path('restaurant_uploads/' . $id . '/Logo');

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if ($request->existing1 == 3) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $mime = $request->file('image')->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                return null;
            }
            $filename1 = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filename1, PATHINFO_FILENAME);

            $imageName = $filename . self::random_string(4) . '.' . $request->file('image')->getClientOriginalExtension();
            $imageName =  str_replace(' ', '', $imageName);
            $request->file('image')->move($path, $imageName);
        } else {
            $image1 = $request->$param; // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);

            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];


            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->$param, PATHINFO_FILENAME);

                $imageName = $filename . self::random_string(6) . '.' . $image_type;

                \File::put($path . '/' . $imageName, base64_decode($image2));

                $imageName =  str_replace(' ', '', $imageName);
                $output_file = $path . '/' . $imageName;
                $input['imagename'] = $imageName;

                $destinationPath = $path;
                // $img = Image::make($output_file);
                // $img->resize(384, 284, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($destinationPath . '/' . $input['imagename']);
            }
        }

        return $imageName;
    }

    // public static function common_upload_images_product($request, $param, $id)
    public static function common_upload_images_product($request, $param)
    {

        $path = public_path('product_image/');
        $req_images = json_decode($request->$param);
        $image_to_upload = $req_images[$request->featured_image];
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        $imageName = '';
        if ($request->existing[$request->featured_image] == 2) {
            $image1 = $image_to_upload;
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $imageName = '';
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);
                $imageName = $filename . self::random_string(4) . '.' . $image_type;
                \File::put($path . '/' . $imageName, base64_decode($image2));
                $output_file = $path . '/' . $imageName;
                $extension = pathinfo($path . '/' . $imageName, PATHINFO_EXTENSION);
                $input['imagename'] = $imageName;
                $destinationPath = public_path('/uploads');
            }
        } else {

            // if ($request->file($param)) {
            $extension = $image_to_upload->getClientOriginalExtension();
            $mime = $image_to_upload->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                return null;
            }
            $imageName = self::random_string(32) . '.' . $image_to_upload->getClientOriginalExtension();
            $image_to_upload->move($path, $imageName);
            // }
        }
        $clodflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        if ($clodflare->value == 1) {
            $response = CloudflareApi::images()->upload($path . $imageName);
            $image = $response->result;
            $image_id = $image->id;
        } else {
            $image_id = null;
        }
        $response_Array = json_encode(['imagename' => $imageName, 'id' => $image_id]);
        return $response_Array;
    }

    public function upload_all_images($request, $param, $id, $rm)
    {


        $path = public_path('product_image/');
        $req_images = json_decode($request->$param);

        $image_to_upload = $rm;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        $imageName = '';
        if ($request->existing[$id] == 2) {
            $image1 = $image_to_upload;
            $image = str_replace('data:image/png;base64,', '', $image1);
            $image2 = str_replace(' ', '+', $image);
            $image_parts = explode(";base64,", $image1);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $imageName = '';
            if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
                $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);
                $imageName = $filename . self::random_string(4) . '.' . $image_type;
                \File::put($path . '/' . $imageName, base64_decode($image2));
                $output_file = $path . '/' . $imageName;
                $extension = pathinfo($path . '/' . $imageName, PATHINFO_EXTENSION);
                $input['imagename'] = $imageName;
                $destinationPath = public_path('/uploads');
            }
        } else {

            // if ($request->file($param)) {
            $extension = $image_to_upload->getClientOriginalExtension();
            $mime = $image_to_upload->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
                return null;
            }
            $imageName = self::random_string(32) . '.' . $image_to_upload->getClientOriginalExtension();
            $image_to_upload->move($path, $imageName);
            // }
        }
        $clodflare = DB::table('settings')->where('key_word', 'cloudflare')->first();
        if ($clodflare->value == 1) {
            $response = CloudflareApi::images()->upload($path . $imageName);
            $image = $response->result;
            $image_id = $image->id;
        } else {
            $image_id = null;
        }
        $response_Array = json_encode(['imagename' => $imageName, 'id' => $image_id]);
        return $response_Array;
    }
    public static function store_restaurant_upload_image_banner($request, $param, $id)
    {
        // $path = public_path('restaurant_uploads/' . $id . '/Banner');
        $path = public_path('restaurant_uploads/' . $id . '/Banner');


        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        // $allowedExtensions = ['jpg', 'jpeg', 'png'];
        // $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        //    dd($request->ex_img);

        // if ($request->ex_img == "1") {
        //     $imageName = $request->$param;
        // }

        // else if ($request->ex_img == "2") {

        // if (!$request->file('banner')) {
        //     return null;
        // }

        $extension = $request->file('banner')->getClientOriginalExtension();
        $mime = $request->file('banner')->getMimeType();
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }
        $filename1 = $request->file('banner')->getClientOriginalName();
        $filename = pathinfo($filename1, PATHINFO_FILENAME);

        $imageName = $filename . self::random_string(4) . '.' . $request->file('banner')->getClientOriginalExtension();
        $imageName =  str_replace(' ', '', $imageName);
        $request->file('banner')->move($path, $imageName);
        // }
        //  else {
        //     $image1 = $request->$param; // your base64 encoded

        //     $image = str_replace('data:image/png;base64,', '', $image1);
        //     $image2 = str_replace(' ', '+', $image);
        //     $image_parts = explode(";base64,", $image1);
        //     $image_type_aux = explode("image/", $image_parts[0]);

        //     $image_type = $image_type_aux[1];

        //     //  dd($image_type);
        //     if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
        //         $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

        //         $imageName = $filename . self::random_string(4) . '.' . $image_type;

        //         \File::put($path.'/' . $imageName, base64_decode($image2));



        //         $output_file = $path . '/' . $imageName;
        //         // file_put_contents($output_file, file_get_contents($request->$param));



        //         $input['imagename'] = $imageName;

        //         $destinationPath =  $path;
        //         // $img = Image::make($output_file);
        //         // $img->resize(1920, 500, function ($constraint) {
        //         //     $constraint->aspectRatio();
        //         // })->save($destinationPath . '/' . $input['imagename']);
        //     }
        // }


        return $imageName;
    }

    public static function store_restaurant_upload_image_logo($request, $param, $id)
    {

        $path = public_path('restaurant_uploads/' . $id . '/Logo');

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        // if ($request->ex_img1 == "1") {
        //     $imageName = $request->$param;
        // }

        // else if ($request->ex_img1 == "2") {

        //     if (!$request->file('image')) {
        //         return null;
        //     }

        $extension = $request->file('image')->getClientOriginalExtension();
        $mime = $request->file('image')->getMimeType();
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($extension, $allowedExtensions) || !in_array($mime, $allowedMimes)) {
            return null;
        }
        $filename1 = $request->file('image')->getClientOriginalName();
        $filename = pathinfo($filename1, PATHINFO_FILENAME);

        $imageName = $filename . self::random_string(4) . '.' . $request->file('image')->getClientOriginalExtension();
        $imageName =  str_replace(' ', '', $imageName);
        $request->file('image')->move($path, $imageName);
        // }
        // else {
        //     $image1 = $request->$param; // your base64 encoded
        //     $image = str_replace('data:image/png;base64,', '', $image1);
        //     $image2 = str_replace(' ', '+', $image);

        //     $image_parts = explode(";base64,", $image1);
        //     $image_type_aux = explode("image/", $image_parts[0]);

        //     $image_type = $image_type_aux[1];


        //     if ($image_type == "jpg" || $image_type == "jpeg" || $image_type == "png") {
        //         $filename = pathinfo($request->$param, PATHINFO_FILENAME);

        //         $imageName = $filename . self::random_string(6) . '.' . $image_type;

        //         \File::put($path . '/' . $imageName, base64_decode($image2));


        //         $output_file = $path . '/' . $imageName;
        //         $input['imagename'] = $imageName;

        //         $destinationPath = $path;
        //         // $img = Image::make($output_file);
        //         // $img->resize(384, 284, function ($constraint) {
        //         //     $constraint->aspectRatio();
        //         // })->save($destinationPath . '/' . $input['imagename']);
        //     }
        // }

        return $imageName;
    }

    public static function upload_pharmacy($request, $param)
    {



        $image1 = $request->$param; // your base64 encoded

        $extension = explode('/', mime_content_type($image1))[1];
        if ($extension ==  "jpg") {
            $image_org = str_replace('data:image/jpg;base64,', '', $image1);
        } elseif ($extension == "jpeg") {
            $image_org = str_replace('data:image/jpeg;base64,', '', $image1);
        } elseif ($extension == "txt") {
            $image_org = str_replace('data:image/txt;base64,', '', $image1);
        } elseif ($extension == "doc") {
            $image_org = str_replace('data:image/doc;base64,', '', $image1);
        } elseif ($extension == "pdf") {
            $image_org = str_replace('data:image/pdf;base64,', '', $image1);
        } else {
            $image_org = str_replace('data:image/png;base64,', '', $image1);
        }

        $image2 = str_replace(' ', '+', $image_org);
        $image_parts = explode(";base64,", $image1);
        $image_type_aux = explode("image/", $image_parts[0]);
        if ($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "txt" || $extension == "pdf" || $extension == "doc") {
            $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

            $imageName = $filename . self::random_string(4) . '.' . $extension;
            $imageName =  str_replace(' ', '', $imageName);
            \File::put(public_path() . '/' . 'uploads/' . $imageName, base64_decode($image2));


            $output_file = public_path('uploads/' . $imageName);
            // file_put_contents($output_file, file_get_contents($request->$param));

            $extension = pathinfo(public_path('uploads/' . $imageName), PATHINFO_EXTENSION);

            $input['imagename'] = $imageName;

            $destinationPath = public_path('/uploads');
        }


        return $imageName;
    }

    public static function driver_image_upload_app($request, $param)
    {

        $image1 = $request->$param; // your base64 encoded

        $extension = explode('/', mime_content_type($image1))[1];
        if ($extension ==  "jpg") {
            $image_org = str_replace('data:image/jpg;base64,', '', $image1);
        } elseif ($extension == "jpeg") {
            $image_org = str_replace('data:image/jpeg;base64,', '', $image1);
        }  else {
            $image_org = str_replace('data:image/png;base64,', '', $image1);
        }

        $image2 = str_replace(' ', '+', $image_org);
        $image_parts = explode(";base64,", $image1);
        $image_type_aux = explode("image/", $image_parts[0]);
        if ($extension == "jpg" || $extension == "jpeg" || $extension == "png" ) {
            $filename = pathinfo($request->Banner_name, PATHINFO_FILENAME);

            $imageName = $filename . self::random_string(4) . '.' . $extension;
            $imageName =  str_replace(' ', '', $imageName);
            \File::put(public_path() . '/' . 'uploads/Profile/' . $imageName, base64_decode($image2));


            $output_file = public_path('uploads/Profile/' . $imageName);
            // file_put_contents($output_file, file_get_contents($request->$param));

            $extension = pathinfo(public_path('uploads/Profile/' . $imageName), PATHINFO_EXTENSION);

            $input['imagename'] = $imageName;

            $destinationPath = public_path('uploads/Profile/');
        }

        return 'uploads/Profile/' . $imageName;
    }

    public function base_image_upload_license_app($req)
    {


        $image1 = $req; // your base64 encoded

        $extension = explode('/', mime_content_type($image1))[1];
        if ($extension ==  "jpg") {
            $image_org = str_replace('data:image/jpg;base64,', '', $image1);
        } elseif ($extension == "jpeg") {
            $image_org = str_replace('data:image/jpeg;base64,', '', $image1);
        }  else {
            $image_org = str_replace('data:image/png;base64,', '', $image1);
        }

        $image2 = str_replace(' ', '+', $image_org);
        $image_parts = explode(";base64,", $image1);
        $image_type_aux = explode("image/", $image_parts[0]);
        if ($extension == "jpg" || $extension == "jpeg" || $extension == "png" ) {
            $filename = pathinfo($req, PATHINFO_FILENAME);

            $imageName = $filename . self::random_string(4) . '.' . $extension;
            $imageName =  str_replace(' ', '', $imageName);
            \File::put(public_path() . '/' . 'uploads/License/' . $imageName, base64_decode($image2));


            $output_file = public_path('uploads/License/' . $imageName);
            // file_put_contents($output_file, file_get_contents($request->$param));

            $extension = pathinfo(public_path('uploads/License/' . $imageName), PATHINFO_EXTENSION);

            $input['imagename'] = $imageName;

            $destinationPath = public_path('uploads/License/');
        }

        return $image_url = "uploads/License/" . $imageName;

    }
}

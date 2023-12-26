<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoGallery;
use App\Models\Photo;
use Validator;
use Auth;

class PhotosApiController extends Controller
{
    public function photosListing(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong!";
        $output['data'] = "";

        if(isset($request->lang) && !empty($request->lang)){
            app()->setLocale($request->lang);
        }

        $photos = [];
        $get_photos = Photo::with('photosgallery')->paginate(10);
        if(!$get_photos->isEmpty()){
            foreach($get_photos as $key => $value){
                $photosArr = [];
                $photosArr['id'] = $value['id'];
                $photosArr['title'] = $value['title'];

                // get gallery images
                $gallery = [];
                foreach ($value['photosgallery'] as $images) {
                    $gallery[] = [
                        'photo_id' => $images['photo_id'],
                        'image' => asset('uploads/photos/'.$images->images),
                    ];
                }
                $photosArr['photosgallery'] = $gallery;
                $photos[] = $photosArr;

            }

            $output['data'] = $photos;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Photos Listing Fetch Successfully!";
        }
        return json_encode($output);
    }

    // 
    public function photosDetail(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Record not Found!";
        $output['data'] =  "";

        $rules = [
            "photo_id" => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $get_photos = [];
        $id = $request->photo_id;

        if(isset($request->lang) && !empty($request->lang)){
            app()->setLocale($request->lang);
        }

        $photos = Photo::with('photosgallery')->where('id', $id)->first();
        // return $photos;
        if(!empty($photos)){
            // get gallery images
            $gallery = [];
            foreach ($photos['photosgallery'] as $images) {
                $gallery[] = [
                    'photo_id' => $images['photo_id'],
                    'image' => asset('uploads/photos/'.$images->images),
                ];
            }
            $get_photos['title'] = $photos->title;
            $get_photos['photosgallery'] = $gallery;

            $output['data'] = $get_photos;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Photos Detail Fetch Successfully!"; 
        }
        return json_encode($output);

    }
}

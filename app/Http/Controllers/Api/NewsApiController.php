<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsImage;
use Validator;
use Hash;
use Image;
use DB;

class NewsApiController extends Controller
{
    /* --- news listing --- */
    public function newsListing(Request $request)
    {  
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";
        $output['data'] = "";

        if(isset($request->lang) && !empty($request->lang)){
            app()->setLocale($request->lang);
        }
        $news= [];
        $get_news = News::with('galleryimages')->where('status','Active')->paginate(10);
        if(!$get_news->isEmpty()){
            foreach($get_news as $key => $value){
                $newsArr = [];
                $newsArr['id'] = $value->id;
                $newsArr['title'] = $value->title;
                $newsArr['description'] = $value->description;
                $newsArr['banner_image'] = asset('uploads/news/bannerImage/'.$value->banner_image);
                $newsArr['lat'] = $value->lat;
                $newsArr['long'] = $value->long;
                
                // get gallery images
                $gallery = [];
                foreach ($value['galleryimages'] as $images) {
                    $gallery[] = [
                        'news_id' => $images['news_id'],
                        'image' => asset('uploads/news/galleryImages/' .$images->images),
                    ];
                }
                $newsArr['gallery'] = $gallery;
                $news[] = $newsArr;
            }

            $output['data'] = $news;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
    }

    /* --- news detail api --- */
    public function newsDetail(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "News Not Found";
        $output['data'] = '';

        $rules = [
            "news_id" => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $news_id = $request->news_id;
        if(isset($request->lang) && !empty($request->lang)){
            app()->setLocale($request->lang);
        }

        $get_news = News::with('galleryimages')->where('id', $news_id)->where('status','Active')->whereNull('deleted_at')->first();

        $get_news_data = [];
        if($get_news){
            $gallery = [];
            foreach ($get_news['galleryimages'] as $images) {
                $gallery[] = [
                    'news_id' => $images['news_id'],
                    'image' => asset('uploads/news/galleryImages/' . $images->images),
                ];
            }
            $get_news_data['title'] = $get_news->title;
            $get_news_data['description'] = $get_news->description;
            $get_news_data['banner_image'] = asset('uploads/news/bannerImage/'.$get_news->banner_image);
            $get_news_data['gallery'] = $gallery;
            
            $output['data'] = $get_news_data;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
    }
}

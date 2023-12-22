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
            // return $request->all();
            $output = [];
            $output['status'] = false;
            $output['status_code'] = 422;
            $output['message'] = "Something Went Wrong";
            $output['data'] = "";
    
            if(isset($request->lang) && !empty($request->lang)){
                app()->setLocale($request->lang);
            }
            $news= [];
            $get_news = News::with('galleryimages')->where('status','Active')->get();
            // return $get_news;
            if(!$get_news->isEmpty()){
                foreach($get_news as $key => $value){
                    $newsArr = [];
                    $newsArr['id'] = $value['id'];
                    $newsArr['title'] = $value->title;
                    $newsArr['description'] = $value->description;
                    $newsArr['banner_image'] = asset('uploads/news/bannerImage/'.$value['banner_image']);
                    $newsArr['lat'] = $value['lat'];
                    $newsArr['long'] = $value['long'];
                  
                    // get gallery images
                    $gallery = [];
                    foreach ($value['galleryimages'] as $images) {
                        $gallery[] = [
                            'news_id' => $images['news_id'],
                            'image' => asset('uploads/news/galleryImages/' . $images['images']),
                        ];
                    }
                    $newsArr['gallery'] = $gallery;
                    $events[] = $newsArr;
                    // unset($get_news['galleryimages']);
                }
    
                // $output['data'] = $get_news;
                $output['data'] = $events;
                $output['status'] = true;
                $output['status_code'] = 200;
                $output['message'] = "Data Fetch Successfully!";
            }
            return json_encode($output);
        }
}

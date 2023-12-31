<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Language;
use Modules\Admin\Entities\Country;
use Modules\Admin\Entities\Region;
use Validator;
use Hash;
use DB;

class CommonController extends Controller
{
    // get countries 
    public function getCountry()
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";
        $output['data']  = "";

        $get_data = [];
        $get_countries = Country::with('regions')->where('is_show', '1')->get();
        if(!$get_countries->isEmpty()){
            foreach($get_countries as $key => $country){
                $countryArr = [];
                $countryArr['id'] = $country['id'];
                $countryArr['name'] = $country['name'];
                $countryArr['phonecode'] = $country['phonecode'];
                $countryArr['iso3'] = $country['iso3'];
                $countryArr['emoji'] = $country['emoji'];  
                $countryArr['region'] = $country['regions'];
                $get_data[] = $countryArr;
            }
        
            $output['data'] = $get_data;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch successfully!";
        }
        return json_encode($output);
    }

    public function getRegions(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Region Not Found";
        $output['data'] = '';

        $rules = [
            "country_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $get_region = [];
        $country_id = $request->country_id;
        $get_regions = Region::where('country', $country_id)->get();
        // return $get_regions;
        if(!$get_regions->isEmpty()){
            foreach($get_regions as $key => $value){
                $regionArr = [];
                $regionArr['id'] = $value['id'];
                $regionArr['region'] = $value['name'];
                $regionArr['country_name'] = "";

                // get country name
                $get_countries = Country::select('id','name')->where('id', $value['country'])->first();
                if(!empty($get_countries)){
                    $regionArr['country_name'] = $get_countries->name;
                    $get_region[] = $regionArr;
                }
            }
            $output['data'] = $get_region;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
    }

    // get languages
    public function getLanguages()
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";

        $get_language = [];
        $get_lang = Language::where('status','Active')->get();
        if(!$get_lang->isEmpty()){
            foreach($get_lang as $key => $value){
                $languageArr = [];
                $languageArr['id'] = $value['id'];
                $languageArr['name'] = $value['name'];
                $languageArr['image'] = $value['image'] !='' ? asset('uploads/languages/'.$value['image']) : asset('uploads/placeholder/placeholder.jpg');
                $get_language[] = $languageArr;
            }
            $output['data']= $get_language;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch successfully!";
        }
        return json_encode($output);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\OfferImage;
use App\Models\Offer;
use App\Models\User;
use Validator;
use Hash;
use Image;
use DB;

class OfferApiController extends Controller
{
    /* --- function for casting--- */
    function allString($object)
    {
        // Get the object's attributes
        $attributes = $object->getAttributes();

        // Iterate through the attributes and apply conversions
        foreach ($attributes as $key => &$value) {
            if (is_null($value)) {
                $value = "";
            } elseif (is_numeric($value) && !is_float($value)) {
                // Convert numeric values to integers (excluding floats)
                $value = (string) $value;
            }
            // Add more conditions for other types if needed
        }

        // Set the modified attributes back to the object
        $object->setRawAttributes($attributes);

        return $object;
    }

    public function createOffer(Request $request)
    {
        $output = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Something Went Wrong";
        
        $rules = [
            "contact_email"  => "required|email",
            "offer_detail"   => "required",
            // "images"          => "required|mimes:jpeg,jpg,png,gif",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $user = auth()->user();
        $get_offer = new Offer();

         // Upload Banner Image
         if($request->hasFile('image')){
            $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = rand(111,99999).'.'.$extension;
                    $image_path = public_path('uploads/offers/'.$imageName);

                    // upload images
                    Image::make($image_tmp)->resize(520, 600)->save($image_path);
                }
            }
        $get_offer->image = $imageName;
        $get_offer->user_id = $user->id;
        $get_offer->contact_email = $request->contact_email;
        $get_offer->offer_detail = $request->offer_detail;
        $get_offer->save();

        $output['status'] = true;
        $output['status_code'] = 200;
        $output['message'] = "Offer Created Successfully!";
        return json_encode($output);
    }

    public function OfferListing(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Record not found";
        $output['data'] = "";

        $user = auth()->user();
        $offer = [];
        $get_offer = DB::table('offers')->get();
        // return $get_offer;
        if(!$get_offer->isEmpty()){
            foreach($get_offer as $key => $value){
                $offerArr = [];
                $offerArr['id'] = $value->id;
                $offerArr['user_id'] = $value->user_id;
                $offerArr['image'] = asset('uploads/offers/'.$value->image);
                
                // get name and company name
                $offerArr['name'] = "";
                $offerArr['company_name'] = "";
                $get_data = User::where('id', $value->user_id)->where('status', 'Active')->first();
                if(!empty($get_data)){
                    $offerArr['name'] = $get_data->name;
                    $offerArr['company_name'] = $get_data->company_name;
                }
                $offerArr['contact_email'] = $value->contact_email;
                $offer[] = $offerArr;
            }

            $output['data'] = $offer;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Offer Listing Fetch Successfully!";
        }
        return json_encode($output);
    }

    public function OfferDetails(Request $request)
    {
        $output = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Something Went Wrong";
        $output['data']        = "";

        $user = auth()->user();
        $offer_detail = Offer::where('user_id', $user->id)->first();
        
        // get name and company name
        $offer_detail['name'] = "";
        $offer_detail['company_name'] = "";
        $get_data = User::where('id', $offer_detail->user_id)->where('status', 'Active')->first();
        // return $get_data;
        if(!empty($get_data)){
            $offer_detail['name'] = $get_data->name;
            $offer_detail['company_name'] = $get_data->company_name;

            $offer_detail = $this->allString($offer_detail);

            $output['data'] = $offer_detail;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Detail Fetch Successfully!";
        }
        return json_encode($output);

    }
}

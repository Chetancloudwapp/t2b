<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\User;
use Validator;
use Hash;
use Image;
use DB;

class InvestmentApiController extends Controller
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

    // create investment
    public function createInvestment(Request $request)
    {
        // return "hello";
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong!";

        $rules = [
            "investment_title"  => "required",
            "investment_detail" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $user = auth()->user();

        // $existingInvestment = Investment::where('user_id', $user->id)->first();

        // if(!empty($existingInvestment)){
        //      return response()->json([
        //         'status' => false,
        //         'status_code' => 422,
        //         'message' => "Investment Detail already sent!!",
        //     ],422);
        // }else{
        // }

        $investments = new Investment();
        $investments->user_id = $user->id;
        $investments->investment_title = $request->investment_title;
        $investments->investment_detail = $request->investment_detail;
        $investments->save();
        $output['status'] = true;
        $output['status_code'] = 200;
        $output['message'] = "Investment Detail sent Successfully!";
        return json_encode($output);
    }

    // listing all investments
    public function allInvestmentListing(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Record not found";
        $output['data'] = "";

        $user = auth()->user();

        $investments = [];
        $get_investment = Investment::get();
        if(!$get_investment->isEmpty()){
            foreach($get_investment as $key => $value){
                $investmentArr = [];
                $investmentArr['id'] = $value['id'];
                $investmentArr['user_id'] = $value['user_id'];
                $investmentArr['investment_title'] = $value['investment_title'];
                $investmentArr['investment_detail'] = $value['investment_detail'];
                $investmentArr['created_at'] = date('d-m-Y',strtotime($value['created_at']));
    
                // get name and company name 
                $get_user = User::where('id', $value->user_id)->where('status','Active')->first();
                if(!empty($get_user)){
                    $investmentArr['name'] = $get_user->name;
                    $investmentArr['company_name'] = $get_user->company_name;
                    $investmentArr['image'] = $get_user->image !='' ? asset('uploads/userimage/'.$get_user->image) : asset('uploads/placeholder/default_user.png');
                }
                // $investmentArr['name'] = $user->name;
                $investments[] = $investmentArr;
            }
            $output['data'] = $investments;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!"; 
        }
        return json_encode($output);
    }

    // view investment
    public function allinvestmentDetail(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Record not found";
        $output['data'] = "";

        $rules = [
            "investment_id" => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $user = auth()->user();
        // return $user;
        $investment_id = $request->investment_id;
        
        $get_investment = Investment::where('id', $investment_id)->first();
        if(!empty($get_investment)){
            $get_investment['name']  = $user->name;
            $get_investment['company_name']  = $user->company_name;
            $get_investment['image']  = asset('uploads/userimage/'.$user->image);

            // typecast the data 
            $get_investment = $this->allString($get_investment);
            $output['data'] = $get_investment;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Details Fetch Successfully!";
        }else{
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message'  => "Record not found!",
            ],422);
        }
        return json_encode($output);
    }

    // edit investment
    public function editInvestment(Request $request)
    {
        return "edit investment";
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong!";
        
        
    }
}

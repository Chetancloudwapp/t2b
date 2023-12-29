<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\InvestmentRequest;
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
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Something Went Wrong!";

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
        $output['status']      = true;
        $output['status_code'] = 200;
        $output['message']     = "Investment Detail sent Successfully!";
        return json_encode($output);
    }

    // listing all investments
    public function allInvestmentListing(Request $request)
    {
        $output = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Record not found";
        $output['data']        = "";

        $user = auth()->user();

        $investments = [];
        $get_investment = Investment::get();
        if(!$get_investment->isEmpty()){
            foreach($get_investment as $key => $value){
                $investmentArr = [];
                $investmentArr['id'] = $value['id'];
                $investmentArr['user_id'] = $value['user_id'];
                $investmentArr['investment_title']  = $value['investment_title'];
                $investmentArr['investment_detail'] = $value['investment_detail'];
                $investmentArr['created_at']   = date('d-m-Y',strtotime($value['created_at']));
    
                // get name and company name 
                $get_user = User::where('id', $value->user_id)->where('status','Active')->first();
                if(!empty($get_user)){
                    $investmentArr['name']         = $get_user->name;
                    $investmentArr['company_name'] = $get_user->company_name;
                    $investmentArr['image']        = $get_user->image !='' ? asset('uploads/userimage/'.$get_user->image) : asset('uploads/placeholder/default_user.png');
                }
                // $investmentArr['name'] = $user->name;
                $investments[] = $investmentArr;
            }
            $output['data']        = $investments;
            $output['status']      = true;
            $output['status_code'] = 200;
            $output['message']     = "Data Fetch Successfully!"; 
        }
        return json_encode($output);
    }

    // view investment
    public function allinvestmentDetail(Request $request)
    {
        $output = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Record not found";
        $output['data']        = "";

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
            $get_investment['name']         = $user->name;
            $get_investment['company_name'] = $user->company_name;
            $get_investment['image']        = asset('uploads/userimage/'.$user->image);

            // typecast the data 
            $get_investment = $this->allString($get_investment);
            $output['data']        = $get_investment;
            $output['status']      = true;
            $output['status_code'] = 200;
            $output['message']     = "Details Fetch Successfully!";
        }else{
            return response()->json([
                'status'      => false,
                'status_code' => 422,
                'message'     => "Record not found!",
            ],422);
        }
        return json_encode($output);
    }

    // edit investment
    public function editInvestment(Request $request, $id=null)
    {
        if($id==""){
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'Investment id is required',
            ],422);
        }else{
            $output = [];
            $output['status']      = false;
            $output['status_code'] = 422;
            $output['message']     = "Something Went Wrong!";
    
            $rules = [
                // "investment_id"     => "required",
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
            $get_investment = Investment::where('id', $id)->first();
            if(!empty($get_investment)){
                $get_investment->investment_title = $request->investment_title;
                $get_investment->investment_detail = $request->investment_detail;
                $get_investment->save();
                $output['status']      = true;
                $output['status_code'] = 200;
                $output['message']     = "Investment Updated Successfully!";
            }else{
                $output['message'] = "Investment not found!";
            }
        }
        return json_encode($output);
    }
    // public function editInvestment(Request $request)
    // {
    //     $output = [];
    //     $output['status']      = false;
    //     $output['status_code'] = 422;
    //     $output['message']     = "Something Went Wrong!";

    //     $rules = [
    //         "investment_id"     => "required",
    //         "investment_title"  => "required",
    //         "investment_detail" => "required",
    //     ];

    //     $validator = Validator::make($request->all(), $rules);
    //     if($validator->fails()){
    //         return response()->json([
    //             'status'  => false,
    //             'message' => $validator->errors()->first(),
    //         ],422);
    //     }

    //     $user = auth()->user();

    //     $investment_id  = $request->investment_id;
    //     $get_investment = Investment::where('id', $investment_id)->first();
    //     if(!empty($get_investment)){
    //         $get_investment->investment_title = $request->investment_title;
    //         $get_investment->investment_detail = $request->investment_detail;
    //         $get_investment->save();
    //         $output['status']      = true;
    //         $output['status_code'] = 200;
    //         $output['message']     = "Investment Updated Successfully!";
    //     }else{
    //         $output['message'] = "Investment not found!";
    //     }
    //     return json_encode($output);
    // }

    // delete investment
    public function deleteInvestment(Request $request)
    {
        $get_investment = Investment::where('id', $request->id)->first();
        if(!empty($get_investment)){
            $get_investment->delete();
            return response()->json([
                'status'      => true,
                'status_code' => 200,
                'message'     => 'Investment deleted Successfully!',
            ],200);
        }else{
            $message = "Investment not found!.";
            return response()->json([
                'status'      => false,
                'status_code' => 422,
                'message'     => $message,
            ],422);
        }
    }

    // investment request
    public function investmentRequest(Request $request)
    {
        $output = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Something Went Wrong!";

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'investment_detail' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }
        
        $user = auth()->user();
        // return $user;
        $investment_req = new InvestmentRequest();
        $investment_req->user_id = $user->id;
        $investment_req->name = $request->name;
        $investment_req->email = $request->email;
        $investment_req->phone_number = $request->phone_number;
        $investment_req->investment_detail = $request->investment_detail;
        $investment_req->save();
        $output['status'] = true;
        $output['status_code'] = 200;
        $output['message'] = "Investment request Sent Successfully!";
        return json_encode($output);
    }

    // my investment detail
    public function myInvestmentDetail(Request $request)
    {
        // return "myinvestment";
        $output = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']     = "Record not found";
        $output['data']        = "";

        $user = auth()->user();
        // $investment_id = $request->investment_id;
        $investmentArr = [];
        $get_investment = Investment::where('id', $request->investment_id)->first();
        if(!empty($get_investment)){
            $investmentArr['id']  = $get_investment->id;
            $investmentArr['user_id']  = $get_investment->user_id;
            $investmentArr['investment_title']  = $get_investment->investment_title;
            $investmentArr['investment_detail']  = $get_investment->investment_detail;
            $investmentArr['name']         = $user->name;
            $investmentArr['company_name'] = $user->company_name;
            $investmentArr['image']        = asset('uploads/userimage/'.$user->image);

            // get investment request 
            $investments = [];
            $get_investment_req = InvestmentRequest::where('user_id', $user->id)->get();
            if(!$get_investment_req->isEmpty()){
                foreach($get_investment_req as $key => $value){
                    $investment = [];
                    $investment['name'] = $value->name;
                    $investment['email'] = $value->email;
                    $investment['phone_number'] = $value->phone_number;
                    $investment['investment_detail'] = $value->investment_detail;
                    $investments[] = $investment;
                }
                $investmentArr['investment_request'] = $investments;
            }
            // typecast the data 
            // $get_investment = $this->allString($get_investment);
            $output['data']        = $investmentArr;
            $output['status']      = true;
            $output['status_code'] = 200;
            $output['message']     = "Details Fetch Successfully!";
        }else{
            return response()->json([
                'status'      => false,
                'status_code' => 422,
                'message'     => "Record not found!",
            ],422);
        }
        return json_encode($output);
    }
}

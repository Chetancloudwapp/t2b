<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Country;
use App\Models\User;
use Validator;
use Hash;
use DB;

class AuthController extends Controller
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

    /*--- register user --- */
    public function RegisterUser(Request $request)
    {

        if($request->isMethod('post')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;
            $rules = [
                "name"         => "required|string|max:255",
                "email"        => "required|email|unique:users",
                "country_code" => "required",
                "phone_number" => "required",
                "company_name" => "required|string|max:255",
                "password"     => "required|min:4|max:6",
                "confirm_password" => "required|same:password",
                "country"      => "required",
                "region"       => "required",
                "fcm_token"    => "required",
                "device_id"    => "required",
                "device_type"  => "required",
                "image"        => "mimes:jpeg,jpg,png,gif",
            ];

            $validation = Validator::make($userData, $rules);
            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validation->errors()->first(),
                ],
                422,
            );
            }

            $user = new User;
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->country_code = $userData['country_code'];
            $user->phone_number = $userData['phone_number'];
            $user->company_name = $userData['company_name'];
            $user->country_id = $userData['country'];
            $user->region = $userData['region'];
            $user->password = Hash::make($userData['password']);
            if($request->has('image')){
                $image = $request->file('image');
                $name = time(). "." .$image->getClientOriginalExtension();
                $path = public_path('uploads/userimage/');
                $image->move($path, $name);
                $user->image = $name;
            }

            $user->save();
            if(Auth::attempt(['email' => $userData['email'], 'password'=> $userData['password']])){
                $user = User::where('email', $userData['email'])->first();
            
                // Generate Authorization Token
                $authorizationToken = $user->createToken("t2b")->accessToken;
                // return $authorizationToken;
                DB::table('oauth_access_tokens')
                ->where('user_id', $user->id)
                ->update(['fcm_token' => $userData['fcm_token'], 'device_id' => $userData['device_id'], 'device_type' => $userData['device_type']]);
                $user['token'] = $authorizationToken;

                /* -- typecast data -- */
                $user = $this->allString($user);

                return response()->json([
                    'status' => true,
                    'message' => "User Register Successfully!",
                    'data' => $user,
                ],201);
            }else{
                $message = "Something Went Wrong please try again.";
                return response()->json(['status'=>false, 'message'=> $message],422);
            }
        }
    }

    /* --- login user api */
    public function LoginUser(Request $request)
    {
        $userData = $request->input();
        $rules = [
            "email" => "required|email",
            "password" => "required",
        ];

        $validation = Validator::make($userData, $rules);
        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message'=> $validation->errors()->first(),
            ],
            422,);
        }

        if (Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])) {

            // user exists
            $user = Auth::user();
            if($user->status == "Active"){
                // create token 
                $token = $user->createToken('t2b')->accessToken;
                $user['token'] = $token;
    
                // casting all the data 
                $user = $this->allString($user);
                return response()->json([
                    'status' => true,
                    'message' => 'User Login Successfully!',
                    'data'   => $user,
                ], 201);
            }else if($user->status == "Reject"){
                return response()->json([
                    'status' => false,
                    'message' => 'Your Account has been rejected by the admin',
                    'reason'   => $user->status_reason,
                ],422);
            }
            else{
                return response()->json(['status' => false, 'message'=> "Your Account is not active, Please Contact Admin."],422);
            }

        } else {
            $message = "Email or Password is Incorrect.";
            return response()->json([
                'status' => false,
                'message' => $message
            ], 422);
        }
        
    }

    /* --- logout user Api --- */
    public function LogoutUser(Request $request)
    {
        $user = Auth::user();
        $user->token()->revoke();
        $message = "User Logout Successfully!";
        return response()->json(['status'=>true, 'message'=> $message],202);
    }

    /* --- User Detail Api --- */
    public function UserDetail(Request $request)
    {
       $user = Auth::user();

       return response()->json([
           "status" => true,
           "message" => "User Detail Fetch Successfully!",
           "data" => $user
       ]);
    }

    /* --- Update profile Api --- */
    public function UserProfileUpdate(Request $request)
    {
        $rules = [
            "name"         => "required",
            "country_code" => "required",
            "phone_number" => "required",
            "company_name" => "required",
        ];

        $validation = Validator::make($request->all(), $rules);
        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message'=> $validation->errors()->first(),
            ],
            422,);
        }

        // Update the user profile
        $user = auth()->user();
        $user->name = $request->name;
        // $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data'   => $user,
        ]);
    }

    public function getCountry()
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";
        $output['countries']  = "";

        $get_data = [];
        $get_countries = Country::get();
        // return $get_countries;
        if(!$get_countries->isEmpty()){
            foreach($get_countries as $key => $value){
                $countryArr = [];
                $countryArr['id'] = $value['id'];
                $countryArr['name'] = $value['name'];
                $countryArr['phonecode'] = $value['phonecode'];
                $countryArr['iso3'] = $value['iso3'];
                $countryArr['region'] = $value['region'];
                $countryArr['emoji'] = $value['emoji'];
                $get_data[] = $countryArr;
            }
            // $data['countries'] = $get_data;

            // // $language 
            // $get_language = [];
            // $get_lang = Language::where('status','Active')->get();
            // if(!$get_lang->isEmpty()){
            //     foreach($get_lang as $key => $value)
            // }

            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch successfully!";
            $output['countries'] = $get_data;
        }
        return json_encode($output);
    }

}

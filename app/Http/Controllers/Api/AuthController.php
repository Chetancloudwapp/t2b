<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Language;
use Modules\Admin\Entities\Country;
use Modules\Admin\Entities\Region;
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
            $rules = [
                "name"         => "required|regex:/^[^\d]+$/|min:2|max:255",
                "email"        => "required|email|unique:users",
                "country_code" => "required|integer",
                "phone_number" => "required|numeric|digits_between:9,15",
                "company_name" => "required|regex:/^[^\d]+$/|min:2|max:255",
                "country"      => "required|integer",
                "region"       => "required|integer",
                "password"     => "required|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
                "confirm_password" => "required|same:password",
                "fcm_token"    => "required",
                "device_id"    => "required",
                "device_type"  => "required",
                "image"        => "mimes:jpeg,jpg,png,gif",
            ];

            $customValidation = [
                "password.regex" => "password must be at least one uppercase letter, one digit & one special character",
                "name.regex" => "The name must contain only characters.",
                "company_name" => "The company name must contain only character.",
            ];

            $validation = Validator::make($userData, $rules, $customValidation);
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
                    'status_code' => 201,
                    'message' => "User Register Successfully!",
                    'data' => $user,
                ],201);
            }else{
                $message = "Something Went Wrong please try again.";
                return response()->json([
                    'status'=>false, 
                    'message'=> $message
                ],422);
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

        // check the user with Auth
        if (Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])) {

            // user exists
            $user = Auth::user();
            if($user->status == "Active"){
                // create token 
                $token = $user->createToken('t2b')->accessToken;
                $user['token'] = $token;
                $user['image'] = url('uploads/userimage/'.$user['image']);
    
                // casting all the data 
                $user = $this->allString($user);
                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'User Login Successfully!',
                    'data'   => $user,
                ], 200);
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
        return response()->json([
            'status'=>true, 
            'status_code'=>200, 
            'message'=> $message]);
    }

    /* --- User Detail Api --- */
    public function UserDetail(Request $request)
    {
       $user = Auth::user();
       $user['image'] = url('uploads/userimage/'.$user['image']);

       $country = Country::select('id','name')->where('id', $user->country_id)->first();
       if($country){
           $user['country_name'] = $country->name;
       }

       $region = Region::select('id', 'name')->where('id', $user->region)->first();
       if($region){
           $user['region_name'] = $region->name;
       }

       $user = $this->allString($user);
       return response()->json([
           "status" => true,
           "status_code" => 200,
           "message" => "User Detail Fetch Successfully!",
           "data" => $user
       ]);
    }

    /* --- Update profile Api --- */
    public function UserProfileUpdate(Request $request)
    {
        $rules = [
            "image"        => "mimes:jpeg,jpg,png,gif",
            "name"         => "required|regex:/^[^\d]+$/|min:2|max:255",
            "email"        => "email|unique:users",
            "country_code" => "required|integer",
            "phone_number" => "required|numeric|digits_between:9,15",
            "company_name" => "required|regex:/^[^\d]+$/|min:2|max:255",
            "country_id"   => "required|integer",
            "region"       => "required|integer",
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
        if(isset($request->image)){
            if($request->has('image')){
                $image = $request->file('image');
                $name = time(). "." .$image->getClientOriginalExtension();
                $path = public_path('uploads/userimage/');
                $image->move($path, $name);
                $user->image = $name;
            }
        }

        if(isset($request->email)){
            $user->email = $request->email;
        }
     
        $user->update($validation->validated());

        $user['image'] = url('uploads/userimage/'.$user['image']);

        // Typecast user data 
        $user = $this->allString($user);

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Profile updated successfully',
            'data'   => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";

        $rules = [
            "old_password" => "required",
            "new_password" => "required|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
            "confirm_password" => "required|same:new_password",
        ];

        $customValidation = [
            "new_password.regex" => "password must be at least one uppercase letter, one digit & one special character",
        ];

        $validator = Validator::make($request->all(), $rules, $customValidation);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first(),
            ],
            422,);
        }

        $get_user = auth()->user();
        $user = User::where('id', $get_user->id)->where('status','Active')->first();
        if($user){
            if(Hash::check($request->old_password, $get_user->password)){
                if(!Hash::check($request->confirm_password, $get_user->password)){
                    $user->password = Hash::make($request->confirm_password);
                    $user->save();
                    $output['status']  = true;
                    $output['status_code'] = 200;
                    $output['message'] = "Password Change Successfully!";
                }else{
                    $output['status']  = false;
                    $output['status_code'] = 422;
                    $output['message'] = "Your new password must be different from previously used password!";
                }

            }else{
                $output['status']  = false;
                $output['status_code'] = 422;
                $output['message'] = " old password was wrong!";
            }
        }
        return json_encode($output); 
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        $get_user = User::where('id', $user->id)->where('status','Active')->first();
        $get_user->delete();
        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => "Account Deleted Successfully!."
        ],200);
    }
}

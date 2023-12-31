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
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use App\Models\Banner;
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

                // get country
                $get_country = Country::select('id','name')->where('id', $user->country_id)->first();
                if(!empty($get_country)){
                    $user['country_id'] = $get_country->name;
                }
                
                // get region
                $get_region = Region::select('id','name')->where('id', $user->region)->first();
                if(!empty($get_region)){
                    $user['region'] = $get_region->name;
                }
                
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

                // get country
                $get_country = Country::select('id','name')->where('id', $user->country_id)->first();
                if(!empty($get_country)){
                    $user['country_id'] = $get_country->name;
                }
                
                // get region
                $get_region = Region::select('id','name')->where('id', $user->region)->first();
                if(!empty($get_region)){
                    $user['region'] = $get_region->name;
                }
    
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

    /* --- members list--- */
    public function MemberListing()
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";
        $output['data'] = "";

        $user_data = [];
        $user = auth()->user();
        $get_user = DB::table('users')->where('status', 'Active')->where(['country_id'=> $user->country_id, 'region'=> $user->region])->get();
        if(!$get_user->isEmpty()){
            foreach($get_user as $key => $value){
                $userArr = [];
                $userArr['id']    = $value->id;
                $userArr['name']  = $value->name;
                $userArr['email'] = $value->email;
                $userArr['status'] = $value->status;
                $userArr['image'] = asset('uploads/userimage/'.$value->image);
                $userArr['country_code'] = $value->country_code;
                $userArr['phone_number'] = $value->phone_number;
                $userArr['company_name'] = $value->company_name;

                // get country
                $userArr['country'] = "";
                $get_country = Country::select('id','name')->where('id', $value->country_id)->first();
                if(!empty($get_country)){
                    $userArr['country'] = $get_country->name;
                }

                // get region
                $userArr['region'] = "";
                $get_region = Region::where('id', $value->region)->first();
                if(!empty($get_region)){
                    $userArr['region'] = $get_region->name;
                }
                $user_data[] = $userArr;
            }

            $output['data'] = $user_data;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
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
            "old_password"     => "required",
            "new_password"     => "required|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
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

    public function Bannerdetails(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message']  = "Something Went Wrong!";
        
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'image'  => 'required|mimes:jpg,jpeg,png,gif',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $get_data = new Banner();

        if($request->has('image')){
            $image = $request->file('image');
            $name = time(). "." .$image->getClientOriginalExtension();
            $path = public_path('uploads/bannerImages/');
            $image->move($path, $name);
            $get_data->image = $name;
        }
        $get_data->title = $request->title;
        $get_data->description = $request->description;
        $get_data->save();
        $output['status'] = true;
        $output['status_code'] = 200;
        $output['message'] = "Banner Details Sent Successfully!";
        return json_encode($output);
    }

    // forget password
    public function ForgetPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $email = $request->email;
        $user = User::select('id','email','status')->where('email', $email)->where('status','Active')->first();
        if(!empty($user)){
            // generate random token
            $token = rand(100000, 999999);
            // return $token;

            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token
            ]);

            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $user->id, // Pass the user's ID
                    'hash' => sha1($user->email), // Generate a hash for email verification (this is just an example, you can use a different hash)
                ]
            );

            // send mail to user
            Mail::to($request->email)->send(new ForgotPasswordMail($token));
            return response()->json([
                'status' => true,
                'message' => 'Password reset email sent successfully',
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => "Email is invalid",
            ],422);
        };
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $get_data = DB::table('password_reset_tokens')->where(['email'=>$request->email, 'token'=>$request->token])->first();
        if(!empty($get_data)){
          $user =  User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

          DB::table('password_reset_tokens')->where('email', $request->email)->delete();
          return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Password Change Successfully!',
          ],200);
        }else{
            return response()->json([
                'status' => false,
                'status_code' => 422,
                'message' => 'email or token is incorrect.',
            ],422);
        }

    }
}

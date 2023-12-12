<?php

namespace Modules\Admin\Http\Controllers;
use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Modules\Admin\Entities\User;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use DB;
use Validator;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

   
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
                "name"         => "required",
                "email"        => "required|email|unique:users",
                "country_code" => "required",
                "phone_number" => "required",
                "company_name" => "required",
                "password"     => "required|min:4|max:6",
                "confirm_password" => "required|same:password",
                "country"      => "required",
                "region"       => "required",
                "fcm_token"    => "required",
                "device_id"    => "required",
                "device_type"  => "required",
                "image"        => "mimes:jpeg,jpg,png,gif",
            ];

            // $customMessages = [
            //     "name.required" => "Name is Required",
            //     "email.required" => "Email address Must be a valid",
            //     "email.unique" => "Email Already exists in database",
            //     "password.required" => "password is required",
            // ];

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
                $authorizationToken = $user->createToken($userData['email'])->accessToken;
                // return $authorizationToken;
                DB::table('oauth_access_tokens')
                ->where('user_id', $user->id)
                ->update(['fcm_token' => $userData['fcm_token'], 'device_id' => $userData['device_id'], 'device_type' => $userData['device_type']]);
                $user['token'] = $authorizationToken;

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
        if($request->isMethod('post')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;
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
                422,
            );
           }

        //    if(Auth::attempt(['email'=> $userData['email'], 'password'=> $userData['password']])){
        //       $user = User::where('email', $userData['email'])->first();

        //       // generate the token
        //       $authorizationToken = $user->createToken($userData['email'])->accessToken;
        //     //   $user['token'] = $authorizationToken;

        //       // update token in users table
        //       User::where('email', $userData['email'])->update(['access_token' => $authorizationToken]);
        //       return response()->json([
        //         'status' => true,
        //         'message' => 'User Login Successfully!',
        //         'data' => $user,
        //       ],201);
        //    }else{
        //       $message = "Email or Password is Incorrect.";
        //       return response()->json([
        //         'status' => false,
        //         'message' => $message
        //       ],422);
        //    }
        if (Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])) {
            $user = Auth::user();
            // return $user;
        
            // Revoke existing tokens before creating a new one
            // $user->tokens()->delete();
        
            // Generate a new token
            $authorizationToken = $user->createToken('app')->accessToken;
        // return $request->user();
            return response()->json([
                'status' => true,
                'message' => 'User Login Successfully!',
                'data' => $user->makeVisible('access_token'), // Include the token in the response
            ], 201);
        } else {
            $message = "Email or Password is Incorrect.";
            return response()->json([
                'status' => false,
                'message' => $message
            ], 422);
        }
        
        }
    }

    /* --- logout user Api --- */
    public function LogoutUser(Request $request)
    {
        // return $request->user();
        // $api_token = $request->header('Authorization');
  
        // if(empty($api_token)){
        //     $message = "Token is missing";
        //     return response()->json(['status'=>false, 'message'=>$message],422);
        // }else{
            
        //     $userCount = Passport::pruneRevokedTokens();
        //     return $userCount;
        //     if($userCount){
        //         $userCount->delete();
        //         $message = "User Logout Successfully!";
        //         return response()->json(['status'=>true, 'message'=> $message],202);
        //     }
        // }
        // $user_id = $request->user_id;

       return $user = Auth::user();
        return $user;
        $user->token()->revoke();
        $message = "User Logout Successfully!";
        return response()->json(['status'=>true, 'message'=> $message],202);
    }

    public function UserDetail(Request $request)
    {
        $output                = [];
        $output['status']      = false;
        $output['status_code'] = 422;
        $output['message']  = "User Not Found";
        // $output['data'] = '';
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'message'=> $validator->errors()->first(),
            ],422
          );
        }
        // $get_user = [];
        $user_id = $request->user_id;
        $user = User::where('id', $user_id)->where('status','Active')->first();
        if(!empty($user)){
            // $get_user
            // $get_user['id'] = $user['id'] !='' ? $user['id'] : '';
            // $get_user['username'] = $user['name'] !='' ? $user['name'] : '';
            // $get_user['image'] = $user['image'] !='' ? asset('uploads/userimage/'.$user['image']) : asset('uploads/placeholder/placeholder.jpg');
            // $get_user['email']  = $user['email'] !='' ? $user['email'] : '';
            // $get_user['country_code']  = $user['country_code'] !='' ? $user['country_code'] : '';
            // $get_user['phone_number']  = $user['phone_number'] !='' ? $user['phone_number'] : '';
            // $get_user['company_name']  = $user['company_name'] !='' ? $user['company_name'] : '';
            // $get_user['country']  = $user['country_id'] !='' ? $user['country_id'] : '';
            // $get_user['region']  = $user['region'] !='' ? $user['region'] : '';

            // $output['data'] = $user;
            $output['data'] = $this->allString($user);

            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "User Detail Fetch Successfully!";
        }else{
            $output['status'] = false;
            $output['status_code'] = 422;
            $output['message'] = "User Not Found!";

        }

        return json_encode($output);
    }

    public function UserProfileUpdate(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong!";

        $req_field = [];
        $req_field['user_id'] = "required";
        $req_field['username'] = "required";
        $req_field['image'] = "mimes:jpeg,jpg,png,gif";
        $req_field['country_code'] = "required";
        $req_field['phone_number'] = "required";
        $req_field['company_name'] = "required";
        $req_field['country'] = "required";
        $req_field['region']  = "required";
        $req_field['password']  = "required";

        $customMessages = [
            'phone_number' => 'Mobile number must be between 6 to 15 digit',
        ];

        $validator = Validator::make($request->all(), $req_field, $customMessages);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $user = User::where('id', $request->user_id)->where('status', 'Active')->first();
        if(!empty($user)){
            $user->name = $request->username;
            $user->country_code = $request->country_code;
            $user->phone_number = $request->phone_number;
            $user->company_name = $request->company_name;
            $user->country_id = $request->country;
            $user->region = $request->region;
            $user->password = $request->password;
            if(isset($request->image)){
                if($request->has('image')){
                    $image = $request->file('image');
                    $name = time(). "." .$image->getClientOriginalExtension();
                    $path = public_path('uploads/userimage/');
                    $image->move($path, $name);
                    $user->image = $name;
                }
            }

            $user->save();
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "User Profile Update Successfully!";
        }else{
            $output['message'] = "User not Found!";
        }
        return json_encode($output);
    }

    public function index()
    {
        return view('admin::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace Modules\Admin\Http\Controllers;
use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use DB;
use Validator;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    function convertNullsAndIntegersToStrings($object) {
        foreach (get_object_vars($object) as $key => $value) {
          // Convert null values to empty strings
          if (is_null($value)) {
            $object->$key = '';
          }
          // Convert integer values to strings (including "0")
          if (is_numeric($value)) {
            $object->$key = (string) $value;
          }
        }
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
                "country"      => "required",
                "region"       => "required",
                "fcm_token"    => "required",
                "device_id"    => "required",
                "device_type"  => "required",
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

                $user = $this->convertNullsAndIntegersToStrings($user);

                // $token = $user->createToken($user->email);
                //     DB::table('oauth_access_tokens')
                // ->where('id', $token->token->id)
                // ->update(['customField' => $request->customField]);

                // Update token in users table
                User::where('email', $userData['email'])->update(['access_token' => $authorizationToken]);
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
        
            // Revoke existing tokens before creating a new one
            $user->tokens()->delete();
        
            // Generate a new token
            $authorizationToken = $user->createToken($user->email)->accessToken;
        return $request->user();
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

      $user = Auth::user();
        // // return $user;

        // DB::table('oauth_access_tokens')
        // ->where('user_id', $user_id)
        // ->delete();
        // $user->token()->revoke();
        // $token = $request->token;
        $message = "User Logout Successfully!";
        return response()->json(['status'=>true, 'message'=> $message],202);

        // $user->tokens->each(function (Token $token) {
        //     $token->revoke();
        // });
        // return $user;
        // if($user){
        //     $user->revoke();
        //     $message = "User Logout Successfully!";
        //     return response()->json(['status'=>true, 'message'=> $message],202);
        // }
        

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

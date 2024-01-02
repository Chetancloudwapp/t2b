<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Country;
use Modules\Admin\Entities\{Region,Event};
use App\Models\{User,EventFeedback};
use Validator;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $common = [];
        $common['title'] = "Users";
        $users = User::orderBy('id', 'desc')->get();
        return view('admin::users.index')->with(compact('common','users'));
    }

    public function addUser(Request $request)
    {
        $common = [];
        $common['title'] = "Users";
        $common['heading_title'] = "Add Users";
        $common['button'] = "Submit";
        $message = "User Added Successfully!";
        
        // get country list
        $get_countries = Country::where('is_show', '1')->get();
        
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                "name"         => "required|regex:/^[^\d]+$/|min:2|max:255",
                "email"        => "required|email|unique:users",
                "company_name" => "required|regex:/^[^\d]+$/|min:2|max:255",
                "image"        => "required|mimes:jpeg,jpg,png,gif",
                "password"     => "required|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
            ];

            $customValidation = [
                "password.regex" => "password must be at least one uppercase letter, one digit & one special character",
            ];
            
            $validator = Validator::make($request->all(), $rules, $customValidation);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            
            $user = new User();
            if($request->has('image')){
                $image = $request->file('image');
                $name = time(). "." .$image->getClientOriginalExtension();
                $path = public_path('uploads/userimage/');
                $image->move($path, $name);
                $user->image = $name;
            }
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->country_id = $data['country'];
            $user->country_code = $data['country_code'];
            $user->phone_number = $data['phone_number'];
            $user->company_name = $data['company_name'];
            $user->password = Hash::make($data['password']);
            $user->region = $data['region'];
            $user->status = $data['status'];
            $user->status_reason = $data['status_reason'];
            $user->save();
            return redirect('admin/user')->with('success_message', $message);
        }
        return view('admin::users.addUser')->with(compact('common','get_countries'));
    }

    public function editUser(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Users";
        $common['heading_title'] = "Edit Users";
        $common['button'] = "Update";
        $id = decrypt($id);
        $user = User::find($id);
        $message = "User Updated Successfully!";
        $get_countries = Country::where('is_show', '1')->get();
        $get_regions = Region::where('country',$user->country_id)->get();
        
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                "name" => "required|regex:/^[^\d]+$/|min:2|max:255",
                "email" => "email",
                "image" => "mimes:jpeg,jpg,png,gif",
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
               return back()->withErrors($validator)->withInput();
            }

            if($request->has('image')){
                $image = $request->file('image');
                $name = time(). "." .$image->getClientOriginalExtension();
                $path = public_path('uploads/userimage/');
                $image->move($path, $name);
                $user->image = $name;
            }

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->country_id = $data['country'];
            $user->country_code = $data['country_code'];
            $user->phone_number = $data['phone_number'];
            $user->company_name = $data['company_name'];
            // $user->password = Hash::make($data['password']);
            $user->region = $data['region'];
            $user->status = $data['status'];
            $user->status_reason = $data['status_reason'];
            $user->save();
            return redirect('admin/user')->with('success_message', $message);
        }
        return view('admin::users.edituser')->with(compact('common','user','get_regions','get_countries'));
    }

    public function viewUser(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Member Details";
         
        $get_users = User::with(['country','get_region','Offers','Eventfeedback.Events', 'Investments'])->find($id);
        // $get_users = User::with(['country','get_region','Offers'])->find($id);
        // $EventsFeedback = EventFeedback::with('Events')->where('user_id',$get_users['id'])->get();
        // echo "<pre>"; print_r($EventsFeedback->toArray()); die;
        // $Events = Event::whereIn('id',$EventsFeedback->pluck('id'))->get(); 
        // echo "<pre>"; print_r($get_users->toArray()); die;
        // dd($get_users);
        return view('admin::users.viewuser')->with(compact('get_users'));
    }

    /* -- get region on behalf of country -- */
    public function getRegions(Request $request)
    {
        $countryId = $request->input('country_id');
        $regions = Region::where('country', $countryId)->pluck('name', 'id');
        return response()->json($regions);
    }

    // delete user
    public function destroy($id)
    {
        $user = User::findOrfail($id)->delete();
        return redirect()->back()->with('success_message', 'User Deleted Successfully!');  
    }
}

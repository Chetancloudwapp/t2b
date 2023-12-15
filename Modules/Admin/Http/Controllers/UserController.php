<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Country;
use Modules\Admin\Entities\Region;
use App\Models\User;
use Validator;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('status','Active')->whereNull('deleted_at')->get();
        return view('admin::users.index')->with(compact('users'));
    }

    public function addUser(Request $request)
    {
        $get_countries = Country::where('is_show', '1')->get();
        $title = "Add User";
        $user = new User;
        $message = "User Added Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $rules = [
                "name" => "required",
                "email" => "required",
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
            $user->password = Hash::make($data['password']);
            $user->region = $data['region'];
            $user->status = $data['status'];
            $user->status_reason = $data['status_reason'];
            $user->save();
            return redirect('admin/user')->with('success_message', $message);
        }
        return view('admin::users.addUser')->with(compact('title','user','get_countries'));
    }

    public function editUser(Request $request, $id)
    {
        $get_countries = Country::where('is_show', '1')->get();
        $title = "Edit User";
        $user = User::find($id);
        $message = "User Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $rules = [
                "name" => "required",
                "email" => "required",
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
        return view('admin::users.edituser')->with(compact('title','user','get_countries'));
    }

    /* -- get region on behalf of country -- */
    public function getRegions(Request $request)
    {
        $countryId = $request->input('country_id');
        $regions = Region::where('country', $countryId)->pluck('name', 'id');
        return response()->json($regions);
    }


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
        $user = User::findOrfail($id)->delete();
        return redirect()->back()->with('success_message', 'User Deleted Successfully!');  
    }
}

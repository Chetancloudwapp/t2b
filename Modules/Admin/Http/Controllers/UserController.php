<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('status','Active')->whereNull('deleted_at')->get();
        return view('admin::users.index')->with(compact('users'));
    }

    public function addUser(Request $request)
    {
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

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->save();
            return redirect('admin/user')->with('success_message', $message);
        }

        return view('admin::users.addUser')->with(compact('title','user'));
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

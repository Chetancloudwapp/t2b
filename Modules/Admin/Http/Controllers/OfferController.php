<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Offer;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common = [];
        $common['title'] = "Offers";
        $get_offers = Offer::orderBy('id','desc')->get();
        return view('admin::offers.index')->with(compact('common','get_offers'));
    }

    // edit offer
    public function editOffer(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Offers";
        $common['heading_title'] = "Edit Offer";
        $common['button'] = "Update";
        $offers = Offer::find($id);
        $message = "Offer Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                "contact_email" => "required|email",
                "image" => "mimes:jpeg,jpg,png,gif",
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
               return back()->withErrors($validator)->withInput();
            }

            if($request->has('image')){
                $image = $request->file('image');
                $name = time(). "." .$image->getClientOriginalExtension();
                $path = public_path('uploads/offers/');
                $image->move($path, $name);
                $offers->image = $name;
            }

            $offers->name = $data['name'];
            $offers->email = $data['email'];
            $offers->country_id = $data['country'];
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
        return view('admin::users.edituser')->with(compact('common','user','get_countries'));
    }

    // delete offer
    public function destroy($id)
    {
        $offers = Offer::findOrFail($id);
        $offers->delete();
        return back()->with('success_message', 'Offer Deleted Successfully!');
    }
}

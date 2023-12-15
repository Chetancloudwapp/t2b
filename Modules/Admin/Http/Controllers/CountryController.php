<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Country;
use Modules\Admin\Entities\Region;
use Validator;


class CountryController extends Controller
{
    /* -- country index -- */
    public function index()
    {
        $get_countries = Country::get();
        return view('admin::countries.index')->with(compact('get_countries'));
    }

    /* -- update country status -- */
    public function updateCountryStatus(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            
            $cat = Country::where('id',$data['country_id'])->first();
            $cat->is_show = $status;
            $cat->save();
            return response()->json(['status'=>$status, 'country_id'=> $data['country_id']]);
        }  
    }

    /* -- region index -- */
    public function region()
    {
        $get_region = Region::get();
        return view('admin::regions.index')->with(compact('get_region'));
    }

    /* -- Add region -- */
    public function addRegion(Request $request)
    {
        $title = "Add Region";
        $region = new Region;
        $message = "Region add Successfully!";

        $get_countries = Country::where('is_show', '1')->get();
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "name" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $region->name = $data['name'];
            $region->country = $data['country'];
            $region->save();
            return redirect('admin/region')->with('success_message', $message);
        }
        return view('admin::regions.addregion')->with(compact('title','region','get_countries'));
    }

    /* -- Edit Region -- */
    public function editRegion(Request $request, $id)
    {
        $title = "Edit Region";
        $region = Region::find($id);
        $message = "Region Updated Successfully!";

        $get_countries = Country::where('is_show', '1')->get();
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "name" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $region->name = $data['name'];
            $region->country = $data['country'];
            $region->save();
            return redirect('admin/region')->with('success_message', $message);
        }
        return view('admin::regions.editregion')->with(compact('title','region','get_countries'));

    }

    /* -- Delete region -- */
    public function destroy($id)
    {
        $region = Region::findOrFail($id)->delete();
        return redirect()->back()->with('success_message', 'Region Deleted Successfully!');
    }
}

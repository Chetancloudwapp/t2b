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
        $common = [];
        $common['title'] = "Countries";
        $get_countries = Country::get();
        return view('admin::countries.index')->with(compact('common','get_countries'));
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
        $common = [];
        $common['title'] = "Region";
        $get_region = Region::orderBy('id','desc')->get();
        return view('admin::regions.index')->with(compact('common','get_region'));
    }

    /* -- Add region -- */
    public function addRegion(Request $request)
    {
        $common = [];
        $common['title']  = "Region";
        $common['heading_title'] = "Add Region";
        $common['button'] = "Submit";
        $message = "Region add Successfully!";
        
        $get_countries = Country::where('is_show', '1')->get();
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                "name" => "required|regex:/^[^\d]+$/|min:2|max:255",
                "country" => "required",
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            
            $region  =  new Region();
            $region->name = $data['name'];
            $region->country = $data['country'];
            $region->save();
            return redirect('admin/region')->with('success_message', $message);
        }
        return view('admin::regions.addregion')->with(compact('common','get_countries'));
    }

    /* -- Edit Region -- */
    public function editRegion(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Region";
        $common['heading_title'] = "Edit Region";
        $common['button'] = "Update";
        $data = decrypt($id);
        $region = Region::find($data);
        // return $region; 
        $message = "Region Updated Successfully!";
        
        $get_countries = Country::where('is_show', '1')->get();
        if($request->isMethod('post')){
            $data = $request->all();
            
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
        return view('admin::regions.editregion')->with(compact('common','region','get_countries'));
        
    }
    
    /* -- Delete region -- */
    public function destroy($id)
    {
        $id = decrypt($id);
        $region = Region::findOrFail($id)->delete();
        return redirect()->back()->with('success_message', 'Region Deleted Successfully!');
    }
}

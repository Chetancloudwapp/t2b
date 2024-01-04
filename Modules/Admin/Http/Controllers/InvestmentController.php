<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Investment;
use Validator;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common = [];
        $common['title'] = "Investment";
        $investments = Investment::orderBy('id', 'desc')->get();
        return view('admin::investment.index')->with(compact('common', 'investments'));
    }

    /* -- add investment-- */
    public function addInvestments(Request $request)
    {        
        $common = [];
        $common['title']  = "Investment";
        $common['heading_title'] = "Add Investment";
        $common['button'] = "Submit";
        $message = "Investment add Successfully!";
        
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                "investment_title" => "required|regex:/^[^\d]+$/|min:2|max:255",
                "investment_detail" => "required",
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            $investments  =  new Investment();
            $investments->investment_title = $data['investment_title'];
            $investments->investment_detail = $data['investment_detail'];
            $investments->save();
            return redirect('admin/investment')->with('success_message', $message);
        }
        return view('admin::investment.addinvestment')->with(compact('common'));
    }

    /* --- edit investment --- */
    public function editInvestments(Request $request, $id)
    {        
        $common = [];
        $common['title']  = "Investment";
        $common['heading_title'] = "Edit Investment";
        $common['button'] = "Update";
        $id = decrypt($id);
        $investments = Investment::find($id);
        $message = "Investment Updated Successfully!";
        
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                "investment_title" => "required|regex:/^[^\d]+$/|min:2|max:255",
                "investment_detail" => "required",
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $investments->investment_title = $data['investment_title'];
            $investments->investment_detail = $data['investment_detail'];
            $investments->save();
            return redirect('admin/investment')->with('success_message', $message);
        }
        return view('admin::investment.editinvestment')->with(compact('common','investments'));
    }

    /* --- view investment --- */
    public function viewInvestments(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Investment Details";
         
        $get_investments = Investment::with(['get_investments'])->find($id);
        // echo "<pre>"; print_r($get_investments->toArray()); die;
        
        // return $get_investments;
        // $get_users = User::with(['country','get_region','Offers'])->find($id);
        // $EventsFeedback = EventFeedback::with('Events')->where('user_id',$get_users['id'])->get();
        // echo "<pre>"; print_r($EventsFeedback->toArray()); die;
        // $Events = Event::whereIn('id',$EventsFeedback->pluck('id'))->get(); 
        // dd($get_users);
        return view('admin::investment.viewinvestment')->with(compact('get_investments'));
    }


    // delete investment
    public function destroy($id)
    {    
        $id = decrypt($id);        
        $investments = Investment::findOrFail($id);
        $investments->delete();
        return redirect()->back()->with('success_message', 'Investment Deleted Successfully');
    }
}

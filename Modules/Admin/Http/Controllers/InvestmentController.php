<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Investment;

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

    public function addInvestments(Request $request)
    {
        
        // return "hello";
        $common = [];
        $common['title']  = "Investment";
        $common['heading_title'] = "Add Investment";
        $common['button'] = "Submit";
        $message = "Investment add Successfully!";
        // return view('admin::investment.addinvestment')->with(compact('common'));

        
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
            // return "hii";
            $investments  =  new Investment();
            $investments->investment_title = $data['investment_title'];
            $investments->investment_detail = $data['investment_detail'];
            $investments->save();
            return redirect('admin/investment')->with('success_message', $message);
        }
        return view('admin::investment.addinvestment')->with(compact('common'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Language;
use Validator;
use Hash;

class LanguageController extends Controller
{
    //
    public function index()
    {
        $common = [];
        $common['title'] = "Languages";
        $languages = Language::where('status','Active')->whereNull('deleted_at')->get();
        return view('admin::languages.index')->with(compact('common','languages'));
    }

    /* -- Add language -- */
    public function addLanguage(Request $request)
    {
        $common = [];
        $common['title'] = "Languages";
        $common['heading_title'] = "Add Languages";
        $common['button'] = "Submit";
        $message = "Language Added Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                "name"   => 'required|unique:languages|regex:/^[^\d]+$/|min:2|max:255',
                "image"  => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            
            $language = new Language();
            if($request->has('image')){
                $image = $request->file('image');
                $name = time().'.'.$image->extension();
                $path = public_path('uploads/languages/');
                $image->move($path, $name);
                $language->image= $name;
            }
            $language->name = $data['name'];
            $language->status = $data['status'];
            $language->save();
            return redirect('admin/language')->with('success_message', $message);
        }
        return view('admin::languages.addlanguage')->with(compact('common'));
    }

    /* -- edit language -- */
    public function editLanguage(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Languages";
        $common['heading_title'] = "Edit Languages";
        $common['button'] = "Update";
        $title = "Edit Languages";
        $id = decrypt($id);
        $language = Language::find($id);
        $message = "Language Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                "name"   => 'required|unique:languages|regex:/^[^\d]+$/|min:2|max:255',
                "image"  => 'mimes:jpeg,jpg,png,gif|max:10000',
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            if($request->has('image')){
                $image = $request->file('image');
                $name = time().'.'.$image->extension();
                $path = public_path('uploads/languages/');
                $image->move($path, $name);
                $language->image= $name;
            }
            $language->name = $data['name'];
            $language->status = $data['status'];
            $language->save();
            return redirect('admin/language')->with('success_message', $message);
        }
        return view('admin::languages.editlanguage')->with(compact('common', 'language'));
    }

    public function destroy($id)
    {
        // return $id;
        $language = Language::findOrFail($id);
        $language->delete();
        return redirect()->back()->with('success_message', 'Language Deleted Successfully!');  
    }
}

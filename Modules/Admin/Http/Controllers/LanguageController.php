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
        $languages = Language::where('status','Active')->whereNull('deleted_at')->get();
        return view('admin::languages.index')->with(compact('languages'));
    }

    /* -- Add language -- */
    public function addLanguage(Request $request)
    {
        $title = "Add Languages";
        $language = new Language;
        $message = "Language Added Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "name"   => 'required|unique:languages|regex:/^[^\d]+$/|min:2|max:255',
                "image"  => 'mimes:jpeg,jpg,png,gif|required|max:10000',
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
        return view('admin::languages.addlanguage')->with(compact('title', 'language'));
    }

    /* -- edit language -- */
    public function editLanguage(Request $request, $id)
    {
        $title = "Edit Languages";
        $language = Language::find($id);
        // return $language;
        $message = "Language Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

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
        return view('admin::languages.editlanguage')->with(compact('title', 'language'));
    }

    public function destroy($id)
    {
        // return $id;
        $language = Language::findOrFail($id);
        $language->delete();
        return redirect()->back()->with('success_message', 'Language Deleted Successfully!');  
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
}

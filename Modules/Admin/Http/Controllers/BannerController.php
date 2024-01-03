<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Banner;
use Validator;
use Image;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $common = [];
        $common['title'] = "Banner";
        $banner = Banner::orderBy('id','desc')->get();
        return view('admin::banner.index')->with(compact('common','banner'));
    }

    // add banners
    public function addBanner(Request $request)
    {

        $common = [];
        $common['title'] = "Banner";
        $common['heading_title'] = "Add banner";
        $common['button'] = "Submit";
        $message = "Banner Added Successfully!";
        // $banner = new Banner();
      
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "images" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }


             // Upload banner Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'banner-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/bannerImages/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in banner table
                    $banner_image = new Banner;
                    $banner_image->images = $imageName;
                    $banner_image->save();
                }
            }
            return redirect('admin/banner')->with('success_message', $message);
        }
        return view('admin::banner.addbanner')->with(compact('common'));
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

<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Language;
use App\Models\News;
use App\Models\NewsImage;
use Validator;
use Image;

class NewsAdminController extends Controller
{
    
    public function index()
    {
        $common = [];
        $common['title'] = "News";
        $get_news = News::where('status','Active')->orderBy('id','desc')->get();
        return view('admin::news.index')->with(compact('common', 'get_news'));
    }

    // Add news 
    public function addNews(Request $request)
    {
        $common = [];
        $common['title']  = "News";
        $common['heading_title'] = "Add News";
        $common['button'] = "Submit";
        $news = new News([
            'title' => [
                'en' => $request->en_name,
                'it' => $request->it_name,
                'de' => $request->de_name,
                'fr' => $request->fr_name,
            ],
            'description' => [
                'en' => $request->en_description,
                'it' => $request->it_description,
                'de' => $request->de_description,
                'fr' => $request->fr_description,
            ],
        ]);
        $message = "News Added Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                "en_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "en_description" => 'required',
                "it_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "it_description" => 'required',
                "de_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "de_description" => 'required',
                "fr_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "fr_description" => 'required',
                "banner_image"   => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            ];

            $customValidation = [
                "en_name.required"      => "The name field is required",
                "en_description"        => "The description field is required",
                "it_name.required"      => "The name field is required",
                "it_description"        => "The description field is required",
                "de_name.required"      => "The name field is required",
                "de_description"        => "The description field is required",
                "fr_name.required"      => "The name field is required",
                "fr_description"        => "The description field is required",
                "banner_image.required" => "The image field is required",
            ];

            $validator = Validator::make($request->all(), $rules, $customValidation);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }


            // Upload featured Image
            if($request->hasFile('banner_image')){
            $image_tmp = $request->file('banner_image');
                if($image_tmp->isValid()){
                    // Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Generate new Image Name
                    $imageName = rand(111,99999).'.'.$extension;
                    $image_path = public_path('uploads/news/bannerImage/'.$imageName);

                    // upload images
                    Image::make($image_tmp)->resize(520, 600)->save($image_path);
                }
            }else if(!empty($data['current_image'])){
                $imageName = $data['current_image'];
            }else{
                $imageName = "";
            }
            $news->banner_image = $imageName;
            $news->status = $data['status'];
            // echo "<pre>"; print_r($news->toArray()); die;

            $news->save();

            // Upload news Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'news-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/news/galleryImages/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in event Images table
                    $news_image = new NewsImage;
                    // $news_image->event_id = DB::getPdo()->lastInsertId();
                    $news_image->news_id = $news->id;
                    $news_image->images = $imageName;
                // echo "<pre>"; print_r($news_image->toArray()); die;
                    $news_image->save();
                }
            }
            return redirect('admin/news')->with('success_message', $message);
        }
        return view('admin::news.addnews')->with(compact('common', 'news'));
    }

    // Edit news 
    public function editNews(Request $request, $id)
    {
        $common = [];
        $common['title']  = "News";
        $common['heading_title'] = "Edit News";
        $common['button'] = "Update";
        $news = News::with('galleryimages')->find($id);
        // echo "<pre>"; print_r($news->toArray()); die;
        $message = "News Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                "en_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "en_description" => 'required',
                "it_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "it_description" => 'required',
                "de_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "de_description" => 'required',
                "fr_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "fr_description" => 'required',
                "banner_image"   => 'mimes:jpeg,jpg,png,gif|max:10000',
            ];

            $customValidation = [
                "en_name.required"      => "The name field is required",
                "en_description"        => "The description field is required",
                "it_name.required"      => "The name field is required",
                "it_description"        => "The description field is required",
                "de_name.required"      => "The name field is required",
                "de_description"        => "The description field is required",
                "fr_name.required"      => "The name field is required",
                "fr_description"        => "The description field is required",
                "banner_image.required" => "The image field is required",
            ];

            $validator = Validator::make($request->all(), $rules, $customValidation);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $news->title = [
                'en' => $request->en_name,
                'it' => $request->it_name,
                'de' => $request->de_name,
                'fr' => $request->fr_name,
            ];
            $news->description =  [
                'en' => $request->en_description,
                'it' => $request->it_description,
                'de' => $request->de_description,
                'fr' => $request->fr_description,
            ];

            // Upload featured Image
            if($request->hasFile('banner_image')){
            $image_tmp = $request->file('banner_image');
                if($image_tmp->isValid()){
                    // Get Image Extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Generate new Image Name
                    $imageName = rand(111,99999).'.'.$extension;
                    $image_path = public_path('uploads/news/bannerImage/'.$imageName);

                    // upload images
                    Image::make($image_tmp)->resize(520, 600)->save($image_path);
                }
            }else if(!empty($data['current_image'])){
                $imageName = $data['current_image'];
            }else{
                $imageName = "";
            }
            $news->banner_image = $imageName;
            $news->status = $data['status'];
            // echo "<pre>"; print_r($news->toArray()); die;

            $news->save();

            // Upload news Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'news-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/news/galleryImages/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in event Images table
                    $news_image = new NewsImage;
                    // $news_image->event_id = DB::getPdo()->lastInsertId();
                    $news_image->news_id = $news->id;
                    $news_image->images = $imageName;
                // echo "<pre>"; print_r($news_image->toArray()); die;
                    $news_image->save();
                }
            }
            return redirect('admin/news')->with('success_message', $message);
        }
        return view('admin::news.editnews')->with(compact('common', 'news'));
    }

    // delete news 
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        $newsgallery = NewsImage::where('news_id', $id)->first();
        if($newsgallery){
            $image_path = public_path('uploads/news/galleryImages/');
            
            if(file_exists($image_path.$newsgallery->images)){
                unlink($image_path.$newsgallery->images);
            }
            $newsgallery = NewsImage::where('news_id', $id)->delete();
        }
        return redirect()->back()->with('success_message', 'News Deleted Successfully!');       
    }

    // delete news gallery images
    public function deleteNewsImages($id)
    {

        $galleryImage = NewsImage::where('id', $id)->first();
        // return $galleryImage;
        
        /* --- get the path of gallery image */
        $image_path = public_path('uploads/news/galleryImages/');
      

        if(file_exists($image_path.$galleryImage->images)){
            unlink($image_path.$galleryImage->images);
        }

        /* --- Delete gallery images --- */
        $galleryImage = NewsImage::where('id', $id)->delete();       
        $message = "Gallery Image has been deleted Successfully!";
        return back()->with('success_message', $message);
    }
}

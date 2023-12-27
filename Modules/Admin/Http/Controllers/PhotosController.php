<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PhotoGallery;
use App\Models\Photo;
use Validator;
use Image;

class PhotosController extends Controller
{
   
    public function index()
    {
        $common = [];
        $common['title'] = "Photos";
        $photos = Photo::orderBy('id','desc')->get();
        return view('admin::photos.index')->with(compact('common','photos'));
    }

    /* --- Add photos --- */
    public function addPhotos(Request $request)
    {
        $common = [];
        $common['title'] = "Photos";
        $common['heading_title'] = "Add Photos";
        $common['button'] = "Submit";
        $message = "Photos Added Successfully!";
        $photos = new Photo([
            'title' => [
                'en' => $request->en_name,
                'it' => $request->it_name,
                'de' => $request->de_name,
                'fr' => $request->fr_name,
            ],
        ]);
      
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "en_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "it_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "de_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "fr_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
            ];

            $customValidation = [
                "en_name.required"      => "The title field is required",
                "it_name.required"      => "The title field is required",
                "de_name.required"      => "The title field is required",
                "fr_name.required"      => "The title field is required",
            ];

            $validator = Validator::make($request->all(), $rules, $customValidation);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }


            // echo "<pre>"; print_r($photos); die;
            $photos->save();
            // return $photos;

             // Upload photos Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'photos-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/photos/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in photo gallery table
                    $photos_image = new PhotoGallery;
                    $photos_image->photo_id = $photos->id;
                    $photos_image->images = $imageName;
                // echo "<pre>"; print_r($photos_image->toArray()); die;
                    $photos_image->save();
                }
            }
            return redirect('admin/photos')->with('success_message', $message);
        }
        return view('admin::photos.addphotos')->with(compact('common', 'photos'));
    }

    /* --- edit Photos --- */
    public function editPhotos(Request $request, $id)
    {
        $common = [];
        $common['title'] = "Photos";
        $common['heading_title'] = "Edit Photos";
        $common['button'] = "Update";
        $photos = Photo::with('photosgallery')->find($id);
        // echo "<pre>"; print_r($photos->toArray()); die;
        $message = "Photos Updated Successfully!";
      
      
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "en_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "it_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "de_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "fr_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
            ];

            $customValidation = [
                "en_name.required"      => "The title field is required",
                "it_name.required"      => "The title field is required",
                "de_name.required"      => "The title field is required",
                "fr_name.required"      => "The title field is required",
            ];

            $validator = Validator::make($request->all(), $rules, $customValidation);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $photos->title = [
                'en' => $request->en_name,
                'it' => $request->it_name,
                'de' => $request->de_name,
                'fr' => $request->fr_name,
            ];

            // echo "<pre>"; print_r($photos); die;
            $photos->save();
            // return $photos;

             // Upload photos Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'photos-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/photos/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in photo gallery table
                    $photos_image = new PhotoGallery;
                    $photos_image->photo_id = $photos->id;
                    $photos_image->images = $imageName;
                // echo "<pre>"; print_r($photos_image->toArray()); die;
                    $photos_image->save();
                }
            }
            return redirect('admin/photos')->with('success_message', $message);
        }
        return view('admin::photos.editphotos')->with(compact('common', 'photos'));
    }

    public function destroy($id)
    {
        $photos = Photo::findOrFail($id);
        $photos->delete();

        $photosgallery = PhotoGallery::where('photo_id', $id)->first();
        if($photosgallery){
            $image_path = public_path('uploads/photos/');
            
            if(file_exists($image_path.$photosgallery->images)){
                unlink($image_path.$photosgallery->images);
            }

            $photosgallery = PhotoGallery::where('photo_id', $id)->delete();

        }
        return redirect()->back()->with('success_message', 'Photos Deleted Successfully!');  
    }

    // delete photos gallery
    public function deletePhotosGallery($id)
    {
        $galleryImage = PhotoGallery::where('id', $id)->first();
        // return $galleryImage;
        
        /* --- get the path of gallery image */
        $image_path = public_path('uploads/photos/');
      

        if(file_exists($image_path.$galleryImage->images)){
            unlink($image_path.$galleryImage->images);
        }

        /* --- Delete gallery images --- */
        $galleryImage = PhotoGallery::where('id', $id)->delete();       
        $message = "Gallery Image has been deleted Successfully!";
        return back()->with('success_message', $message);
   
    }

}

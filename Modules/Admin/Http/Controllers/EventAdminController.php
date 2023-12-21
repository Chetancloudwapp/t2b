<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Entities\Event;
use Modules\Admin\Entities\EventImage;
use Modules\Admin\Entities\Language;
use Validator;
use Image;

class EventAdminController extends Controller
{
    // Event listing
    public function index()
    {
        $common = [];
        $common['title'] = "Events";
        $events = Event::where('status', 'Active')->orderBy('id','desc')->get();
        return view('admin::events.index')->with(compact('common','events'));
    }

    // Add events 
    public function addEvents(Request $request)
    {
        $common = [];
        $common['title']  = "Events";
        $common['heading_title'] = "Add Events";
        $common['button'] = "Submit";
        $events = new Event([
            'name' => [
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
        $message = "Events Added Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

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
                "en_name.required"      => "Name is required",
                "en_description"        => "Description is required",
                "it_name.required"      => "Name is required",
                "it_description"        => "Description is required",
                "de_name.required"      => "Name is required",
                "de_description"        => "Description is required",
                "fr_name.required"      => "Name is required",
                "fr_description"        => "Description is required",
                "banner_image.required" => "Image is required",
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
                    $image_path = public_path('uploads/events/bannerImage/'.$imageName);

                    // upload images
                    Image::make($image_tmp)->resize(520, 600)->save($image_path);
                }
            }else if(!empty($data['current_image'])){
                $imageName = $data['current_image'];
            }else{
                $imageName = "";
            }
            $events->banner_image = $imageName;

            // if(isset($request->en_name) || $request->en_description){
            //     $events->name = $data['en_name'];
            //     $events->description = $data['en_description'];
            //     }
            //     if(isset($request->it_name) || $request->it_description){
            //         $events->name = $data['it_name'];
            //         $events->description = $data['it_description'];
            //     }
            //     if(isset($request->de_name) || $request->de_description){
            //         $events->name = $data['de_name'];
            //         $events->description = $data['de_description'];
            //     }
            //     if(isset($request->fr_name) || $request->fr_description){
            //         $events->name = $data['fr_name'];
            //         $events->description = $data['fr_description'];
            // }

            $events->eventdate = $data['eventdate'];
            $events->status = $data['status'];
            $events->save();

            // Upload Events Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'event-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/events/galleryImages/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in event Images table
                    $events_image = new EventImage;
                    // $events_image->event_id = DB::getPdo()->lastInsertId();
                    $events_image->event_id = $events->id;
                    $events_image->images = $imageName;
                // echo "<pre>"; print_r($events_image->toArray()); die;
                    $events_image->save();
                }
            }
            return redirect('admin/events')->with('success_message', $message);
        }
        return view('admin::events.addEvents')->with(compact('common', 'events'));
    }


    // edit events 
    public function editEvents(Request $request, $id)
    {
        $common = [];
        $common['title']  = "Events";
        $common['heading_title'] = "Edit Events";
        $common['button'] = "Update";
        $events = Event::with('galleryimages')->find($id);
        $message = "Events Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "en_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "en_description" => 'required',
                "it_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "it_description" => 'required',
                "de_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "de_description" => 'required',
                "fr_name"        => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "fr_description" => 'required',
            ];

            $customValidation = [
                "en_name.required"      => "Name is required",
                "en_description"        => "Description is required",
                "it_name.required"      => "Name is required",
                "it_description"        => "Description is required",
                "de_name.required"      => "Name is required",
                "de_description"        => "Description is required",
                "fr_name.required"      => "Name is required",
                "fr_description"        => "Description is required",
            ];

            $validator = Validator::make($request->all(), $rules, $customValidation);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $events->name = [
                'en' => $request->en_name,
                'it' => $request->it_name,
                'de' => $request->de_name,
                'fr' => $request->fr_name,
            ];
            $events->description =  [
                'en' => $request->en_description,
                'it' => $request->it_description,
                'de' => $request->de_description,
                'fr' => $request->fr_description,
            ];

             // Upload Banner Image
            if($request->hasFile('banner_image')){
                $image_tmp = $request->file('banner_image');
                    if($image_tmp->isValid()){
                        $extension = $image_tmp->getClientOriginalExtension();
                        $imageName = rand(111,99999).'.'.$extension;
                        $image_path = public_path('uploads/events/bannerImage/'.$imageName);
    
                        // upload images
                        Image::make($image_tmp)->resize(520, 600)->save($image_path);
                    }
                }else if(!empty($data['current_image'])){
                    $imageName = $data['current_image'];
                }else{
                    $imageName = "";
                }
            $events->banner_image = $imageName;
            $events->eventdate = $data['eventdate'];
            $events->status = $data['status'];
            $events->save();

              // Upload Events Image
            if($request->hasFile('images')){
                $images = $request->file('images');
                foreach($images as $key => $image){
                    // Get temp image
                    $image_temp = Image::make($image);
                    // Get Image Extension
                    $extension = $image->getClientOriginalExtension();

                    // Generate new Image Name
                    $imageName = 'event-'.rand(1111,9999999).'.'.$extension;
                    $image_path = public_path('uploads/events/galleryImages/'.$imageName);

                    // upload images
                    Image::make($image_temp)->resize(520, 600)->save($image_path);

                    // insert images in event Images table
                    $events_image = new EventImage;
                    // $events_image->event_id = DB::getPdo()->lastInsertId();
                    $events_image->event_id = $events->id;
                    $events_image->images = $imageName;
                    $events_image->save();
                }
            }
            return redirect('admin/events')->with('success_message', $message);
        }
        return view('admin::events.editEvents')->with(compact('common', 'events'));
    }

    // delete event images 
    public function deleteEventImages($id)
    {
        $eventImage = EventImage::where('id', $id)->first();
        // return $eventImage;
        
        /* --- get the path of gallery image */
        $image_path = public_path('uploads/events/galleryImages/');
      

        if(file_exists($image_path.$eventImage->images)){
            unlink($image_path.$eventImage->images);
        }

        /* --- Delete gallery images --- */
        $eventImage = EventImage::where('id', $id)->delete();       
        $message = "Gallery Image has been deleted Successfully!";
        return redirect('admin/events')->with('success_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $events = Event::findOrFail($id);
        $events->delete();
        return redirect()->back()->with('success_message', 'Events Deleted Successfully!');  
    }
}

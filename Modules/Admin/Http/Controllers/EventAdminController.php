<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Event;
use App\Models\EventImage;
use Validator;
use Image;

class EventAdminController extends Controller
{
    // Event listing
    public function index()
    {
        $events = Event::where('status', 'Active')->get();
        return view('admin::events.index')->with(compact('events'));
    }

    // Add events 
    public function addEvents(Request $request)
    {
        $title = "Add Events";
        $events = new Event();
        $message = "Events Added Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "name"   => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "banner_image"  => 'mimes:jpeg,jpg,png,gif|required|max:10000',
                "description" => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            // if($request->has('image')){
            //     $image = $request->file('image');
            //     $name = time().'.'.$image->extension();
            //     $path = public_path('uploads/eventss/');
            //     $image->move($path, $name);
            //     $events->image= $name;
            // }

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
            $events->name = $data['name'];
            $events->description = strip_tags($data['description']);
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
        return view('admin::events.addEvents')->with(compact('title', 'events'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editEvents(Request $request, $id)
    {
        $title = "Edit Events";
        $events = Event::find($id);
        $message = "Events Updated Successfully!";

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                "name"   => 'required|regex:/^[^\d]+$/|min:2|max:255',
                "banner_image"  => 'mimes:jpeg,jpg,png,gif|max:10000',
                "description" => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

             // Upload Banner Image
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
            $events->name = $data['name'];
            $events->description = strip_tags($data['description']);
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
        return view('admin::events.editEvents')->with(compact('title', 'events'));
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
        $events = Event::findOrFail($id);
        $events->delete();
        return redirect()->back()->with('success_message', 'Events Deleted Successfully!');  
    }
}

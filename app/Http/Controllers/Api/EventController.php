<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventImage;
use Validator;
use Hash;
use Image;
// use Intervention\Image\Facades\Image;
use DB;

class EventController extends Controller
{
    public function addEvents(Request $request)
    {  
        // $userData = $request->input();
        // echo "<pre>"; print_r($userData); die;
        $rules = [
            "images.*"      => "required|mimes:jpeg,jpg,png,gif",
            "name"        => "required|regex:/^[a-zA-Z\s\']+$/u|max:255",
            "description" => "required",
            "eventdate"   => "required",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],
            422,
           );
        }

       $events = new Event();
       $events->name = $request->name;
       $events->description = $request->description;
       $events->eventdate = $request->eventdate;
       $events->status = $request->status;
       $events->save();
    //    echo "<pre>"; print_r($events->toArray()); die;
    
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
            $image_path = public_path('uploads/events/'.$imageName);

            // upload images
            Image::make($image_temp)->resize(520, 600)->save($image_path);

            // insert images in event Images table
            $events_image = new EventImage;
            // $events_image->event_id = DB::getPdo()->lastInsertId();
            $events_image->event_id = $events->id;
            $events_image->image = $imageName;
            $events_image->save();
            }
        }
    //    $events['images'] = url('uploads/events/'.$events['images']);
       $events['eventdate'] = date('d-M-Y',strtotime($events['eventdate']));

       return response()->json([
           'status' => true,
           'status_code' => 200,
           'message' => "Events Added Successfully!",
           'data' => $events,
        //    'data1' => $events_image,
        ],201);
    }
}

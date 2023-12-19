<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Modules\Admin\Entities\Event;
use Modules\Admin\Entities\EventImage;
use Validator;
use Hash;
use Image;
// use Intervention\Image\Facades\Image;
use DB;

class EventController extends Controller
{
     /* --- function for casting--- */
    function allString($object)
    {
        // Get the object's attributes
        $attributes = $object->getAttributes();

        // Iterate through the attributes and apply conversions
        foreach ($attributes as $key => &$value) {
            if (is_null($value)) {
                $value = "";
            } elseif (is_numeric($value) && !is_float($value)) {
                // Convert numeric values to integers (excluding floats)
                $value = (string) $value;
            }
            // Add more conditions for other types if needed
        }

        // Set the modified attributes back to the object
        $object->setRawAttributes($attributes);
        return $object;
    }

    /* --- event listing --- */
    public function eventListing(Request $request)
    {  
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";
        $output['data'] = "";

        $events= [];
        $get_events = Event::with('galleryimages')->where('status','Active')->whereNull('deleted_at')->get();
        // echo "<pre>"; print_r(($get_events)); die;
        if(!$get_events->isEmpty()){
            foreach($get_events as $key => $value){
                $eventArr = [];
                $eventArr['name'] = $value['name'];
                $eventArr['banner_image'] = asset('uploads/events/bannerImage/'.$value['banner_image']);
                $eventArr['description'] = $value['description'];
                $eventArr['eventdate'] = date("d-M-Y",strtotime($value['eventdate']));
                // $eventArr['images'] = asset('uploads/events/galleryImages/'.$value['images']);
                $eventArr['gallery_images'] = $value['galleryimages'];
                $events[] = $eventArr;
            }

            $output['data'] = $events;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
    }

    /* --- Event Detail Api --- */
    public function eventDetail(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Event Not Found";
        $output['data'] = '';

        $rules = [
            "event_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        $get_events = [];
        $event_id = $request->event_id;
        $get_event = Event::with('galleryimages')->where('id', $event_id)->where('status','Active')->first();
        // echo "<pre>"; print_r($get_event->toArray()); die;
        
        if($get_event){
           
            $gallery = [];
            foreach ($get_event['galleryimages'] as $images) {
                $gallery[] = [
                    'event_id' => $images['event_id'],
                    'image' => asset('uploads/events/galleryImages/' . $images['images']),
                ];
            }
            unset($get_event['galleryimages']);
            $get_event['banner_image'] = asset('uploads/events/bannerImage/'.$get_event['banner_image']);
            $get_event['gallery'] = $gallery;
            $get_event['eventdate'] = date("d-M-Y",strtotime($get_event['eventdate']));

            $output['data'] = ($get_event);
            // $output['data'] = $this->allString($get_event);
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
    }
}

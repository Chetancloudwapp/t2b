<?php

namespace App\Http\Controllers\Api;

use Laravel\Passport\{Passport, Token};
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Modules\Admin\Entities\Event;
use Modules\Admin\Entities\EventImage;
use App\Models\EventFeedback;
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
        // return $request->all();
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";
        $output['data'] = "";

        $user = auth()->user();
        // return $user;
        if(isset($request->lang) && !empty($request->lang)){
            app()->setLocale($request->lang);
        }
        $events= [];
        $get_events = Event::with('galleryimages')->where('status','Active')->where(['country_id'=> $user->country_id, 'region'=> $user->region])->get();
        // return $get_events;
        
        if(!$get_events->isEmpty()){
            foreach($get_events as $key => $value){
                $eventArr = [];
                $eventArr['id'] = $value['id'];
                $eventArr['name'] = $value->name;
                $eventArr['banner_image'] = asset('uploads/events/bannerImage/'.$value['banner_image']);
                $eventArr['description'] = $value->description;
                $eventArr['eventdate'] = date("d-M-Y",strtotime($value['eventdate']));

                // get event feedback
                $get_event_feedback = EventFeedback::where('event_id', $value['id'])->first();
                if($get_event_feedback){
                    $eventArr['event_feedback'] = $get_event_feedback->response;
                }
              
                // get gallery images
                $gallery = [];
                foreach ($value['galleryimages'] as $images) {
                    $gallery[] = [
                        'event_id' => $images['event_id'],
                        'image' => asset('uploads/events/galleryImages/' . $images['images']),
                    ];
                }
                $eventArr['gallery'] = $gallery;
                $events[] = $eventArr;
                // unset($get_events['galleryimages']);
            }

            // $output['data'] = $get_events;
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
        // return $request->all();
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Event Not Found";
        $output['data'] = '';

        $rules = [
            "event_id" => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }

        // $event_feedback = new EventFeedback();

        $get_events = [];
        $event_id = $request->event_id;
       
        if(isset($request->lang) && !empty($request->lang)){
            app()->setLocale($request->lang);
        }

        $get_event = Event::with('galleryimages')->where('id', $event_id)->where('status','Active')->whereNull('deleted_at')->first();
        // return $get_event;

        $get_event_data = [];
        if($get_event){
            $gallery = [];
            foreach ($get_event['galleryimages'] as $images) {
                $gallery[] = [
                    'event_id' => $images['event_id'],
                    'image' => asset('uploads/events/galleryImages/' . $images['images']),
                ];
            }
            $get_event_data['name'] = $get_event->name;
            $get_event_data['description'] = $get_event->description;
            $get_event_data['eventdate'] = date("d-M-Y",strtotime($get_event['eventdate']));
            $get_event_data['banner_image'] = asset('uploads/events/bannerImage/'.$get_event['banner_image']);
            $get_event_data['gallery'] = $gallery;
            
            $output['data'] = $get_event_data;
            $output['status'] = true;
            $output['status_code'] = 200;
            $output['message'] = "Data Fetch Successfully!";
        }
        return json_encode($output);
    }

    public function eventFeedback(Request $request)
    {
        $output = [];
        $output['status'] = false;
        $output['status_code'] = 422;
        $output['message'] = "Something Went Wrong";

        $rules = [
            "event_id" => "required|numeric",
            "status"   => "required|in:yes,no,maybe",
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ],422);
        }
        $user = auth()->user();

        // check if the user has already submitted feedback for this event
        $existingFeedback = EventFeedback::where(['event_id'=> $request->event_id, 'user_id' => $user->id])->first();

        if(!empty($existingFeedback)){
            $output['status']      = false;
            $output['status_code'] = 422;
            $output['message']     = "Event Feedback has already been Submitted!";
            return json_encode($output);

            // return response()->json([
            //     'status' => false,
            //     'message' => "Event Feedback has already been Submitted!",
            // ],421);
        }else{
            $event_feedback = new EventFeedback;
            $event_feedback->event_id = $request->event_id;
            $event_feedback->user_id  = $user->id;
            $event_feedback->response = $request->status;
            $event_feedback->save();
            $output['status']      = true;
            $output['status_code'] = 200;
            $output['message']     = "Event Feedback Sent Successfully!";
        }
        return json_encode($output);
    }
}

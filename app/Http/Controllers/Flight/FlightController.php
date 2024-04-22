<?php

namespace App\Http\Controllers\Flight;

use App\Http\Controllers\Controller;
use App\Models\Booking_flight;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    //CRUD Flight
    public function index(){
        $flights=Flight::all();
        //response
        return response()->json([
            'status'=>true,
            'msg'=>'All flights retrieved Successfully',
            'data'=>$flights
        ]);
    }

    public function create(Request $request){
        //validation
        $rules=$request->validate([
            'flight_number'=>'required|unique:flights',
            'airline'=>'required',
            'origin'=>'required',
            'destination'=>'required',
            'boarding_time'=>'required',
            'arrival_time'=>'required',
            'price'=>'required',
            'distanceInKilo'=>'required',
            'capacity'=>'required',
            'serviceOwner_id'=>'required'
          //  'image'=>'mimes:png,jpg,jpeg,gif|nullable',
        ]);

        // Calculate duration in minutes
       // $boardingTime = Carbon::parse($request->input('boarding_time'));
       // $arrivalTime = Carbon::parse($request->input('arrival_time'));
       // $durationInMin = $boardingTime->diffInMinutes($arrivalTime);

        // Add duration to the validation rules
       // $rules['durationInMin'] = $durationInMin;


        //creating the flight
        $flight=Flight::create($rules);


        //response
        return response()->json([
            'status'=>true,
            'msg'=>'Flight Created Successfully',
            'data'=>$flight
        ]);
    }

    public function show($id){
        $flight=Flight::findOrFail($id);

        //response
        return response()->json([
            'status'=>true,
            'msg'=>'Flight showed Successfully',
            'data'=>$flight
        ]);
    }

    public function update(Request $request, $id)
    {
        //validation
        $rules=$request->validate([
            'flight_number'=>'required|',
            'airline'=>'required',
            'origin'=>'required',
            'destination'=>'required',
            'boarding_time'=>'required',
            'arrival_time'=>'required',
            'price'=>'required',
            'distanceInKilo'=>'required',
            'capacity'=>'required',
            'serviceOwner_id'=>'required'
            //  'image'=>'mimes:png,jpg,jpeg,gif|nullable',
        ]);
        // Calculate duration in minutes
       // $boardingTime = Carbon::parse($request->input('boarding_time'));
        //$arrivalTime = Carbon::parse($request->input('arrival_time'));
       // $durationInMin = $boardingTime->diffInMinutes($arrivalTime);

        // Add duration to the validation rules
        //$rules['durationInMin'] = $durationInMin;

        // Find the flight by ID
        $flight = Flight::findOrFail($id);

        // Update the flight attributes
        $flight->flight_number = $rules['flight_number'];
        $flight->airline = $rules['airline'];
        $flight->origin = $rules['origin'];
        $flight->destination = $rules['destination'];
        $flight->boarding_time = $rules['boarding_time'];
        $flight->arrival_time = $rules['arrival_time'];
        $flight->price=$rules['price'];
        //$flight->durationInMIn=$rules['durationInMin'];
        $flight->distanceInKilo=$rules['distanceInKilo'];
        $flight->capacity = $rules['capacity'];


        // Check if a new image is uploaded
//        if ($request->hasFile('image')) {
//            // Delete the previous image
//            if ($flight->image) {
//                $imagePath = public_path('/uploads/') . $flight->image;
//                if (file_exists($imagePath)) {
//                    unlink($imagePath);
//                }
//            }
//
//            // Upload the new image
//            $img = $request->file('image');
//            $ext = $img->getClientOriginalExtension();
//            $imageName = time() . '.' . $ext;
//            $img->move(public_path('/uploads/'), $imageName);
//
//            $flight->image = $imageName;
////
/// }

        // Save the updated flight
        $flight->save();

        // Response array
        $success['flight_number'] = $flight->flight_number;
        $success['airline'] = $flight->airline;
        $success['origin'] = $flight->origin;
        $success['destination'] = $flight->destination;
        $success['boarding_time'] = $flight->boarding_time;
        $success['arrival_time'] = $flight->arrival_time;
        $success['price']=$flight->price;
      //  $success['durationInMin']=$flight->durationInMIn;
        $success['distanceInKile']=$flight->distanceInKile;
        $success['capacity'] = $flight->capacity;
        $success['serviceOwner_id']=$flight->serviceOwner_id;
       // $success['image'] = $flight->image;

        // Response
        return response()->json([
            'status' => true,
            'msg' => 'Flight Updated Successfully',
            'data' => $success
        ]);
    }

    public function delete($id){
        $flight=Flight::findOrFail($id);
        $flight->delete();

        //response
        return response()->json([
            'status'=>true,
            'msg'=>'Flight deleted Successfully',
        ]);
    }


    //Search
    public function searchByDestination(Request $request)
    {
        $request->validate([
            'destination'=>'required'
        ]);
        $destination = $request['destination'];

        $flights = Flight::where('destination', 'like', $destination[0] . '%')->get();

        return response()->json([
            'status' => true,
            'msg' => 'Flights found successfully.',
            'data' => $flights,
        ],200);
    }

    public function searchByPrice(Request $request)
    {
        $request->validate([
            'minPrice' => 'required|numeric',
            'maxPrice' => 'required|numeric',
        ]);

        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');

        $flights = Flight::whereBetween('price', [$minPrice, $maxPrice])->get();

        return response()->json([
            'status' => true,
            'msg' => 'Flights found successfully.',
            'data' => $flights,
        ], 200);
    }




}

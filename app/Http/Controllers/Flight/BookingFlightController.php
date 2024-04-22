<?php

namespace App\Http\Controllers\Flight;

use App\Http\Controllers\Controller;
use App\Models\Booking_flight;
use App\Models\Flight;
use App\Models\User;
use App\Models\Wallet;
use http\Env\Response;
use Illuminate\Http\Request;

class BookingFlightController extends Controller
{
    public function create(Request $request){
        $rules=$request->validate([
           'status'=>'required|in:active',
            'flightClass' => 'required|in:first,business,economy',
            'flight_id'=>'required|numeric',
            'user_id'=>'required|numeric',
        ]);

        //find flight id
        $flightId=$request->input('flight_id');
        $flight=Flight::find($flightId);
        if(!$flight){
            return response()->json([
                'status'=>false,
                'msg'=>'Flight is Not Found!'
            ],404);
        }
        $capacity=$flight->capacity;

        //find user id
        $userId=$request->input('user_id');
        $user=User::find($userId);
        if(!$user){
            return response()->json([
                'status'=>false,
                'msg'=>'User is Not Found!'
            ],404);
        }
        $userName=$user->name;

        // Check if the flight capacity is exceeded
        $bookingsCount = Booking_flight::where('flight_id', $flightId)->count();

        if ($bookingsCount >= $capacity) {
            return response()->json([
                'status'=>false,
                'msg'=>'Flight capacity exceeded!'
            ],400);
        }



        // Deduct price from user's wallet
        $price = $flight->price;
        $wallet = Wallet::where('user_id', $userId)->first();
        if (!$wallet) {
            return response()->json([
                'status' => false,
                'msg' => 'User wallet not found!'
            ], 404);
        }

        $currentBalance = $wallet->amount;
        if ($currentBalance < $price) {
            return response()->json([
                'status' => false,
                'msg' => 'Insufficient funds in the wallet. Please charge it first.'
            ], 400);
        }


        $book=Booking_flight::create($rules);

        //response array
        $success['status']=$book->status;
        $success['flightClass']=$book->flightClass;
        $success['flight_id']=$book->flight_id;
        $success['user_id']=$book->user_id;
        $success['user_name']=$userName;
        $success['flight_number']=$flight->flight_number;
        $success['airline']=$flight->airline;
        $success['origin']=$flight->origin;
        $success['destination']=$flight->destination;
        $success['boarding_time']=$flight->boarding_time;
        $success['arrival_time']=$flight->arrival_time;
        $success['distanceInKilo']=$flight->distanceInKilo;
        $success['price']=$flight->price;


        return response()->json([
            'status'=>true,
            'msg'=>'Booked Successfully!',
            'data'=>$success,
        ],201);

    }

    public function update(Request $request, $id)
    {
        $booking = Booking_flight::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'msg' => 'Booking not found!'
            ], 404);
        }

        $rules = $request->validate([
            'status' => 'required|in:active,cancelled',
            'flightClass' => 'required|in:first,business,economy',
        ]);



        // Retrieve the associated flight and user
        $flight = $booking->flight()->first();
        $user = $booking->user()->first();

        // Check if the booking status is being updated to "cancelled"
        if ($booking->status !== 'cancelled' && $rules['status'] === 'cancelled') {
            // Update the booking status
            $booking->update($rules);

            // Refund the price to the user's wallet
            $price = $flight->price;
            $wallet = Wallet::where('user_id', $user->id)->first();
            if (!$wallet) {
                return response()->json([
                    'status' => false,
                    'msg' => 'User wallet not found!'
                ], 404);
            }

            // Add the price back to the wallet balance
            $currentBalance = $wallet->amount;
            $wallet->amount = $currentBalance + $price;
            $wallet->save();
        } else {
            // Update the booking status without refunding
            $booking->update($rules);
        }
        // Response array
        $success['status'] = $booking->status;
        $success['flightClass'] = $booking->flightClass;
        $success['user_name'] = $user->name;
        $success['flight_number'] = $flight->flight_number;
        $success['airline'] = $flight->airline;
        $success['origin'] = $flight->origin;
        $success['destination'] = $flight->destination;
        $success['boarding_time'] = $flight->boarding_time;
        $success['arrival_time'] = $flight->arrival_time;
        $success['distanceInKilo'] = $flight->distanceInKilo;
        $success['price'] = $flight->price;


        return response()->json([
            'status' => true,
            'msg' => 'Booking updated successfully!',
            'data' => $success,
        ], 200);
    }


}

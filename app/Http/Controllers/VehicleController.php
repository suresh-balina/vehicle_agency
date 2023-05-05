<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreVehicleRequest;
// use App\Http\Requests\UpdateVehicleRequest;

use App\Http\Requests\storerequest;
use App\Http\Requests\UpdateRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Vehicle $vehicle)
    {

        $myModels = Vehicle::where('user_id', Auth::id())->get();
        if ($myModels == NULL) {
            return response()->json([
                "message" => "No Vehicles found"
            ]);
        }
        return $myModels;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storerequest $request)
    {

        $vehicle = new Vehicle();
        $vehicle->user_id = Auth::id();
        $vehicle->name = $request->input('name');
        $vehicle->type = $request->input('type');
        $vehicle->fueltype = $request->input('fueltype');
        $vehicle->seatingcount = $request->input('seatingcount');
        $vehicle->hourlyrate = $request->input('hourlyrate');
        $vehicle->save();

        return response()->json(['success' => 'Vehicle Update Succesfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {

        if (Auth::id() == $vehicle->user_id) {
            return $vehicle;
        }
        return response()->json([
            'message' => 'unauthourised'
        ], 403);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest  $request, Vehicle $vehicle)
    {



            $vehicle->name = $request->input('name');
            $vehicle->type = $request->input('type');
            $vehicle->fueltype = $request->input('fueltype');
            $vehicle->seatingcount = $request->input('seatingcount');
            $vehicle->hourlyrate = $request->input('hourlyrate');
            $vehicle->save();

            return response()->json(['success' => 'Vehicle Update Succesfully']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        if (Auth::id() == $vehicle->user_id) {

            $vehicle->delete();
            return response()->json(['success' => 'Vehicle Deleted Succesfully']);
        }

        return response()->json(['meassage' => 'Unauthorised']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => "required",
            "password" => "required"
        ]);
        if (!Auth::attempt($credentials)) {

            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => 'required | max:30',
            "password" => 'required | min:4',
            'email' => 'required|email',
        ]);


        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return $user;
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
